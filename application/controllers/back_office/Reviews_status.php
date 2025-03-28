<?php
if ( ! defined("BASEPATH")) exit("No direct script access allowed");
class Reviews_status extends MY_Controller {

public function __construct()
{
	parent::__construct();
	$this->template->set_template("admin");
	$this->admin_dir = admin_dir();
	$this->module = $this->uri->segment(4);
	//$this->module = $this->module.'_review';
	$this->fields = array();
	$this->id = 0;
}
public function manage($module,$id,$lang) {
	check_permission($module,'edit',$id);
	$this->id = $id;
	$data["title"]="Review Content ".$module;
	$model_custom = $module.'_m';
	if(file_exists(APPPATH."models/".$this->admin_dir."/".$model_custom.".php")){
	   $this->load->model($this->admin_dir.'/'.$model_custom);
	   $mod = $model_custom;
	}
	else{
	  $mod = 'manage_m';
	}
	if($lang == default_lang()) {
		$this->fields = $this->fct->get_module_fields($module);
	}
	else {
		$this->fields = $this->fct->get_module_fields($this->module,FALSE,FALSE,array('translated'=>1));
	}
	$this->load->model($this->admin_dir."/".$mod);
	$cond=array("id_".$module=>$id);
	$data["id"]=$id;
	
	//print '<pre>'; print_r($this->fields); exit;
	$data['content_type'] = $module;
	if(isset($_SERVER['HTTP_REFERER']))
	$this->session->set_userdata("admin_redirect_link",$_SERVER['HTTP_REFERER']);
	else
	$this->session->set_userdata("admin_redirect_link",admin_url("dashboard"));
	//exit($mod);
	if($lang == default_lang()) {
		$data["info"] = $this->$mod->get_from_review($id);
	}
	else {
		$data["info"] = $this->$mod->get_translation_from_review($this->module,$id,$lang);
		//print '<pre>'; print_r($data["info"]); exit;
	}

	$section = $module;
	
	$data["languages"] = $this->db->get("languages")->result_array();
	$data["section"] = $section;
	$data['lang'] = $lang;
	$data['record'] = get_cell($section,"translation_id","id_".$section,$id);
	//print '<pre>'; print_r($data['info']); exit;
	$this->template->write_view('header',$this->admin_dir.'/includes/header',$data);
	$this->template->write_view('leftbar',$this->admin_dir.'/includes/leftbar',$data);
	$this->template->write_view('rightbar',$this->admin_dir.'/includes/rightbar',$data);
	$this->template->write_view('content',$this->admin_dir.'/fields/form',$data);
	$this->template->render();

}
}