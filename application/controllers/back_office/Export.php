<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Export extends MY_Controller {
	
public function __construct()
{
	parent::__construct();
	$this->module = $this->uri->segment(4);
	$this->id = $this->uri->segment(5);
	$this->conmod = $this->uri->segment(6);
	$this->load->model("export_m");
	$this->template->set_template("admin");
	$this->admin_dir = admin_dir();
	$content_type_data = $this->fct->getonerow("content_type",array("name"=>$this->module));
	if(!empty($content_type_data))
	$cond=array('id_content'=>$content_type_data['id_content']);
	$this->fields =$this->fct->get_module_fields($this->module);
}

public function index(){
 exit();
}

public function display(){
check_permission($this->module,'create');
$title = $this->module."_".date('Y_m_d_h_i_s');
$import_row = $this->fct->getonerow("export", array("tables" => $this->module, "status" => 1));
$fields = explode(',',$import_row["fields"]);
$q = "SELECT ".$import_row["fields"]." FROM `".$this->module."` ORDER BY `created_on` DESC";
//$primary_key = (array_key_exists('id_'.$content_type, $import_row)) ? true : false;
$this->export_m->to_excel($q,$title);
}	
	
}