<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class EightKTechnologies extends My_Controller{

public function __construct(){
parent::__construct();
	$this->lang->load('statics');
	$this->langue = $this->lang->lang();
}

public function index() {
    $data = array();
    $data["seo"] = get_seo(1);
    $data["lang"] = $this->langue;

    $this->template->write_view('header','includes/header',$data);
    $this->template->write_view('content','content/aquosTechnologies',$data);
    $this->template->write_view('footer','includes/footer',$data);
    $this->template->render();
}

	
}