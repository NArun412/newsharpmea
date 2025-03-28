<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manage_m extends CI_Model{

public function __construct(){
	parent::__construct();
	$this->table = $this->module;
	if(isset($this->table_ext)) {
		$this->table = $this->table.$this->table_ext;
	}
	elseif($this->user_role > 2) {
		$this->table_ext = "_review";
		$this->table = $this->table.$this->table_ext;
	}
	else {$this->table_ext = "";}
}
//////////////////////////////////////////////////////////////////////
public function get($id) {
	$this->db->select($this->table.".*");
	$this->db->from($this->table);
	$this->db->where($this->table.".id_".$this->module,$id);
	$query = $this->db->get();
	$result = $query->row_array();
	if(!empty($result))
	$result["translations"] = $this->db->where("translation_id",$id)->get($this->table)->result_array();
	return $result;
}
public function get_from_review($id) {
	$this->db->select($this->module."_review.*");
	$this->db->from($this->module."_review");
	$this->db->where($this->module."_review".".id_".$this->module,$id);
	$query = $this->db->get();
	$result = $query->row_array();
	if(!empty($result))
	$result["translations"] = $this->db->where("translation_id",$id)->get($this->module."_review")->result_array();
	return $result;
}
//////////////////////////////////////////////////////////////////////
public function get_from_connect($connected_table,$id){
	$this->db->select($connected_table.$this->table_ext.".*");
	$this->db->from($connected_table.$this->table_ext);
	$this->db->where($connected_table.$this->table_ext.".id_".$connected_table,$id);
	$query = $this->db->get();
	$result = $query->row_array();
	if(!empty($result))
	$result["translations"] = $this->db->where("translation_id",$id)->get($connected_table.$this->table_ext)->result_array();
	return $result;
}
//////////////////////////////////////////////////////////////////////
public function getall($cond = array(), $like = array(), $limit = '', $offset = 0,$order = 'sort_order') {
	$check_if_view_only = get_cell('content_type','view_details_only','name',$this->table);
	if($check_if_view_only == 1) unset($cond['lang']);
	$this->db->select($this->table.'.*');
	if($this->user_info['manage_his_content'] == 1) {
		$this->db->join('log','log.record = '.$this->table.'.id_'.$this->module.' AND log.module = "'.$this->module.'"');
	}
	if($this->user_info['manage_his_division'] == 1) {
		if(in_array($this->module,array('products_technologies','products_tabs','product_support')))
		$this->db->join('products','products.id_products = '.$this->table.'.record AND '.$this->table.'.module = "products"');
	}
	//exit($cond['lang']);
	if(isset($cond['lang']) && $cond['lang'] == $this->default_lang && $check_if_view_only == 0)
	$this->db->where($this->table.".translation_id",0);
	if(!empty($cond))
	$this->db->where($cond);
	if($this->user_info['manage_his_content'] == 1) {
		//exit($this->user_id);
		$this->db->where(array(
			'log.action'=>'create',
			'log.uid'=>$this->user_id
		));
	}
	if($this->user_info['manage_his_division'] == 1) {
		if(in_array($this->module,array('products_technologies','products_tabs','product_support')))
		$this->db->where('products.id_divisions',$this->user_info['id_divisions']);
		
		if(in_array($this->module,array('divisions','categories','products')))
		$this->db->where('id_divisions',$this->user_info['id_divisions']);
	}
	if(!empty($like))
	$this->db->like($like);
	$this->db->order_by($order); 
	if($limit != "")
	$this->db->limit($limit,$offset);
	$query=$this->db->get($this->table);
	//echo $this->db->last_query();exit;
	if($limit != "")
	return $query->result_array();	
	else
	return $query->num_rows();	
}
//////////////////////////////////////////////////////////////////////
public function getall_from_connect($connected_table,$cond = array(), $like = array(), $limit = '', $offset = 0,$order = 'sort_order'){
	  $this->db->where("translation_id",0);
	  if(!empty($cond))
	  $this->db->where($cond);
	  if(!empty($like))
	  $this->db->like($like);
	  $this->db->order_by($order); 
	  if($limit != "")
	  $this->db->limit($limit,$offset);
	  $query=$this->db->get($connected_table.$this->table_ext);
	  //echo $this->db->last_query();exit;
	  if($limit != "")
	  return $query->result_array();	
	  else
	  return $query->num_rows();	
}
//////////////////////////////////////////////////////////////////////
public function insert_update($data,$id = "",$old_data = array())
{
	$postdata = $data;
	$fields = $this->db->list_fields($this->module);
	$arr = array();
	foreach($fields as $k => $field) {
		if($field != "id".$this->module) {
			if(isset($data[$field])) {
				$content_type_attr_type = $this->db->select("a.type, a.foreign_key, a.id_content")->from("content_type_attr a")->join("content_type c","c.id_content = a.id_content")->where(array(
					"c.name"=>$this->module,
					"a.name"=>str_replace(" ","_",$field)	))->get()->row_array();
		if(isset($content_type_attr_type) && $content_type_attr_type["type"] != 12) {
				if(isset($content_type_attr_type["type"]) && $content_type_attr_type["type"] == 1) {
					$arr[$field] = dateformat($data[$field],"Y-m-d");
				}
				else {
					$arr[$field] = $data[$field];
				}
		}else{
                    $arr[$field] = $data[$field];
                }
			}
		}
	}
	$rid = false;
	if(isset($postdata['lang']))
	$lang = $postdata['lang'];
	else
	$lang = default_lang();
	if(!empty($arr)) {
		$table_ext = "";
		if(enable_review() && !user_can_publish())
		$table_ext = "_review";
		if($id == "") {
			if($this->module == 'seo' && isset($arr['url_route']))
			$arr['url_route'] = $this->fct->prepare_url($arr['url_route']);
			$arr["lang"] = $lang;
			$arr["created_on"] = date("Y-m-d H:i:s");
			if(enable_review() && !user_can_publish())
			$arr["status"] = 0;
			$this->db->insert($this->module,$arr);
			$rid = $this->db->insert_id();
			
			if($this->module != 'seo' && isset($arr['title']) && $this->fct->is_connected_to_module($this->module,'seo'))
			$this->fct->create_default_seo($this->module,$rid,$arr['title'],$arr["lang"]);
			
			if(enable_review()) {
				$arr["id_".$this->module] = $rid;
				$arr["live_id"] = $rid;
				$this->db->insert($this->module."_review",$arr);
			}
			if($rid) {
				$this->fct->record_review($this->module,$rid,$arr["lang"]);
				$this->fct->insert_log("create",$this->module,$rid);
			}
		}
		else {
			if($this->module == 'seo' && isset($arr['url_route']))
			$arr['url_route'] = $this->fct->prepare_url($arr['url_route'],$id);
			$rid = $id;
			if(empty($old_data))
			$old_data = $this->get($rid);
//print_r($old_data);exit;
			//exit($this->module.$this->table_ext);
		//print '<pre>'; print_r(); exit;
		//exit($rid);
			$rec_lang = get_cell($this->module,'lang','id_'.$this->module,$rid);
			$lang = $rec_lang;
			$this->db->where("id_".$this->module,$id)->update($this->module.$this->table_ext,$arr);
			$affected_rows = $this->db->affected_rows();
			if(enable_review()) {
				if(user_can_publish()) {
					$this->db->where("id_".$this->module,$id)->update($this->module."_review",$arr);
				}
				else {
					//if($affected_rows > 0) {
						
						$this->fct->record_review($this->module,$rid,$rec_lang);
					//}
				}
			}
			if($affected_rows > 0)
			$this->fct->insert_log("update",$this->module,$rid);
			if(isset($old_data["status"]) && isset($arr["status"]) && $old_data["status"] != $arr["status"]) {
				if($old_data["status"] == 0) {
					$this->fct->change_status($this->module,$rid,1);
				}
				else {
					$this->fct->change_status($this->module,$rid,0);
				}
			}
		}
		if(enable_review() && isset($postdata["approval"])) {
		    if($postdata["approval"] == 1) {
		    	$this->fct->change_approval($this->module,$rid,$lang,$postdata["approval"]);
		    	if($this->user_role < 3)
		    		$this->fct->change_status($this->module,$rid,1);	
		    }
		    else {
		    	$this->fct->change_approval($this->module,$rid,$lang,$postdata["approval"]);
		    	if($this->user_role < 3) {
		    		$this->fct->change_status($this->module,$rid,0);	
		    	}
		    }
		}
		// many to many
		$get_mu = $this->db->select("a.*")->join("content_type c","c.id_content = a.id_content")->where(array(
			"c.used_name"=>$this->module,
			"a.type"=>12
		))->get("content_type_attr a")->result_array();
		//print '<pre>'; print_r( $data ); exit;
		if(!empty($get_mu) && $this->table_ext == '') {
			foreach($get_mu as $tb) {
				$fld = clean_field_name($tb['name']);
				if(isset($postdata[$fld])) {
					$post_arr = $postdata[$fld];
					$foreign_table = get_cell("content_type","used_name","id_content",$tb['foreign_key']);
					$rel_table = $this->module."_".$foreign_table;
					$this->db->where("id_".$this->module,$rid)->delete($rel_table);
					if(!empty($post_arr)) {
						foreach($post_arr as $ar) {
							$this->db->insert($rel_table,array(
								"id_".$this->module=>$rid,
								"id_".$foreign_table=>$ar
							));
						}
					}
				}
			}
		}
	}
	if($this->module == 'seo') {
		$this->fct->update_routes();
	}
	if($this->module == 'static_words') {
		$this->fct->update_translations();
	}
	return $rid;
}
//////////////////////////////////////////////////////////////////////
public function delete($id,$mod = '')
{
	if($mod == '') {
		$data = $this->get($id);
		$mod = $this->module;
	}
	else {
		$data = $this->get_from_connect($mod,$id);
	}
	$this->db->where("id_".$mod,$id)->delete($mod);
	$this->db->where("translation_id",$id)->delete($mod);
	if(enable_review()) {
		$this->db->where("id_".$mod,$id)->delete($mod."_review");
		$this->db->where("translation_id",$id)->delete($mod."_review");
	}
	$this->fct->insert_log("delete",$mod,$id);
	$this->fct->move_to_trash($mod,$id,$data);
	return true;
}
//////////////////////////////////////////////////////////////////////
public function get_translation($module,$id,$lang){
	$this->db->select($module.".*");
	$this->db->from($module);
	$this->db->where("translation_id",$id);
	$this->db->where("lang",$lang);
	$query = $this->db->get();
	//echo $this->db->last_query();exit;
	$result = $query->row_array();
	return $result;
}
public function get_translation_from_review($module,$id,$lang){
	$this->db->select($module."_review.*");
	$this->db->from($module."_review");
	$this->db->where("id_products",$id);
	$this->db->where("lang",$lang);
	$query = $this->db->get();
	//echo $this->db->last_query();exit;
	$result = $query->row_array();
	return $result;
}
//////////////////////////////////////////////////////////////////////
}