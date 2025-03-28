<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('static_modules'))
{
	function static_modules()
	{
		return array("roles","users","roles_permissions","users_permissions","languages");
	}
}



if ( ! function_exists('clean_field_name'))
{
	function clean_field_name($field)
	{
		return strtolower(str_replace(" ","_",$field));
	}
}

if ( ! function_exists('display_field_name'))
{
	function display_field_name($field)
	{
		return str_replace("_"," ",$field);
	}
}

if ( ! function_exists('admin_dir'))
{
	function admin_dir()
	{
		return 'back_office';
	}
}
if ( ! function_exists('admin_url'))
{
	function admin_url($url = '')
	{ 
		if($url == '')
		return site_url('back_office').'/';
		else
		return site_url('back_office/'.$url);
	}
}

if ( ! function_exists('enable_review'))
{
function enable_review()
{
	$CI =& get_instance();
	return $CI->config->item('enable_review');
}
}

if ( ! function_exists('render_approval'))
{
function render_approval($module,$record,$role)
{
	$check = check_approval($module,$record,$role);
	if($check) {
		echo '<span class="label label-success">Approved</span>';
	}
	else {
		echo '<span class="label label-danger">Not Approved</span>';
	}
}
}



if ( ! function_exists('check_approval'))
{
function check_approval($module,$record,$role)
{
	$CI =& get_instance();
	if($role == 2)
	$field = 'approved_by_super_admin';
	else
	$field = 'approved_by_admin';
	$check = $CI->db->where(array(
		"module"=>$module,
		"record"=>$record,
		$field=>1
	))->get("content_type_reviews")->num_rows();
	if($check > 0) {
		return true;
	}
	else {
		return false;
	}
}
}

if ( ! function_exists('check_import'))
{
function check_import($module, $result = true)
{
	return false;
	$CI =& get_instance();
	if($result == false){
		$check = 0;
	} else {
	$check = $CI->db->where(array(
		"tables"=>$module,
		"status" =>1
	))->get("import")->num_rows();
	}
	if($check > 0) {
		return true;
	}
	else {
		return false;
	}
}
}
if ( ! function_exists('check_export'))
{
function check_export($module, $result = true)
{
	return false;
	$CI =& get_instance();
	if($result == false){
		$check = 0;
	} else {
	$check = $CI->db->where(array(
		"tables"=>$module,
		"status" =>1
	))->get("export")->num_rows();
	}
	if($check > 0) {
		return true;
	}
	else {
		return false;
	}
}
}

if ( ! function_exists('admin_per_page'))
{
function admin_per_page()
{
	$CI =& get_instance();
	return $CI->config->item('admin_per_page');
}
}


if ( ! function_exists('display_label')){
function display_label($field,$field_id = 0)
{
	if($field_id != 0 && required_field($field_id)) {
		$label = get_cell("content_type_attr","name","id_attr",$field_id);
		return ucfirst($label).'&nbsp;<em class="red">*</em>';
	}
	else {
		return ucfirst(str_replace("_"," ",$field));
	}
}
}

if ( ! function_exists('render_seo_fields')){
function render_seo_fields()
{
	$CI =& get_instance();
	$CI->load->view('back_office/fields/seo');
}
}


if ( ! function_exists('view_only')){
function view_only($module)
{
	$CI =& get_instance();
	$check = $CI->db->select("view_details_only")->where(
		array(
			'name'=>$module
		)
	)->get('content_type')->row_array();
	if(!empty($check)) {
		if(isset($check['view_details_only']) && $check['view_details_only'] == 1) {
			return true;
		}
		else {
			return false;
		}
	}
	return false;
}
}


if ( ! function_exists('required_field')){
function required_field($fid)
{
	$CI =& get_instance();
	$check = $CI->db->where(
		array(
			'id_attr'=>$fid
		)
	)->get('content_type_attr')->row_array();
	if(!empty($check)) {
		if(isset($check['validation']) && in_array('required',explode('|',$check['validation']))) {
			return true;
		}
		else {
			return false;
		}
	}
	return false;
}
}

if ( ! function_exists('created_by')){
function created_by($module,$record,$field = 'name')
{
	$CI =& get_instance();
	$check = $CI->db->select('t.'.$field)->from('users t')->join("log l","l.uid = t.id_users")->where(
		array(
			'module'=>$module,
			'record'=>$record,
			'action'=>'create'
		)
	)->get()->row_array();
	if(!empty($check)) {
		if(isset($check[$field])) {
			return $check[$field];
		}
		else {
			return '';
		}
	}
	return '';
}
}

