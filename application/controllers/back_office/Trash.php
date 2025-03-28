<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Trash extends MY_Controller {
public function __construct()
{
parent::__construct();
$this->template->set_template("admin");
}

public function index(){
$data["title"] = "Trash";
$data['trash'] = $this->fct->get_trash();
$url = admin_url('trash');
$data["show_items"] = admin_per_page();	
$cond = array();
$like = array();
$url_parm_q = "?";
if($this->input->get("title") != "") {
	$like["title"] = $this->input->get("title");
	$url .= $url_parm_q."title=".$this->input->get("title");
	$url_parm_q = "&";
}
if($this->input->get("module") != "") {
	$cond["module"] = $this->input->get("module");
	$url .= $url_parm_q."module=".$this->input->get("module");
	$url_parm_q = "&";
}
// pagination  start :
$count_news = $this->fct->get_trash($cond,$like);
$data['count_records'] = $count_news;
$show_items = ($data["show_items"] == 'All') ? $count_news : $data["show_items"];
$this->load->library('pagination');
$config['base_url'] = $url;
$config['total_rows'] = $count_news;
$config['per_page'] = $data["show_items"];
$config['use_page_numbers'] = TRUE;
$config['page_query_string'] = TRUE;
$this->pagination->initialize($config);
if($this->input->get('per_page') != '')
$page = $this->input->get('per_page');
else $page = 0;
$data['info'] = $this->fct->get_trash($cond,$like,$config['per_page'],$page);
$this->session->set_userdata("admin_redirect_link",$url);
// end pagination .

$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/trash',$data);
$this->template->render();
}
	
	
}