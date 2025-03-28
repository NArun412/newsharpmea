<div class="form-item full">  <?php if($cid == 0) {?><label><?php echo lang("category"); ?></label><?php }?>
<select name="category[<?php echo $cid; ?>]" id="categorydropdown-<?php echo $cid; ?>" onchange="changeCategory(this)">
  <option value="">- <?php echo lang("select"); ?> <?php if($cid != 0) {?>(<?php echo get_cell_translate("categories","title","id_categories",$cid); ?>)<?php }?> -</option>
  <?php if(isset($all_categories) && !empty($all_categories)) {?>
  <?php foreach($all_categories as $rec) {
							$cl = '';
							if(isset($selected_categories) && in_array($rec['id'],$selected_categories)) $cl = 'selected="selected"';
							?>
  <option value="<?php echo $rec['id']; ?>" <?php echo $cl; ?>><?php echo $rec['title']; ?></option>
  <?php }?>
  <?php }?>
</select>
</div>