if ( ! function_exists('enable_editor')){
function enable_editor($fid)
{
	$CI =& get_instance();
	$check = $CI->db->where(
		array(
			'id_attr'=>$fid
		)
	)->get('content_type_attr')->row_array();
	if(!empty($check)) {
		if(isset($check['enable_editor']) && $check['enable_editor'] == 1) {
			return true;
		}
		else {
			return false;
		}
	}
	return false;
}
}

if ( ! function_exists('get_foreign_table')){
function get_foreign_table($id)
{
	$CI =& get_instance();
	$res = '';
	$check = $CI->db->where(
		array(
			'id_content'=>$id
		)
	)->get('content_type')->row_array();
	if(!empty($check)) return $check['name'];
	else return '';
}
}

if ( ! function_exists('has_parent_foreign')){
function has_parent_foreign($table_name)
{
	$CI =& get_instance();
	$res = '';
	$q = "SELECT * FROM content_type_attr a JOIN content_type c ON c.id_content = a.id_content WHERE a.foreign_key = a.id_content AND c.used_name = '".$table_name."'";
	$query = $CI->db->query($q);
	$check = $query->num_rows();
/*	$check = $CI->db->join("content_type c","c.id_content = a.id_content")->where(
		array(
			'a.foreign_key'=>'a.id_content',
			'c.used_name'=>$table_name
		)
	)->get('content_type_attr a')->num_rows();
	 echo $CI->db->last_query();exit;
	*/
	if($check > 0) {return true;}
	else {return false;}
}
}

if ( ! function_exists('seo_enabled')){
function seo_enabled($content_type)
{
	$CI =& get_instance();
	$check = $CI->db->where(
		array(
			'name'=>$content_type,
			'enable_seo'=>1
		)
	)->get('content_type')->num_rows();
	if($check > 0) return true;
	return false;
}
}

if ( ! function_exists('has_gallery')){
function has_gallery($content_type)
{
	$CI =& get_instance();
	$check = $CI->db->where(
		array(
			'name'=>$content_type,
			'gallery'=>1
		)
	)->get('content_type')->num_rows();
	if($check > 0) return true;
	return false;
}
}



if( ! function_exists('multi_languages') ) {
	function multi_languages()
	{
		$CI =& get_instance();
		$check = $CI->db->select('multi_languages')->from('settings')->where('id_settings',1)->get()->row_array();
		if(isset($check['multi_languages']) && $check['multi_languages'] == 1)
		return true;
		return false;
	}
}

if ( ! function_exists('parent_foreign')){
function parent_foreign($keyid)
{
	$CI =& get_instance();
	$check = $CI->db->where(
		array(
			//'name'=>$content_type,
			'foreign_key'=>$keyid,
			'id_content'=>$keyid,
			'type'=>7
		)
	)->get('content_type_attr')->num_rows();
	if($check > 0) return true;
	return false;
}
}


//////////////////////////////////////////////////////////////////////
if ( ! function_exists('render_foreign')){
function render_foreign($attr,$selected = 0,$attributes = '')
{
	$view = 'back_office/fields/field-foreign';
	//echo 'back_office/fields/custom/field-foreign-'.$field_id.'.php';
	if(file_exists('./application/views/back_office/fields/custom/field-foreign-'.$attr['table_name'].'-'.$attr['name'].'.php'))
	$view = 'back_office/fields/custom/field-foreign-'.$attr['table_name'].'-'.$attr['name'];
	$CI =& get_instance();
	$html =$CI->load->view($view,array(
		'attr'=>$attr,
		'selected'=>$selected,
		'options'=>array(
			'key'=>'id_'.$attr['foreign_table'],
			'label'=>'title',
			'order'=>'sort_order'
		),
		'attributes'=>$attributes
	),true);
	return $html;
}
}

if ( ! function_exists('render_foreign_parent')){
function render_foreign_parent($attr,$selected = 0)
{
	$view = 'back_office/fields/field-foreign-parent';
	if(file_exists('./application/views/back_office/fields/custom/field-foreign-parent-'.$attr['table_name'].'-'.$attr['name'].'.php'))
	$view = 'back_office/fields/custom/field-foreign-parent-'.$attr['table_name'].'-'.$attr['name'];
	
	$CI =& get_instance();
	$html =$CI->load->view($view,array(
		'attr'=>$attr,
		'selected'=>$selected,
		'options'=>array(
			'key'=>'id_'.$attr['foreign_table'],
			'label'=>'title',
			'order'=>'sort_order'
		)
	),true);
	return $html;
}
}

