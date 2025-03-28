<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Md extends My_Controller{

public function __construct(){
parent::__construct();
	$this->lang->load('statics');
	$this->langue = $this->lang->lang();
}

public function index(){
$data = array();
$data["seo"] = get_seo(12);
$data["lang"] = $this->langue;
$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
$data['banners'] = $this->website_fct->get_banners(14);
$data['intro'] = get_page(8);
//print '<pre>'; print_r($data['intro']); exit;
$this->template->write_view('header','includes/header',$data);
$this->template->write_view('content','content/md',$data);
$this->template->write_view('footer','includes/footer',$data);
$this->template->render();
}

}