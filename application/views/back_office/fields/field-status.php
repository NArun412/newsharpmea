<div class="form-group">
<?php echo form_label('<b>Status&nbsp;<em class="red">*</em></b>'); ?>
<?php
$options = array(
	1 => "Published",
	2 => "Under Review",
	0 => "UnPublished"
);
//$selected = set_value("status",$info["status"]);
echo form_dropdown("status", $options,$selected, 'class="form-control chosen-select"');
?>
<?php echo form_error("status","<span class='text-lightred'>","</span>"); ?>
</div>
 