if ( ! function_exists('render_modules')){
function render_modules($modules,$selected = 0,$attributes = '')
{
	$view = 'back_office/fields/field-modules';
	//echo 'back_office/fields/custom/field-foreign-'.$field_id.'.php';
/*	if(file_exists('./application/views/back_office/fields/custom/field-modules-'.$attr['table_name'].'-'.$attr['name'].'.php'))
	$view = 'back_office/fields/custom/field-modules-'.$attr['table_name'].'-'.$attr['name'];*/
	$CI =& get_instance();
	$html =$CI->load->view($view,array(
		'modules'=>$modules,
		'selected'=>$selected,
		'attributes'=>$attributes
	),true);
	return $html;
}
}

if ( ! function_exists('render_module_records')){
function render_module_records($mod_records,$mod_val,$selected = 0,$attributes = '')
{
	$view = 'back_office/fields/field-module-records';
	//echo 'back_office/fields/custom/field-foreign-'.$field_id.'.php';
/*	if(file_exists('./application/views/back_office/fields/custom/field-modules-'.$attr['table_name'].'-'.$attr['name'].'.php'))
	$view = 'back_office/fields/custom/field-modules-'.$attr['table_name'].'-'.$attr['name'];*/
	$CI =& get_instance();
	$html =$CI->load->view($view,array(
		'mod_records'=>$mod_records,
		'mod_val'=>$mod_val,
		'selected'=>$selected,
		'attributes'=>$attributes
	),true);
	return $html;
}
}




if ( ! function_exists('render_multiselect')){
function render_multiselect($attr,$selected = array(),$attributes = '')
{
	$view = 'back_office/fields/field-multiselect';
	//echo 'back_office/fields/custom/field-foreign-'.$field_id.'.php';
	if(file_exists('./application/views/back_office/fields/custom/field-multiselect-'.$attr['table_name'].'-'.$attr['name'].'.php'))
	$view = 'back_office/fields/custom/field-multiselect-'.$attr['table_name'].'-'.$attr['name'];
	$CI =& get_instance();
	$html =$CI->load->view($view,array(
		'attr'=>$attr,
		'selected'=>$selected,
		'options'=>array(
			'key'=>'id_'.$attr['foreign_table'],
			'label'=>'title',
			'order'=>'sort_order'
		),
		'attributes'=>$attributes
	),true);
	return $html;
}
}

if ( ! function_exists('render_active')){
function render_active($attr,$selected = 0)
{
	$view = 'back_office/fields/field-active';
	if(file_exists('./application/views/back_office/fields/custom/field-active-'.$attr['table_name'].'-'.$attr['name'].'.php'))
	$view = 'back_office/fields/custom/field-active-'.$attr['table_name'].'-'.$attr['name'];
	
	$CI =& get_instance();
	$html =$CI->load->view($view,array(
		'selected'=>$selected,
		'attr'=>$attr
	),true);
	return $html;
}
}

if ( ! function_exists('render_file')){
function render_file($attr,$id,$filename = '')
{
	$view = 'back_office/fields/field-file';
	if(file_exists('./application/views/back_office/fields/custom/field-file-'.$attr['table_name'].'-'.$attr['name'].'.php'))
	$view = 'back_office/fields/custom/field-file-'.$attr['table_name'].'-'.$attr['name'];
	
	$CI =& get_instance();
	$html = $CI->load->view($view,array(
		'attr'=>$attr,
		'id'=>$id,
		'filename'=>$filename
	),true);
	return $html;
}
}

if ( ! function_exists('render_date')){
function render_date($attr,$selected = '')
{
	$view = 'back_office/fields/field-date';
	if(file_exists('./application/views/back_office/fields/custom/field-date-'.$attr['table_name'].'-'.$attr['name'].'.php'))
	$view = 'back_office/fields/custom/field-date-'.$attr['table_name'].'-'.$attr['name'];
	
	$CI =& get_instance();
	$html =$CI->load->view($view,array(
		'attr'=>$attr,
		'selected'=>$selected
	),true);
	return $html;
}
}

