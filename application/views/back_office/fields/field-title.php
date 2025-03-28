<div class="form-group">
<?php echo form_label('<b>'.display_label('title').'&nbsp;<em class="red">*</em></b>'); ?>
<?php
echo form_input(array('name' => 'title', 'value' => $value,'class' =>'form-control' ));
echo form_error('title',"<span class='text-lightred'>","</span>"); ?>
</div>