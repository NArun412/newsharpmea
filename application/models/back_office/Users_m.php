<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users_m extends CI_Model{

public function get($id){
$this->db->select("users.*, seo.meta_title, seo.meta_keywords, seo.meta_description, seo.url_route");
$this->db->from("users");
if("users" != "seo")
$this->db->join("seo","seo.record = users.id_users AND seo.module = 'users'","LEFT");
$this->db->where("users.id_users",$id);
$query = $this->db->get();
$result = $query->row_array();
$result["translations"] = $this->db->where("translation_id",$id)->get("users")->result_array();
return $result;
}

public function getall($cond = array(), $like = array(), $limit = '', $offset = 0,$order = 'sort_order'){
	$this->db->where("translation_id",0);
	if(!empty($cond))
	$this->db->where($cond);
	if(!empty($like))
	$this->db->like($like);
$this->db->order_by($order); 
if($limit != "")
$this->db->limit($limit,$offset);
$query=$this->db->get('users');
if($limit != "")
return $query->result_array();	
else
return $query->num_rows();	
}

public function insert_update($data,$id = "")
{
	$postdata = $data;
	$fields = $this->db->list_fields("users");
	$arr = array();
	foreach($fields as $k => $field) {
		if($field != "id_users") {
			if(isset($data[$field])) {
				$content_type_attr_type = $this->db->select("a.type, a.foreign_key, a.id_content")->from("content_type_attr a")->join("content_type c","c.id_content = a.id_content")->where(array(
					"c.name"=>"users",
					"a.name"=>str_replace("_"," ",$field)
	))->get()->row_array();
				if(isset($content_type_attr_type["type"]) && $content_type_attr_type["type"] == 1) {
					$arr[$field] = dateformat($data[$field],"Y-m-d");
				}
				else {
					$arr[$field] = $data[$field];
				}
			}
		}
	}
	
	if(isset($arr['password']) && $arr['password'] != '') {
		$salt = generate_salt();
		$arr['password'] = secure_password($arr['password'],$salt);
		$arr['salt'] = $salt;
	}
	//print '<pre>'; print_r($arr); exit;
	$rid = false;
	if(!empty($arr)) {
		$default_lang = default_lang();
		$table_ext = "";
		//if(enable_review() && !user_can_publish())
		//$table_ext = "_review";
		if($id == "") {
			$arr["lang"] = $default_lang;
			$arr["created_on"] = date("Y-m-d H:i:s");
			if(enable_review() && !user_can_publish())
			$arr["status"] = 0;
			$this->db->insert("users",$arr);
			$rid = $this->db->insert_id();
			if(enable_review()) {
				//$arr["id_users"] = $rid;
				//$this->db->insert("users_review",$arr);
			}
			if($rid) {
				//if(enable_review() && !user_can_publish())
				//$this->fct->record_review("users",$rid,$default_lang);
				$this->fct->insert_log("create","users",$rid);
			}
		}
		else {
			$rid = $id;
			$data = $this->get($rid);
			$this->db->where("id_users",$id)->update("users",$arr);
			$affected_rows = $this->db->affected_rows();
			if(enable_review()) {
				if(user_can_publish()) {
					//$this->db->where("id_users",$id)->update("users_review",$arr);
				}
				else {
					if($affected_rows > 0) {
						//$this->fct->record_review("users",$rid,$default_lang);
					}
				}
			}
			if($affected_rows > 0)
			$this->fct->insert_log("update","users",$rid);
			if(isset($data["status"]) && isset($arr["status"]) && $data["status"] != $arr["status"]) {
				if($data["status"] == 0) {
					$this->fct->change_status("users",$rid,1);
				}
				else {
					$this->fct->change_status("users",$rid,0);
				}
			}
		}
		/*if(enable_review() && isset($postdata["approval"])) {
			$this->fct->change_approval("users",$rid,$default_lang,$postdata["approval"]);
		}*/
	}
	return $rid;
}
public function delete($id)
{
	$this->fct->insert_log("delete","users",$id);
	$data = $this->get($id);
	$this->fct->move_to_trash("users",$id,$data);
	$this->db->where("id_users",$id)->delete("users");
	$this->db->where("translation_id",$id)->delete("users");
	return true;
}

function redirect_denied_user_record_access($module,$record,$user)
{
	$valid = $this->check_user_record_access($module,$record,$user);
	if(!$valid) {
		redirect(admin_url("access_denied"));
	}
}
function check_user_record_access($module,$record,$user)
{
	$user_info = $user;
	$user_id = $user['id_users'];
	if($user_info['manage_his_content'] == 1) {
		$count = $this->db->where(array(
			'action'=>'create',
			'uid'=>$user_id,
			'module'=>$module,
			'record'=>$record
		))->get('log')->num_rows();
		if($count == 0)
		return false;
	}
	if($user_info['manage_his_division'] == 1) {
		if(in_array($module,array('products_technologies','products_tabs','product_support'))) {
			$this->db->join('products','products.id_products = '.$module.'.record AND '.$module.'.module = "products"');
			$this->db->where('products.id_divisions',$user_info['id_divisions']);
		}
		if(in_array($module,array('divisions','categories','products'))) {
			$this->db->where('id_divisions',$user_info['id_divisions']);
		}
		$query = $this->db->get($module);
		$count = $query->num_rows();
		if($count == 0)
		return false;
		else
		return true;
	}
	return true;
}

}