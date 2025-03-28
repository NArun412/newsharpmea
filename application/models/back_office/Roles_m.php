<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Roles_m extends CI_Model{

public function get($id){
$this->db->select("roles.*, seo.meta_title, seo.meta_keywords, seo.meta_description, seo.url_route");
$this->db->from("roles");
if("roles" != "seo")
$this->db->join("seo","seo.record = roles.id_roles AND seo.module = 'roles'","LEFT");
$this->db->where("roles.id_roles",$id);
$query = $this->db->get();
$result = $query->row_array();
$result["translations"] = $this->db->where("translation_id",$id)->get("roles")->result_array();
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
$query=$this->db->get('roles');
//exit($this->db->last_query());
if($limit != "")
return $query->result_array();	
else
return $query->num_rows();	
}

public function insert_update($data,$id = "")
{
	$postdata = $data;
	$fields = $this->db->list_fields("roles");
	$arr = array();
	foreach($fields as $k => $field) {
		if($field != "id_roles") {
			if(isset($data[$field])) {
				$content_type_attr_type = $this->db->select("a.type, a.foreign_key, a.id_content")->from("content_type_attr a")->join("content_type c","c.id_content = a.id_content")->where(array(
					"c.name"=>"roles",
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
	$rid = false;
	if(!empty($arr)) {
		$default_lang = default_lang();
		$table_ext = "";
		if(enable_review() && !user_can_publish())
		$table_ext = "_review";
		if($id == "") {
			$arr["lang"] = $default_lang;
			$arr["created_on"] = date("Y-m-d H:i:s");
			if(enable_review() && !user_can_publish())
			$arr["status"] = 0;
			$this->db->insert("roles",$arr);
			$rid = $this->db->insert_id();
			if(enable_review()) {
				$arr["id_roles"] = $rid;
				$this->db->insert("roles_review",$arr);
			}
			if($rid) {
				//if(enable_review() && !user_can_publish())
				$this->fct->record_review("roles",$rid,$default_lang);
				$this->fct->insert_log("create","roles",$rid);
			}
		}
		else {
			$rid = $id;
			$data = $this->get($rid);
			$this->db->where("id_roles",$id)->update("roles".$table_ext,$arr);
			$affected_rows = $this->db->affected_rows();
			if(enable_review()) {
				if(user_can_publish()) {
					$this->db->where("id_roles",$id)->update("roles_review",$arr);
				}
				else {
					if($affected_rows > 0) {
						$this->fct->record_review("roles",$rid,$default_lang);
					}
				}
			}
			if($affected_rows > 0)
			$this->fct->insert_log("update","roles",$rid);
    //	print '<pre>'; print_r($data);
	//print '<pre>'; print_r($arr);exit;
			if(isset($data["status"]) && isset($arr["status"]) && $data["status"] != $arr["status"]) {
				if($data["status"] == 0) {
					$this->fct->change_status("roles",$rid,1);
				}
				else {
					$this->fct->change_status("roles",$rid,0);
				}
			}
		}
		if(enable_review() && isset($postdata["approval"])) {
		    if($postdata["approval"] == 1 && $this->user_role <= 3)
		    	$this->fct->change_status("roles",$rid,1);
			$this->fct->change_approval("roles",$rid,$default_lang,$postdata["approval"]);
		}
	}
	return $rid;
}
public function delete($id)
{
    if($id != 1) {
	$this->fct->insert_log("delete","roles",$id);
	$data = $this->get($id);
	$this->fct->move_to_trash("roles",$id,$data);
	$this->db->where("id_roles",$id)->delete("roles");
	$this->db->where("translation_id",$id)->delete("roles");
	return true;
    }
    return false;
}
}