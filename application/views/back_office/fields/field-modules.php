<?php $fld = 'module'; ?>
<div class="form-group">
<?php echo form_label('<b>Module</b>'); ?>
<?php //if(!empty($attr['hint'])) {?>
<?php //echo form_label('<small class="yellow">'.$attr['hint'].'</small>'); ?>
<?php //}?>
<?php
$options = array(''=>'- select -');
foreach($modules as $mod) {
	$mod_name = str_replace(' ','_',$mod['name']);
	$options[$mod_name] = $mod['name'];
}
echo form_dropdown($fld, $options,$selected, 'class="form-control chosen-select" onchange="get_module_records(this)"');
?>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>