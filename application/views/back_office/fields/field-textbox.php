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
if($attr['type'] == 11)
echo form_password(array('name' => $fld, 'value' => $selected,'class' =>'form-control' ));
elseif($attr['type'] == 10)
echo form_input(array('name' => $fld,'type'=>'email', 'value' => $selected,'class' =>'form-control' ));
else
echo form_input(array('name' => $fld, 'value' => $selected,'class' =>'form-control' ));
echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>
