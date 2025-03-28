<div class="md_content-wrapper" >
<div class="centered2" >
<?php if(validate_user()) $this->load->view("includes/manage"); ?>
<div class="span" >
<figure>
<img src="<?php echo img_url(); ?>md_photo.png"  />
</figure>

<div class="md_content" >
<h1><?php echo lang("Shogo Hara"); ?></h1>
<h2><?php echo lang("Managing Director"); ?></h2>
<h3><?php echo lang("Sharp Middle East FZE"); ?></h3>
<div class="description" >
<?php
echo $intro["description"];
?>
</div>
</div>
</div>
</div>
</div>