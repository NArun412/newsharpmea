<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends MY_Controller {
public function __construct()
{
parent::__construct();
$this->template->set_template("admin");
}

public function index(){
$data["title"] = "Dashboard";
$data['reviews'] = $this->fct->get_reviews();
//print '<pre>'; print_r($data['reviews']); exit;
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/dashboard',$data);
$this->template->render();
}
	
	
}