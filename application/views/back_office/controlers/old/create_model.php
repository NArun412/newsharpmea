<?
$multi_languages = getonecell('settings','multi_languages',array('id_settings' => 1 ));
$models='<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');
class '.ucfirst($table_name).'_m extends CI_Model{

public function get_'.$table_name.'($id){
$this->db->where(\'id_'.$table_name.'\',$id);
$this->db->where(\'deleted\',0);
$query = $this->db->get(\''.$table_name.'\');
return $query->row_array();
}


public function list_paginate($order,$limit, $offset){
$this->db->where(\'deleted\',0);
';
if($multi_languages == 1){
$models .= '$this->db->where(\'id_parent\',0);';
}
$models .= 'if ($this->db->field_exists($order, \''.$table_name.'\')){
$this->db->order_by($order); }
$this->db->limit($limit,$offset);
$query=$this->db->get(\''.$table_name.'\');
return $query->result_array();	
}

public function getAll($table,$order){
$this->db->where(\'deleted\',0);
';
if($multi_languages == 1){
$models .= '$this->db->where(\'id_parent\',0);';
}
$models .= 'if ($this->db->field_exists($order, $table)){
$this->db->order_by($order); }
$query=$this->db->get($table);
return $query->num_rows();
}

}';
?>