<?
$multi_languages = getonecell('settings','multi_languages',array('id_settings' => 1 ));
$models='<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');
class '.ucfirst($table_name).'_m extends CI_Model{

public function __construct(){
	parent::__construct();
	$this->table = "'.$table_name.'";
	if($this->user_role > 3)
	$this->table = "'.$table_name.'_review";
}

public function get($id){
$this->db->select($this->table.".*");
$this->db->from($this->table);
$this->db->where($this->table.".id_".$this->module,$id);
$query = $this->db->get();
$result = $query->row_array();
$result["translations"] = $this->db->where("translation_id",$id)->get($this->table)->result_array();
return $result;
}

public function get_seo($id){
$this->db->select("*");
$this->db->from("seo");
$this->db->where("module",$this->table);
$this->db->where("record",$id);
$query = $this->db->get();
$result = $query->row_array();
$result["translations"] = $this->db->where("translation_id",$id)->get("seo")->result_array();
return $result;
}

public function getall($cond = array(), $like = array(), $limit = \'\', $offset = 0,$order = \'sort_order\'){
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
	$fields = $this->db->list_fields("'.$table_name.'");
	$arr = array();
	foreach($fields as $k => $field) {
		if($field != "id_'.$table_name.'") {
			if(isset($data[$field])) {
				$content_type_attr_type = $this->db->select("a.type, a.foreign_key, a.id_content")->from("content_type_attr a")->join("content_type c","c.id_content = a.id_content")->where(array(
					"c.name"=>"'.$table_name.'",
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
			$this->db->insert("'.$table_name.'",$arr);
			$rid = $this->db->insert_id();
			if(enable_review()) {
				$arr["id_'.$table_name.'"] = $rid;
				$this->db->insert("'.$table_name.'_review",$arr);
			}
			if($rid) {
				//if(enable_review() && !user_can_publish())
				$this->fct->record_review("'.$table_name.'",$rid,$default_lang);
				$this->fct->insert_log("create","'.$table_name.'",$rid);
			}
		}
		else {
			$rid = $id;
			$data = $this->get($rid);
			$this->db->where("id_'.$table_name.'",$id)->update("'.$table_name.'".$table_ext,$arr);
			$affected_rows = $this->db->affected_rows();
			if(enable_review()) {
				if(user_can_publish()) {
					$this->db->where("id_'.$table_name.'",$id)->update("'.$table_name.'_review",$arr);
				}
				else {
					if($affected_rows > 0) {
						$this->fct->record_review("'.$table_name.'",$rid,$default_lang);
					}
				}
			}
			if($affected_rows > 0)
			$this->fct->insert_log("update","'.$table_name.'",$rid);
			if(isset($data["status"]) && isset($arr["status"]) && $data["status"] != $arr["status"]) {
				if($data["status"] == 0) {
					$this->fct->change_status("'.$table_name.'",$rid,1);
				}
				else {
					$this->fct->change_status("'.$table_name.'",$rid,0);
				}
			}
		}
		if(enable_review() && isset($postdata["approval"])) {
			$this->fct->change_approval("'.$table_name.'",$rid,$default_lang,$postdata["approval"]);
		}
	}
	return $rid;
}
public function delete($id)
{
	$data = $this->get($id);
	$this->db->where("id_'.$table_name.'",$id)->delete("'.$table_name.'");
	$this->db->where("translation_id",$id)->delete("'.$table_name.'");
	if(enable_review()) {
		$this->db->where("id_'.$table_name.'",$id)->delete("'.$table_name.'_review");
		$this->db->where("translation_id",$id)->delete("'.$table_name.'_review");
	}
	$this->fct->insert_log("delete","'.$table_name.'",$id);
	$this->fct->move_to_trash("'.$table_name.'",$id,$data);
	return true;
}
}';
?>