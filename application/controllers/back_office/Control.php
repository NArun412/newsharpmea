<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Control extends MY_Controller{

public function __construct()
{
parent::__construct();
$this->table="content_type";
$this->template->set_template('admin');
$this->load->model(admin_dir()."/database_g");
}


public function index(){
$data["title"]="List Content Type";
$data["content"]="back_office/controlers/list";
$data["info"]=$this->fct->getAll($this->table,'sort_order');
$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
$this->template->add_js('assets/js/custom/controlers.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/controlers/list',$data);
$this->template->render();
}

public function add(){
$data["title"]="Add Content Type";
$data["content"]="back_office/controlers/add";

$this->template->add_js('assets/js/custom/controlers.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/controlers/add',$data);
$this->template->render();
} 

public function edit($id){
$data["title"]="Edit Content Type";
$cond=array('id_content'=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$data["content"]="back_office/controlers/add";
$this->template->add_js('assets/js/custom/controlers.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/controlers/add',$data);
$this->template->render();
}

public function delete($id){
//$_data=array('deleted'=>1,'deleted_date'=>date("Y-m-d h:i:s"));
	$this->database_g->delete_module($id);
	$this->session->set_userdata('success_message','Information was deleted successfully');
	redirect(base_url().'back_office/control');
}

public function delete_all(){
$cehcklist= $this->input->post('cehcklist');
$check_option= $this->input->post('check_option');
if($check_option == "delete_all"){
	if(count($cehcklist) > 0){
		for($i = 0; $i < count($cehcklist); $i++){
			$this->database_g->delete_module($cehcklist[$i]);
		} 
	} 
	$this->session->set_userdata('success_message','Informations were deleted successfully');
}
redirect(base_url().'back_office/control');	
}

public function sorted(){
$sort=array();
foreach($this->input->get('usersList') as $key => $val){
if(!empty($val))
$sort[]=$val;	
}
$i=0;
for($i=0; $i<count($sort); $i++){
$_data=array('sort_order'=>$i);
$this->db->where('id_content',$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

function checkIfModuleExists()
{
	if($this->input->post('id')!=''){
			return true;
	}
	else {
	$condd=array('name' =>strtolower($this->input->post('name')));
$content_type = $this->fct->getonerow('content_type',$condd);
if(empty($content_type)) {
	return true;
}
else {
	$this->session->set_userdata('error_message','This Content Type Already Exist .');
	$this->form_validation->set_message("checkIfModuleExists","Content Type Exists!");
	return false;
}
	}
}
public function submit(){
if(!isset($_POST['enable_seo'])) $_POST['enable_seo'] = 0;
if($this->input->post('id')!='')
$this->form_validation->set_rules('name', 'Content Type Name', 'trim|callback_checkIfModuleExists[]');
else
$this->form_validation->set_rules('name', 'Content Type Name', 'trim|required|callback_checkIfModuleExists[]');
$this->form_validation->set_rules('url_name', 'url_name', 'trim');
$this->form_validation->set_rules('icon', 'Icon', 'trim'); 
$this->form_validation->set_rules('enable_seo', 'Enable SEO', 'trim'); 
$this->form_validation->set_rules('menu_group', 'Menu Group', 'trim'); 
$this->form_validation->set_rules('gallery', 'Icon', 'trim');
if ($this->form_validation->run() == FALSE){
if($this->input->post('id')!=''){
$this->edit( $this->input->post('id') );
}
else {
$this->add();
}
}
else
{
$condd=array('name' =>strtolower($this->input->post('name')));
$content_type = $this->fct->getonerow('content_type',$condd);
$_data = array();
if($this->input->post('id')=='') {
	$_data=array(
		'name'=>str_replace(" ","_",strtolower($this->input->post('name'))),
		'used_name'=>str_replace(" ","_",strtolower($this->input->post('name')))
	);
}
$_data['gallery'] = $this->input->post("gallery");
$_data['url_name'] = $this->input->post("url_name");
$_data['menu_group'] = $this->input->post("menu_group");
$_data['thumb_val_gal'] = $this->input->post("thumb_val_gal");
$_data['resize_status'] = $this->input->post("resize_status");
// upload image 
	$_data=array(
	'name'=>str_replace(" ","_",strtolower($this->input->post('name'))),
	'url_name'=>strtolower(str_replace(" ","_",$this->input->post('url_name'))),
	'gallery'=>$this->input->post('gallery'),
	'menu_group'=>$this->input->post('menu_group'),
	'thumb_val_gal'=>$this->input->post('thumb_val_gal'),
	'resize_status'=>$this->input->post('resize_status'),
	'used_name'=>str_replace(" ","_",strtolower($this->input->post('name'))));
$enable_seo = 0;
if($this->input->post('enable_seo') == 1)
$enable_seo = 1;
$_data['enable_seo'] = $enable_seo;

$permissions = array();
foreach(permissions() as $perm) {
	$p = 0;
	if($this->input->post("perm_".$perm) == 1)
	$p = 1;
	$_data['perm_'.$perm] = $p;
}
$_data['perm_help'] = $this->input->post("perm_help");

if(!empty($_FILES["icon"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_content"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("content_type","icon",$cond_image);
if(!empty($old_image)){
unlink("./uploads/content_type/".$old_image);	
unlink("./uploads/content_type/32x32/".$old_image);	 
}								
}
$image1= $this->fct->uploadImage("icon","content_type");
$this->fct->createthumb($image1,"content_type","32x32"); 
$_data["icon"]=$image1;	
}
	
	if($this->input->post('id')!=''){
		$_data["updated_date"]=date("Y-m-d h:i:s");
		$this->db->where('id_content',$this->input->post('id'));
		$this->db->update($this->table,$_data);
		$new_id = $this->input->post("id");
		$this->session->set_userdata('success_message','Information was updated successfully');
	} else {
		$_data["created_date"]=date("Y-m-d h:i:s");
		$this->db->insert($this->table,$_data);
		$new_id = $this->db->insert_id();
		$this->session->set_userdata('success_message','Information was inserted successfully');
	}
	

	$this->database_g->create_module(get_cell("content_type","used_name","id_content",$new_id),$this->input->post('gallery'));
	$this->fct->insert_connected_modules($new_id,$this->input->post("connectedmodules"));
	$this->fct->insert_module_connections($new_id,$this->input->post("moduleconnections"));
	//if($this->input->post('id')=='')
	//echo 'ID:'.$new_id.get_cell("content_type","name","id_content",$new_id);exit;
	
//
redirect(base_url().'back_office/control');

}
}

//manage content Type 
public function manage($id){

$data["title1"]="<a href='".base_url()."back_office/control' class='blue'>Manage Content Type</a>";
$data["content"]="back_office/controlers/manage";
$cond=array('id_content'=>$id);
$data["title"]="Attributes: ".get_cell("content_type","name","id_content",$id);
$data["con_type"]=$this->fct->getonerecord($this->table,$cond);
$data["info"]=$this->fct->getAll_cond('content_type_attr','sort_order',$cond);
$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
$this->template->add_js('assets/js/custom/controlers.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/controlers/manage',$data);
$this->template->render();
}

//add_attr
public function add_attr($id){
$data["length_attr"] = 0;
$data["thumb_attr"] = 0;
$data["foreign_attr"] = 0;
$data["title"]="Add Attribute for content type";
$data["title1"]="<a href='".base_url()."back_office/control/manage/".$id."' class='blue'>Add Attribute for content type</a>";
$data["content"]="back_office/controlers/add_attr";
$cond=array('id_content'=>$id);
$data["con_type"]=$this->fct->getonerecord($this->table,$cond);
$data["attr_type"]=$this->fct->getAll_1('attr_type','name');
$data["info"]=$this->fct->getAll('content_type_attr','sort_order');

$tbl_name = str_replace(' ','_',$data["con_type"]['name']);
$data['fields'] = $this->fct->get_module_fields($tbl_name);

$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/controlers/add_attr',$data);
$this->template->render();
}

// submit Attr
function submit_attr(){
$data["length_attr"] = 0;
$data["thumb_attr"] = 0;
$data["foreign_attr"] = 0;
$id=$this->input->post('id_content');
if($this->input->post('id')==''){
	$this->form_validation->set_rules('name', 'Attr Name', 'trim|required');
$this->form_validation->set_rules('type', 'Type', 'trim|required');
if($this->input->post('type') == 2 || $this->input->post('type') == 8 || $this->input->post('type') == 9 || $this->input->post('type') == 10 || $this->input->post('type') == 11){
$data["length_attr"] = 1;
$this->form_validation->set_rules('length', 'length', 'trim|required|min_length[1]|max_length[5]');
}
$this->form_validation->set_rules('thumbs', 'thumbs', 'trim');
if($this->input->post('thumbs') == true){
$data["thumb_attr"] = 1;
$this->form_validation->set_rules('thumb_val', 'thumb_val', 'trim|required');
}
if($this->input->post('type') == 7){
$data["foreign_attr"] = 1;
$this->form_validation->set_rules('foreign_key', 'Foreign Key', 'trim|required');
}

}
$this->form_validation->set_rules('display_name', 'Attr Display Name', 'trim|required');
$this->form_validation->set_rules('display', 'display', 'trim');

if($this->form_validation->run() == FALSE){
	//echo validation_errors();exit;
	if($this->input->post('id')!=''){
		$this->edit_attr($id,$this->input->post('id'));
	}
	else {
		$this->add_attr($id);
	}
}
else
{
	$_data = array();
	if($this->input->post('id')==''){
$_data=array(
'name'=>strtolower(str_replace(" ","_",$this->input->post('name'))),
'type'=>$this->input->post('type'),
'id_content'=>$id
);
	}
	
	$_data['hint'] = $this->input->post('hint');
	
	if($this->input->post('display') == 1)
$_data['display'] = 1;
else
$_data['display'] = 0;
	
$_data['display_name'] = $this->input->post('display_name');
if($this->input->post('translated') == 1)
$_data['translated'] = 1;
else
$_data['translated'] = 0;

if($this->input->post('enable_editor') == 1)
$_data['enable_editor'] = 1;
else
$_data['enable_editor'] = 0;

if($this->input->post('enable_filtration') == 1)
$_data['enable_filtration'] = 1;
else
$_data['enable_filtration'] = 0;


if($this->input->post('required') == 1)
$_data['required'] = 1;
else
$_data['required'] = 0;

if($this->input->post('unique_val') == 1)
$_data['unique_val'] = 1;
else
$_data['unique_val'] = 0;


$_data["max_length"]=$this->input->post('max_length');
$_data["min_length"]=$this->input->post('min_length');
$_data["matches"]=$this->input->post('matches');

$_data["additional_attributes"]=$this->input->post('additional_attributes');

if($this->input->post('type') == 2 || $this->input->post('type') == 8 || $this->input->post('type') == 9 || $this->input->post('type') == 10 || $this->input->post('type') == 11){
$_data["length"]=$this->input->post('length'); 
}
if($this->input->post('type') == 4){
$_data["thumb"]=$this->input->post('thumbs');
$_data["thumb_val"]=$this->input->post('thumb_val');	
$_data["resize_status"]=$this->input->post('resize_status');	
}
if($this->input->post('type') == 7 || $this->input->post('type') == 12){	
$_data["foreign_key"]=$this->input->post('foreign_key');
}
if($this->input->post('id')!=''){
$this->db->where('id_attr',$this->input->post('id'));
$this->db->update('content_type_attr',$_data);
$new_id = $this->input->post('id');	
$this->session->set_userdata('success_message','Information was updated successfully');
} else {
$this->db->insert('content_type_attr',$_data);
$new_id = $this->db->insert_id();	
$this->session->set_userdata('success_message','Information was inserted successfully');
}
$this->database_g->create_attribute($new_id);
redirect(base_url().'back_office/control/manage/'.$id);
}
	
}

public function edit_attr($id_cont,$id){
$data["length_attr"] = 0;
$data["thumb_attr"] = 0;
$data["foreign_attr"] = 0;
$data["title"]="Edit Attribute for content type";
$data["title1"]="<a href='".base_url()."back_office/control/manage/".$id_cont."' class='blue'>Edit Attribute for content type</a>";
$data["content"]="back_office/controlers/add_attr";
$cond=array('id_content'=>$id_cont);
$data["id"]=$id;
$data["con_type"]=$this->fct->getonerecord($this->table,$cond);
$data["attr_type"]=$this->fct->getAll_1('attr_type','name');
$cond1=array('id_attr'=>$id);
$data["info"]=$this->fct->getonerecord('content_type_attr',$cond1);
$check=$data["info"];
if($check["type"] == 2)
$data["length_attr"] = 1;
if($check["type"] == 4)
$data["thumb_attr"] = 1;
if($check["type"] == 7)
$data["foreign_attr"] = 1;
//$this->load->view('back_office/template',$data);

/*
$data["length_attr"] = 0;
$data["thumb_attr"] = 0;
$data["foreign_attr"] = 0;
$data["title"]="Add Attribute for content type";
$data["title1"]="<a href='".base_url()."back_office/control/manage/".$id."' class='blue'>Add Attribute for content type</a>";
$data["content"]="back_office/controlers/add_attr";
$cond=array('id_content'=>$id);
$data["con_type"]=$this->fct->getonerecord($this->table,$cond);
$data["attr_type"]=$this->fct->getAll_001('attr_type','name');
$data["info"]=$this->fct->getAll('content_type_attr','sort_order');
*/
$tbl_name = str_replace(' ','_',$data["con_type"]['name']);
$data['fields'] = $this->fct->get_module_fields($tbl_name);
//print '<pre>';print_r($data['fields']);exit;
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/controlers/add_attr',$data);
$this->template->render();
}

public function delete_attr($id){
$cond=array('id_attr'=>$id);
$attribute = $this->fct->getonerow("content_type_attr",$cond);
$id1 = $attribute['id_content'];
$valid = $this->database_g->delete_attribute($id);
if($valid)
$this->session->set_userdata('success_message','Information was deleted successfully');
else
$this->session->set_userdata('error_message','Attribute not found');
redirect(base_url().'back_office/control/manage/'.$id1);
}

public function delete_all_attr(){
$cehcklist= $this->input->post('cehcklist');
$check_option= $this->input->post('check_option');
if($check_option == "delete_all"){
if(count($cehcklist) > 0){
for($i = 0; $i < count($cehcklist); $i++){
if($cehcklist[$i] != ''){
	$valid = $this->database_g->delete_attribute($cehcklist[$i] );
}
} 
} 
$this->session->set_userdata('success_message','Informations were deleted successfully');
}
redirect(base_url().'back_office/control/manage/'.$id1);	
}

public function sorted_attr(){
$sort=array();
foreach($this->input->get('table-1') as $key => $val){
if(!empty($val))
$sort[]=$val;	
}
$i=0;
for($i=0; $i<count($sort); $i++){
$_data=array('sort_order'=>$i);
$this->db->where('id_attr',$sort[$i]);
$this->db->update('content_type_attr',$_data);	
}
}

public function delete_image($field,$image,$id){
unlink("./uploads/content_type/".$image);
unlink("./uploads/content_type/32x32/".$image);									
$_data[$field]="";
$this->db->where("id_content",$id);
$this->db->update("content_type",$_data);
redirect(base_url()."back_office/control");	
}

public function admin_rules(){
$data["title"]="Manage Admin Rules";
$data["content"]="back_office/controlers/rules";
$data["info"]=$this->fct->getAll_1('admin_rules','rules');
$this->load->view('back_office/template',$data);
}

public function update_admin_rules(){

$cehcklist= $this->input->post('cehcklist');
$checklist= array();
if(count($cehcklist) != 0){
$rule=array();
for($i = 0; $i < count($cehcklist); $i++){
if($cehcklist[$i] != ''){
$rule[]=$this->input->post('rule'.$cehcklist[$i]);
}
}
// update admin rules table 
$info=$this->fct->getAll_1('admin_rules','rules');
foreach($info as $val){
if( in_array($val["rules"], $rule) ){
$active = 1; }
else {
$active = 0; }
$_data=array('active'=>$active);
$this->db->where('rules',$val["rules"]);
$this->db->update('admin_rules',$_data);
}
$this->session->set_userdata('success_message','Informations were updated successfully');
}
redirect(base_url()."back_office/control/admin_rules");	 
}

//manage gallery 
public function manage_gallery($content_type,$id){
$data["title"]="Manage Gallery";
$data["title1"]="<a href='".base_url()."back_office/".$content_type."' class='blue'>Manage ".str_replace("_"," ",$content_type)." Poto Gallery</a>";
$data["content_type"]=$content_type;
$data["id_gallery"]=$id;
$table=$content_type."_gallery";
$q="SELECT * FROM `".$table."` WHERE id_".$content_type."='".$id."' ORDER BY sort_order";
$query=$this->db->query($q);
$data["info"]=$query->result_array();
//print_r($data["info"]);
//$data["info"]=$this->fct->getAll_cond('content_type_attr','sort_order',$cond);
//$data["content"]="back_office/manage_gallery/list";
//$this->load->view('back_office/template',$data);
$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
$this->template->add_js('assets/js/custom/manage_photos.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/manage_gallery/list',$data);
$this->template->render();
}

// add photo gallery 
public function add_photos($content_type,$id){
$data["title"]="Add Photos";
$data["title1"]="<a href='".base_url()."back_office/control/manage_gallery/".$content_type."/".$id."' class='blue' >
Manage ".str_replace("_"," ",$content_type)." Poto Gallery</a>";
$data["content_type"]=$content_type;
$data["id_gallery"]=$id;
$data['sizes'] = get_cell('content_type','thumb_val_gal','name',$content_type);
//$data["content"]="back_office/manage_gallery/add";
//$this->load->view('back_office/template',$data);	
$this->template->add_js('assets/js/custom/manage_photos.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/manage_gallery/add',$data);
$this->template->render();
}

public function edit_photo($content_type="",$id="",$id_gallery=""){
$data["title"]="Edit Photo";
$data["title1"]="<a href='".base_url()."back_office/control/manage_gallery/".$content_type."/".$id."' class='blue' >
Manage ".str_replace("_"," ",$content_type)." Poto Gallery</a>";
$data["content_type"]=$content_type;
$data["id_gallery"]=$id;
$data["id"]=$id_gallery;
$cond=array('id_gallery'=>$id_gallery);
$data["info"]=$this->fct->getonerecord($content_type.'_gallery',$cond);

$data['sizes'] = get_cell('content_type','thumb_val_gal','name',$content_type);
//exit($data['sizes']);
//$data["content"]="back_office/manage_gallery/edit";
//$this->load->view('back_office/template',$data);
$this->template->add_js('assets/js/custom/manage_photos.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/manage_gallery/edit',$data);
$this->template->render();
}

public function edit_photo_submit(){
$content_type=$this->input->post('content_type');
$id=$this->input->post('id');
$id_gallery=$this->input->post('id_gallery');
$cond=array("id_gallery"=>$id);
$image=$this->fct->getonecell($content_type."_gallery","image",$cond);
if(!empty($_FILES["image"]["name"])) {
if(file_exists('./uploads/'.$content_type.'/gallery/'.$image)){
unlink('./uploads/'.$content_type.'/gallery/'.$image); }
if(file_exists('./uploads/'.$content_type.'/gallery/120x120/'.$image)){
unlink('./uploads/'.$content_type.'/gallery/120x120/'.$image); }
// unlink thumbnails also
$cond2=array('used_name'=>$content_type);
$thumb_val_gal=$this->fct->getonecell("content_type","thumb_val_gal",$cond2);
$resize_status= $this->fct->getonecell('content_type','resize_status',$cond2);
$sumb_val1=explode(",",$thumb_val_gal);
foreach($sumb_val1 as $key => $value){
if(file_exists('./uploads/'.$content_type.'/gallery/'.$value.'/'.$image)){
unlink("./uploads/".$content_type."/gallery/".$value."/".$image); }									
}
// end unlink  thumbnails 
$image1= $this->fct->uploadImage("image",$content_type."/gallery");
$this->fct->createthumb($image1,$content_type."/gallery","120x120");
if($resize_status == 1){
$this->fct->createthumb1($image1,$content_type."/gallery",$thumb_val_gal);
} else {
$this->fct->createthumb($image1,$content_type."/gallery",$thumb_val_gal); }
$_data=array('title' => $this->input->post("title"),
'image' =>$image1);
} else {
$_data=array('title' => $this->input->post("title"));
}
$this->db->where('id_gallery',$id);
$this->db->update($content_type.'_gallery',$_data);	
$this->session->set_userdata('success_message','Photos were updated successfully');
redirect(base_url().'back_office/control/manage_gallery/'.$content_type.'/'.$id_gallery);	
	
}
// submit photos 
public function submit_photos(){
$content_type=$this->input->post('content_type');
$cond=array('used_name' => $content_type);
$thumb_val_gal= $this->fct->getonecell('content_type','thumb_val_gal',$cond);
$resize_status= $this->fct->getonecell('content_type','resize_status',$cond);
$id_gallery=$this->input->post('id_gallery');
for($i =0 ; $i < 5 ; $i++){
 $image="image".$i;
 $title="title".$i;
if(!empty($_FILES[$image]["name"]) ) {
 $image1= $this->fct->uploadImage($image,$content_type."/gallery");
$this->fct->createthumb($image1,$content_type."/gallery","120x120");
if($resize_status == 1){
$this->fct->createthumb1($image1,$content_type."/gallery",$thumb_val_gal);	
} else {
$this->fct->createthumb($image1,$content_type."/gallery",$thumb_val_gal);
}
$_data=array('title' => $this->input->post($title),
'image' =>$image1,
'id_'.$content_type => $id_gallery);
 $this->db->insert($content_type.'_gallery',$_data);
}
}

$this->session->set_userdata('success_message','Photos were uploaded successfully');
redirect(base_url().'back_office/control/manage_gallery/'.$content_type.'/'.$id_gallery);	
}

public function delete_photo($content_type,$id_gallery){
$cond1=array('id_gallery'=>$id_gallery);
$id=$this->fct->getonecell($content_type."_gallery","id_".$content_type,$cond1);
$image=$this->fct->getonecell($content_type."_gallery","image",$cond1);
$cond2=array('used_name'=>$content_type);
$thumb_val_gal=$this->fct->getonecell("content_type","thumb_val_gal",$cond2);
unlink('./uploads/'.$content_type.'/gallery/'.$image);
unlink('./uploads/'.$content_type.'/gallery/120x120/'.$image);
$sumb_val1=explode(",",$thumb_val_gal);
foreach($sumb_val1 as $key => $value){
unlink("./uploads/".$content_type."/gallery/".$value."/".$image);									
}
$this->db->where('id_gallery',$id_gallery);	
$this->db->delete($content_type."_gallery");
$this->session->set_userdata('success_message','Photo was deleted successfully');
redirect(base_url().'back_office/control/manage_gallery/'.$content_type.'/'.$id);
}

public function delete_all_photos(){
$cehcklist= $this->input->post('cehcklist');
$check_option= $this->input->post('check_option');
$content_type=$this->input->post('content_type');
$id=$this->input->post('id_gallery');
if($check_option == "delete_all"){
if(count($cehcklist) > 0){
for($i = 0; $i < count($cehcklist); $i++){
if($cehcklist[$i] != ''){
// deleted action 
$cond1=array('id_gallery'=>$cehcklist[$i]);
$image=$this->fct->getonecell($content_type."_gallery","image",$cond1);
unlink('./uploads/'.$content_type.'/gallery/'.$image);
unlink('./uploads/'.$content_type.'/gallery/120x120/'.$image);
// delter thumbnails 
$cond2=array('used_name'=>$content_type);
$thumb_val_gal=$this->fct->getonecell("content_type","thumb_val_gal",$cond2);
$sumb_val1=explode(",",$thumb_val_gal);
foreach($sumb_val1 as $key => $value){
unlink("./uploads/".$content_type."/gallery/".$value."/".$image);									
}
// end delete thumbnails 

$this->db->where('id_gallery',$cehcklist[$i]);	
$this->db->delete($content_type."_gallery");		
}
} } 
$this->session->set_userdata('success_message','Photos were deleted successfully');
}
redirect(base_url().'back_office/control/manage_gallery/'.$content_type.'/'.$id);
}

public function sorteddd(){
$content_type=$this->session->userdata('content_type_gallery');
$sort=array();
foreach($this->input->get('usersList') as $key => $val){
if(!empty($val))
$sort[]=$val;	
}
$i=0;
for($i=0; $i<count($sort); $i++){
$_data=array('sort_order'=>$i);
$this->db->where('id_gallery',$sort[$i]);
$this->db->update($content_type.'_gallery',$_data);	
}
}

public function sort_attributes(){
$content_type=$this->session->userdata('content_type_attr');
$sort=array();
foreach($this->input->get('attributes_sort') as $key => $val){
if(!empty($val))
$sort[]=$val;	
}
$i=0;
for($i=0; $i<count($sort); $i++){
$_data=array('sort_order'=>$i);
$this->db->where('id_attr',$sort[$i]);
$this->db->update('content_type_attr',$_data);	
}
}

public function delete_image_gallery($field,$image,$id_gallery,$id,$content_type){
if(file_exists('./uploads/'.$content_type.'/gallery/'.$image)){
unlink("./uploads/".$content_type."/gallery/".$image); }
if(file_exists('./uploads/'.$content_type.'/gallery/120x120/'.$image)){
unlink("./uploads/".$content_type."/gallery/120x120/".$image); }

$cond2=array('used_name'=>$content_type);
$thumb_val_gal=$this->fct->getonecell("content_type","thumb_val_gal",$cond2);
$sumb_val1=explode(",",$thumb_val_gal);
foreach($sumb_val1 as $key => $value){
if(file_exists('./uploads/'.$content_type.'/gallery/'.$value.'/'.$image)){
unlink("./uploads/".$content_type."/gallery/".$value."/".$image); }								
}								
$_data[$field]="";
$this->db->where("id_gallery",$id);
$this->db->update($content_type."_gallery",$_data);
redirect(base_url()."back_office/control/edit_photo/".$content_type."/".$id_gallery."/".$id);	
}


public function menu_groups(){
$data["title"]="List Menu Groups";
$data["content"]="back_office/controlers/menu_groups";
$data["info"]=$this->fct->getAll('menu_groups','sort_order');
$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
$this->template->add_js('assets/js/custom/controlers.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/controlers/menu_groups',$data);
$this->template->render();
} 

public function create_group(){
$data["title"]="Create Group";
$data["content"]="back_office/controlers/add_group";
$this->template->add_js('assets/js/custom/controlers.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/controlers/add_group',$data);
$this->template->render();
} 

public function edit_group($id){
$data["title"]="Edit Group";
$cond=array('id'=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord('menu_groups',$cond);
$data["content"]="back_office/controlers/add_group";
$this->template->add_js('assets/js/custom/controlers.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/controlers/add_group',$data);
$this->template->render();
}
function sort_groups()
{
	$sort=array();
	foreach($this->input->get('menu_groups_order') as $key => $val){
	if(!empty($val))
	$sort[]=$val;	
	}
	$i=0;
	for($i=0; $i<count($sort); $i++){
		$_data=array('sort_order'=>$i);
		$this->db->where('id',$sort[$i]);
		$this->db->update('menu_groups',$_data);	
	}
}
function submit_group()
{
$this->form_validation->set_rules('name', 'Name', 'trim|required');
if($this->form_validation->run() == FALSE){
	if($this->input->post('id')!=''){
		$this->edit_group($this->input->post('id'));
	}
	else {
		$this->create_group();
	}
}
else
{
$_data['name'] = $this->input->post('name');
if($this->input->post('id')!=''){
$this->db->where('id',$this->input->post('id'));
$this->db->update('menu_groups',$_data);
$new_id = $this->input->post('id');	
$this->session->set_userdata('success_message','Information was updated successfully');
} else {
$this->db->insert('menu_groups',$_data);
$new_id = $this->db->insert_id();	
$this->session->set_userdata('success_message','Information was inserted successfully');
}
redirect(admin_url('control/menu_groups'));
}

}
function delete_group($id)
{
	$this->db->where("menu_group",$id)->update('content_type',array('menu_group'=>0));
	$this->db->where("id",$id)->delete("menu_groups");
	$this->session->set_userdata('success_message','Information was deleted successfully');
	redirect(admin_url('control/menu_groups'));
}
}