<div class="support-form">
  <div class="head-title with-border center"><?php echo lang("product support"); ?></div>
  <div class="custom-form-style"> <?php echo form_open("",array("id"=>"supportForm")); ?>
    <fieldset>
      <legend> </legend>
      <div class="form-item full">
        <select name="product">
          <option value=""><?php echo lang("select product"); ?></option>
          <?php foreach($product_keys as $pk) {?>
          	<option value="<?php echo $pk['id']; ?>"><?php echo $pk['title']; ?></option>
          <?php }?>
        </select>
        <span class="form-error" id="product-error"></span> </div>
      <div class="form-item full">
        <input type="text" name="modal" value="" placeholder="<?php echo lang("Modal"); ?>" />
        <span class="form-error" id="modal-error"></span> </div>
      <div class="form-item full">
        <select name="language">
          <option value=""><?php echo lang("select language"); ?></option>
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
      
       <div class="form-item full">
        <select name="opsystem">
          <option value=""><?php echo lang("operating systems"); ?></option>
          <?php foreach($operating_systems as $pk) {?>
          	<option value="<?php echo $pk['id']; ?>"><?php echo $pk['title']; ?></option>
          <?php }?>
        </select>
        <span class="form-error" id="opsystem-error"></span> </div>
        
            <div class="form-item full">
        <select name="emulation">
          <option value=""><?php echo lang("emulations"); ?></option>
          <?php foreach($emulations as $pk) {?>
          	<option value="<?php echo $pk['id']; ?>"><?php echo $pk['title']; ?></option>
          <?php }?>
        </select>
        <span class="form-error" id="emulation-error"></span> </div>
        
        
    </fieldset>
    <div class="form-item full">
      <input type="submit" value="<?php echo lang("submit"); ?>" />
    </div>
    <?php echo form_close(); ?> </div>
</div>
