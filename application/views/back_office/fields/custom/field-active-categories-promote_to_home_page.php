<?php $fld = clean_field_name($attr['name']); ?>
<div class="form-group">
<?php echo form_label('<b>'.ucfirst($attr['display_name']).'</b>'); ?>
<?php if(!empty($attr['hint'])) {?>
<?php echo form_label('<small class="yellow">'.$attr['hint'].'</small>'); ?>
<?php }?>
<?php
$options = array(
                  0 => "Do Not Display",
				  1 => "Normal Box",
				  2 => "Wide Box",
                );
echo form_dropdown($fld, $options,$selected, 'class="form-control chosen-select"');
?>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>