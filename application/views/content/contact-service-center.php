<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
<div class="centered2" >
<?php if(validate_user()) $this->load->view("includes/manage"); ?>
<h1 class="head-title with-border center">
<?php echo lang('Support'); ?> - <?php echo lang("service center location"); ?>
</h1>
<?php $this->load->view("content/support-menu"); ?>
<div class="span" >
<div class="third">
<div class="custom-form-style service_center_form full"> <?php echo form_open(route_to("contact/filter_service_centers"),array("id"=>"serviceCentersForm")); ?>
              <fieldset>
                <!--<legend><?php //echo lang("current location"); ?></legend>-->
                <div class="form-item full">
                  <label><?php echo lang("country"); ?> *</label>
                  <select name="country" onchange="change_country(this,'with_centers')">
                    <option value="">- <?php echo lang('select'); ?> -</option>
                    <?php foreach($countries as $country) {?>
                    	<option value="<?php echo $country['id']; ?>"><?php echo $country['title']; ?></option>
                    <?php }?>
                  </select>
                  <span class="form-error" id="country-error"></span> </div>
                   <div class="form-item full">
                  <label><?php echo lang("state"); ?> *</label>
                  <select name="state" onchange="change_state_get_products(this)">
                    <option value=""></option>
                  </select>
                  <span class="form-error" id="state-error"></span> </div>       
                  <div class="form-item full">
                  <label><?php echo lang("product"); ?></label>
                  <select name="product">
                    <option value=""></option>
                  </select>
                  <span class="form-error" id="product-error"></span> </div>
                  <div class="form-item full"> <div class="radios">
                  <label><?php echo lang("view results as"); ?>: </label>
               <label><input type="radio" checked="checked" name="view_results" value="list" /> <?php echo lang('list'); ?></label>
               <label><input type="radio" name="view_results" value="map" /> <?php echo lang('map'); ?></label></div>
                  <span class="form-error" id="view_results-error"></span> </div>
              </fieldset>
              <div class="form-item full">
                <input type="submit" value="<?php echo lang("search"); ?>" />
              </div>
              <?php echo form_close(); ?> </div>
              </div>
              <div class="third-quarter" id="service_center_loader">
              		<span class="results-will-show-here"><?php echo lang("search results will appear here"); ?></span>
              </div>
<!--<div class="no-record-found"><?php echo lang('under construction'); ?>...</div>-->

</div>
<!--<h1  class="with-border">
Service center location
</h1>
<div class="span" >
<h1>Contact Us</h1>
<div class="span" >
<form action="<?php echo site_url('contact/submit'); ?>" method="post" name="fileinfo_contact_message" >
<div class="FormResult" ></div>
<div class="span" >
<div class="span2" >
<label >Full Name</label>
<input type="text" name="name" placeholder=""  />
<label >Mobile Number</label>
<input type="text" name="mobile" placeholder=""  />
<label >Email Address</label>
<input type="text" name="email" placeholder=""  />
</div>
<div class="span2" >
<label >Message</label>
<textarea name="message" class="" ></textarea>
<input type="submit" name="" value="submit" class="submit_form" id="contact_message" />
</div>
</div>
</form>
</div>

</div>-->
</div>
</div>
