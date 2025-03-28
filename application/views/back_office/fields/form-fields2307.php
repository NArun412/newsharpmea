<?php
if(isset($info_edit)) $info = $info_edit;
//print '1<pre>'; print_r($info); exit;
if($this->router->method == 'mod') {
	$content_name = $this->conmod;
	$table_name = $this->conmod;
}
else {
	$content_name = $this->module;
	$table_name = $this->module;
}
if(!isset($id)){
foreach($this->fields as $val){
	$field = str_replace(" ","_",$val["name"]);
	if($val['type'] == 7) {
		$var = intval($val['id_content']) - intval($val['foreign_key']);
		if($var == 0) {
$info["id_parent"] = 0;
		}
		else {
			$f_table = get_foreign_table($val['foreign_key']);
$info["id_".$f_table] = 0;
		}
	}
	else {
$info[$field] = "";
	}
}
$info["title"] = "";
$info["status"] = "";
}

echo render_title(set_value("title",$info["title"]));
$i =0;
//print '<pre>'; print_r($this->fields); exit;
foreach($this->fields as $val){ 
$i++;
$attr_name = str_replace(" ","_",$val["name"]);

if($val["type"]== 3) {
echo render_textarea($val,set_value($attr_name,$info[$attr_name]));
}
elseif($val["type"] == 4) { 
if(!isset($id)) $fid = 0;
else
$fid = $id;
echo render_image($val,$fid);
}
elseif($val["type"] == 1){
echo render_date($val,set_value($attr_name,$info[$attr_name]));
}
elseif($val["type"] == 14){
echo render_time($val,set_value($attr_name,$info[$attr_name]));
}
elseif($val["type"] == 5){
if(!isset($id)) $fid = 0;
else
$fid = $id;
echo render_file($val,$fid);
}
elseif($val["type"] == 6){
echo render_active($val,set_value($attr_name,$info[$attr_name]));
} 
elseif($val["type"] == 7){
	$f_table = get_foreign_table($val['foreign_key']);
	$var = intval($val['id_content']) - intval($val['foreign_key']);
	if($var == 0)
	echo render_foreign_parent($val,set_value('id_parent',$info['id_parent']));
	else
	echo render_foreign($val,set_value('id_'.$val['foreign_table'],$info['id_'.$val['foreign_table']]));
} 
elseif($val['type'] == 10) {
	echo render_email($val,set_value($attr_name,$info[$attr_name]));
}
elseif($val['type'] == 11) {
	echo render_password($val,set_value($attr_name,$info[$attr_name]));
}
elseif($val['type'] == 12) {
	$selecte = array();
	if(is_array(set_select($attr_name))){
	$selecte = set_select($attr_name);}
if(isset($info['id_'.$val['table_name']])){
	$selecte=$this->fct->select_many_to_many('id_'.$val['table_name'],$info['id_'.$val['table_name']],$val['foreign_table'],$val['table_name'].'_'.$val['foreign_table'],$order ='sort_order asc');}

	echo render_multiselect($val,$selecte);
}
else { 
echo render_textbox($val,set_value($attr_name,$info[$attr_name]));
}
}
//echo render_status(set_value("status",$info["status"]));
