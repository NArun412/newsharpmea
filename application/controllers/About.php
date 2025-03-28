<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class About extends My_Controller{

public function __construct(){
parent::__construct();
	$this->lang->load('statics');
	$this->langue = $this->lang->lang();
}

public function index(){
$data = array();
$data["seo"] = get_seo(2);
$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));

$data['products_history'] = $this->website_fct->get_products_history();
$data['get_history_max_year'] = $this->website_fct->get_history_max_year();
$data['get_history_min_year'] = $this->website_fct->get_history_min_year();
$data['banners'] = $this->website_fct->get_banners(2);
$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/history',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}

function load_timeline($y1,$y2)
{
	if(!is_numeric($y1) || !is_numeric($y2)) {
		$return['error'] = 'access denied';
	}
	else {
		$html = '';
		$data['timeline'] = $this->website_fct->get_history_timeline($y1,$y2);
		$html = $this->load->view("content/history-timeline",$data,true);
		$return['html'] = $html;
	}
	$this->load->view("json",array("return"=>$return));
}

public function corporate_profile($id = '') {
	$data = array();
	$data['categories'] = $this->website_fct->get_corporate_profile_categories();
	//print '<pre>';print_r($data['categories']);exit;
	if($id == '' || !is_numeric($id)) {
		//exit('WW');
		//exit($data['categories'][0]['id']);
		//exit(route_to("about/corporate_profile/".$data['categories'][0]['id']));
		redirect( route_to("about/corporate_profile/".$data['categories'][0]['id']),302 );
	}
	//exit('AA');
$data['category'] = $this->website_fct->get_corporate_profile_one_category($id);
//$data["seo"] = $data['category'];
$data["seo"] = get_seo(0,'corporate_profile_categories',$id);

$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
$data['banners'] = $this->website_fct->get_banners(3);

$data['corporate_profile_items'] = $this->website_fct->corporate_profile_items($id);
//print '<pre>';print_r($data['corporate_profile_items']);exit;
$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/corporate_profile',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}

public function philosophy(){
$data = array();
$data["seo"] = get_seo(3);
$data["intro"] = get_page(10);
//print '<pre>'; print_r($data['intro']); exit;
$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
$data['banners'] = $this->website_fct->get_banners(11);
$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/philosophy',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}

public function environmental_csr($id = ''){
$data = array();

$data['all_envcsr'] = $this->website_fct->get_all_envcsr();
	if($id == '' || !is_numeric($id)) {
		redirect( route_to("about/environmental_csr/".$data['all_envcsr'][0]['id']),302 );
	}



$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
$data['banners'] = $this->website_fct->get_banners(12);
$data['all_envcsr'] = $this->website_fct->get_all_envcsr();
if($id != '' && is_numeric($id) ) {
	$data['envcsr'] = $this->website_fct->get_one_envcsr($id);
	//$data["seo"] = $data['envcsr'];
}
else {
	$data['envcsr'] = $this->website_fct->get_first_envcsr();
}

$data["seo"] = get_seo(0,'environmental_and_csr',$data['envcsr']['id']);

$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/environmental_csr',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}

}