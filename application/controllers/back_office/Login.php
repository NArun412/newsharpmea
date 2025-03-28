<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends MY_Controller {
public function __construct()
{
	parent::__construct();
}

public function index(){
	if(validate_user()){
		redirect(admin_url('dashboard'));
	}else{
		$this->load->view('back_office/login');
	}
}

public function login_validate(){
$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
$this->form_validation->set_rules('pass', 'Password', 'trim|required|xss_clean');
if ($this->form_validation->run() == FALSE){
    $this->index();
}
else{
	$email= $this->input->post('email');
	$password=$this->input->post('pass');
	$user_id = validate_user_login($email,$password);
	set_user_session($user_id);
	if($user_id) {
	redirect(admin_url("dashboard"));	
	}
	else {
		$this->session->set_flashdata('login_error','check your email or password.');
		$this->index(); 	
	}
}
}

public function logout() {
	logout();
	redirect(admin_url("login"));	 
}	
////////////////////////////////////////////////////////////////////////////////////////////////////////////

public function validate_email()
	{
		$email = $this->input->post('email');
		$cond = array(
			'email'=>$email
		);
		$user = $this->fct->getonerow('users',$cond);
		//print_r($user);exit;
		if(empty($user)) {
			$this->form_validation->set_message('validate_email',"Account does not exist.");
			return false;
		}
		else {
			if($user['status'] == 0) {
				$this->form_validation->set_message('validate_email',"Account does not exist.");
				return false;
			}
			else {
				$this->load->model('send_emails');
				$request = $this->fct->create_password_request($user);
				$this->send_emails->sendPasswordRequest($user,$request);
				$this->session->set_flashdata('success_message','Password request is sent to: '.'<a href="mailto:'.$user['email'].'">'.$user['email'].'</a>');
				return true;
			}
		}
	}
	public function password()
	{
		if(isset($_POST) && !empty($_POST)) {
			//echo 'test';exit;
			$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|callback_validate_email[]');
			if($this->form_validation->run() == FALSE) {
				$data['error_messages'] = validation_errors();
				$this->load->view('back_office/password',$data);
			}
			else {
				redirect(admin_url('login/password'));
			}
		}
		else {
			$this->load->view('back_office/password');
		}
	}
function loginbypassword()
{
	if(isset($_GET['token'])) {
		$token = $_GET['token'];
		$cond = array('token'=>$token);
		$check_token = $this->fct->getonerow('password_requests',$cond);
		/*print '<pre>';
		print_r($check_token);
		exit;*/
		if(empty($check_token)) {
			$this->session->set_flashdata('error_messages','invalid token');
			redirect(admin_url('login/password'));
		}
		else {
			if($check_token['expiration_date'] < date('Y-m-d h:i:s')) {
				$this->session->set_flashdata('error_messages','Token is expired');
				redirect(admin_url('login/password'));
			}
			else {
				if($check_token['logged'] == 1) {
					$this->session->set_flashdata('error_messages','Token is expired');
					redirect(admin_url('login/password'));
				}
				else {
					$data['logged'] = 1;
					$data['used_date'] =date('Y-m-d h:i:s');
					$this->db->where('id_password_requests',$check_token['id_password_requests']);
					$this->db->update('password_requests',$data);
					//print_r($check_token);exit;
					$user = $this->fct->getonerow('users',array('id_users'=>$check_token['id_user']));
					$role = $this->fct->getonecell('roles','title',array('id_roles' => $user["id_roles"]));
					
					set_user_session($user['id_users']);
					
					$this->load_loginbypassword($check_token['id_user']);
				}
			}
		}
	}
	else {
		reirect(site_url());
	}
	}
	function load_loginbypassword($id_user,$data = array())
	{
		if(!isset($data['error_messages'])) {
			$data['success_messages'] = 'You are now logged in, you can change your password below.';
		}		
		$data['id_user'] = $id_user;
		$this->load->view('back_office/fillnewpassword',$data);
}
function update_password()
{
	if(isset($_POST) && !empty($_POST)) {
		//$this->form_validation->set_rules('id_user',"user id",'trim|required|xss_clean');
		$this->form_validation->set_rules('password',"password",'trim|required|xss_clean');
		$this->form_validation->set_rules('confirm_password',"confirm password",'trim|required|xss_clean|matches[password]');
		if($this->form_validation->run() == FALSE) {
			$data['error_messages'] = validation_errors();
			$this->load_loginbypassword($this->input->post('id_user'),$data);
		}
		else {
			$id_user = user_id();
			$password = $this->input->post('password');
			$salt = generate_salt();
			$data['salt'] = $salt;
			$password = secure_password($password,$salt);
			$data['password'] = $password;
			//$data['updated_date'] = date('Y-m-d h:i:s');
			//$data['last_login'] = date('Y-m-d h:i:s');
			$this->db->where('id_users',$id_user);
			$this->db->update('users',$data);
			
			$this->session->set_userdata('success_message','Passwords are updated successfully.');
			redirect(admin_url('profile'));
		}
	}
	else {
		$this->session->set_flashdata('error_message','The link is expired.');
		redirect(admin_url('login/password'));
	}
}
	
	
	
	
}