<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Images_m extends CI_Model{

public function __construct(){
	parent::__construct();
	$this->table = "images";
	if($this->user_role > 3)
	$this->table = "images_review";
}

public function get($id){
$this->db->select($this->table.".*, seo.meta_title, seo.meta_keywords, seo.meta_description, seo.url_route");
$this->db->from($this->table);
if($this->table != "seo")
$this->db->join("seo","seo.record = images.id_images AND seo.module = 'images'","LEFT");
$this->db->where($this->table.".id_images",$id);
$query = $this->db->get();
$result = $query->row_array();
$result["translations"] = $this->db->where("translation_id",$id)->get($this->table)->result_array();
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
$query=$this->db->get($this->table);
if($limit != "")
return $query->result_array();	
else
return $query->num_rows();	
}

public function insert_update($data,$id = "")
{
	$postdata = $data;
	$fields = $this->db->list_fields("images");
	$arr = array();
	foreach($fields as $k => $field) {
		if($field != "id_images") {
			if(isset($data[$field])) {
				$content_type_attr_type = $this->db->select("a.type, a.foreign_key, a.id_content")->from("content_type_attr a")->join("content_type c","c.id_content = a.id_content")->where(array(
					"c.name"=>"images",
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
			$this->db->insert("images",$arr);
			$rid = $this->db->insert_id();
			if(enable_review()) {
				$arr["id_images"] = $rid;
				$this->db->insert("images_review",$arr);
			}
			if($rid) {
				//if(enable_review() && !user_can_publish())
				$this->fct->record_review("images",$rid,$default_lang);
				$this->fct->insert_log("create","images",$rid);
			}
		}
		else {
			$rid = $id;
			$data = $this->get($rid);
			$this->db->where("id_images",$id)->update("images".$table_ext,$arr);
			$affected_rows = $this->db->affected_rows();
			if(enable_review()) {
				if(user_can_publish()) {
					$this->db->where("id_images",$id)->update("images_review",$arr);
				}
				else {
					if($affected_rows > 0) {
						$this->fct->record_review("images",$rid,$default_lang);
					}
				}
			}
			if($affected_rows > 0)
			$this->fct->insert_log("update","images",$rid);
			if(isset($data["status"]) && isset($arr["status"]) && $data["status"] != $arr["status"]) {
				if($data["status"] == 0) {
					$this->fct->change_status("images",$rid,1);
				}
				else {
					$this->fct->change_status("images",$rid,0);
				}
			}
		}
		if(enable_review() && isset($postdata["approval"])) {
			$this->fct->change_approval("images",$rid,$default_lang,$postdata["approval"]);
		}
	}
	return $rid;
}
public function delete($id)
{
	$this->fct->insert_log("delete","images",$id);
	$data = $this->get($id);
	$this->fct->move_to_trash("images",$id,$data);
	$this->db->where("id_images",$id)->delete("images");
	$this->db->where("translation_id",$id)->delete("images");
	return true;
}
}