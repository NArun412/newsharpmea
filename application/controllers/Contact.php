<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contact extends My_Controller{

public function __construct(){
	parent::__construct();
	$this->lang->load('statics');
	$this->langue = $this->lang->lang();
}

public function index(){
$data = array();
$data["seo"] = get_seo(7);
$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
$data['banners'] = $this->website_fct->get_banners(9);

$data['contact_info'] = $this->website_fct->get_office(1);

$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/contact',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}



public function inquiry(){
$data = array();
$data["seo"] = get_seo(9);
$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
$data['banners'] = $this->website_fct->get_banners(9);

$data['countries'] = $this->website_fct->get_countries(FALSE);
$data['feedbacktypes'] = $this->website_fct->get_feedback_types();

$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/contact-inquiry',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}

public function thankyou(){
$data = array();
$data["seo"] = get_seo(10);
$data['msg'] = $this->website_fct->get_dynamic(15);

$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
//$data['banners'] = $this->website_fct->get_banners(9);

$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/thankyou',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}

//
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

public function submit()
{
  //print '<pre>'; print_r($_POST); exit;
  $allPostedVals = $this->input->post(NULL, TRUE);
  $this->form_validation->set_rules('name',lang('name'),'trim|required');
  $this->form_validation->set_rules('email',lang('email'),'trim|required|valid_email');
  $this->form_validation->set_rules('country',lang('country'),'trim|required');
 // $this->form_validation->set_rules('state',lang('state'),'trim|required');
  $this->form_validation->set_rules('city',lang('city'),'trim|required');
 // $this->form_validation->set_rules('address',lang('address'),'trim|required');
  $this->form_validation->set_rules('cnumber',lang('contact number'),'trim|required');
 //$this->form_validation->set_rules('feedback',lang('Feedback/Complaint'),'trim|required');
  $this->form_validation->set_rules('subject',lang('Subject'),'trim');
  $this->form_validation->set_rules('message',lang('message'),'trim|required');
  $this->form_validation->set_rules('captcha',"Captcha",'trim|required|callback_validate_captcha[]');
  if($this->form_validation->run() == FALSE) {
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
      //====restrict public email domain========
       $tempDomainName=substr(strrchr($_POST['email'], "@"), 1);
       $domainName=substr($tempDomainName, 0, strrpos($tempDomainName, '.'));
       $publicDomain=array('gmail','yahoo','hotmail','outlook','msn','googlemail','live','zoho','gmx'); 
       
       if($_POST['subject'] == '1' && in_array($domainName, $publicDomain)){
                $return['nct'] = $this->security->get_csrf_hash();
                $return['result'] = 0;
                $return['errors']['email'] =lang('PUBLIC DOMAIN RESTRICTION');
                $return['message'] = 'Error!';
                $return['captcha'] = $this->fct->createNewCaptcha();
        }else{ 
                $_data['name'] = $this->input->post('name');
                $_data['email'] = $this->input->post('email');
                $_data['id_countries'] = intval($this->input->post('country'));
               // $_data['id_states'] = $this->input->post('state');
                $_data['city'] = $this->input->post('city');
                $_data['phone'] = $this->input->post('cnumber');
                //$_data['address'] = $this->input->post('address');
               // $_data['id_feedback_types'] = $this->input->post('feedback');
               $_data['subject'] = 'General Inquiry';
               $_data['id_categories'] = $this->input->post("subject");
               if($_data['id_categories'] != 0 && is_numeric($_data['id_categories']))
                $_data['subject'] = get_cell("categories","title","id_categories",$_data['id_categories']);
                $_data['message'] = $this->input->post('message');
                $_data['created_on'] = date('Y-m-d H:i:s');
                $_data['ip'] = user_ip();
                $this->db->insert('contact_form',$_data);
                $_data['id'] = $this->db->insert_id();
                // send emails
                $this->load->model('send_emails');
                $this->send_emails->sendContactToAdmin($_data);
                $this->send_emails->sendContactReplyToUser($_data);
                $return['result'] = 1;
                $return['redirect_link'] = route_to('contact/thankyou');
        }
  }
  $this->load->view("json",array("return"=>$return));
}	


}