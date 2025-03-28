<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Access_denied extends MY_Controller {
public function __construct()
{
parent::__construct();
$this->template->set_template("admin");
}

public function index(){
	exit("You do not have permission to access this section");
/*$data["title"] = "Dashboard";
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/dashboard',$data);
$this->template->render();*/
}
	
	
}