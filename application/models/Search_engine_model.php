<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search_engine_model extends CI_Model{

public function __construct(){
	parent::__construct();
	$this->loggedIn = validate_user();
	$this->langue = $this->lang->lang();
	$this->default_langue = default_lang(); 
	$this->tb = '';
	if($this->langue != $this->default_langue)
	$this->tb = '1';
}

public function searchWebsite($keywords,$type = "autocomplete",$limit = "",$offset = "")
{
	$sqla = "SELECT id, title, created_on, sorder, image, cid FROM (";
	$sqla .= " SELECT concat(d.id_divisions,'s:divisions','s:Divisions') as id, d".$this->tb.".title , d.created_on, 1 as sorder, d.image, 0 AS cid FROM divisions d";
	if($this->langue != $this->default_langue) {
		$sqla .= " LEFT JOIN divisions d1 ON d.id_divisions = d1.translation_id AND d1.lang = '".$this->langue."'";
	}
	$sqla .=" WHERE d.status = 1 ";
	$sqla.="AND (UPPER(d".$this->tb.".title) like ?) ";
	
	
	
	$sqla .=" UNION(SELECT concat(c.id_categories,'s:categories','s:Categories') as id, c".$this->tb.".title , c.created_on, 2 as sorder, c.image_small AS image, 0 AS cid FROM categories c";
	if($this->langue != $this->default_langue) {
		$sqla .= " LEFT JOIN categories c1 ON c.id_categories = c1.translation_id AND c1.lang = '".$this->langue."'";
	}
	$sqla .=" WHERE c.status = 1 ";
	$sqla.="AND (UPPER(c".$this->tb.".title) like ?)";
	$sqla .= " )";
	
	$sqla.=" UNION (SELECT concat(p.id_products,'s:products','s:Products') as id , p".$this->tb.".title , p.created_on, 3 as sorder, p.image, 0 AS cid FROM products p";
	if($this->langue != $this->default_langue) {
		$sqla .= " LEFT JOIN products p1 ON p.id_products = p1.translation_id AND p1.lang = '".$this->langue."'";
	}
	$sqla .= " WHERE p.status = 1 AND p.id_divisions != 0 AND p.id_categories != 0 ";
	$sqla .="AND (UPPER(p".$this->tb.".title) like ?)";
	$sqla .= " )";	

	$sqla .= ") AS sql_result ORDER BY sorder, created_on DESC";
	if($limit != "") {
		//$sqla .= " LIMIT ".intval($limit)." OFFSET ".intval($offset);
	}
//	echo $sqla;exit;
	$query = $this->db->query($sqla,array("%".strtoupper($keywords)."%","%".strtoupper($keywords)."%","%".strtoupper($keywords)."%"));

	//$query = $this->db->query($sqla);
	//echo $this->db->last_query();exit;
	if($limit != "") {
		$results = $query->result_array();
	}
	else {
		$results = $query->num_rows();
	}
	return $results;
}



/*public function searchWebsite_mobile($keywords,$limit = "",$offset = "")
{
	$sqla="SELECT concat(id_products,'s:products','s:Products') as id, title_url , title, created_date , features AS description  FROM products where deleted = 0 AND status = 0 ";
	$sqla.="AND (UPPER(products.title) like '%".strtoupper($keywords)."%' OR UPPER(products.features) like '%".strtoupper($keywords)."%' OR UPPER(products.specifications) like '%".strtoupper($keywords)."%' OR UPPER(products.related_products) like '%".strtoupper($keywords)."%' OR UPPER(products.downloads) like '%".strtoupper($keywords)."%' OR UPPER(products.why_and_when) like '%".strtoupper($keywords)."%') ";	
	
	
	$sqla .= " ORDER BY created_date DESC";
	if($limit != "") {
		$sqla .= " LIMIT ".$limit." OFFSET ".$offset;
	}

	$query = $this->db->query($sqla);
	if($limit != "") {
		$results = $query->result_array();
	}
	else {
		$results = $query->num_rows();
	}
	return $results;
}*/



}