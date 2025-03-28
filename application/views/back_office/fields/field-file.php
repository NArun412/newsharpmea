<?php $fld = clean_field_name($attr['name']); 
$filename = '';
if(isset($info[$fld])) $filename = $info[$fld];
?>
<div class="form-group">
<?php 
$mark = '';
if($attr['required'] == 1) $mark = '&nbsp;<em class="red">*</em>';
echo form_label('<b>'.ucfirst($attr['display_name']).$mark.'</b>'); ?>
<?php if(!empty($attr['hint'])) {?>
<?php echo form_label('<small class="yellow">'.$attr['hint'].'</small>'); ?>
<?php }?>
<?php
echo form_upload(array("name" => $fld, "class" => "filestyle", "data-buttonText" => "Find file", "data-iconName" => "fa fa-inbox"));
echo "<span>";
if($filename != ""){ 
	echo anchor('uploads/'.$attr['table_name'].'/'.$fld.'/'.$filename,'download file',array("class" => 'text-cyan','target' => '_blank'));
	echo nbs(3);
	echo '<a onclick="delete_file(this,\''.$attr['table_name'].'\',\''.$fld.'\',\''.$filename.'\','.$id.')" class="cur">'.img('assets/images/delete.png').'</a>';
	/*echo anchor("back_office/".$content_type."/delete_image/".$field."/".$filename."/".$id, img('assets/images/delete.png'),array('class' => 'cur','onClick' => "return confirm('Are you sure you want to delete this file ?')" ));*/
} 
else { 
	echo "<span class='blue'>No File Available</span>"; 
} 
echo "</span>";
?>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>