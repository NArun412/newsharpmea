<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
  <div class="centered4" >
  <?php if(validate_user()) $this->load->view("includes/manage"); ?>
    <div class="span">
      <h1><?php echo lang("Global Basic Policy on Information Security"); ?></h1>
<?php if(!empty($policies)) {
	foreach($policies as $k => $policy) {?>
          <div class="careers-box">
          <?php if($k > 0) {?>
          	<h3><?php echo $policy['title']; ?></h3><?php }?>
            <div class="description"> <?php echo $policy['description']; ?> </div>
          </div>
<?php }?>
<?php }?>
    </div>
  </div>
</div>
