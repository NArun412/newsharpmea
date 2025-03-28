<?php
$content_name = $table_name;
$cond1["name"]=str_replace("_"," ",$content_name);
$id_content=$this->fct->getonecell("content_type","id_content",$cond1);
$content_type_data = $this->fct->getonerow("content_type",$cond1);
$add_view='
<?php
if(!isset($id)){
';
foreach($attr as $val){
	if($val['type'] == 7) {
		$var = intval($val['id_content']) - intval($val['foreign_key']);
		if($var == 0) {
			$add_view.='
$info["id_parent"] = 0;';
		}
		else {
			$f_table = get_foreign_table($val['foreign_key']);
			$add_view.='
$info["id_'.$f_table.'"] = 0;';
		}
	}
	else {
		$add_view.='
$info["'.str_replace(" ","_",$val["name"]).'"] = "";';
	}
}
$add_view.='
$info["title"] = "";
$info["status"] = "";
}
echo render_title(set_value("title",$info["title"]));
';
$i =0;
foreach($attr as $val){ 
$i++;
$attr_name = str_replace(" ","_",$val["name"]);

if($val["type"]== 3) {
$add_view .= 'echo render_textarea("'.$attr_name.'",set_value(\''.$attr_name.'\',$info[\''.$attr_name.'\']),'.$val['id_attr'].');
';
}
elseif($val["type"] == 4) { 
$add_view .= 'if(!isset($id)) $fid = 0;
else
$fid = $id;
echo render_image("'.$table_name.'","'.$attr_name.'",$fid,$info["'.$attr_name.'"],'.$val['id_attr'].');
';
}
elseif($val["type"] == 1){
$add_view .= 'echo render_date("'.$attr_name.'",set_value(\''.$attr_name.'\',$info[\''.$attr_name.'\']),'.$val['id_attr'].');
';
}
elseif($val["type"] == 5){
$add_view .= 'if(!isset($id)) $fid = 0;
else
$fid = $id;
echo render_file("'.$table_name.'","'.$attr_name.'",$fid,$info["'.$attr_name.'"],'.$val['id_attr'].');
';
}
elseif($val["type"] == 6){
$add_view .= 'echo render_active("'.$attr_name.'",set_value(\''.$attr_name.'\',$info[\''.$attr_name.'\']),'.$val['id_attr'].');
';
} 
elseif($val["type"] == 7){
	$f_table = get_foreign_table($val['foreign_key']);
	$var = intval($val['id_content']) - intval($val['foreign_key']);
	if($var == 0)
$add_view .= 'echo render_foreign_parent("'.$f_table.'","id_parent",set_value(\'id_parent\',$info[\'id_parent\']),'.$val['id_attr'].');
';
	else
$add_view .= 'echo render_foreign("'.$f_table.'","id_'.$f_table.'",set_value(\'id_'.$f_table.'\',$info[\'id_'.$f_table.'\']),'.$val['id_attr'].');
';
} else { 
$add_view .= 'echo render_textbox("'.$attr_name.'",set_value(\''.$attr_name.'\',$info[\''.$attr_name.'\']),'.$val['id_attr'].');
';
}
}
$add_view .= '
//echo render_status(set_value("status",$info["status"]));
';