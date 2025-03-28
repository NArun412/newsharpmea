<?php
if ( ! defined("BASEPATH")) exit("No direct script access allowed");
class Note_labels extends MY_Controller {

public function __construct()
{
parent::__construct();
$this->table="note_labels";
$this->load->model("note_labels_m");
$this->template->set_template("admin");
}

public function index($order=""){
check_permission('note_labels','view');	
if($order == "")
$order ="sort_order";
$data["title"]="List note labels";
$data["content"]="back_office/note_labels/list";
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
$url = admin_url("note_labels");
$url_parm_q = "?";
$filters = $this->fct->get_module_fields("note_labels",FALSE,TRUE);

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
// pagination  start :
$count_news = $this->note_labels_m->getall($cond,$like);
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
$data['info'] = $this->note_labels_m->getall($cond,$like,$config['per_page'],$page);
$this->session->set_userdata("admin_redirect_link",$url);
// end pagination .
$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
$this->template->add_js('assets/js/custom/note_labels.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/note_labels/list',$data);
$this->template->render();
}

public function add(){
check_permission('note_labels','create');
$data["title"]="Add note labels";
$data["content"]="back_office/note_labels/add";
$this->template->add_js('assets/js/custom/note_labels.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/fields/form',$data);
$this->template->render();
} 

public function edit($id){
check_permission('note_labels','edit',$id);
$data["title"]="Edit note labels";
$data["content"]="back_office/note_labels/add";
$cond=array("id_note_labels"=>$id);
$data["id"]=$id;
$data["info"]=$this->note_labels_m->get($id);
$this->template->add_js('assets/js/custom/note_labels.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/fields/form',$data);
$this->template->render();
}

public function delete($id){
check_permission('note_labels','delete',$id);
$this->note_labels_m->delete($id);

$this->session->set_userdata("success_message","Information was deleted successfully");
if($this->session->userdata("admin_redirect_link") != "")
redirect($this->session->userdata("admin_redirect_link"));	
else
redirect(admin_url("note_labels/".$this->session->userdata("back_link")));

}

public function delete_all(){
check_permission('note_labels','delete');
$cehcklist= $this->input->post("cehcklist");
$check_option= $this->input->post("check_option");
if($check_option == "delete_all"){
if(count($cehcklist) > 0){
for($i = 0; $i < count($cehcklist); $i++){
if($cehcklist[$i] != ""){
if(has_permission('note_labels','delete',$cehcklist[$i]))
$this->note_labels_m->delete($cehcklist[$i]);
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
if($this->session->userdata("admin_redirect_link") != "")
redirect($this->session->userdata("admin_redirect_link"));	
else
redirect(admin_url("note_labels/".$this->session->userdata("back_link")));	
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
$this->db->where("id_note_labels",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
if($this->input->post("id")!="")
check_permission('note_labels','edit',$this->input->post("id"));
else
check_permission('note_labels','create');
	
$data["title"]="Add / Edit note labels";
$this->form_validation->set_rules("title", "TITLE", "trim|required");
if(seo_enabled("note labels")) {
$this->form_validation->set_rules("meta_title", "PAGE TITLE", "trim");
$this->form_validation->set_rules("url_route", "TITLE URL", "trim");
$this->form_validation->set_rules("meta_description", "META DESCRIPTION", "trim");
$this->form_validation->set_rules("meta_keywords", "META KEYWORDS", "trim");
}

if ($this->form_validation->run() == FALSE){ 
if($this->input->post("id")!="")
$this->edit($this->input->post("id"));
else
$this->add();

} else {
$_data["title"]=$this->input->post("title");	

$_data["lang"] = default_lang();	
	if($this->input->post("id")!=""){
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	$new_id = $this->note_labels_m->insert_update($this->input->post(),$this->input->post("id"));
	if(has_permission("note labels","edit") && seo_enabled("note labels")) {
		$this->fct->insert_update_seo("note_labels",$new_id,$this->input->post());
	}

if($this->session->userdata("admin_redirect_link") != "")
redirect($this->session->userdata("admin_redirect_link"));	
else
	redirect(admin_url("note_labels/".$this->session->userdata("back_link")));
	
}
}
}