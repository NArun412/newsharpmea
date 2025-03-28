<?php $fld = clean_field_name($attr['name']); ?>
<div class="form-group">
<?php 
$mark = '';
if($attr['required'] == 1) $mark = '<small style="colro:red"> *</span>';
echo form_label('<b>'.ucfirst($attr['display_name']).'</b>'.$mark); ?>
<?php if(!empty($attr['hint'])) {?>
<?php echo form_label('<small class="yellow">'.$attr['hint'].'</small>'); ?>
<?php }?>
<?php
echo form_input(array('name' => $fld,'type'=>'email', 'value' => $selected,'class' =>'form-control' ));
echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>