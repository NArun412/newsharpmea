<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manage extends MY_Controller {
	
public function __construct()
{
	parent::__construct();
	$this->module = $this->uri->segment(4);
	$this->id = $this->uri->segment(5);
	$this->conmod = $this->uri->segment(6);
	$this->load->model("manage_m");
	$this->template->set_template("admin");
	$this->admin_dir = admin_dir();
	
	$content_type_data = $this->fct->getonerow("content_type",array("name"=>$this->module));
	$cond=array('id_content'=>$content_type_data['id_content']);
	$this->fields =$this->fct->get_module_fields($this->module);
}

public function index(){
	exit();
}

public function display(){
check_permission($this->module,'view');	
$data["title"]="List ".ucfirst($this->module);
$data["show_items"] = admin_per_page();	
// filtration
$cond = array();
$like = array();
$url = admin_url($this->module);
$url_parm_q = "?";
$filters = $this->fct->get_module_fields($this->module,FALSE,TRUE);

if($this->input->get("title") != "") {
	$like["title"] = $this->input->get("title");
	$url .= $url_parm_q."title=".$this->input->get("title");
	$url_parm_q = "&";
}
if($this->input->get("status") != "") {
	$cond["status"] = $this->input->get("status");
	$url .= $url_parm_q."status=".$this->input->get("status");
	$url_parm_q = "&";
}
if(has_parent_foreign($this->module)) {
$cond["id_parent"] = 0;
}
foreach($filters as $filter) {
	$attr_name = str_replace(" ","_",$filter["name"]);
		if($filter["type"] == 2) {
			if($this->input->get($attr_name) != "") {
				$like[$attr_name] = $this->input->get($attr_name);
				$url .= $url_parm_q.$attr_name."=".$this->input->get($attr_name);
				$url_parm_q = "&";
			}
		}
		else {
			if($filter["type"] == 7) {
				$f_table = get_foreign_table($filter['foreign_key']);
				$var = intval($filter['id_content']) - intval($filter['foreign_key']);
				if($var == 0) {
					if($this->input->get("id_parent") != "") {
						$cond["id_parent"] = $this->input->get("id_parent");
						$url .= $url_parm_q."id_parent=".$this->input->get("id_parent");
						$url_parm_q = "&";
					}
				}
				else {
					if($this->input->get("id_".$f_table) != "") {
						$cond["id_".$f_table] = $this->input->get("id_".$f_table);
						$url .= $url_parm_q."id_".$f_table."=".$this->input->get("id_".$f_table);
						$url_parm_q = "&";
					}
				}
			}
			else {
				if($this->input->get($attr_name) != "") {
					$cond[$attr_name] = $this->input->get($attr_name);
					$url .= $url_parm_q.$attr_name."=".$this->input->get($attr_name);
					$url_parm_q = "&";
				}
			}
		}
}
// end filtration
$model_custom = $this->module.'_m';
if(file_exists(APPPATH."models/".$model_custom.".php")){
   $this->load->model($model_custom);
   $model = $model_custom;
}
else{
  $model = 'manage_m';
}
// pagination  start :
$count_news = $this->$model->getall($cond,$like);
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
$data['info'] = $this->$model->getall($cond,$like,$config['per_page'],$page);
$this->session->set_userdata("admin_redirect_link",$url);
// end pagination .
//$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
//$this->template->add_js('assets/js/custom/'.$this->module.'.js');
$this->template->write_view('header',$this->admin_dir.'/includes/header',$data);
$this->template->write_view('leftbar',$this->admin_dir.'/includes/leftbar',$data);
$this->template->write_view('rightbar',$this->admin_dir.'/includes/rightbar',$data);
$this->template->write_view('content',$this->admin_dir.'/manage/list',$data);
$this->template->render();
}

public function add(){
check_permission($this->module,'create');
$data["title"]="Add ".$this->module;
$data['obj'] = 'add-edit';
$this->template->add_js('assets/js/custom/'.$this->module.'.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/fields/form',$data);
$this->template->render();
} 


public function edit(){
check_permission($this->module,'edit',$this->id);
$model_custom = $this->module.'_m';
if(file_exists(APPPATH."models/".$model_custom.".php")){
   $this->load->model($model_custom);
   $model = $model_custom;
}
else{
  $model = 'manage_m';
}
$data["id"]=$this->id;
$data["info"]=$this->$model->get($this->id);
//print '<pre>'; print_r($data["info"]); exit;
if(isset($data["info"]['title']))
$data["title"]="Edit ".$this->module.": ".$data["info"]['title'];
else
$data["title"]="Edit ".$this->module." ";

$data['obj'] = 'add-edit';

//$this->template->add_js('assets/js/custom/'.$this->module.'.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/fields/form',$data);
$this->template->render();
}

public function seo(){
check_permission($this->module,'edit',$this->id);
$model_custom = $this->module.'_m';
if(file_exists(APPPATH."models/".$model_custom.".php")){
   $this->load->model($model_custom);
   $model = $model_custom;
}
else{
  $model = 'manage_m';
}
$this->fields =$this->fct->get_module_fields("seo");

$data["info"]=$this->$model->get_seo($this->id);
//print_r($data['info']);exit;
if(!empty($data['info']))
$data["id"]=$data['info']['id_seo'];
//print '<pre>'; print_r($data["info"]); exit;
if(isset($data["info"]['title']))
$data["title"]="Edit SEO ".$this->module.": ".$data["info"]['title'];
else
$data["title"]="Edit SEO ".$this->module." ";
$this->template->add_js('assets/js/custom/'.$this->module.'.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/fields/form',$data);
$this->template->render();
}

public function mod(){
check_permission($this->module,'edit',$this->id);
$model_custom = $this->module.'_m';
if(file_exists(APPPATH."models/".$model_custom.".php")){
   $this->load->model($model_custom);
   $model = $model_custom;
}
else{
  $model = 'manage_m';
}
$this->fields =$this->fct->get_module_fields($this->conmod);

$data["info"]=$this->$model->get_seo($this->id);
if(!empty($data['info']))
$data["id"]=$data['info']['id_seo'];
//print '<pre>'; print_r($data["info"]); exit;
if(isset($data["info"]['title']))
$data["title"]="Edit ".$this->module.": ".$data["info"]['title'];
else
$data["title"]="Edit ".$this->module." ";

$data['obj'] = 'mod-list';
$this->template->add_js('assets/js/custom/'.$this->module.'.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/fields/form',$data);
$this->template->render();
}


public function submit(){
if($this->input->post("id")!="")
check_permission($this->module,'edit',$this->input->post("id"));
else
check_permission($this->module,'create');
$model_custom = $this->module.'_m';
if(file_exists(APPPATH."models/".$model_custom.".php")){
   $this->load->model($model_custom);
   $model = $model_custom;
}
else{
  $model = 'manage_m';
}
//print '<pre>'; print_r($this->input->post()); exit;
$data["title"]="Add / Edit ".$this->module;
$this->form_validation->set_rules("title", "TITLE", "trim|required");
if(seo_enabled($this->module)) {
	$this->form_validation->set_rules("meta_title", "PAGE TITLE", "trim");
	$this->form_validation->set_rules("url_route", "TITLE URL", "trim");
	$this->form_validation->set_rules("meta_description", "META DESCRIPTION", "trim");
	$this->form_validation->set_rules("meta_keywords", "META KEYWORDS", "trim");
}
foreach($this->fields as $val) {
	if($val['type'] == 7) {
		$var  = intval('id_content') - intval($val['foreign_key']);
		if($var == 0) {
			$this->form_validation->set_rules("id_parent", $val["name"], $val["validation"]);
		}
		else {
			$content001 = get_cell("content_type","used_name","id_content",$val["foreign_key"]);
			$this->form_validation->set_rules("id_".$content001, $val["name"], $val["validation"]);
		}
	}
	elseif($val['type'] == 4 || $val['type'] == 5) {
		if(!empty($_FILES[str_replace(" ","_",$val["name"])]["name"])) {
			$_POST[str_replace(" ","_",$val["name"])] = $_FILES[str_replace(" ","_",$val["name"])]["name"];
		}
		else {
			//$_POST["'.str_replace(" ","_",$val["name"]).'"] = "";
		}
		$this->form_validation->set_rules(str_replace(" ","_",$val["name"]), $val["name"], $val["validation"]);
	}
	elseif($val['type'] == 10) {
		$this->form_validation->set_rules(str_replace(" ","_",$val["name"]), $val["name"], $val["validation"]);
	}
	elseif($val['type'] == 12) {
		if($this->input->post(str_replace(" ","_",$val["name"])) == "")
		$_POST[str_replace(" ","_",$val["name"])] = '';
		$this->form_validation->set_rules(str_replace(" ","_",$val["name"]), $val["name"], $val["validation"]);
	}
	else {
		$this->form_validation->set_rules(str_replace(" ","_",$val["name"]), $val["name"], $val["validation"]);
	}
}
if ($this->form_validation->run() == FALSE){
	//print '11<pre>'; print_r($this->input->post()); exit;
	if($this->input->post("id")!="")
	$this->edit();
	else
	$this->add();
} 
else {
	$_data["title"]=$this->input->post("title");	
	foreach($this->fields as $val){
		$field = str_replace(" ","_",$val["name"]); 
		// Image 
		if($val["type"]==4){
			if(!empty($_FILES[str_replace(" ","_",$val["name"])]["name"])) {
				$_POST[$$field] = $this->fct->upload_image_from_admin($this->module,$val["name"]);
			}
		} 
		// File 
		elseif($val["type"] == 5) {
			if(!empty($_FILES[str_replace(" ","_",$val["name"])]["name"])) {
				$_POST[$field] = $this->fct->upload_file_from_admin($this->module,$val["name"]);
			}
		}
	}
	$_data["lang"] = default_lang();	
	if($this->input->post("id")!=""){
		$this->session->set_userdata("success_message","Information was updated successfully");
	} 
	else {
		$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	$new_id = $this->$model->insert_update($this->input->post(),$this->input->post("id"));
	if(seo_enabled($this->module)) {
		if($this->input->post("id") == '') {
			if(has_permission('seo',"create"))
			$this->fct->insert_update_seo($this->module,$new_id,$this->input->post());
		}
		else {
			if(has_permission('seo',"edit"))
			$this->fct->insert_update_seo($this->module,$new_id,$this->input->post());
		}
	}
	//if($this->session->userdata("admin_redirect_link") != "")
	//redirect($this->session->userdata("admin_redirect_link"));	
	//else
	redirect(admin_url('manage/display/'.$this->module));
}
}

public function sorted(){
	$sort=array();
	foreach($this->input->get("usersList") as $key => $val){
	if(!empty($val))
		$sort[]=$val;	
	}
	$i=0;
	for($i=0; $i<count($sort); $i++){
		$_data=array("sort_order"=>$i);
		$this->db->where("id_".$this->module,$sort[$i]);
		$this->db->update($this->module,$_data);	
	}
}

public function delete(){
check_permission($this->module,'delete',$this->id);

$model_custom = ucfirst($this->module).'_m';
if(file_exists(APPPATH."models/".$model_custom.".php")){
   $this->load->model($model_custom);
   $model = $model_custom;
}
else{
  $model = 'manage_m';
}
$this->$model->delete($this->id);
$this->session->set_userdata("success_message","Information was deleted successfully");
//if($this->session->userdata("admin_redirect_link") != "")
//redirect($this->session->userdata("admin_redirect_link"));	
//else
redirect(admin_url('manage/display/'.$this->module));

}

public function delete_all(){
check_permission($this->module,'delete');
$model_custom = ucfirst($this->module).'_m';
if(file_exists(APPPATH."models/".$model_custom.".php")){
   $this->load->model($model_custom);
   $model = $model_custom;
}
else{
  $model = 'manage_m';
}

$cehcklist= $this->input->post("cehcklist");
$check_option= $this->input->post("check_option");
if($check_option == "delete_all"){
if(count($cehcklist) > 0){
for($i = 0; $i < count($cehcklist); $i++){
if($cehcklist[$i] != ""){
if(has_permission($this->module,'delete',$cehcklist[$i]))
$this->$model->delete($cehcklist[$i]);
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
//if($this->session->userdata("admin_redirect_link") != "")
//redirect($this->session->userdata("admin_redirect_link"));	
//else
redirect(admin_url('manage/display/'.$this->module));
}


}