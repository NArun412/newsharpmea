<?php $fld = 'record'; ?>
<div class="form-group">
<?php echo form_label('<b>Record</b>'); ?>
<?php //if(!empty($attr['hint'])) {?>
<?php //echo form_label('<small class="yellow">'.$attr['hint'].'</small>'); ?>
<?php //}?>
<?php
$options = array(''=>'- select -');
foreach($mod_records as $rec) {
	$options[$rec['id_'.$mod_val]] = $rec['title'];
}
//exit($selected);
echo form_dropdown($fld, $options,$selected, 'class="form-control chosen-select"');
?>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>