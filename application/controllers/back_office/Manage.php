<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manage extends MY_Controller {
	
public function __construct()
{
	parent::__construct();
	$this->module = $this->uri->segment(4);
	$this->id = $this->uri->segment(5);
	$this->conmod = $this->uri->segment(6);
	$this->conid = $this->uri->segment(7);
	$this->translate_lang = $this->uri->segment(6);
	$this->translate_id = $this->uri->segment(7);

	$this->load->model(admin_dir()."/users_m");
	$this->load->model(admin_dir()."/manage_m");
	$this->template->set_template("admin");
	$this->admin_dir = admin_dir();
	$content_type_data = $this->fct->getonerow("content_type",array("name"=>$this->module));
	$cond=array('id_content'=>$content_type_data['id_content']);
	if($this->uri->segment(3) == 'mod' || $this->uri->segment(3) == 'submit_connect')
	$this->fields =$this->fct->get_module_fields($this->conmod);
	else
	$this->fields =$this->fct->get_module_fields($this->module);
	//print '<pre>'; print_r($this->fields); exit;
	$this->default_lang = default_lang();
	$this->cur_lang = $this->lang->lang();
}

public function index(){
	exit();
}

public function sorted(){
	/*echo "tesT";exit;*/
	$sort=array();
	foreach($this->input->get("usersList") as $key => $val){
	if(!empty($val))
		$sort[]=$val;	
	}
	//echo '<pre>';print_r($sort);
	$i=0;
	for($i=0; $i<count($sort); $i++){
		$_data=array("sort_order"=>$i);
		$this->db->where("id_".$this->module,$sort[$i]);
		$this->db->update($this->module,$_data);	
	}
}

public function display(){
check_permission($this->module,'view');	
$data["title"]="List ".ucfirst($this->module);
$data["show_items"] = admin_per_page();	
// filtration
$cond = array();
$like = array();
$url = admin_url('manage/display/'.$this->module);

$order = 'sort_order';
if(view_only($this->module)) 
$order = 'id_'.$this->module.' DESC';

$url_parm_q = "?";
$filters = $this->fct->get_module_fields($this->module,FALSE,TRUE);
if($this->input->get("title") != "") {
	$like["title"] = $this->input->get("title");
	$url .= $url_parm_q."title=".$this->input->get("title");
	$url_parm_q = "&";
}
if($this->input->get("lang") != "") {
	$cond["lang"] = $this->input->get("lang");
	$url .= $url_parm_q."lang=".$this->input->get("lang");
	$url_parm_q = "&";
}
else {
	$cond["lang"] = default_lang();
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
$data['info'] = $this->$model->getall($cond,$like,$config['per_page'],$page,$order);
$this->session->set_userdata("admin_redirect_link",$url);
$data["check_import"] = check_import($this->module);
$data["check_export"] = check_export($this->module);
// end pagination .
$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
$this->template->add_js('assets/js/custom/'.$this->module.'.js');
$this->template->write_view('header',$this->admin_dir.'/includes/header',$data);
$this->template->write_view('leftbar',$this->admin_dir.'/includes/leftbar',$data);
$this->template->write_view('rightbar',$this->admin_dir.'/includes/rightbar',$data);
$this->template->write_view('content',$this->admin_dir.'/manage/list',$data);
$this->template->render();
}

public function add(){
check_permission($this->module,'create');
$data["title"]="Create ".$this->module;
//
if($this->module == "import")
$content = 'back_office/fields/import';
elseif($this->module == "export")
$content = 'back_office/fields/export';	
else
$content = 'back_office/fields/form';
//
$this->template->add_js('assets/js/custom/'.$this->module.'.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);

$this->template->write_view('content',$content,$data);
$this->template->render();
} 

public function edit(){
check_permission($this->module,'edit',$this->id);
$this->users_m->redirect_denied_user_record_access($this->module,$this->id,$this->user_info);
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

if(isset($data["info"]['title']))
$data["title"]="Edit ".$this->module.": ".$data["info"]['title'];
else
$data["title"]="Edit ".$this->module." ";
//
if($this->module == "import")
$content = 'back_office/fields/import';
elseif($this->module == "export")
$content = 'back_office/fields/export';	
else
$content = 'back_office/fields/form';
//
$this->template->add_js('assets/js/custom/'.$this->module.'.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content',$content,$data);
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
$data["title"]="Add / Edit ".$this->module;
$this->form_validation->set_rules("title", "TITLE", "trim|required");
foreach($this->fields as $val) {
	$validations = 'trim';
	if($val['required'] == 1)
	$validations .= '|required';
	if($val['type'] == 10)
	$validations .= '|valid_email';
	
	if($val['min_length'] != 0)
	$validations .= '|min_length['.$val['min_length'].']';
	
	if($val['max_length'] != 0)
	$validations .= '|max_length['.$val['max_length'].']';
	
	//if($val['unique_val'] == 1)
	//$validations .= '|max_length['.$val['max_length'].']';
	
	if($val['matches'] == 1)
	$validations .= '|matches['.$val['matches'].']';
	
	
	if($val['type'] == 7) {
		$var  = intval('id_content') - intval($val['foreign_key']);
		if($var == 0) {
			$this->form_validation->set_rules("id_parent", $val["name"], $validations);
		}
		else {
			$content001 = get_cell("content_type","used_name","id_content",$val["foreign_key"]);
			$this->form_validation->set_rules("id_".$content001, $val["name"], $validations);
		}
	}
	elseif($val['type'] == 4 || $val['type'] == 5) {
		if(!empty($_FILES[str_replace(" ","_",$val["name"])]["name"])) {
			$_POST[str_replace(" ","_",$val["name"])] = $_FILES[str_replace(" ","_",$val["name"])]["name"];
		}
		else {
			//$_POST["'.str_replace(" ","_",$val["name"]).'"] = "";
		}
		$this->form_validation->set_rules(str_replace(" ","_",$val["name"]), $val["name"], $validations);
	}
	elseif($val['type'] == 10) {
		$this->form_validation->set_rules(str_replace(" ","_",$val["name"]), $val["name"], $validations);
	}
	elseif($val['type'] == 12) {
		if($this->input->post(str_replace(" ","_",$val["name"])) == "")
		$_POST[str_replace(" ","_",$val["name"])] = '';
		$this->form_validation->set_rules(str_replace(" ","_",$val["name"]), $val["name"], $validations);
	}
	else {
		$this->form_validation->set_rules(str_replace(" ","_",$val["name"]), $val["name"], $validations);
	}
}
if ($this->form_validation->run() == FALSE) {
	//echo validation_errors();exit;
	if($this->id != "")
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
			
				$_POST[$field] = $this->fct->upload_image_from_admin($this->module,$val["name"]);
			}
		} 
		// File 
		elseif($val["type"] == 5) {
			if(!empty($_FILES[str_replace(" ","_",$val["name"])]["name"])) {
				$_POST[$field] = $this->fct->upload_file_from_admin($this->module,$val["name"]);
			}
		}
                elseif($val["type"] == 16) {
                  $_POST[$field]=html_escape($_POST[$field], FALSE); 
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
	redirect(admin_url('manage/display/'.$this->module));
}
}

public function mod(){
check_permission($this->conmod,'view');	
$this->users_m->redirect_denied_user_record_access($this->module,$this->id,$this->user_info);
$url = admin_url('manage/mod/'.$this->module.'/'.$this->id.'/'.$this->conmod);
$model_custom = $this->conmod.'_m';
if(file_exists(APPPATH."models/".$model_custom.".php")){
   $this->load->model($model_custom);
   $model = $model_custom;
}
else{
  $model = 'manage_m';
}
$data["title"]="List ".ucfirst(display_field_name($this->conmod));
$data["row"]=$this->$model->get($this->id);
$breadcrumb = array(
array("List ".ucfirst($this->module), admin_url('manage/display/'.$this->module)),
$data["row"]["title"]
);

$data["breadcrumb"] = breadcrumb($breadcrumb);
//$this->fields =$this->fct->get_module_fields("types");
// pagination  start :
$like = array();
//exit($this->module);
$cond = array(
"module"=>$this->module,
"record"=>$this->id,
);
$this->table = $this->conmod;
$count_news = $this->$model->getall_from_connect($this->conmod,$cond,$like);
$data['count_records'] = $count_news;
//$show_items = ($data["show_items"] == 'All') ? $count_news : $data["show_items"];
$show_items = "All";
$data["show_items"] = $show_items;
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
//var_dump($cond);exit;
$data['info'] = $this->$model->getall_from_connect($this->conmod,$cond,$like,$config['per_page'],$page);
$data['info_edit'] = array();
if($this->conid != '') {
	$data['info_edit'] = $this->$model->get_from_connect($this->conmod,$this->conid);
	$data['id'] = $this->conid;
	//var_dump($data['info_edit']);exit;
}
//print '<pre>'; print_r($data['info']); exit;
$this->session->set_userdata("admin_redirect_link",$url);
$data["check_import"] = check_import($this->module,false);
$data["check_export"] = check_export($this->module,false);
// end pagination .

$data['content_type'] = $this->conmod;
//exit($this->conmod.','.$data['content_type']);
$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
$this->template->add_js('assets/js/custom/'.$this->module.'.js');
$this->template->write_view('header',$this->admin_dir.'/includes/header',$data);
$this->template->write_view('leftbar',$this->admin_dir.'/includes/leftbar',$data);
$this->template->write_view('rightbar',$this->admin_dir.'/includes/rightbar',$data);
$this->template->write_view('content',$this->admin_dir.'/manage/mod',$data);
$this->template->render();
}

public function submit_connect(){

if($this->input->post("id")!="")
check_permission($this->conmod,'edit',$this->input->post("id"));
else
check_permission($this->conmod,'create');
$model_custom = $this->conmod.'_m';
if(file_exists(APPPATH."models/".$model_custom.".php")){
   $this->load->model($model_custom);
   $model = $model_custom;
}
else{
  $model = 'manage_m';
}
$this->form_validation->set_rules("title", "TITLE", "trim|required");
$this->form_validation->set_rules("module", "MODULE NAME", "trim|required");
$this->form_validation->set_rules("record", "CONNECTED RECCORD", "trim|required");
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

if ($this->form_validation->run() == FALSE) {
	//exit(validation_errors());
	$this->mod();
} 
else {
	$_data["title"]=$this->input->post("title");	
	foreach($this->fields as $val){
		$field = str_replace(" ","_",$val["name"]); 
		// Image 
		if($val["type"]==4){
			if(!empty($_FILES[str_replace(" ","_",$val["name"])]["name"])) {
				$_POST[$field] = $this->fct->upload_image_from_admin($this->conmod,$val["name"]);
			}
		} 
		// File 
		elseif($val["type"] == 5) {
			if(!empty($_FILES[str_replace(" ","_",$val["name"])]["name"])) {
				$_POST[$field] = $this->fct->upload_file_from_admin($this->conmod,$val["name"]);
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
	$redirect_link = admin_url('manage/mod/'.$this->module.'/'.$this->id.'/'.$this->conmod);
	$this->module = $this->conmod;
	$old_data = $this->manage_m->get_from_connect($this->module,$this->input->post("id"));
	$new_id = $this->$model->insert_update($this->input->post(),$this->input->post("id"),$old_data);
	redirect($redirect_link);
}
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

public function translate(){
check_permission($this->module,'translate');	
$url = admin_url('manage/translation/'.$this->module.'/'.$this->id);
$model_custom = $this->module.'_m';
if(file_exists(APPPATH."models/".$this->admin_dir."/".$model_custom.".php")){
   $this->load->model($this->admin_dir."/".$model_custom);
   $model = $model_custom;
}
else{
  $model = 'manage_m';
}
$data["title"]="List ".ucfirst(display_field_name($this->conmod));
$data["row"]=$this->$model->get($this->id);
$breadcrumb = array(
	array(
		"List ".ucfirst($this->module), 
		admin_url('manage/display/'.$this->module)),
		$data["row"]["title"]
	);

$data["breadcrumb"] = breadcrumb($breadcrumb);

$data['languages'] = $this->fct->get_languages();
$data['info'] = array();
if($this->translate_lang != '')
$data['info'] = $this->$model->get_translation($this->module,$this->id,$this->translate_lang);
if($this->translate_lang != '' && $this->translate_id != '')
$data['id'] = $this->translate_id;
$this->fields = $this->fct->get_module_fields($this->module,FALSE,FALSE,array('translated'=>1));
//print '<pre>'; print_r($this->fields); exit;
$this->session->set_userdata("admin_redirect_link",$url);
$data["check_import"] = check_import($this->module,false);
$data["check_export"] = check_export($this->module,false);
// end pagination .
//exit($this->module);
$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
$this->template->add_js('assets/js/custom/'.$this->module.'.js');
$this->template->write_view('header',$this->admin_dir.'/includes/header',$data);
$this->template->write_view('leftbar',$this->admin_dir.'/includes/leftbar',$data);
$this->template->write_view('rightbar',$this->admin_dir.'/includes/rightbar',$data);
$this->template->write_view('content',$this->admin_dir.'/manage/translate',$data);
$this->template->render();
}

function submit_translate()
{
	$postdata = $this->input->post();
	$section = $postdata["module"];
	check_permission($section,'translate');

	$id = $postdata["record"];
	$lang = $postdata["lang"];
	$title = $postdata["title"];
	
	$transate_id = '';
	if(isset($postdata['id'])) {
		$transate_id = $postdata['id'];
		unset($postdata['module']);
		unset($postdata['record']);
		unset($postdata['id']);
		unset($postdata['lang']);
	}
	else {
		if($postdata['module'] == 'seo') {
			$get_seo_record = $this->db->where(array(
				'id_seo'=>$id
			))->get('seo')->row_array();
			if($get_seo_record['module'] != '') {
				$get_record = $this->db->where(array(
					'lang'=>$postdata['lang'],
					'translation_id'=>$get_seo_record['record']
				))->get($get_seo_record['module'])->row_array();
				if(!empty($get_record)) {
					$postdata['module'] = $get_seo_record['module'];
					$postdata['record'] = $get_record['id_'.$get_seo_record['module']];
				}
			}
		}
		else {
			/*$get_record = $this->db->where(array(
					'id_'.$postdata['module']=>$postdata['record']
				))->get($postdata['module'])->row_array();*/
			unset($postdata['module']);
			unset($postdata['record']);
		}
	}
	//$model = $section."_m";
	if($transate_id == '')
	$postdata['translation_id'] = $id;
	
	$model_custom = $section.'_m';
	if(file_exists(APPPATH."models/".$model_custom.".php")){
	   $this->load->model($model_custom);
	   $model = $model_custom;
	}
	else{
	  $model = 'manage_m';
	}
	$this->load->model($model);
	$rid = $this->$model->insert_update($postdata,$transate_id);
	
	// special conditions
	/*if($section == 'static_words') {
		$this->fct->update_translations();
	}*/
	$this->session->set_userdata("success_message","Translation updated.");

	redirect(admin_url('manage/translate/'.$this->module.'/'.$this->id));
}

/***********************************************************************/

public function submit_import(){
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
$this->form_validation->set_rules("tables", "TABLE", "trim|required");
$this->form_validation->set_rules("fields[]", "FIELDS", "trim|required");
$this->form_validation->set_rules("status", "STATUS", "trim|required");

if ($this->form_validation->run() == FALSE) {
	//print '11<pre>'; print_r($this->input->post()); exit;
	if($this->input->post("id")!="")
	$this->edit();
	else
	$this->add();
} 
else {
	$_data = array(
	'title' => $this->input->post('title'),
	'tables' => $this->input->post('tables'),
	'status' => $this->input->post('status'),
	'fields' => join(',',$this->input->post('fields'))
	);
	//echo '<pre>';
	//print_r($_data);
	$new_id = $this->$model->insert_update($_data,$this->input->post("id"));
	
	redirect(admin_url('manage/display/'.$this->module));
}
	
}

public function delete(){
	if(isset($this->conmod) && $this->conmod != '') {
		$del_mod = $this->conmod;
		$del_id = $this->conid;
		$redirect_url = admin_url('manage/mod/'.$this->module.'/'.$this->id.'/'.$this->conmod);
	}
	else {
		$del_mod = $this->module;
		$del_id = $this->id;
		$redirect_url = admin_url('manage/display/'.$del_mod);
	}
check_permission($del_mod,'delete',$del_id);
$this->users_m->redirect_denied_user_record_access($del_mod,$del_id,$this->user_info);
$model_custom = $del_mod.'_m';
if(file_exists(APPPATH."models/".$model_custom.".php")){
   $this->load->model($model_custom);
   $model = $model_custom;
}
else{
  $model = 'manage_m';
}
$this->$model->delete($del_id,$del_mod);
$this->session->set_userdata("success_message","Information was deleted successfully");
//if($this->session->userdata("admin_redirect_link") != "")
//redirect($this->session->userdata("admin_redirect_link"));	
//else
redirect($redirect_url);
}

public function delete_all(){
	
	if(isset($this->conmod) && $this->conmod != '') {
		$del_mod = $this->conmod;
		$redirect_url = admin_url('manage/mod/'.$this->module.'/'.$this->id.'/'.$this->conmod);
	}
	else {
		$del_mod = $this->module;
		$redirect_url = admin_url('manage/display/'.$del_mod);
	}
	
check_permission($del_mod,'delete');
$model_custom = $del_mod.'_m';
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
if(has_permission($del_mod,'delete',$cehcklist[$i]) && $this->users_m->check_user_record_access($del_mod,$cehcklist[$i],$this->user_info))
$this->$model->delete($cehcklist[$i],$del_mod);
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect($redirect_url);
}


}