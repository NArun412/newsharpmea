<div class="span">
  <div class="head-title with-border center"><?php echo lang("download center"); ?></div>

  <div class="custom-form-style "> <?php echo form_open(route_to("support/confirm_download"),array("id"=>"downloadBrochureConfirmationForm")); ?>
  <?php echo form_hidden("fid",$id); ?>
    <fieldset>
      <legend><?php echo lang("Enter your details to receive brochure download link"); ?></legend>
<div class="span">

      <div class="form-item full">
        <input type="text" name="name" value="<?php echo get_cookie("sh_do_name"); ?>" placeholder="<?php echo lang("Name"); ?>" />
        <span class="form-error" id="name-error"></span> </div>
		
		  <div class="form-item full">
        <input type="text" name="email" value="<?php echo get_cookie("sh_do_email"); ?>" placeholder="<?php echo lang("Email"); ?>" />
        <span class="form-error" id="email-error"></span> </div>
		</div>
        <div class="download-center-disclaimer span">
       
        <?php echo lang("Optional"); ?>:  <input type="checkbox" name="add_to_newsletter" value="1" /> <?php echo lang("I want to receive Sharp Middle East and Africa Newsletters."); ?>
 <?php echo lang("We value your privacy. Your personal information will be kept confidential and will never be shared to other parties. Privacy Policy"); ?>
</div>
    
    </fieldset>


    <div class="form-item full">
      <input type="submit" value="<?php echo lang("Download"); ?>" />
    </div>
    <?php echo form_close(); ?> </div>
</div>
