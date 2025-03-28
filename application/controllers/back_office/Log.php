<?php
if ( ! defined("BASEPATH")) exit("No direct script access allowed");
class Log extends MY_Controller {

public function __construct()
{
parent::__construct();
$this->table="log";
$this->load->model(admin_dir()."/log_m");
$this->load->model(admin_dir()."/users_m");
$this->template->set_template("admin");
/*$this->module = 'users';
$this->admin_dir = admin_dir();
$this->id = $this->uri->segment(5);
$content_type_data = $this->fct->getonerow("content_type",array("name"=>$this->module));
$cond=array('id_content'=>$content_type_data['id_content']);
$this->fields =$this->fct->get_module_fields($this->module);*/
}

public function index(){

$data["title"]="Log";
//
$this->session->unset_userdata("back_link");
//
if($this->input->post('show_items')){
$show_items  =  $this->input->post('show_items');
$this->session->set_userdata('show_items',$show_items);
} elseif($this->session->userdata('show_items')) {
$show_items  = $this->session->userdata('show_items'); 	}
else {
$show_items = admin_per_page();	
}
$data["show_items"] = $show_items;
// filtration
$cond = array();
$like = array();
$url = admin_url("log");
$url_parm_q = "?";


if($this->input->get("uid") != "") {
	$cond["log.uid"] = $this->input->get("uid");
	$url .= $url_parm_q."uid=".$this->input->get("uid");
	$url_parm_q = "&";
}
if($this->input->get("module") != "") {
	$cond["log.module"] = $this->input->get("module");
	$url .= $url_parm_q."module=".$this->input->get("module");
	$url_parm_q = "&";
}
// end filtration
// pagination  start :
$count_news = $this->log_m->getall($cond);
$data['count_records'] = $count_news;
$show_items = ($show_items == 'All') ? $count_news : $show_items;
$this->load->library('pagination');
$config['base_url'] = $url;
$config['total_rows'] = $count_news;
$config['per_page'] = $show_items;
$config['use_page_numbers'] = TRUE;
$config['page_query_string'] = TRUE;
$this->pagination->initialize($config);
if($this->input->get('per_page') != '')
$page = $this->input->get('per_page');
else $page = 0;
if($page == 0)
$offset = 0;
else
$offset = ($page - 1) * $config['per_page'];
$data['info'] = $this->log_m->getall($cond,$config['per_page'],$offset);
$this->session->set_userdata("admin_redirect_link",$url);

$data['modules'] = $this->fct->get_content_types();
$data['users'] = $this->users_m->getall(array(),array(),10000,0,'name');
// end pagination .
$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/manage/log',$data);
$this->template->render();
}
/*
public function delete($id){
check_permission('users','delete',$id);
$this->log_m->delete($id);
$this->session->set_userdata("success_message","Information was deleted successfully");
if($this->session->userdata("admin_redirect_link") != "")
redirect($this->session->userdata("admin_redirect_link"));	
else
redirect(admin_url("log/".$this->session->userdata("back_link")));
}
public function delete_all(){
check_permission('log','delete');
$cehcklist= $this->input->post("cehcklist");
$check_option= $this->input->post("check_option");
if($check_option == "delete_all"){
if(count($cehcklist) > 0){
for($i = 0; $i < count($cehcklist); $i++){
if($cehcklist[$i] != ""){
if(has_permission('log','delete',$cehcklist[$i]))
$this->log_m->delete($cehcklist[$i]);
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
if($this->session->userdata("admin_redirect_link") != "")
redirect($this->session->userdata("admin_redirect_link"));	
else
redirect(admin_url("log/".$this->session->userdata("back_link")));	
}
*/

function truncate()
{
	$this->db->truncate('log');
	$this->session->set_userdata("success_message","Data truncated successfully");
	if($this->session->userdata("admin_redirect_link") != "")
		redirect($this->session->userdata("admin_redirect_link"));	
	else
		redirect(admin_url("log"));	
	
}


}