<?php $fld = clean_field_name($attr['name']); ?>
<div class="form-group">
<?php 
$mark = '';
if($attr['required'] == 1) $mark = '&nbsp;<em class="red">*</em>';
echo form_label('<b>'.ucfirst($attr['display_name']).$mark.'</b>'); ?>
<?php if(!empty($attr['hint'])) {?>
<?php echo form_label('<small class="yellow">'.$attr['hint'].'</small>'); ?>
<?php }?>
<?php 
if(enable_editor($attr['id']))
$c = 'summernote';
else
$c = '';
echo '<div>'.form_textarea(array("name" => $fld, "value" => $selected,"class" =>$c,"id"=>"fid-".$fld )).'</div>';?>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>