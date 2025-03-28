<?php 
//echo $content_type;exit;
$fields = $this->fct->get_module_fields($content_type);
//print '<pre>'; print_r($fields); exit;
foreach($fields as $fld) {
 $field = str_replace(" ","_",$fld['name']);
 echo '<div class="form-group">';
 echo form_label('<b>'.display_label($fld['name']).'</b>').'<br />';
 if($fld['type'] == 4)
 echo anchor('uploads/'.$content_type.'/'.$info[$field],'show image',array("class" =>'text-cyan gallery'));
 elseif($fld['type'] == 5)
 echo anchor('uploads/'.$content_type.'/'.$info[$field],'download file',array("class" =>'text-cyan','target'=>'_blank'));
 elseif($fld['type'] == 7) {
 	echo get_cell($content_type,'title','id_'.$val['foreign_table'],$info[$field]);
 }
 else
 echo form_label($info[$field]);
 echo '</div>';
}?>