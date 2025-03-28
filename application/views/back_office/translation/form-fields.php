<?php
echo '<div class="form-group">';
echo form_label('<b>Title&nbsp;<em class="red">*</em>:</b>', 'Title');
echo form_input(array("name" => "title", "value" => set_value("title",$info["title"]),"class" =>"form-control" ));
echo form_error("title","<span class='text-lightred'>","</span>");
echo '</div>';
$table_name = $section;
foreach($fields as $val){ 
$attr_name = str_replace(" ","_",$val["name"]);
if($val['translated'] == 1) {
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
	
//	echo "test:".$attr_name;
	$selecte = array();
	
	//print_r($selecte);exit;
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
}
$user_role = user_role();
if($user_role <= 3) {?>
<div class="form-group">
<?php echo form_label('<b>Your Approval&nbsp;<em class="red">*</em>:</b></b>'); ?>
<?php
$options = array(
	1 => "Approved",
	0 => "Not Approved"
);
$selected = 0;
if($user_role == 3 && check_approval($table_name,$info["id_".$table_name],4)) $selected = 1;
if($user_role == 2 && check_approval($table_name,$info["id_".$table_name],2)) $selected = 1;
//$selected = set_value("status",$info["status"]);
echo form_dropdown("approval", $options,$selected, 'class="form-control"');
?>
<?php echo form_error("approval","<span class='text-lightred'>","</span>"); ?>
</div>
<?php }?>