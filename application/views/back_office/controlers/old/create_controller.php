<?
$controllers='<?
class '.ucfirst($table_name).' extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="'.$table_name.'";
$this->load->model("'.$table_name.'_m");
}


public function index($order=""){
if ($this->acl->has_permission(\''.$table_name.'\',\'index\')){	
if($order == "")
$order ="sort_order";
$data["title"]="List '.str_replace("_"," ",$content_type).'";
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
$show_items = "25";	
}
$this->session->set_userdata(\'back_link\',\'index/\'.$order.\'/\'.$this->uri->segment(5));
$data["show_items"] = $show_items;
// pagination  start :
$count_news = $this->'.$table_name.'_m->getAll($this->table,$order);
$show_items = ($show_items == \'All\') ? $count_news : $show_items;
$this->load->library(\'pagination\');
$config[\'base_url\'] = site_url("back_office/'.$table_name.'/index/".$order);
$config[\'total_rows\'] = $count_news;
$config[\'per_page\'] = $show_items;
$config[\'uri_segment\'] = 5;
$this->pagination->initialize($config);
$data[\'info\'] = $this->'.$table_name.'_m->list_paginate($order,$config[\'per_page\'],$this->uri->segment(5));
// end pagination .
$this->load->view("back_office/template",$data);
} else {
	redirect(site_url("back_office/home/dashboard"));
}
}


public function add(){
if ($this->acl->has_permission(\''.$table_name.'\',\'add\')){	
$data["title"]="Add '.str_replace("_"," ",$content_type).'";
$data["content"]="back_office/'.$table_name.'/add";
$this->load->view("back_office/template",$data);
} else {
	redirect(site_url("back_office/home/dashboard"));
}
} 

public function view($id){
if ($this->acl->has_permission(\''.$table_name.'\',\'index\')){	
$data["title"]="View '.str_replace("_"," ",$content_type).'";
$data["content"]="back_office/'.$table_name.'/add";
$cond=array("id_'.$table_name.'"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
} else {
	redirect(site_url("back_office/home/dashboard"));
}
}

public function edit($id){
if ($this->acl->has_permission(\''.$table_name.'\',\'edit\')){	
$data["title"]="Edit '.str_replace("_"," ",$content_type).'";
$data["content"]="back_office/'.$table_name.'/add";
$cond=array("id_'.$table_name.'"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
} else {
	redirect(site_url("back_office/home/dashboard"));
}
}

public function delete($id){
if ($this->acl->has_permission(\''.$table_name.'\',\'delete\')){
//$_data=array("deleted"=>1,
//"deleted_date"=>date("Y-m-d h:i:s"));
//$this->db->where("id_'.$table_name.'",$id);
//$this->db->update($this->table,$_data);

$this->db->where("id_parent",$id);
$this->db->delete($this->table);

$this->db->where("id_'.$table_name.'",$id);
$this->db->delete($this->table);

$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/'.$table_name.'/".$this->session->userdata("back_link"));
} else {
	redirect(site_url("back_office/home/dashboard"));
}
}

public function delete_all(){
if ($this->acl->has_permission(\''.$table_name.'\',\'delete_all\')){
$cehcklist= $this->input->post("cehcklist");
$check_option= $this->input->post("check_option");
if($check_option == "delete_all"){
if(count($cehcklist) > 0){
for($i = 0; $i < count($cehcklist); $i++){
if($cehcklist[$i] != ""){
//$_data=array("deleted"=>1,
//"deleted_date"=>date("Y-m-d h:i:s"));
//$this->db->where("id_'.$table_name.'",$cehcklist[$i]);
//$this->db->update($this->table,$_data);	

$this->db->where("id_parent",$cehcklist[$i]);
$this->db->delete($this->table);

$this->db->where("id_'.$table_name.'",$cehcklist[$i]);
$this->db->delete($this->table);

}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/'.$table_name.'/".$this->session->userdata("back_link"));	
} else {
	redirect(site_url("back_office/home/dashboard"));
}
}';
$multi_languages = getonecell('settings','multi_languages',array('id_settings' => 1 ));
if($multi_languages == 1){
$controllers .= '
public function translate($id = ""){
if ($this->acl->has_permission(\''.$table_name.'\',\'index\')){	
$data["content"]="back_office/'.$table_name.'/translate";
$cond=array("id_'.$table_name.'" => $id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$data["title"]="Translations for ".$data["info"]["title"];
$default_lang=$this->fct->getonecell("languages","symbole",array(\'status\' => 1,\'default\' => 1));	
$data["translations"] = $this->fct->getAll_cond($this->table,\'lang\',array(\'id_parent\' => $id, \'lang !=\' => $default_lang));
$data["languages"] = $this->fct->not_translated_language($this->table,$id);
$this->load->view("back_office/template",$data);
} else {
	redirect(site_url("back_office/home/dashboard"));
}
}

public function add_translation($id_parent){
if ($this->acl->has_permission(\''.$table_name.'\',\'add\')){
$data["content"]="back_office/'.$table_name.'/add_translation";
$cond=array("id_'.$table_name.'" => $id_parent);
$data["id_parent"]=$id_parent;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$data["title"]="Add Translation for ".$data["info"]["title"];
$data["languages"] = $this->fct->not_translated_language($this->table,$id_parent);
$this->load->view("back_office/template",$data);
} else {
	redirect(site_url("back_office/home/dashboard"));
}
}

public function edit_translation($id){
if ($this->acl->has_permission(\''.$table_name.'\',\'edit\')){
$data["content"]="back_office/'.$table_name.'/add_translation";
$cond=array("id_'.$table_name.'" => $id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$id_parent = $data["info"]["id_parent"];
$data["id_parent"] = $id_parent;
$data["title"]="Edit Translation (".$data["info"]["lang"].")";
$data["languages"] = $this->fct->not_translated_language($this->table,$id_parent);
$this->load->view("back_office/template",$data);
} else {
	redirect(site_url("back_office/home/dashboard"));
}
}

public function view_translation($id){
if ($this->acl->has_permission(\''.$table_name.'\',\'index\')){
$data["content"]="back_office/'.$table_name.'/add_translation";
$cond=array("id_'.$table_name.'" => $id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$id_parent = $data["info"]["id_parent"];
$data["id_parent"] = $id_parent;
$data["title"]="Edit Translation (".$data["info"]["lang"].")";
$data["languages"] = $this->fct->not_translated_language($this->table,$id);
$this->load->view("back_office/template",$data);
} else {
	redirect(site_url("back_office/home/dashboard"));
}
}

public function delete_translation($id,$id_parent){
if ($this->acl->has_permission(\''.$table_name.'\',\'delete\')){
//$_data=array("deleted"=>1,
//"deleted_date"=>date("Y-m-d h:i:s"));
//$this->db->where("id_'.$table_name.'",$id);
//$this->db->update($this->table,$_data);

$this->db->where("id_'.$table_name.'",$id);
$this->db->delete($this->table);

$this->session->set_userdata("success_message","Information was deleted successfully");

redirect(base_url()."back_office/'.$table_name.'/translate/".$id_parent);
} else {
	redirect(site_url("back_office/home/dashboard"));
}
} ';

}
$controllers .= '
public function sorted(){
$sort=array();
foreach($this->input->get("table-1") as $key => $val){
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
$data["title"]="Add / Edit '.str_replace("_"," ",$content_type).'";
$this->form_validation->set_rules("title", "TITLE", "trim|required");
$this->form_validation->set_rules("meta_title", "PAGE TITLE", "trim|max_length[65]");
$this->form_validation->set_rules("title_url", "TITLE URL", "trim");
$this->form_validation->set_rules("meta_description", "META DESCRIPTION", "trim|max_length[160]");
$this->form_validation->set_rules("meta_keywords", "META KEYWORDS", "trim|max_length[160]");
';
foreach($attrrr as $val){
$controllers.='$this->form_validation->set_rules("'.str_replace(" ","_",$val["name"]).'", "'.$val["name"].'", "'.$val["validation"].'");
';
}
if($multi_languages == 1){
$controllers.='
if($this->input->post("id_parent")!="")
$this->form_validation->set_rules("language", "Language", "trim|required");
';
}
$controllers.='
if ($this->form_validation->run() == FALSE){ ';
if($multi_languages == 1){

$controllers.= '
if($this->input->post("id_parent") != ""){
if($this->input->post("id")!="")
$this->edit_translation($this->input->post("id"));
else
$this->add_translation($this->input->post("id_parent"));
} else {
if($this->input->post("id")!="")
$this->edit($this->input->post("id"));
else
$this->add();
}';
	
} else {
	
$controllers.= '
if($this->input->post("id")!="")
$this->edit($this->input->post("id"));
else
$this->add();
';

}

$controllers.='
} else {
$_data["title"]=$this->input->post("title");
$_data["meta_title"]=$this->input->post("meta_title");
if($this->input->post("title_url") == "")
$title_url = $this->input->post("title");
else
$title_url = $this->input->post("title_url");
$_data["title_url"]=$this->fct->cleanURL("'.$table_name.'",url_title($title_url),$this->input->post("id"));
$_data["meta_description"]=$this->input->post("meta_description");
$_data["meta_keywords"]=$this->input->post("meta_keywords");	
';

if($multi_languages == 1){
	$controllers.='$parent = $this->fct->getonerow($this->table, array( \'id_'.$table_name.'\' => $this->input->post("id_parent") ));
	';
}

foreach($attrrr as $val){
if($multi_languages == 1 && $val["translated"] != 1){
$controllers.='
if($this->input->post("id_parent") == ""){
';
}
// Date
if($val["type"]==1){
$controllers.='
$_data["'.str_replace(" ","_",$val["name"]).'"]=$this->fct->date_in_formate($this->input->post("'.str_replace(" ","_",$val["name"]).'"));
';
}
// foreign_key
elseif($val["type"]==7){
$cond=array("id_content" => $val["foreign_key"]);
$content001 = $this->fct->getonecell("content_type","name",$cond);
if(str_replace(" ","_",$content001) == $table_name)
$frn="parent";
else
$frn=str_replace(" ","_",$content001);
$controllers.='$_data["id_'.$frn.'"]=$this->input->post("'.str_replace(" ","_",$val["name"]).'"); 
';
}
// Image	
elseif($val["type"]==4){
$controllers.='if(!empty($_FILES["'.str_replace(" ","_",$val["name"]).'"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_'.$table_name.'"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("'.$table_name.'","'.str_replace(" ","_",$val["name"]).'",$cond_image);
if(!empty($old_image) && file_exists(\'./uploads/'.$table_name.'/\'.$old_image)){
unlink("./uploads/'.$table_name.'/".$old_image);
';	
if($val["thumb"] == 1){
$controllers.='$sumb_val1=explode(",","'.$val["thumb_val"].'");
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/'.$table_name.'/".$value."/".$old_image)){
unlink("./uploads/'.$table_name.'/".$value."/".$old_image);	 }							
} 
';
} 
$controllers.=' } }
$image1= $this->fct->uploadImage("'.str_replace(" ","_",$val["name"]).'","'.$table_name.'");
';
if($val["thumb"] == 1){
if($val["resize_status"] == 1){
$controllers.='$this->fct->createthumb1($image1,"'.$table_name.'","'.$val["thumb_val"].'");';	
} else {	
$controllers.='$this->fct->createthumb($image1,"'.$table_name.'","'.$val["thumb_val"].'");';
	}
}
$controllers.='$_data["'.str_replace(" ","_",$val["name"]).'"]=$image1;	
}
';
}
// File 
elseif($val["type"] == 5){
$controllers.='
if(!empty($_FILES["'.str_replace(" ","_",$val["name"]).'"]["name"])) {
if($this->input->post("id")!=""){
$cond_file=array("id_'.$table_name.'"=>$this->input->post("id"));
$old_file=$this->fct->getonecell("'.$table_name.'","'.str_replace(" ","_",$val["name"]).'",$cond_file);
if(!empty($old_file))
unlink("./uploads/'.$table_name.'/".$old_file);	
}
$file1= $this->fct->uploadImage("'.str_replace(" ","_",$val["name"]).'","'.$table_name.'");
$_data["'.str_replace(" ","_",$val["name"]).'"]=$file1;	
}
';		
}
// others
else {
$controllers.='$_data["'.str_replace(" ","_",$val["name"]).'"]=$this->input->post("'.str_replace(" ","_",$val["name"]).'");
';
}

if($multi_languages == 1 && $val["translated"] != 1){
$controllers.='
} else {			
$_data["'.str_replace(" ","_",$val["name"]).'"]= $parent["'.str_replace(" ","_",$val["name"]).'"];
}';
}
// end foreach 
}
$controllers.='
if($this->input->post("id_parent") != ""){
$_data["lang"]=$this->input->post("language");
$_data["id_parent"]=$this->input->post("id_parent");
} else {
$_data["lang"]=$this->fct->getonecell("languages","symbole",array(\'status\' => 1,\'default\' => 1));	
}

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d H:i:s");
	$this->db->where("id_'.$table_name.'",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$new_id = $this->input->post("id");
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d H:i:s");
	$this->db->insert($this->table,$_data); 
	$new_id = $this->db->insert_id();	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
';		
if($multi_languages == 1){
$controllers.='
	if($this->input->post("id_parent") != "")
	redirect(base_url()."back_office/'.$table_name.'/translate/".$_data["id_parent"]);
	else
	redirect(base_url()."back_office/'.$table_name.'/".$this->session->userdata("back_link"));
	';
} else {
$controllers.='
	redirect(base_url()."back_office/'.$table_name.'/".$this->session->userdata("back_link"));
	';	
}
$controllers.='
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/'.$table_name.'/".$image)){
unlink("./uploads/'.$table_name.'/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = \''.str_replace("_"," ",$table_name).'\')
AND name = \'".$field."\'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/'.$table_name.'/".$value."/".$image)){
unlink("./uploads/'.$table_name.'/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_'.$table_name.'",$id);
$this->db->update("'.$table_name.'",$_data);
redirect(base_url()."back_office/'.$table_name.'");	
}

}';




?>