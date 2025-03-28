<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dropdowns extends My_Controller{

public function __construct(){
parent::__construct();
	$this->lang->load('statics');
	$this->langue = $this->lang->lang();
}

public function get_states_by_country($cid,$selected = 0,$return = 0){
	$with_centers = FALSE;
	if($this->input->get('wc') == 1)
	$with_centers = TRUE;
	$records = $this->website_fct->get_states_by_country($cid,$with_centers);
	$html = '<option value="">- '.lang('select').' -</option>';
	if(!empty($records)) {
		foreach($records as $rec) {
			$cl = '';
			if($selected  == $rec['id']) $cl = 'selected="selected"';
			$html .= '<option value="'.$rec['id'].'">'.$rec['title'].'</option>';
		}
	}
	die( $html );
}

public function get_products_by_state($sid,$selected = 0)
{
	$records = $this->website_fct->get_products_by_state($sid);
	$html = '<option value="">- '.lang('select').' -</option>';
	if(!empty($records)) {
		foreach($records as $rec) {
			$cl = '';
			if($selected  == $rec['id']) $cl = 'selected="selected"';
			$html .= '<option value="'.$rec['id'].'">'.$rec['title'].'</option>';
		}
	}
	die( $html );
}


public function get_departments_by_division($did,$selected = 0)
{
	$records = $this->website_fct->get_departments_by_division($did);
	$html = '<option value="">- '.lang('select').' -</option>';
	if(!empty($records)) {
		foreach($records as $rec) {
			$cl = '';
			if($selected  == $rec['id']) $cl = 'selected="selected"';
			$html .= '<option value="'.$rec['id'].'">'.$rec['title'].'</option>';
		}
	}
	die( $html );
}

public function get_catgories_extra($id)
{
	$sub_categories = $this->website_fct->get_categories_by_category($id);
	$html = '';
	if(!empty($sub_categories)) {
		$html = $this->load->view('content/categories-drop-down',array(
			'all_categories'=>$sub_categories,
			'cid'=>$id,
			//'category'=>$category,
		),true);
	}
	$return['result'] = 1;
	$return['id'] = $id;
	$return['html'] = $html;
	$this->load->view('json',array('return'=>$return));
}

function get_categories_by_division($id)
{
	if(is_numeric($id)) {
		$records = $this->website_fct->get_categories_by_division($id);
		
		$html = '';
		if(!empty($records)) {
			$html = $this->load->view('content/categories-drop-down',array(
				'all_categories'=>$records,
				'cid'=>0,
				//'category'=>$category,
			),true);
		}
		die($html);
		/*$html = '<option value="">- '.lang("select").' -</option>';
		if(!empty($records)) {
			foreach($records as $rec) {
				$html .= '<option value="'.$rec['id'].'">'.$rec['title'].'</option>';
			}
		}
		echo $html;*/
	}
	return false;
}

function get_products_lines_by_category($id)
{
	if(is_numeric($id)) {
		$records = $this->website_fct->get_product_lines_by_category($id);
		$html = '<option value="">- '.lang("select").' -</option>';
		if(!empty($records)) {
			foreach($records as $rec) {
				$html .= '<option value="'.$rec['id'].'">'.$rec['title'].'</option>';
			}
		}
		echo $html;
	}
	return false;
}


}