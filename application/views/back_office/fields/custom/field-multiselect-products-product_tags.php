<?php $fld = clean_field_name($attr['name']); ?>
<div class="form-group">
<?php echo form_label('<b>'.ucfirst($attr['display_name']).'</b>'); ?>
<?php if(!empty($attr['hint'])) {?>
<?php echo form_label('<small class="yellow">'.$attr['hint'].'</small>'); ?>
<?php }?>
<?php
$items = $this->fct->get_tree($attr['foreign_table'],0); 

?>
<select name="<?php echo $fld; ?>[]" multiple="multiple"  class="form-control searchable">
<option value="" > - select - </option>
<?php echo $this->fct->display_tree_dropdown($items,'',$selected); ?>
</select>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>