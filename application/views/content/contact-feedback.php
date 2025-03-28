<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
  <div class="centered2" >
  <?php if(validate_user()) $this->load->view("includes/manage"); ?>
    <h1 class="with-border"> <?php echo lang('Contact Us'); ?> - <?php echo lang("feedback"); ?> </h1>
    <?php $this->load->view("content/contact-menu"); ?>
    <div class="span" > 
      <!--<div class="no-record-found"><?php //echo lang('under construction'); ?>...</div>-->
      <div class="form-centered">
      <div class="custom-form-style service_center_form full"> <?php echo form_open(route_to("contact/submit"),array("id"=>"feedbackForm")); ?>
        <fieldset>
          <!--<legend><?php //echo lang("current location"); ?></legend>-->
          <div class="form-item">
            <label><?php echo lang("name"); ?> *</label>
            <input type="text" name="name" value="" />
            <span class="form-error" id="name-error"></span> </div>
          <div class="form-item">
            <label><?php echo lang("email"); ?> *</label>
            <input type="text" name="email" value="" />
            <span class="form-error" id="email-error"></span> </div>
          <div class="form-item">
            <label><?php echo lang("country"); ?> *</label>
            <select name="country" onchange="change_country(this,'no_centers')">
              <option value="">- <?php echo lang('select'); ?> -</option>
              <?php foreach($countries as $country) {?>
              <option value="<?php echo $country['id']; ?>"><?php echo $country['title']; ?></option>
              <?php }?>
            </select>
            <span class="form-error" id="country-error"></span> </div>
          <div class="form-item">
            <label><?php echo lang("state"); ?> *</label>
            <select name="state">
              <option value=""></option>
            </select>
            <span class="form-error" id="state-error"></span> </div>
          <div class="form-item full">
            <label><?php echo lang("city"); ?> *</label>
            <input type="text" name="city" value="" />
            <span class="form-error" id="city-error"></span> </div>
          <div class="form-item full">
            <label><?php echo lang("address"); ?> *</label>
            <textarea name="address"></textarea>
            <span class="form-error" id="address-error"></span> </div>
          <div class="form-item">
            <label><?php echo lang("contact number"); ?> *</label>
            <input type="text" name="cnumber" class="phone-format" value="" />
            <span class="form-error" id="cnumber-error"></span> </div>
          <div class="form-item">
            <label><?php echo lang("Feedback/Complaint"); ?> *</label>
            <select name="feedback">
              <option value="">- <?php echo lang('select'); ?> -</option>
              <?php foreach($feedbacktypes as $fd) {?>
              <option value="<?php echo $fd['id']; ?>"><?php echo $fd['title']; ?></option>
              <?php }?>
            </select>
            <span class="form-error" id="feedback-error"></span> </div>
                  <div class="form-item full">
            <label><?php echo lang("remarks"); ?> *</label>
            <textarea name="remarks"></textarea>
            <span class="form-error" id="remarks-error"></span> </div>
              
              <div class="form-item full">
        <label><?php echo lang("Please enter the characters in the image"); ?> *</label>
        <span class="captchaImage"><?php echo $this->fct->createNewCaptcha(); ?></span>
        <input type="text" class="textBox" id="captcha" name="captcha" value="" style="text-transform:none" />
        <span id="captcha-error" class="form-error"></span> </div>
        </fieldset>
        <div class="form-item full">
          <input type="submit" value="<?php echo lang("submit"); ?>" />
        </div>
        <?php echo form_close(); ?> </div>
        </div>
    </div>
  </div>
</div>
