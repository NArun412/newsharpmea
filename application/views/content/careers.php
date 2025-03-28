<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
  <div class="centered4" >
  <?php if(validate_user()) $this->load->view("includes/manage"); ?>
    <div class="span">
      <div>
        <div class="careers-left"> <a href="<?php echo route_to("careers"); ?>" class="left-menu-item <?php if($this->router->method == 'index') {?>active<?php }?>" title="<?php echo lang("careers"); ?>"><?php echo lang("careers"); ?></a> <a href="<?php echo route_to("careers/whyworkhere"); ?>" class="left-menu-item <?php if($this->router->method == 'whyworkhere') {?>active<?php }?>" title="<?php echo lang("why work here?"); ?>"><?php echo lang("why work here?"); ?></a>
        
        <a href="<?php echo route_to("careers/culture_at_sharp"); ?>" class="left-menu-item <?php if($this->router->method == 'culture_at_sharp') {?>active<?php }?>" title="<?php echo lang("culture at sharp"); ?>"><?php echo lang("culture at sharp"); ?></a> </div>
        <div class="careers-right">
          <div class="careers-box">
          	<?php if(isset($intro['image']) && !empty($intro['image'])) {?>
            	<div class="img-box"><img src="<?php echo dynamic_img_url(); ?>website_pages/image/<?php echo $intro['image']; ?>" alt="<?php echo $intro['title']; ?>" title="<?php echo $intro['title']; ?>" /></div>
            <?php }?>
            <div class="description"> <?php echo $intro['description']; ?> </div>
          </div>
          <?php if($this->router->method == 'index') {?>
          <div class="careers-box ">
            <div class="custom-form-style"> <?php echo form_open(route_to('careers/submit'),array("id"=>"careersForm")); ?>
              <fieldset>
                <div class="form-item full">
                  <label><?php echo lang("your name"); ?> *</label>
                  <input type="text" name="name" value="" />
                  <span class="form-error" id="name-error"></span> </div>
                <div class="form-item full">
                  <label><?php echo lang("contact number"); ?> *</label>
                  <input type="text" name="cnumber" class="phone-format" value="" />
                  <span class="form-error" id="cnumber-error"></span> </div>
                <div class="form-item full">
                  <label><?php echo lang("email ID"); ?> *</label>
                  <input type="text" name="emailid" value="" />
                  <span class="form-error" id="emailid-error"></span> </div>
              </fieldset>
              <fieldset>
                <legend><?php echo lang("current location"); ?></legend>
                <div class="form-item full">
                  <label><?php echo lang("country"); ?> *</label>
                  <select name="country" onchange="change_country(this,'no_centers')">
                    <option value="">- <?php echo lang('select'); ?> -</option>
              <?php foreach($countries as $country) {?>
              <option value="<?php echo $country['id']; ?>"><?php echo $country['title']; ?></option>
              <?php }?>
                  </select>
                  <span class="form-error" id="country-error"></span> </div>
                <div class="form-item full">
                  <label><?php echo lang("state"); ?> *</label>
                  <select name="state">
                    <option value=""></option>
                  </select>
                  <span class="form-error" id="state-error"></span> </div>
                <div class="form-item full">
                  <label><?php echo lang("city"); ?> *</label>
                  <input type="text" name="city" value="" />
                  <span class="form-error" id="city-error"></span> </div>
              </fieldset>
              <fieldset>
                <div class="form-item full">
                  <label><?php echo lang("experience"); ?> *</label>
                  <div class="form-item-right">
                    <div class="form-item">
                      <label><?php echo lang("years"); ?></label>
                      <input type="integer" name="years" value="" />
                      <span class="form-error" id="years-error"></span> </div>
                    <div class="form-item">
                      <label><?php echo lang("months"); ?></label>
                      <input type="integer" name="months" value="" />
                      <span class="form-error" id="months-error"></span> </div>
                  </div>
                </div>
                <div class="form-item full">
                  <label><?php echo lang("position applying for"); ?> *</label>
                  <input type="text" name="position" value="" />
                  <span class="form-error" id="position-error"></span> </div>
                <div class="form-item full">
                  <label><?php echo lang("highest qualification"); ?> *</label>
                  <input type="text" name="highestqualification" value="" />
                  <span class="form-error" id="highestqualification-error"></span> </div>
                <div class="form-item full">
                  <label><?php echo lang("division applying for"); ?> *</label>
                  <select name="division" onchange="change_careers_division(this)">
                    <option value="">- <?php echo lang('select'); ?> -</option>
                    <?php foreach($divisions as $div) {?>
                    	<option value="<?php echo $div['id']; ?>"><?php echo $div['title']; ?></option>
                    <?php }?>
                  </select>
                  <span class="form-error" id="division-error"></span> </div>
                <div class="form-item full">
                  <label><?php echo lang("department"); ?> *</label>
                  <select name="department">
                    <option value=""></option>
                  </select>
                  <span class="form-error" id="department-error"></span> </div>
                <div class="form-item full">
                  <label><?php echo lang("region applying for"); ?> *</label>
                  <select name="region">
                    <option value="">- <?php echo lang('select'); ?> -</option>
                    <?php foreach($regions as $reg) {?>
                    	<option value="<?php echo $reg['id']; ?>"><?php echo $reg['title']; ?></option>
                    <?php }?>
                  </select>
                  <span class="form-error" id="region-error"></span> </div>
                <div class="form-item full">
                  <label><?php echo lang("specify your experience level"); ?> *</label>
                  <select name="experiencelevel">
                   <option value="">- <?php echo lang('select'); ?> -</option>
                    <?php foreach($experience_levels as $lev) {?>
                    	<option value="<?php echo $lev['id']; ?>"><?php echo $lev['title']; ?></option>
                    <?php }?>
                  </select>
                  <span class="form-error" id="experiencelevel-error"></span> </div>
                <div class="form-item full">
                  <label><?php echo lang("briefly describe yourself"); ?> *</label>
                  <textarea name="brief"></textarea>
                  <span class="form-error" id="brief-error"></span> </div>
                <div class="form-item full">
                  <label><?php echo lang("upload CV"); ?> *</label>
                  <input type="file" name="cv" value="" />
                  <span class="form-error" id="cv-error"></span> </div>
                  <div class="span">
                  <div class="careers-captcha">
                  <div class="form-item full">
        <label><?php echo lang("Please enter the characters in the image"); ?> *</label>
        <span class="captchaImage"><?php echo $this->fct->createNewCaptcha(); ?></span>
        <input type="text" class="textBox" id="captcha" name="captcha" value="" style="text-transform:none" />
        <span id="captcha-error" class="form-error"></span> </div>
        </div></div>
              </fieldset>
              <div class="form-item full">
                <input type="submit" value="<?php echo lang("submit"); ?>" />
                <input type="reset" value="<?php echo lang("cancel"); ?>" />
              </div>
              <?php echo form_close(); ?> </div>
          </div>
          <?php }?>
        </div>
      </div>
    </div>
  </div>
</div>
