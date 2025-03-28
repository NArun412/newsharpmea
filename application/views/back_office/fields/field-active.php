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
if($fld == 'exported') {
$options = array(
	0 => "InActive",
	1 => "Active"
);
}
else {
$options = array(
	1 => "Active",
	0 => "InActive"
);
}
echo form_dropdown($fld, $options,$selected, 'class="form-control chosen-select"');
?>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>