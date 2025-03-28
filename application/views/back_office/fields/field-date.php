<?php $fld = clean_field_name($attr['name']); 
if(!isset($info[$fld])) $info[$fld] = '';
?>
<div class="form-group">
<?php 
$mark = '';
if($attr['required'] == 1) $mark = '&nbsp;<em class="red">*</em>';
echo form_label('<b>'.ucfirst($attr['display_name']).$mark.'</b>'); ?>
<?php if(!empty($attr['hint'])) {?>
<?php echo form_label('<small class="yellow">'.$attr['hint'].'</small>'); ?>
<?php }?>
<div class="input-group datepicker w-360 mt-10" data-format="L">
<?php echo form_input(array('name' => $fld, 'value' => dateformat(set_value($fld,$info[$fld]),"m/d/Y"),'class' => 'form-control','size'=>16 ));?>
<span class="input-group-addon"><span class="fa fa-calendar"></span></span></div>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>