<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Export_model extends CI_Model{


public function get_export_fields_by_module($module)
{
	//->where('ca.name !=','google_map_coordinates')->where('ca.name !=','google map coordinates')
	$fields = $this->db->select('ca.*')
	->where('c.name',$module)
	->join('content_type_attr ca','ca.id_content = c.id_content')
	->group_by('id_attr')->order_by('ca.sort_order')
	->get('content_type c')
	->result_array();
	//echo $this->db->last_query();exit;
	
		array_unshift($fields,array(
			'name'=>'status',
			'type'=>6
		));
		
		array_unshift($fields,array(
			'name'=>'title',
			'type'=>2
		));
		
	//if(!in_array($section,$this->force_exported)) {
		array_unshift($fields,array(
			'name'=>'lang',
			'type'=>2
		));
			//}
	
		//if(!in_array($section,$this->force_exported)) {
	array_unshift($fields,array(
			'name'=>'translation_id',
			'type'=>8
		));
		//}
		
		array_unshift($fields,array(
			'name'=>'id_'.$module,
			'type'=>8
		));
		return $fields;
}
public function getCount($section,$cond = array(),$like = array())
{
	$cc = 0;
	$model_name = $section.'_m';
	$model = $this->load->model($model_name);
	$count = $this->$model_name->getall($cond,$like);
	return $count;
}
public function getRecords($section,$cond = array(),$like = array(),$limit,$offset)
{
	$data = array();
	$model_name = $section.'_m';
	$model = $this->load->model($model_name);
	if($section == 'seo') $cond['module'] = '';
	$records = $this->$model_name->getall($cond,$like,$limit,$offset,'id_'.$section.' DESC');
	//print '<pre>'; print_r($records); exit;
	$data = $this->generate_excel_data($section,$records);
	return $data;
}
/////////////////////////////////////////////////////////////////////////
private function generate_excel_data($section,$records)
{
	$fields = $this->get_export_fields_by_module($section);
    //$data['title'] = 'list of '.ucfirst(str_replace('_',' ',$section));
	$data['title'] = ucfirst(str_replace('_',' ',$section));
	//echo $data['title'];exit;
	$results = array();
	foreach($fields as $k => $fld) {
		$data['keys'][$k] = ucfirst(str_replace('_',' ',$fld['name']));
	}
	$data['arr'] = array();
	$update_exported = array();
	if(!empty($records)) {
		//print '<pre>'; print_r($records); exit;
		$i=0;
		foreach($records as $key => $val) {
			if(!in_array($section,$this->force_exported)) {
				$ar_lang = $this->db->where(array(
					'translation_id'=>$val['id_'.$section],
					'lang'=>'ar'
				))->get($section)->row_array();
				$ar_rows = array();
			}
			foreach($fields as $k => $fld) {
				$field_key = str_replace(' ','_',$fld['name']);
				if($fld['type'] == 7) {
					$data['arr'][$i][$field_key] = 0;
					$f_table = get_foreign_table($fld['foreign_key']);
					$tt = get_foreign_table($fld['id_content']);
					$fid = 'id_'.$f_table;
					if($tt == $f_table) {
						$fid = 'id_parent';
					}
				
					
					if(isset($val[$fid]) && $val[$fid] != 0)
					$data['arr'][$i][$field_key] = $val[$fid];
					//$data['arr'][$i][$field_key] = get_cell($f_table,'title','id_'.$f_table,$val[$fid]);
					if(!in_array($section,$this->force_exported)) {
						// $ar_rows[$fid] = $ar_lang[$fid];
						if(isset($ar_lang[$fid]))
						$ar_rows[$fid] = $ar_lang[$fid];
						//$ar_rows[$fid] = get_cell_translate_only($f_table,'title',$val[$fid],'ar');
						else
						$ar_rows[$fid] = 0;
					}
			
				}
				else {
					if(isset($val[$field_key]))
					$data['arr'][$i][$field_key] = $this->clean_text($val[$field_key]);
					else
					$data['arr'][$i][$field_key] = '';
					
					if(!in_array($section,$this->force_exported)) {
						if(isset($ar_lang[$field_key]))
						$ar_rows[$field_key] = $this->clean_text($ar_lang[$field_key]);
						else
						$ar_rows[$field_key] = 0;
					}
				}
			}
			if(!in_array($section,$this->force_exported)) {
				$new_id = $i + 1;
				$ar_rows['translation_id'] = $val['id_'.$section];
				if(!isset($ar_rows['lang']) || (isset($ar_rows['lang']) && $ar_rows['lang'] == ''))
				$ar_rows['lang'] = 'ar';
				foreach($ar_rows as $k1 => $v1) {
					$data['arr'][$new_id][$k1] = $v1;
				}
				$i++;
			}
			array_push($update_exported,$val['id_'.$section]);
			$i++;
		}
		if(!empty($update_exported) && in_array($section,$this->force_exported))
		$this->db->query('UPDATE '.$section.' SET exported = 1 WHERE id_'.$section.' IN('.implode(',',$update_exported).')');
	}
	return $data;
}
function clean_text($str)
{
	$find = array('"','\'','<colgroup>','<col>','</colgroup>');
	$replace = array('','','','','');
	$str = str_replace($find,$replace,strip_tags($str));
	//$str = preg_replace( "/\r|\n/", "",$str);
	return $str;
}
/////////////////////////////////////////////////////////////////////////
}