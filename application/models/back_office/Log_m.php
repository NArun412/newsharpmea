<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Log_m extends CI_Model{

public function getall($cond = array(),$limit = '',$offset = 0){
$this->db->select("log.*, users.name AS user_name");
$this->db->from("log");
$this->db->join("users","users.id_users = log.uid");
if(!empty($cond))
$this->db->where($cond);
$this->db->order_by('log.id DESC');
if($limit != '') {
$this->db->limit($limit,$offset);
}
$query = $this->db->get();
if($limit != '') {

$result = $query->result_array();
}
else
$result = $query->num_rows();
return $result;
}

function delete($id)
{
	$this->db->where('id',$id)->delete('log');
	return true;
}
}