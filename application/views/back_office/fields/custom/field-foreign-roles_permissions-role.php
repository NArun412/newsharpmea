<?php 
if(isset($this->id) && isset($this->conmod) && !isset($id)){
$selected = $this->id;
}
$fld = clean_field_name($attr['name']); ?>
<div class="form-group">
<?php echo form_label('<b>'.ucfirst($attr['display_name']).'</b>'); ?>
<?php if(!empty($attr['hint'])) {?>
<?php echo form_label('<small class="yellow">'.$attr['hint'].'</small>'); ?>
<?php }?>
<?php
$items = $this->fct->get_roles(); 
?>
<select name="id_<?php echo $attr['foreign_table']; ?>"  class="form-control chosen-select" <?php if(!empty($attributes)) echo $attributes; ?>>
<option value="" > - select - </option>
<?php
foreach($items as $v){
$cl = '';
if($selected == $v[$options['key']]) $cl = 'selected="selected"';
?>
<option value="<?php echo $v[$options['key']]; ?>" <?php echo $cl; ?>><?php echo $v[$options['label']]; ?></option>
<?
}
?>
</select>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>