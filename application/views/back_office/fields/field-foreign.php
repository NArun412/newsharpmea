<?php 
if(isset($this->id) && isset($this->conmod) && !isset($id)){
$selected = $this->id;
}
$fld = clean_field_name($attr['name']); ?>
<div class="form-group">
<?php 
$mark = '';
if($attr['required'] == 1) $mark = '&nbsp;<em class="red">*</em>';
echo form_label('<b>'.ucfirst($attr['display_name']).$mark.'</b>'); ?>
<?php if(!empty($attr['hint'])) {?>
<?php echo form_label('<small class="yellow">'.$attr['hint'].'</small>'); ?>
<?php }?>
<?php
/*$items = $this->fct->getAll_cond($attr['foreign_table'],$options['order'],array("translation_id"=>0)); */

$items = $this->fct->get_tree($attr['foreign_table'],false,FALSE,true);
?>
<select name="id_<?php echo $attr['foreign_table']; ?>"  class="form-control chosen-select" <?php if(!empty($attributes)) echo $attributes; ?>>
<option value="" > - select - </option>
<?php
foreach($items as $v){
$cl = '';
//if($selected == $v[$options['key']]) $cl = 'selected="selected"';
if($selected == $v['id']) $cl = 'selected="selected"';
?>
<option value="<?php echo $v['id']; ?>" <?php echo $cl; ?>><?php echo $v[$options['label']]; ?></option>
<?
}
?>
</select>
<?php echo form_error($fld,"<span class='text-lightred'>","</span>"); ?>
</div>