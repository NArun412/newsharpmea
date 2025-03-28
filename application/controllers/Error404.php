<?php
class Error404 extends My_Controller {

public function __construct(){
	parent::__construct();
	$this->lang->load('statics');
	$this->langue = $this->lang->lang();
	header("HTTP/1.0 404 Not Found");
}

public function index() {
	$data = array();
	$data["lang"] = $this->langue;
	$data["settings"] = $this->fct->getonerow("settings", array("id_settings" => 1));
	//$data['banners'] = $this->website_fct->get_banners(1);
	$data['intro'] = $this->website_fct->get_dynamic(14);
	$data["seo"] = get_seo(23);
	$this->template->write_view('header','includes/header',$data);
	$this->template->write_view('content','404',$data);
	$this->template->write_view('footer','includes/footer',$data);
	$this->template->render();
}

}