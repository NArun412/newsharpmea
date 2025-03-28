<?php $fld = clean_field_name($attr['name']); 
$filename = '';
//if($fld == 'image') { print '<pre>'; print_r($info); exit; }
if(isset($info[$fld])) $filename = $info[$fld];
elseif(isset($info[0][$fld])) $filename = $info[0][$fld];
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
?>
<span>
<?php
if($filename != ""){ 
echo anchor('uploads/'.$attr['table_name'].'/'.$fld.'/'.$filename,'show image',array("class" =>'text-cyan gallery'));
echo nbs(3);
echo '<a onclick="delete_image(this,\''.$attr['table_name'].'\',\''.$fld.'\',\''.$filename.'\','.$id.')" class="cur">'.img('assets/images/delete.png').'</a>';
/*echo anchor("back_office/".$content_type."/delete_image/".$field."/".$filename."/".$id, img('assets/images/delete.png'),array('class' => 'cur','onClick' => "return confirm('Are you sure you want to delete this image ?')" ));*/
} else { 
?>
<span class='blue'>No Image Available</span>
<?php } 
?>
</span>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>