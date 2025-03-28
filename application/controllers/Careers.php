<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Careers extends My_Controller{

public function __construct(){
	parent::__construct();
	$this->lang->load('statics');
	$this->langue = $this->lang->lang();
}

public function index(){
$data = array();
$data["seo"] = get_seo(4);
$data["lang"] = $this->langue;
$data['intro'] = get_page(11);
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
$data['banners'] = $this->website_fct->get_banners(10);
$data['countries'] = $this->website_fct->get_countries(FALSE);
$data['divisions'] = $this->website_fct->get_careers_divisions();
$data['regions'] = $this->website_fct->get_regions();
$data['experience_levels'] = $this->website_fct->get_experience_levels();

$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/careers',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}

public function whyworkhere(){
$data = array();
$data["seo"] = get_seo(5);
$data["lang"] = $this->langue;
$data['intro'] = get_page(12);
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));

$data['banners'] = $this->website_fct->get_banners(12);
$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/careers',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}

public function culture_at_sharp(){
$data = array();
$data["seo"] = get_seo(55);
$data["lang"] = $this->langue;
$data['intro'] = get_page(17);
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));

$data['banners'] = $this->website_fct->get_banners(13);
$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/careers',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}



public function thankyou(){
$data = array();
$data["seo"] = get_seo(6);
$data['msg'] = $this->website_fct->get_dynamic(16);

$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
//$data['banners'] = $this->website_fct->get_banners(9);

$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/thankyou',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}

function validate_captcha()
{
  $row_count = 1;
  if(!isset($_POST['no_captcha'])) {
	  $expiration = time()-7200; // Two hour limit
	  $this->db->query("DELETE FROM captcha WHERE captcha_time < ".$expiration);
	  // Then see if a captcha exists:
	  $sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
	  $binds = array($_POST['captcha'], $this->input->ip_address(), $expiration);
	  $query = $this->db->query($sql, $binds);
	  $row = $query->row();
	  $row_count = $row->count;
	  if($row_count == 0) {
		  $this->form_validation->set_message('validate_captcha',lang("Characters do not match"));
		  return false;
	  }
	  else {
		  return true;
	  }
  }
}

function validatecvformat()
{
	 if(!empty($_FILES['cv']['name'])) {
		$allowed =  array('pdf','docx' ,'doc','txt');
		$filename = $_FILES['cv']['name'];
		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		if(!in_array($ext,$allowed) ) {
			$this->form_validation->set_message('validatecvformat',lang('Invalid file type'));
		 	return false;
		}
		else {
			return true;
		}
	 }
	 else {
		 $this->form_validation->set_message('validatecvformat',lang('CV is required'));
		 return false;
	 }
}
function validateexperience()
{
	$years = $this->input->post('years');
	$months = $this->input->post('months');
	if(empty($years) && empty($months)) {
		$this->form_validation->set_message('validateexperience',lang('Please fill at least one of the experience fields'));
		 	return false;
	}
	else {
		return true;
	}
}
public function submit()
{
  //print '<pre>'; print_r($_POST); exit;
 
  if(empty($_FILES['cv']['name'])) $_POST['cv'] = '';
  else
  $_POST['cv'] = 'ok_proceed';
  
  $allPostedVals = $this->input->post(NULL, TRUE);
  $this->form_validation->set_rules('name',lang('name'),'trim|required');
  $this->form_validation->set_rules('emailid',lang('email ID'),'trim|required|valid_email');
  $this->form_validation->set_rules('country',lang('country'),'trim|required');
  $this->form_validation->set_rules('state',lang('state'),'trim|required');
  $this->form_validation->set_rules('city',lang('city'),'trim|required');
  $this->form_validation->set_rules('cnumber',lang('contact number'),'trim|required');
  $this->form_validation->set_rules('cv',lang('upload CV'),'trim|required|callback_validatecvformat[]');
  
  $this->form_validation->set_rules('years',lang('experience').' '.lang('years'),'trim');
  $this->form_validation->set_rules('months',lang('experience').' '.lang('months'),'trim|callback_validateexperience[]');
  $this->form_validation->set_rules('position',lang('position applying for'),'trim|required');
  $this->form_validation->set_rules('highestqualification',lang('highest qualification'),'trim|required');
  $this->form_validation->set_rules('division',lang('division applying for'),'trim|required');
  $this->form_validation->set_rules('department',lang('department'),'trim|required');
  $this->form_validation->set_rules('region',lang('region applying for'),'trim|required');
  $this->form_validation->set_rules('experiencelevel',lang('specify your experience level'),'trim|required');
  
  $this->form_validation->set_rules('brief',lang('briefly describe yourself'),'trim|required');
  $this->form_validation->set_rules('captcha',"Captcha",'trim|required|callback_validate_captcha[]');
  
  
  if($this->form_validation->run() == FALSE) {
	  //echo validation_errors();exit;
	  $return['nct'] = $this->security->get_csrf_hash();
	  $return['result'] = 0;
	  $return['errors'] = array();
	  $return['message'] = 'Error!';
	  $return['captcha'] = $this->fct->createNewCaptcha();
	  $find =array('<p>','</p>');
	  $replace =array('','');
	  foreach($allPostedVals as $key => $val) {
		  if(form_error($key) != '') {
			  $return['errors'][$key] = str_replace($find,$replace,form_error($key));
		  }
	  }
  }
  else {
 	  $_data['resume'] = $this->fct->uploadImage("cv","careers_applications");
	  $_data['name'] = $this->input->post('name');
	  $_data['email'] = $this->input->post('emailid');
	  $_data['id_countries'] = $this->input->post('country');
	  $_data['id_states'] = $this->input->post('state');
	  $_data['city'] = $this->input->post('city');
	  $_data['phone'] = $this->input->post('cnumber');
	  $_data['experience_years'] = $this->input->post('years');
	  $_data['experience_months'] = $this->input->post('months');
	  $_data['position'] = $this->input->post('position');
	  $_data['highest_qualification'] = $this->input->post('highestqualification');
	  $_data['id_careers_divisions'] = $this->input->post('division');
	  $_data['id_departments'] = $this->input->post('department');
	  $_data['id_regions'] = $this->input->post('region');
	  $_data['id_experience_levels'] = $this->input->post('experiencelevel');
	 // $_data['id_feedback_types'] = $this->input->post('feedback');
	  $_data['brief'] = $this->input->post('brief');
	  $_data['created_on'] = date('Y-m-d H:i:s');
	  $_data['ip'] = user_ip();
	  $this->db->insert('careers_applications',$_data);
	  $_data['id'] = $this->db->insert_id();
	  // send emails
	  $this->load->model('send_emails');
	  $this->send_emails->sendCareersToAdmin($_data);
	  $this->send_emails->sendCareersReplyToUser($_data);
	  $return['result'] = 1;
	  $return['redirect_link'] = route_to('careers/thankyou');
  }
  $this->load->view("json",array("return"=>$return));
}

function downloadCV($id)
{
  $app = $this->db->where('id_careers_applications',$id)->get('careers_applications')->row_array();
  if(isset($app['resume']) && !empty($app['resume'])) {
  	$this->load->helper('download');
  	$file=dynamic_img_url().'careers_applications/'.$app['resume'];
	 echo "<script type='text/javascript'>window.open('".$file."', '_download')</script>";
  /*	$data = file_get_contents($file);
  	force_download($app['resume'],$data); */
  }
}

}