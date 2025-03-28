<?php
if ( ! defined("BASEPATH")) exit("No direct script access allowed");
class Common extends MY_Controller {

public function __construct()
{
	parent::__construct();
	$this->template->set_template("admin");
}

function update_status($module,$record,$st)
{
	check_permission($module,'edit',$record);
	$this->load->model($module."_m");
	$m = $module."_m";
	$data = $this->$m->get($record);
	$return['result'] = 0;
	if(!empty($data)) {
		if($data['status'] == $st) {
			$return['message'] = '';
		}
		elseif($st != 0 && $st != 1 && $st != 2){
			$return['message'] = 'access denied';
		}
		else {
			$return['result'] = 1;
			$this->db->where('id_'.$module,$record)->update($module,array("status"=>$st));
			if($st == 0) {
				$return['label'] = '<span class="label label-danger">UnPublished</span>';
				$this->fct->insert_log("unpublish",$module,$record);
			}
			elseif($st == 2) {
				$return['label'] = '<span class="label label-warning">Under Review</span>';
				$this->fct->insert_log("put_under_review",$module,$record);
			}
			else {
				//$this->fct->publish_record($module,$record);
				$this->db->where('id_'.$module,$record)->update($module,array("status"=>1));
				$return['label'] = '<span class="label label-success">Published</span>';
				$this->fct->insert_log("publish",$module,$record);
			}
		}
	}
	$this->load->view("json",array("return"=>$return));
}

function get_module_records($mod) {
	//$mod_name = get_cell('content_type','name','id_content',$mod_id);
	//$mod_name = str_replace(' ','_',$mod_name);
	$mod_records = $this->fct->getAll_cond($mod,'title',array('translation_id'=>0));
	$html = render_module_records($mod_records,$mod,0);
	$this->load->view('json',array(
		'return'=>array(
			'result'=>1,
			'html'=>$html
		)
	));
}
/////////////////////////////////////////////////////////////////
function delete_file($module,$field,$name,$id)
{
	check_permission($module,'edit',$id);
	/*$get_content = $this->db->where("used_name",$module)->get("content_type")->row_array();
	$get_attr = $this->db->where(array(
		"id_content"=>$get_content['id_content'],
		"name"=>str_replace("_"," ",$field)
	))->get("content_type_attr")->row_array();*/
	
	if(file_exists("./uploads/".$module."/".$field."/".$name)){
		unlink("./uploads/".$module."/".$field."/".$name); 
	}
	$this->db->where("id_".$module,$id)->update($module,array($field=>""));
	$this->fct->insert_log('delete_file',$module,$id);
	$return['result'] = 1;
	$this->load->view("json",array("return"=>$return));
}
function delete_image($module,$field,$name,$id)
{
	check_permission($module,'edit',$id);
	$get_content = $this->db->where("used_name",$module)->get("content_type")->row_array();
	$get_attr = $this->db->where(array(
		"id_content"=>$get_content['id_content'],
		"name"=>str_replace("_"," ",$field)
	))->get("content_type_attr")->row_array();
	
	if(file_exists("./uploads/".$module."/".$field."/".$name)){
		unlink("./uploads/".$module."/".$field."/".$name); 
	}
	if(isset($get_attr["thumb"]) && $get_attr["thumb"] == 1 && $get_attr["thumb_val"] != ''){
		$sumb_val1=explode(",",$get_attr["thumb_val"]);
		foreach($sumb_val1 as $key => $value){
			if(file_exists("./uploads/".$module."/".$field."/".$value."/".$name)){
				unlink("./uploads/".$module."/".$field."/".$value."/".$name);	 
			}								
		} 
	} 
	$this->db->where("id_".$module,$id)->update($module,array($field=>""));
	$this->fct->insert_log('delete_image',$module,$id);
	$return['result'] = 1;
	$this->load->view("json",array("return"=>$return));
}

/** custom functions*********************************************/
function get_categories_by_division($id)
{
	$data['categories'] = $this->fct->getAll_cond("categories","sort_order",array("id_divisions"=>$id));
	$this->load->view("back_office/products/categories_drop_down",$data);
}
function get_lines_by_category($id)
{
	$data['lines'] = $this->fct->getAll_cond("products_lines","sort_order",array("id_categories"=>$id));
	$this->load->view("back_office/products/lines_drop_down",$data);
}
function tags_by_category($id)
{
	$data['tags'] = $this->fct->getAll_cond("products_tags","sort_order",array("id_parent"=>$id));
	$this->load->view("back_office/products/tags_drop_down",$data);
}

function delete_tag()
{
	$pid = $this->input->post("pid");
	$tid = $this->input->post("tid");
	$this->db->where(array(
		'id_products'=>$pid,
		'id_products_tags'=>$tid
	))->delete('products_tags_rel');
	return true;
}
function tabs($key_type,$key)
{
	$data['key_type'] = $key_type;
	$data['key'] = $key;
	$data['tabs'] = array();
	
	if($key_type == 'edit')
	$data['tabs'] = $this->db->where("id_products",$key)->get("products_tabs")->result_array();
	else
	$data['tabs'] = $this->db->where("rowkey",$key)->get("products_tabs")->result_array();
	$data["title"]="List Tabs";
	$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
	$this->template->write_view('content','back_office/products/tabs',$data);
	$this->template->render();

}
function tab_form($key_type,$key,$id = '')
{
	$data['key_type'] = $key_type;
	$data['key'] = $key;
	$data['info'] = array();
	if($id != '' && is_numeric($id)) {
		$data['id'] = $id;
		$data['info'] = $this->db->where("id_products_tabs",$id)->get("products_tabs")->row_array();
	}
	//$this->load->view("back_office/products/tab_form");
	
	$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
	$this->template->write_view('content','back_office/products/tab_form',$data);
	$this->template->render();
}
function delete_tab($key_type,$key,$id)
{
	check_permission('products_tabs','delete',$id);
	$this->load->model("products_tabs_m");
	$this->products_tabs_m->delete($id);
	$this->session->set_userdata("success_message","Information was deleted successfully");
	redirect(admin_url("common/tabs/".$key_type."/".$key));
}
public function submit_tab(){
	$this->load->model("products_tabs_m");
if($this->input->post("id")!="")
check_permission('products_tabs','edit',$this->input->post("id"));
else
check_permission('products_tabs','create');
	
$data["title"]="Add / Edit products tabs";
$this->form_validation->set_rules("title", "TITLE", "trim|required");
if(seo_enabled("products tabs")) {
$this->form_validation->set_rules("meta_title", "PAGE TITLE", "trim");
$this->form_validation->set_rules("url_route", "TITLE URL", "trim");
	$this->form_validation->set_rules("meta_description", "META DESCRIPTION", "trim");
	$this->form_validation->set_rules("meta_keywords", "META KEYWORDS", "trim");
}
$this->form_validation->set_rules("description","description", "trim");
		if(!empty($_FILES["file"]["name"])) {
			$_POST["file"] = $_FILES["file"]["name"];
		}
		else {
			//$_POST["file"] = "";
		}
		$this->form_validation->set_rules("file", "file", "trim");
//$this->form_validation->set_rules("id_products", "product", "trim|required");
if ($this->form_validation->run() == FALSE){ 
if($this->input->post("id")!="")
$this->tab_form($this->input->post("key_type"),$this->input->post("key"),$this->input->post("id"));

} else {
$_data["title"]=$this->input->post("title");	

if(!empty($_FILES["file"]["name"])) {
	$_POST["file"] = $this->fct->upload_file_from_admin("products_tabs","file");
}

if($this->input->post('key_type') == 'edit') {
	$_POST['id_products'] = $this->input->post('key');
}
else {
	$_POST['rowkey'] = $this->input->post('key');
}

$_POST["lang"] = default_lang();	
	if($this->input->post("id")!=""){
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	$new_id = $this->products_tabs_m->insert_update($this->input->post(),$this->input->post("id"));
	if(has_permission("products tabs","edit") && seo_enabled("products tabs")) {
		$this->fct->insert_update_seo("products_tabs",$new_id,$this->input->post());
	}
	redirect(base_url()."back_office/common/tabs/".$this->input->post('key_type').'/'.$this->input->post('key'));
}
}


function documents($key_type,$key)
{
	$data['key_type'] = $key_type;
	$data['key'] = $key;
	$data['documents'] = array();
	
	if($key_type == 'edit')
	$data['documents'] = $this->db->where("id_products",$key)->get("product_support")->result_array();
	else
	$data['documents'] = $this->db->where("rowkey",$key)->get("product_support")->result_array();
	$data["title"]="List Documents";
	$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
	$this->template->write_view('content','back_office/products/documents',$data);
	$this->template->render();

}
function document_form($key_type,$key,$id = '')
{
	$data['title'] = "Add New Document";
	$data['key_type'] = $key_type;
	$data['key'] = $key;
	$data['info'] = array();
	if($id != '' && is_numeric($id)) {
		$data['id'] = $id;
		$data['info'] = $this->db->where("id_product_support",$id)->get("product_support")->row_array();
		$data['title'] = "Edit Document";
	}
	$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
	$this->template->write_view('content','back_office/product_support/add',$data);
	$this->template->render();
}

function delete_document($key_type,$key,$id)
{
	check_permission('product_support_m','delete',$id);
	$this->load->model("product_support_m");
	$this->product_support_m->delete($id);
	$this->session->set_userdata("success_message","Information was deleted successfully");
	redirect(admin_url("common/documents/".$key_type."/".$key));
}
public function submit_document(){
	$this->load->model("product_support_m");
if($this->input->post("id")!="")
check_permission('product_support','edit',$this->input->post("id"));
else
check_permission('product_support','create');
	
$data["title"]="Add / Edit product support";
if(seo_enabled("product support")) {
$this->form_validation->set_rules("meta_title", "PAGE TITLE", "trim");
$this->form_validation->set_rules("url_route", "TITLE URL", "trim");
$this->form_validation->set_rules("meta_description", "META DESCRIPTION", "trim");
$this->form_validation->set_rules("meta_keywords", "META KEYWORDS", "trim");
}

//$this->form_validation->set_rules("id_product_keys", "product key", "trim|required");
$this->form_validation->set_rules("id_products_languages", "product language", "trim|required");
$this->form_validation->set_rules("id_document_types", "document type", "trim|required");
$this->form_validation->set_rules("id_operating_systems", "operating system", "trim");
$this->form_validation->set_rules("id_emulations", "emulation", "trim");
		if(!empty($_FILES["file"]["name"])) {
			$_POST["file"] = $_FILES["file"]["name"];
		}
		else {
			//$_POST["file"] = "";
		}
		$this->form_validation->set_rules("file", "file", "trim");
$this->form_validation->set_rules("link", "link", "trim");
/*$this->form_validation->set_rules("id_products", "product", "trim|required");*/
if ($this->form_validation->run() == FALSE){ 
$this->document_form($this->input->post("key_type"),$this->input->post("key"),$this->input->post("id"));

} else {
$_data["title"]=$this->input->post("title");	

if(!empty($_FILES["file"]["name"])) {
	$_POST["file"] = $this->fct->upload_file_from_admin("product_support","file");
}
if($this->input->post('key_type') == 'edit') {
$_POST['id_products'] = $this->input->post('key');
}
else {
	$_POST['rowkey'] = $this->input->post('key');
}

$_POST["lang"] = default_lang();	
	if($this->input->post("id")!=""){
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	$new_id = $this->product_support_m->insert_update($this->input->post(),$this->input->post("id"));
	if(has_permission("product support","edit") && seo_enabled("product support")) {
		$this->fct->insert_update_seo("product_support",$new_id,$this->input->post());
	}
redirect(base_url()."back_office/common/documents/".$this->input->post('key_type').'/'.$this->input->post('key'));
	
}
}


}