<?php 
$cl_b = 'no-banner';
if(isset($banners) && !empty($banners)) $cl_b = ''; ?>
<div class="content_inner <?php echo $cl_b; ?>" >
<div class="centered2" >
<?php if(validate_user()) $this->load->view("includes/manage"); ?>
<h1 class="head-title with-border center">
<?php echo lang('Support'); ?> - <?php echo lang("download center"); ?>
</h1>
<?php //$this->load->view("content/support-menu"); ?>
<div class="span" >
<div class="third">
 <div class="span"><a class="filter-download-center"><?php echo lang("filter"); ?></a></div>
<div class="custom-form-style service_center_form full filter-download-center-container"> <?php echo form_open(route_to("support"),array("id"=>"downloadCenterFilterForm","method"=>"GET")); ?>
              <fieldset>
                <!--<legend><?php //echo lang("current location"); ?></legend>-->
              <div class="form-item full">
      <label><?php echo lang("product"); ?></label>
        <select name="category">
          <option value=""><?php echo lang("select"); ?></option>
          <?php foreach($categories as $pk) {
			  $cl = '';
			  if($this->input->get("category") == $pk['id']) $cl = 'selected="selected"';
			  ?>
          	<option value="<?php echo $pk['id']; ?>" <?php echo $cl; ?>><?php echo $pk['title']; ?></option>
          <?php }?>
        </select>
       <!-- <input type="hidden" name="category_filter" value="" />-->
        <span class="form-error" id="product-error"></span> </div>
      <div class="form-item full">
       <label><?php echo lang("keyword"); ?></label>
        <input type="text" name="keyword" value="<?php echo $this->input->get("keyword"); ?>" placeholder="<?php echo lang("Keyword"); ?>" />
        <span class="form-error" id="modal-error"></span> </div>
              </fieldset>
              <div class="form-item full">
                <input type="submit" value="<?php echo lang("search"); ?>" />
              </div>
              <?php echo form_close(); ?> </div>
              </div>
              <div class="third-quarter" id="service_center_loader">
             	 	<?php $this->load->view("content/download_center_items"); ?>
              		<!--<span class="results-will-show-here"><?php //echo lang("search results will appear here"); ?></span>-->
              </div>
</div>
</div>
</div>
