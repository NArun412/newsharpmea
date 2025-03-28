<?php
if ( ! defined("BASEPATH")) exit("No direct script access allowed");
class Roles_permissions extends MY_Controller {

public function __construct()
{
parent::__construct();
$this->table="roles_permissions";
$this->load->model(admin_dir()."/roles_permissions_m");
$this->template->set_template("admin");
}

public function index($order=""){
check_permission('roles_permissions','view');	
if($order == "")
$order ="sort_order";
$data["title"]="List roles permissions";
$data["content"]="back_office/roles_permissions/list";
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
/*$cond = array();
$like = array();
$url = admin_url("roles_permissions");
$url_parm_q = "?";
$filters = $this->fct->get_module_fields("roles_permissions",FALSE,TRUE);

if($this->input->get("title") != "") {
	$like["title"] = $this->input->get("title");
	$url .= $url_parm_q."title=".$this->input->get("title");
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
$count_news = $this->roles_permissions_m->getall($cond,$like);
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
$data['info'] = $this->roles_permissions_m->getall($cond,$like,$config['per_page'],$page);
$this->session->set_userdata("admin_redirect_link",$url);*/
// end pagination .
$this->template->add_js('assets/js/jquery.tablednd_0_5.js');
$this->template->add_js('assets/js/custom/roles_permissions.js');
$this->template->write_view('header','back_office/includes/header',$data);
$this->template->write_view('leftbar','back_office/includes/leftbar',$data);
$this->template->write_view('rightbar','back_office/includes/rightbar',$data);
$this->template->write_view('content','back_office/roles_permissions/list',$data);
$this->template->render();

}


/////////////////////////////////////////////////////////////////////
function submit_permissions()
{
	check_permission('roles_permissions','edit');	
	//print '<pre>'; print_r($_POST); exit;
	$role_id = $this->input->post("role_id");
	$permissions = array();
	foreach(permissions() as $perm) {
		$permissions[$perm] = $this->input->post("perm_".$perm);
	}
	
	$all_content_types = $this->fct->get_content_types();
	
	$this->db->where("id_roles",$role_id)->delete("roles_permissions");
	$insert_batch = array();
	
	if(!empty($all_content_types) && !empty($permissions)) {
		foreach($all_content_types as $ct) {
			$id = $ct['id_content'];
			$i = array(
				'created_on'=>date("Y-m-d H:i:s"),
				'id_roles'=>$role_id,
				'id_content'=>$id,
				'module'=>get_cell("content_type","used_name","id_content",$id),
			);
			foreach(permissions() as $perm) {
				$p = 0;
				if(isset($permissions[$perm][$id]) && $permissions[$perm][$id] == 1)
				$p = 1;
				$i[$perm] = $p;
			}
			$insert_batch[] = $i;
		}
		if(!empty($insert_batch)) {
			$this->db->insert_batch("roles_permissions",$insert_batch);
			$this->fct->insert_log("update","roles_permissions",$role_id);
		}
	}
	if($this->input->post('update_all') == 1) {
		$this->db->query('DELETE FROM users_permissions WHERE id_users IN(SELECT id_users FROM users WHERE id_roles = '.$role_id.')');
		/*$this->db->join('users','users.id_users = users_permissions.id_users')->where("users.id_roles",$role_id)->delete("users_permissions");*/
	}
	$this->session->set_userdata("success_message","Permissions updated successfully.");
	redirect(admin_url("roles_permissions").'?id_roles='.$role_id);
}
}