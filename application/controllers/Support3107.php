<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Support extends My_Controller{

public function __construct(){
	parent::__construct();
	$this->lang->load('statics');
	$this->langue = $this->lang->lang();
	$this->limit = 20;
	$this->load->helper('cookie');
    $this->default_langue = default_lang();
}

public function index($cat= NULL,$key=NULL){
    
	$data = array();
	$data["seo"] = get_seo(16);
	$data["lang"] = $this->langue;
	$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
	
	//$data['product_keys'] = $this->website_fct->get_product_keys();
	/*$data['products_languages'] = $this->website_fct->get_products_languages();
	$data['document_types'] = $this->website_fct->get_document_types();
	$data['emulations'] = $this->website_fct->get_emulations();
	$data['operating_systems'] = $this->website_fct->get_operating_systems();*/
	
	$get_results = $this->website_fct->filter_support_categories();
	$data['categories'] = $get_results;
	$data['banners'] = $this->website_fct->get_banners(17);
	$cond = array();
	$like = array();
	$url = route_to('support');
	$p = '/';
        
	if($cat != "0" && $cat != '' && $cat !=NULL) {
            $cond['category'] = $cat;
	}
        $url .= $p.$cat;
        $p = '/';
	if($key != '' && $key !=NULL) {
            
            if ($this->langue == $this->default_langue)
             $like['p.title'] = urldecode($key);
             else
            $like['p1.title'] = urldecode($key);
	
	}
        $url .= $p.urldecode($key);
        $p = '/';
	$cc = $this->website_fct->get_download_center($cond,$like);

	$data['url'] = $url;
	$config['base_url'] = $url;
	$config['total_rows'] = $cc;
	$config['num_links'] = '8';
	$config['use_page_numbers'] = TRUE;
	$config['page_query_string'] = TRUE;
	$config['per_page'] = 9;
	$this->pagination->initialize($config);
	if($this->input->get('per_page') != '')
	$page = ($this->input->get('per_page') - 1) * $config['per_page'];
	else $page = 0;
	$data['count'] = $cc;
	$data['documents'] = $this->website_fct->get_download_center($cond,$like,$config['per_page'],$page);
	//print '<pre>'; print_r($data['documents']);exit;
	/*$data['categories'] = $this->website_fct->get_support_categories();*/
	
	$this->template->write_view('header','includes/header',$data);
	$this->template->write_view('content','content/download_center',$data);
	$this->template->write_view('footer','includes/footer',$data);
	$this->template->render();
}
function get_results()
{
	$get_results = $this->website_fct->filter_support_categories($this->input->post());
	//print '<pre>'; print_r($get_results); exit;
	$data['categories'] = $get_results;
	$data['items'] = array();
	$data['limit'] = $this->limit;
	$data['items_count'] = 0;
	$data['offset'] = 0;
	if(!empty($get_results)) {
		
		if($this->input->post("category") == "") {
			$cat_id = $data['categories'][0]['id'];
		}
		else {
			$cat_id = $this->input->post("category");
		}
		if(is_numeric($cat_id)) {
			$data['selected_category'] = $cat_id;
			if($this->input->post("category") == "")
			$_POST['category'] = $this->input->post("category_filter");
			$data['items_count'] = $this->website_fct->filter_support_products($this->input->post());
			$offset = $this->input->post("offset");
			if($offset == "" || !is_numeric($offset))
			$offset = 0;
			$data['offset'] = $offset;
			$data['items'] = $this->website_fct->filter_support_products($this->input->post(),$this->limit,$offset);
			//print '<pre>'; print_r($data['items']); exit;
		}
	}
	$html = $this->load->view("content/support-results",$data,true);
	$html1 = $this->load->view("content/support-items",$data,true);
	$return['result'] = 1;
	$return['html'] = $html;
	$return['items'] = $html1;
	$return['nct'] = $this->security->get_csrf_hash();
	$this->load->view("json",array("return"=>$return));
}
function popup()
{
	$data['product_keys'] = $this->website_fct->get_product_keys();
	$data['products_languages'] = $this->website_fct->get_products_languages();
	$data['document_types'] = $this->website_fct->get_document_types();
	$data['emulations'] = $this->website_fct->get_emulations();
	$data['operating_systems'] = $this->website_fct->get_operating_systems();
	
	$return['html'] = $this->load->view("content/support-popup",$data,true);
	$this->load->view("json",array("return"=>$return));
}

function download($token)
{
  $token_access = $this->db->where('token',$token)->get('confirmed_downloads')->row_array();
  if(!empty($token_access)) {
	  $get_file = $this->db->where('id_product_support',$token_access['id_product_support'])->get('product_support')->row_array();
	  if(isset($get_file['file']) && !empty($get_file['file'])) {
		$this->load->helper('download');
		//$file=dynamic_img_url().'product_support/file/'.$get_file['file'];
		$file='./uploads/product_support/file/'.$get_file['file'];
	//	exit($file);
		$filedata = file_get_contents($file);
		$this->db->where("id_confirmed_downloads",$token_access['id_confirmed_downloads'])->update("confirmed_downloads",array(
			"used"=>$token_access['used'] + 1
			)
		);
		//exit($filedata);
		/*$data["seo"] = get_seo(10);
		$data['msg'] = $this->website_fct->get_dynamic(18);
		
		$data["lang"] = $this->langue;
		$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
		//$data['banners'] = $this->website_fct->get_banners(9);
		//$data['download_data']
		
		$this->template->write_view('header','includes/header',$data);
		$this->template->write_view('content','content/thankyou',$data);
		$this->template->write_view('footer','includes/footer',$data);
		
		$this->template->render();*/
		force_download($get_file['file'],$filedata); 
		
	  }
	  else {
		  redirect(route_to("error404"));
	  }
  }
  else {
	  redirect(route_to("error404"));
  }
}



function get_confirm_download_form($id)
{
	$data['id'] = $id;
	$this->load->view("content/support-confirm-download",$data);
}
function checkindatabase()
{
	$name = $this->input->post("name");
	$email = $this->input->post("email");
	
	$check = $this->db->where("email",$email)->get("confirmed_downloads")->num_rows();
	if($check > 0) {
		return true;
	}
	else {
		return false;
	}
}
function confirm_download()
{
  $allPostedVals = $this->input->post(NULL, TRUE);
  $this->form_validation->set_rules('fid',lang('file'),'trim|required');
 $this->form_validation->set_rules('name',lang('name'),'trim|required'); 
 $this->form_validation->set_rules('email',lang('email'),'trim|required|valid_email');
  if($this->form_validation->run() == FALSE) {
	  $return['nct'] = $this->security->get_csrf_hash();
	  $return['result'] = 0;
	  $return['errors'] = array();
	  $return['message'] = 'Error!';
	 // $return['captcha'] = $this->fct->createNewCaptcha();
	  $find =array('<p>','</p>');
	  $replace =array('','');
	  foreach($allPostedVals as $key => $val) {
		  if(form_error($key) != '') {
			  $return['errors'][$key] = str_replace($find,$replace,form_error($key));
		  }
	  }
  }
  else {
	 // $in_database = $this->checkindatabase();
	  //if(!$in_database) {
		  $name = $this->input->post("name");
		  $email = $this->input->post("email");
		  set_cookie("sh_do_name",$name);
		  set_cookie("sh_do_email",$email);
	  $_data = array(
	  	'name'=>$name,
		'email'=>$email,
		'id_product_support'=>$this->input->post("fid"),
		'ip_address'=>user_ip(),
		'token'=>md5(time().$this->input->post("name").$this->input->post("email").$this->session->userdata("session_id")),
		'used'=>0
	  );
	  $this->db->insert("confirmed_downloads",$_data);
	  // send emails
	  $this->load->model('send_emails');
	  $this->send_emails->sendDownloadLinkToCustomer($_data);
	  if($this->input->post("add_to_newsletter") == 1) {
	  $check = $this->db->where("email",$this->input->post("email"))->get("newsletter_subscribers")->num_rows();
	  if($check == 0) {
		  $_data1 = array();
	  $_data1["name"]=$this->input->post("name");
	  $_data1["email"]=$this->input->post("email");
	  $_data1["created_on"]=date("Y-m-d H:i:s");
	  $this->db->insert('newsletter_subscribers',$_data1); 
	  $new_id = $this->db->insert_id();	
	  $this->load->model("send_emails");
	  $this->send_emails->sendNewsletterSubscribeReplyToUser($_data1);
		  }
	  }
		//  }
	  $return['result'] = 1;
	 // $return['']
	  $return['redirect_link'] = route_to('support/thankyou');
  }
  $this->load->view("json",array("return"=>$return));
}

public function thankyou(){
$data = array();
$data["seo"] = get_seo(10);
$data['msg'] = $this->website_fct->get_dynamic(18);

$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
//$data['banners'] = $this->website_fct->get_banners(9);

$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/thankyou',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}

public function service_center_location_DELETED(){
$data = array();
$data["seo"] = get_seo(8);
$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
$data['banners'] = array();
$data['countries'] = $this->website_fct->get_countries(TRUE);
$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/contact-service-center',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}
function filter_service_centers_DELETED()
{
	$country = $this->input->post('country');
	$state = $this->input->post('state');
	$product = $this->input->post('product');
	$view_results = $this->input->post('view_results');
	$return = array();
	$get_results = $this->website_fct->search_service_centers($country,$state,$product);
	$data['results'] = $get_results;
	if($view_results == 'map') {
		$locations = array();
		if(!empty($get_results)) {
			$i=0;
			foreach($get_results as $key => $res) {
				if(!empty($res['map_co'])) {
					$longitude = '';
					$latitude = '';
					$arr11233 = explode(",",$res['map_co']);
					if(count($arr11233) == 2) {
						$longitude = $arr11233[0];
						$latitude = $arr11233[1];
						$locations[$i]['div'] = '<article class="last onMap">';
						$locations[$i]['div'] .= '<div class="description dark">'.$res['address'].'</div>';
						$locations[$i]['div'] .= '</article>';
						$locations[$i]['longitude'] = $longitude;
						$locations[$i]['latitude'] = $latitude;
						$locations[$i]['zoom'] = 2;
						$i++;
					}
				}
			}
		}
		$return['locationsdata'] = $locations; 
		$return['display_map'] = 1; 
		$return['html'] = $this->load->view("content/contact-service-center-map",$data,true);
	}
	else {
		$return['display_map'] = 0; 
		$return['html'] = $this->load->view("content/contact-service-center-list",$data,true);
	}
	$this->load->view('json',array('return'=>$return));
}


}