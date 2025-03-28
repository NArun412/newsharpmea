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
$items = $this->fct->getAll_cond($attr['foreign_table'],$options['order'],array("translation_id"=>0)); 

?>
<select name="<?php echo $fld; ?>[]" multiple="multiple"  class="form-control searchable" <?php if(!empty($attributes)) echo $attributes; ?>>
<?php
foreach($items as $v){ 
$cl = '';
if(in_array($v[$options['key']],$selected)) $cl = 'selected="selected"';
?>
<option value="<?php echo $v[$options['key']]; ?>" <?php echo $cl; ?>><?php echo $v[$options['label']]; ?></option>
<?
}
?>
</select>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>