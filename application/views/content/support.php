<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
  <div class="centered4" >
  <?php if(validate_user()) $this->load->view("includes/manage"); ?>
  
    <h1 class="head-title with-border center"><?php echo lang("product support"); ?></h1>
  <?php $this->load->view("content/support-menu"); ?>
  
<div class="support-form">

  <div class="custom-form-style"> <?php echo form_open(route_to("support/get_results"),array("id"=>"supportForm")); 
  echo form_hidden("offset",0);
  ?>
    <fieldset>
      <div class="form-item full">
      <label><?php echo lang("product"); ?></label>
        <select name="category">
          <option value=""><?php echo lang("select"); ?></option>
          <?php foreach($categories as $pk) {?>
          	<option value="<?php echo $pk['id']; ?>"><?php echo $pk['title']; ?></option>
          <?php }?>
        </select>
        <input type="hidden" name="category_filter" value="" />
        <span class="form-error" id="product-error"></span> </div>
      <div class="form-item full">
       <label><?php echo lang("modal"); ?></label>
        <input type="text" name="modal" value="" placeholder="<?php echo lang("Modal"); ?>" />
        <span class="form-error" id="modal-error"></span> </div>
      <div class="form-item full">
       <label><?php echo lang("language"); ?></label>
        <select name="language">
          <option value="">- <?php echo lang("select"); ?> -</option>
          <?php foreach($products_languages as $pk) {?>
          	<option value="<?php echo $pk['id']; ?>"><?php echo $pk['title']; ?></option>
          <?php }?>
        </select>
        <span class="form-error" id="language-error"></span> </div>
    </fieldset>
    <fieldset>
      <legend><?php echo lang("Document Type"); ?></legend>
      <div class="form-radios">
      <div class="span">
        <label><input type="checkbox" id="checkAllDocumentTypes" value="<?php echo $pk['id']; ?>" /> <span><?php echo lang("All"); ?></span></label></div>
             <div class="clear"></div>
      <?php foreach($document_types as $pk) {?>
      <label><input type="checkbox" class="checkAllDocumentTypes" name="d[]" value="<?php echo $pk['id']; ?>" /> <span><?php echo $pk['title']; ?></span></label>
          <?php }?>
      </div>
           
    </fieldset>
      <fieldset id="additional-options-container">
      <legend><?php echo lang("additional options"); ?></legend>
       <div class="form-item full" id="opsystem-container">
        <label><?php echo lang("operating systems"); ?></label>
        <select name="opsystem">
          <option value="">- <?php echo lang("select"); ?> -</option>
          <?php foreach($operating_systems as $pk) {?>
          	<option value="<?php echo $pk['id']; ?>"><?php echo $pk['title']; ?></option>
          <?php }?>
        </select>
        <span class="form-error" id="opsystem-error"></span> </div>
        
            <div class="form-item full" id="emu-container">
            <label><?php echo lang("emulations"); ?></label>
        <select name="emulation">
          <option value="">- <?php echo lang("select"); ?> -</option>
          <?php foreach($emulations as $pk) {?>
          	<option value="<?php echo $pk['id']; ?>"><?php echo $pk['title']; ?></option>
          <?php }?>
        </select>
        <span class="form-error" id="emulation-error"></span> </div>
        </fieldset>
   
    <div class="form-item full">
      <input type="submit" value="<?php echo lang("search"); ?>" />
    </div>
    <?php echo form_close(); ?> </div>
</div>
<div id="support-loader" class="span">
  	<div class="support-left">
    </div>
    <div class="support-right">
    </div>
  </div>
</div>
</div>
