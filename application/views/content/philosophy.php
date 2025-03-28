<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
<div class="centered2" >
<?php if(validate_user()) $this->load->view("includes/manage"); ?>
<div class="left">
<div class="text" >
<?php echo $intro['description']; ?>
</div>
</div>

</div>
</div>