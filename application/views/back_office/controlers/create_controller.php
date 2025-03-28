<?php
$controllers='<?php
if ( ! defined("BASEPATH")) exit("No direct script access allowed");
class '.ucfirst($table_name).' extends MY_Controller {

public function __construct()
{
parent::__construct();
$this->table="'.$table_name.'";
$this->load->model("'.$table_name.'_m");
$this->template->set_template("admin");
}

public function index($order=""){
check_permission(\''.$table_name.'\',\'view\');	
if($order == "")
$order ="sort_order";
$data["title"]="List '.str_replace("_"," ",$table_name).'";
$data["content"]="back_office/'.$table_name.'/list";
//
$this->session->unset_userdata("back_link");
//
if($this->input->post(\'show_items\')){
$show_items  =  $this->input->post(\'show_items\');
$this->session->set_userdata(\'show_items\',$show_items);
} elseif($this->session->userdata(\'show_items\')) {
$show_items  = $this->session->userdata(\'show_items\'); 	}
else {
$show_items = admin_per_page();	
}
$data["show_items"] = $show_items;
// filtration
$cond = array();
$like = array();
$url = admin_url("'.$table_name.'");
$url_parm_q = "?";
$filters = $this->fct->get_module_fields("'.$table_name.'",FALSE,TRUE);

if($this->input->get("title") != "") {
	$like["title"] = $this->input->get("title");
	$url .= $url_parm_q."title=".$this->input->get("title");
	$url_parm_q = "&";
}
if($this->input->get("status") != "") {
	$cond["status"] = $this->input->get("status");
	$url .= $url_parm_q."status=".$this->input->get("status");
	$url_parm_q = "&";
}';
if(has_parent_foreign($table_name)) {
$controllers .= '
$cond["id_parent"] = 0;';
}
$controllers .= '
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
				$f_table = get_foreign_table($filter[\'foreign_key\']);
				$var = intval($filter[\'id_content\']) - intval($filter[\'foreign_key\']);
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
// pagination  start :
$count_news = $this->'.$table_name.'_m->getall($cond,$like);
$data[\'count_records\'] = $count_news;
$show_items = ($show_items == \'All\') ? $count_news : $show_items;
$this->load->library(\'pagination\');
$config[\'base_url\'] = $url;
$config[\'total_rows\'] = $count_news;
$config[\'per_page\'] = $show_items;
$config[\'use_page_numbers\'] = TRUE;
$config[\'page_query_string\'] = TRUE;
$this->pagination->initialize($config);
if($this->input->get(\'per_page\') != \'\')
$page = $this->input->get(\'per_page\');
else $page = 0;
$data[\'info\'] = $this->'.$table_name.'_m->getall($cond,$like,$config[\'per_page\'],$page);
$this->session->set_userdata("admin_redirect_link",$url);
// end pagination .
$this->template->add_js(\'assets/js/jquery.tablednd_0_5.js\');
$this->template->add_js(\'assets/js/custom/'.$table_name.'.js\');
$this->template->write_view(\'header\',\'back_office/includes/header\',$data);
$this->template->write_view(\'leftbar\',\'back_office/includes/leftbar\',$data);
$this->template->write_view(\'rightbar\',\'back_office/includes/rightbar\',$data);
$this->template->write_view(\'content\',\'back_office/'.$table_name.'/list\',$data);
$this->template->render();
}

public function add(){
check_permission(\''.$table_name.'\',\'create\');
$data["title"]="Add '.str_replace("_"," ",$table_name).'";
$data["content"]="back_office/'.$table_name.'/add";
$this->template->add_js(\'assets/js/custom/'.$table_name.'.js\');
$this->template->write_view(\'header\',\'back_office/includes/header\',$data);
$this->template->write_view(\'leftbar\',\'back_office/includes/leftbar\',$data);
$this->template->write_view(\'rightbar\',\'back_office/includes/rightbar\',$data);
$this->template->write_view(\'content\',\'back_office/fields/form\',$data);
$this->template->render();
} 

public function edit($id){
check_permission(\''.$table_name.'\',\'edit\',$id);
$data["title"]="Edit '.str_replace("_"," ",$table_name).'";
$data["content"]="back_office/'.$table_name.'/add";
$cond=array("id_'.$table_name.'"=>$id);
$data["id"]=$id;
$data["info"]=$this->'.$table_name.'_m->get($id);
$this->template->add_js(\'assets/js/custom/'.$table_name.'.js\');
$this->template->write_view(\'header\',\'back_office/includes/header\',$data);
$this->template->write_view(\'leftbar\',\'back_office/includes/leftbar\',$data);
$this->template->write_view(\'rightbar\',\'back_office/includes/rightbar\',$data);
$this->template->write_view(\'content\',\'back_office/fields/form\',$data);
$this->template->render();
}

public function delete($id){
check_permission(\''.$table_name.'\',\'delete\',$id);
$this->'.$table_name.'_m->delete($id);

$this->session->set_userdata("success_message","Information was deleted successfully");
if($this->session->userdata("admin_redirect_link") != "")
redirect($this->session->userdata("admin_redirect_link"));	
else
redirect(admin_url("'.$table_name.'/".$this->session->userdata("back_link")));

}

public function delete_all(){
check_permission(\''.$table_name.'\',\'delete\');
$cehcklist= $this->input->post("cehcklist");
$check_option= $this->input->post("check_option");
if($check_option == "delete_all"){
if(count($cehcklist) > 0){
for($i = 0; $i < count($cehcklist); $i++){
if($cehcklist[$i] != ""){
if(has_permission(\''.$table_name.'\',\'delete\',$cehcklist[$i]))
$this->'.$table_name.'_m->delete($cehcklist[$i]);
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
if($this->session->userdata("admin_redirect_link") != "")
redirect($this->session->userdata("admin_redirect_link"));	
else
redirect(admin_url("'.$table_name.'/".$this->session->userdata("back_link")));	
}';

$controllers .= '
public function sorted(){
$sort=array();
foreach($this->input->get("usersList") as $key => $val){
if(!empty($val))
$sort[]=$val;	
}
$i=0;
for($i=0; $i<count($sort); $i++){
$_data=array("sort_order"=>$i);
$this->db->where("id_'.$table_name.'",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
if($this->input->post("id")!="")
check_permission(\''.$table_name.'\',\'edit\',$this->input->post("id"));
else
check_permission(\''.$table_name.'\',\'create\');
	
$data["title"]="Add / Edit '.str_replace("_"," ",$table_name).'";
$this->form_validation->set_rules("title", "TITLE", "trim|required");
if(seo_enabled("'.str_replace("_"," ",$table_name).'")) {
$this->form_validation->set_rules("meta_title", "PAGE TITLE", "trim");
$this->form_validation->set_rules("url_route", "TITLE URL", "trim");
$this->form_validation->set_rules("meta_description", "META DESCRIPTION", "trim");
$this->form_validation->set_rules("meta_keywords", "META KEYWORDS", "trim");
}
';
foreach($attrrr as $val) {
	if($val['type'] == 7) {
		$var  = intval('id_content') - intval($val['foreign_key']);
		if($var == 0) {
$controllers.='
$this->form_validation->set_rules("id_parent", "'.$val["name"].'", "'.$val["validation"].'");
';
		}
		else {
			$content001 = get_cell("content_type","used_name","id_content",$val["foreign_key"]);
$controllers.='
$this->form_validation->set_rules("id_'.$content001.'", "'.$val["name"].'", "'.$val["validation"].'");';
		}
	}
	elseif($val['type'] == 4 || $val['type'] == 5) {
		$controllers.='
		if(!empty($_FILES["'.str_replace(" ","_",$val["name"]).'"]["name"])) {
			$_POST["'.str_replace(" ","_",$val["name"]).'"] = $_FILES["'.str_replace(" ","_",$val["name"]).'"]["name"];
		}
		else {
			//$_POST["'.str_replace(" ","_",$val["name"]).'"] = "";
		}
		$this->form_validation->set_rules("'.str_replace(" ","_",$val["name"]).'", "'.$val["name"].'", "'.$val["validation"].'");';

	}
	else {
		$controllers.='
$this->form_validation->set_rules("'.str_replace(" ","_",$val["name"]).'", "'.$val["name"].'", "'.$val["validation"].'");';
	}

}
$controllers.='
if ($this->form_validation->run() == FALSE){ ';
$controllers.= '
if($this->input->post("id")!="")
$this->edit($this->input->post("id"));
else
$this->add();
';


$controllers.='
} else {
$_data["title"]=$this->input->post("title");	
';

foreach($attrrr as $val){
if($val["type"]==4){
$controllers.='
if(!empty($_FILES["'.str_replace(" ","_",$val["name"]).'"]["name"])) {
	$_POST["'.str_replace(" ","_",$val["name"]).'"] = $this->fct->upload_image_from_admin("'.$table_name.'","'.$val["name"].'");
}
';
} 
// File 
elseif($val["type"] == 5){
$controllers.='
if(!empty($_FILES["'.str_replace(" ","_",$val["name"]).'"]["name"])) {
	$_POST["'.str_replace(" ","_",$val["name"]).'"] = $this->fct->upload_file_from_admin("'.$table_name.'","'.$val["name"].'");
}
';	
}
// end foreach 
}
$controllers.='
$_data["lang"] = default_lang();	
	if($this->input->post("id")!=""){
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	$new_id = $this->'.$table_name.'_m->insert_update($this->input->post(),$this->input->post("id"));
	if(has_permission("'.str_replace("_"," ",$table_name).'","edit") && seo_enabled("'.str_replace("_"," ",$table_name).'")) {
		$this->fct->insert_update_seo("'.$table_name.'",$new_id,$this->input->post());
	}
';		
$controllers.='
if($this->session->userdata("admin_redirect_link") != "")
redirect($this->session->userdata("admin_redirect_link"));	
else
	redirect(admin_url("'.$table_name.'/".$this->session->userdata("back_link")));
	';	

$controllers.='
}
}
}';