if ( ! function_exists('render_image')){
function render_image($attr,$id,$filename = '')
{
	$view = 'back_office/fields/field-image';
	if(file_exists('./application/views/back_office/fields/custom/field-image-'.$attr['table_name'].'-'.$attr['name'].'.php'))
	$view = 'back_office/fields/custom/field-image-'.$attr['table_name'].'-'.$attr['name'];
	
	$CI =& get_instance();
	$html = $CI->load->view($view,array(
		'attr'=>$attr,
		'id'=>$id,
		'filename'=>$filename,
	),true);
	return $html;
}
}

if ( ! function_exists('render_textarea')){
function render_textarea($attr,$selected = '')
{
	
	$view = 'back_office/fields/field-textarea';
	if(file_exists('./application/views/back_office/fields/custom/field-textarea-'.$attr['table_name'].'-'.$attr['name'].'.php'))
	$view = 'back_office/fields/custom/field-textarea-'.$attr['table_name'].'-'.$attr['name'];
	
	$CI =& get_instance();
	$html =$CI->load->view($view,array(
		'attr'=>$attr,
		'selected'=>$selected
	),true);
	return $html;
}
}

if ( ! function_exists('render_textbox')){
function render_textbox($attr,$selected = '')
{
	//print '<pre>'; print_r($attr); exit;
	$view = 'back_office/fields/field-textbox';
	if(file_exists('./application/views/back_office/fields/custom/field-textbox-'.$attr['table_name'].'-'.$attr['name'].'.php'))
	$view = 'back_office/fields/custom/field-textbox-'.$attr['table_name'].'-'.$attr['name'];
	
	$CI =& get_instance();
	$html =$CI->load->view($view,array(
		'attr'=>$attr,
		'selected'=>$selected
	),true);
	return $html;
}
}


if ( ! function_exists('render_title')){
function render_title($value)
{
	$view = 'back_office/fields/field-title';
	//if(file_exists('./application/views/back_office/fields/custom/field-textbox-'.$field_id.'.php'))
	//$view = 'back_office/fields/custom/field-textbox-'.$field_id;
	$CI =& get_instance();
	$html =$CI->load->view($view,array(
		'value'=>$value,
	),true);
	return $html;
}
}

if ( ! function_exists('render_status')){
function render_status($selected = 0)
{
	$view = 'back_office/fields/field-status';
	$CI =& get_instance();
	$html =$CI->load->view($view,array(
		'selected'=>$selected,
	),true);
	return $html;
}
}

if ( ! function_exists('breadcrumb')){
function breadcrumb($breadcrumb = array())
{
	$result = "";
	foreach($breadcrumb as $key => $value){
		if(is_array($value))
			$result .= '<li><a href="'.$value[1].'" >'.$value[0].'</a></li>';
        else
		$result .= '<li class="active"> '.$value.' </li>';
	}
	return $result;
}
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ( ! function_exists('display_status')){
	function display_status($module,$record,$st,$allow_change = TRUE)
	{
		$html = '';
		if($st == 1) {
			$txt = 'Published';
			$cl = 'success';
		}
		elseif($st == 2) {
			$txt = 'Under Review';
			$cl = 'warning';
		}
		else {
			$cl = 'danger';
			$txt = 'UnPublished';
			//$html = '<a class="cur" onclick="update_status(this,\''.$module.'\','.$record.')"><span class="label label-danger">UnPublished</span></a>';
		}
		if($allow_change) {
			$c1 = '';
			$c2 = '';
			$c3 = '';
			if($st == 1) $c1 = 'selected="selected"';
			if($st == 2) $c2 = 'selected="selected"';
			if($st == 0) $c3 = 'selected="selected"';
			$html = '<div style="position:relative"><a class="cur" onclick="change_status(this)">
			<span class="label label-'.$cl.'">'.$txt.'</span></a>
			<select name="change_status" onchange="update_status(this,\''.$module.'\','.$record.')" style="display:none; position:absolute; top:100%; left:0; z-index:10">
			<option value="1" '.$c1.'>Publish</option>
			<option value="2" '.$c2.'>Under Review</option>
			<option value="0" '.$c3.'>UnPublish</option>
			</select></div>';
		}
		else
		$html = '<span class="label label-'.$cl.'">'.$txt.'</span>';
		
		return $html;
	}
}

