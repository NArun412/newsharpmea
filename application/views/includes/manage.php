<?php 
if($this->user_role <= 4) {
if(isset($manage_module) && isset($manage_record) && has_permission($manage_module,'edit',$manage_record) && !check_approval($manage_module,$manage_record,2)) {?>
<div class="span">
	<div class="centered2">
    <div class="management-container">
    <span><?php echo lang("This content is not visible for public"); ?></span>
    <?php if($this->user_role == 2 || $this->user_role == 3) {?>
	<a href="<?php echo admin_url('reviews_status/manage/'.$manage_module.'/'.$manage_record.'/'.$this->lang->lang()); ?>" target="_blank"><?php echo lang("edit"); ?></a><?php }?>
   <!-- <a onclick="publish_record(<?php //echo $manage_module; ?>,<?php //echo $manage_record; ?>)"><?php //echo lang("publish"); ?></a>-->
    </div>
    </div>
</div>
<?php }
}?>