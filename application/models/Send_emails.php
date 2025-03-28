<?php

class Send_emails extends CI_Model {
	public function __construct(){
	parent::__construct();
	$this->load->library('email');
        
	$this->langue = $this->lang->lang();
	require('mail/sendMail.php');
            
	$this->email_from_user = 'web_ced@smef.sharp-world.com';
	$this->email_from_admin = 'web_ced@smef.sharp-world.com';
	
	$this->lang_ex = '';
	$this->lang_dir = '';
	if($this->langue == 'ar') {
		$this->lang_ex = '_ar';
		$this->lang_dir = 'dir="rtl"';
	}
	

}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function sendContactToAdmin($data)
{
	  $config= Array(
	  	'mailtype'  => 'html',
		'charset' => 'UTF-8',
		'wordwrap' => TRUE
	  );
	  $condition1 = array('id_settings'=>1);
	  $admindata =$this->fct->getonerow('settings',$condition1);
	  $adminemail = $admindata['email'];
	  $adminname = $admindata['website_title'];
	  $subject = 'New feedback form submitted on '.$data['created_on'];
	 // $condition2 = array('id_emails_templates'=>1);
	  $replymessage =$this->website_fct->get_email_template(1);
	  if(isset($replymessage['email']) && !empty($replymessage['email'])) $adminemail = $replymessage['email'];
	  
	  $post_to_crm = FALSE;
	  if(isset($data['id_categories']) && $data['id_categories'] != 0) {
		  $div_id = $this->website_fct->get_div_id_by_category($data['id_categories']);
		  if($div_id) {
		  	$div = get_cell("divisions","email","id_divisions",$div_id);
			if(!empty($div)) {
				$adminemail = $div;
				if($div_id == 1)
				$post_to_crm = TRUE;
			}
		  }
	  }
	  $sysdata = date("Y-m-d h:i:s");
	  $body = '<p>'.$subject.'.</p>';
	  $body .= $this->load->view('emails/contact',array('form'=>$data),true);
  	  //$body.='<p><strong>Thank you, <a href="'.base_url().'" target="_blank">'.$adminname.'</a></strong></p>';
  	 // $body.='<p><strong>Thank you, '.$adminname.'</strong></p>';
 	  //$body.='<p><img src="'.base_url().'uploads/website/logo.png" /></p>';
	  // get admin email
	  $this->email->clear(TRUE);
	  $this->email->initialize($config);
	  $this->email->set_newline("\r\n");
	  $this->email->from($this->email_from_admin,$adminname.' - Contact Us Form');

	  $this->email->to($adminemail);
 	  $this->email->subject($subject);
	  $this->email->message($body);
          $test = new CI_Sendmail();
           $test->sendMail($adminemail,$subject,$body);
            if($post_to_crm) {
                    $this->post_inquiry_to_crm($data);
            }
	  /*if(!if_localhost()) {
	  	$this->email->sendMail();
		if($post_to_crm) {
			$this->post_inquiry_to_crm($data);
		}
	  }*/
}	
function post_inquiry_to_crm($data)
{
	$params = array();
	$name_arr = explode(" ",$data['name']);
	$first_name = $name_arr[0];
	$last_name = '';
	$comma = '';
	if(count($name_arr) > 1) {
		for($i=1;$i<count($name_arr);$i++) {
			$last_name .= $comma.$name_arr[$i];
			$comma = ' ';
		}
	}
	$params['oid'] = '00D460000017JfB';
	$params['lead_source'] = 'Sharp MEA Website';
	$params['first_name'] = $first_name;
	$params['last_name'] = $last_name;
	$params['email'] = $data['email'];
	$params['phone'] = $data['phone'];
	$params['company'] = 'empty';
	$params['title'] = 'empty';
	$params['00N46000009uqz1'] = get_cell("countries","title","id_countries",$data['id_countries']);
	$params['00N46000009uqzB'] = $data['subject'];
	$params['00N4600000AsDpP'] = ''; //  product model (optional)
	$params['00N4600000AsDpU'] = $data['message']; // message
	if ($params) {
		$kv = array();
		foreach ($params as $key => $value) {
			$kv[] = stripslashes($key) . "=" . stripslashes($value);
		}
		$query_string = join("&", $kv);
	}
	if (!function_exists('curl_init')){
		die('Sorry cURL is not installed!');
	}
	$url = 'https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($kv));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	$result = curl_exec($ch);
	curl_close($ch);
    //var_dump($result);exit; // Show output	
	return true;
}
public function sendDownloadLinkToCustomer($data)
{
	$config= Array(
	  'mailtype'  => 'html'
	  );
	  $condition1 = array('id_settings'=>1);
	  $admindata =$this->fct->getonerow('settings',$condition1);
	  $adminemail = $admindata['email'];
	  $adminname = $admindata['website_title'.$this->lang_ex];
 	  $name = $data['name'];
	 //$condition2 = array('id_emails_templates'=>4);
	  $replymessage =$this->website_fct->get_email_template(4);
	  //print '<pre>'; print_r($replymessage); exit;
	  if(isset($replymessage['email']) && !empty($replymessage['email'])) $adminemail = $replymessage['email'];
	  $message = '';
	  $message = $replymessage['message'];
	  $link = route_to("support/download/".$data['token']);
	  $find = array("{link}","{product}");
	  $replace = array($link,get_cell_translate('products','title','id_products',(isset($data['id_products']) ? $data['id_products'] : $data['id_product_support'])));
	  $subject = str_replace($find,$replace,$replymessage['email_subject']);
	  $message = str_replace($find,$replace,$message);
	  // get reply message
	  $body = $this->userReplytemplate($name,$message,$adminname);
	  //$body.='<p><img src="'.base_url().'uploads/website/logo.png" /></p>';
	  // get admin email
	  $this->email->clear(TRUE);
	  $this->email->initialize($config);
	  $this->email->set_newline("\r\n");
	  $this->email->from($adminemail,$adminname);
	  $this->email->to($data['email']);
	  $this->email->subject($subject);
	  $this->email->message($body);
	  //if(!if_localhost())
	  $test = new CI_Sendmail();
          $test->sendMail($data['email'],$subject,$body);
}
public function sendContactReplyToUser($data)
{
	 $config= Array(
	  'mailtype'  => 'html'
	  );
	  $condition1 = array('id_settings'=>1);
	  $admindata =$this->fct->getonerow('settings',$condition1);
	  $adminemail = $admindata['email'];
	  $adminname = $admindata['website_title'.$this->lang_ex];
	//  exit($this->lang_ex.','.$adminname);
 	  $name = $data['name'];
	 // $condition2 = array('id_emails_templates'=>1);
	  $replymessage =$this->website_fct->get_email_template(1);
	  if(isset($replymessage['email']) && !empty($replymessage['email'])) $adminemail = $replymessage['email'];
	  $message = '';
	  $message = $replymessage['message'];
	  // get reply message
	  $body = $this->userReplytemplate($name,$message,$adminname);
	  //$body.='<p><img src="'.base_url().'uploads/website/logo.png" /></p>';
	  // get admin email
	  $this->email->clear(TRUE);
	  $this->email->initialize($config);
	  $this->email->set_newline("\r\n");
	  $this->email->from($adminemail,$adminname);
	  $this->email->to($data['email']);
	  $this->email->subject($replymessage['email_subject']);
	  $this->email->message($body);
	  //if(!if_localhost())
	  //$this->email->send();
           $test = new CI_Sendmail();
            $test->sendMail($data['email'],$replymessage['email_subject'],$body);
	 // $this->email->send();
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function sendCareersToAdmin($data)
{
	  $config= Array(
	  	'mailtype'  => 'html'
	  );
	  
	  $condition1 = array('id_settings'=>1);
	  $admindata =$this->fct->getonerow('settings',$condition1);
	  $adminemail = $admindata['email'];
	  $adminname = $admindata['website_title'];
	  
	  $subject = 'New CV submitted on '.$data['created_on'];

	 // $condition2 = array('id_emails_templates'=>2);
	  $replymessage =$this->website_fct->get_email_template(2);
	  if(isset($replymessage['email']) && !empty($replymessage['email'])) $adminemail = $replymessage['email'];
	  
	  $sysdata = date("Y-m-d h:i:s");
	  
	  $body = '<p>'.$subject.'.</p>';
	  $body .= $this->load->view('emails/careers',array('form'=>$data),true);
	 
      //$body.='<p><strong>Thank you, <a href="'.base_url().'" target="_blank">'.$adminname.'</a></strong></p>';
      //$body.='<p><strong>Thank you, '.$adminname.'</strong></p>';
      //$body.='<p><img src="'.base_url().'uploads/website/logo.png" /></p>';

	  // get admin email
	  $this->email->clear(TRUE);
	  $this->email->initialize($config);
	  $this->email->set_newline("\r\n");
	  $this->email->from($this->email_from_admin,$adminname.' - Careers Form');
	  $this->email->to($adminemail);
 	  $this->email->subject($subject);
	  $this->email->message($body);
          $test = new CI_Sendmail();
          $test->sendMail($adminemail,$subject,$body);
	  /*if(!if_localhost())
	  $this->email->send();*/
}	
public function sendCareersReplyToUser($data)
{
	 $config= Array(
	  'mailtype'  => 'html'
	  );
	  $condition1 = array('id_settings'=>1);
	  $admindata =$this->fct->getonerow('settings',$condition1);
	  $adminemail = $admindata['email'];
	  $adminname = $admindata['website_title'.$this->lang_ex];
 $name = $data['name'];
	  //$condition2 = array('id_emails_templates'=>2);
	  $replymessage =$this->website_fct->get_email_template(2);
	  if(isset($replymessage['email']) && !empty($replymessage['email'])) $adminemail = $replymessage['email'];
	  $message = '';
	  $message = $replymessage['message'];
	  // get reply message
	  $body = $this->userReplytemplate($name,$message,$adminname);
	 //$body.='<p><img src="'.base_url().'uploads/website/logo.png" /></p>';
	  // get admin email
	  $this->email->clear(TRUE);
	  $this->email->initialize($config);
	  $this->email->set_newline("\r\n");
	  $this->email->from($adminemail,$adminname);
	  $this->email->to($data['email']);
	  $this->email->subject($replymessage['email_subject']);
	  $this->email->message($body);
          $test = new CI_Sendmail();
          $test->sendMail($data['email'],$replymessage['email_subject'],$body);
	  /*if(!if_localhost())
	  $this->email->send();*/
	 // $this->email->send();
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function userReplytemplate($name,$message,$adminname)
{
	//$body = '<p '.$this->lang_dir.'>'.lang('Dear').' '.$name.'</p>';
	$body = '<p '.$this->lang_dir.'>'.$message.'</p>';
	//$body.='<p><strong>Thank you, <a href="'.base_url().'" target="_blank">'.$adminname.'</a></strong></p>';
  $body.='<p '.$this->lang_dir.'><strong>'.$adminname.'</strong></p>';
	$body.='<p '.$this->lang_dir.'><img src="'.assets_url().'images/sharp-logo.png" /></p>';
	return $body;
}
////////////////////////////////////////////////////////////////////////////////////////////
public function sendNewsletterSubscribeReplyToUser($data)
{
	 $config= Array(
	  'mailtype'  => 'html'
	  );
	  $condition1 = array('id_settings'=>1);
	  $admindata =$this->fct->getonerow('settings',$condition1);
	  $adminemail = $admindata['email'];
	  $adminname = $admindata['website_title'.$this->lang_ex];
 	  $name = $data['name'];
	 // $condition2 = array('id_emails_templates'=>3);
	  $replymessage =$this->website_fct->get_email_template(3);
	  if(isset($replymessage['email']) && !empty($replymessage['email'])) $adminemail = $replymessage['email'];
	  $message = '';
	  $message = $replymessage['message'];
	  // get reply message
	  $body = $this->userReplytemplate($name,$message,$adminname);
	  //$body.='<p><img src="'.base_url().'uploads/website/logo.png" /></p>';
	  // get admin email
	  $this->email->clear(TRUE);
	  $this->email->initialize($config);
	  $this->email->set_newline("\r\n");
	  $this->email->from($adminemail,$adminname);
	  $this->email->to($data['email']);
	  $this->email->subject($replymessage['email_subject']);
	  $this->email->message($body);
	 // if(!if_localhost())
	  //$this->email->send();
	 $test = new CI_Sendmail();
          $test->sendMail($data['email'],$replymessage['email_subject'],$body);
}
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////	
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////

public function sendPasswordRequest($data,$request)
{
	$config= Array(
	  'mailtype'  => 'html'
	  );
	  $condition1 = array('id_settings'=>1);
	  $admindata =$this->fct->getonerow('settings',$condition1);
	  $adminemail = $admindata['email'];
	  $adminname = $admindata['website_title'.$this->lang_ex];

	  // get reply message
	  $reset_link = admin_url('login/loginbypassword').'?token='.$request['token'];
	  if($this->lang->lang()=='ar') {
		  $body = '<p '.getDir().'>'.$data['name'].',</p>';
	  	  $body .= '<p '.getDir().'> تم تقديم طلب لإعادة تعيين كلمة المرور لحسابك في مركز '.$adminname.'.</p>';
      	  $body .= '<p '.getDir().'>تستطيع الآن تسجيل الدخول من خلال النقر على هذا الرابط أو نسخ ولصق إلى المتصفح الخاص بك:<br /><a href="'.$reset_link.'">'.$reset_link.'</a></p>';
          $body .= '<p '.getDir().'>يمكن استخدام هذا الرابط مرة واحدة فقط للدخول وسوف يقودك إلى صفحة حيث يمكنك تعيين كلمة المرور الجديدة الخاصة بك. يرجى الأخذ بعين الإعتبار بانتهاء صلاحية الرابط بعد خمسة أيام.</p>';
	  }
	  else {
		  $body = '<p>'.lang('dear').' '.$data['name'].',</p>';
	  	  $body .= '<p>A request to reset the password for your account has been made at '.$adminname.'.</p>';
      	  $body .= '<p>You may now log in by clicking this link or copying and pasting it to your browser:<br /><a href="'.$reset_link.'">'.$reset_link.'</a></p>';
          $body .= '<p>This link can only be used once to log in and will lead you to a page where you can set your password. It expires after five days and nothing will happen if it\'s not used.</p>';
	  }
	/*  if($this->get_domain($data['email'])) {
		$config = $this->getSMTPConfig();
	}*/
	 // $body.='<p><strong>'.lang('thank_you').' <a href="'.base_url().'" target="_blank">'.$adminname.'</a></strong></p>';
	  // get admin email
	  $this->email->clear(TRUE);
	  $this->email->initialize($config);
	  $this->email->set_newline("\r\n");
	 /* if($this->get_domain($data['email'])) {
		  $this->email->from($this->defaultEmail(),$adminname);
	  }
	  else {
		  $this->email->from($adminemail,$adminname);
	  }*/
	  $this->email->from($this->email_from_user);
	  $this->email->to($data['email']);
	  $this->email->subject($adminname.', '.lang('password_request'));
	  $this->email->message($body); 
	  $this->email->send();
}
/////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////	

}