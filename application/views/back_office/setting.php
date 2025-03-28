<div class="page page-forms-common">
<div class="pageheader">
<h2>Edit Your Profile <span></span></h2>
<div class="page-bar">
<ul class="page-breadcrumb">
<li>
<a href="<?php echo site_url('back_office/home/dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
</li>
<li>Profile</li>
</ul>
</div>
</div>

<div class="row">  
<div class="col-md-12">


    <?php echo form_open_multipart(admin_url('settings/submit'),array('class'=>middle-forms)); ?>
<section class="tile">
<!-- tile header -->
<div class="tile-header dvd dvd-btm">
<h1 class="custom-font text-cyan"><strong></strong> Please complete the form below. Mandatory fields marked <em>*</em></h1>
<ul class="controls">
<li class="dropdown">

<a role="button" tabindex="0" class="dropdown-toggle settings" data-toggle="dropdown">
<i class="fa fa-cog"></i>
<i class="fa fa-spinner fa-spin"></i>
</a>

<ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
<li>
<a role="button" tabindex="0" class="tile-toggle">
<span class="minimize"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;&nbsp;Minimize</span>
<span class="expand"><i class="fa fa-angle-up"></i>&nbsp;&nbsp;&nbsp;Expand</span>
</a>
</li>
<li>
<a role="button" tabindex="0" class="tile-refresh">
<i class="fa fa-refresh"></i> Refresh
</a>
</li>
<li>
<a role="button" tabindex="0" class="tile-fullscreen">
<i class="fa fa-expand"></i> Fullscreen
</a>
</li>
</ul>

</li>
</ul>
</div>
<!-- /tile header -->
<div class="tile-body">
<fieldset>
<? if($this->session->userdata('success_message')){ ?>
<div class="alert alert-success">
<?= $this->session->userdata('success_message'); ?>
</div>
<? } ?>
<? if($this->session->userdata('error_message')){ ?>
<div class="alert alert-error">
<?= $this->session->userdata('error_message'); ?>
</div>
<? } ?>
<div class="form-group">
<label class="field-title">Website Title:</label>
<input type="text" class="form-control" name="website_title" value="<?=$admin["website_title"]; ?>" />
</div>

<div class="form-group">
<label class="field-title">Website Title (Arabic):</label>
<input type="text" class="form-control" name="website_title_ar" value="<?=$admin["website_title_ar"]; ?>" />
</div>

<div class="form-group">
<?php
if($this->session->userdata('level') == 3){ ?>	
<label class="field-title">Multi Languages:</label>
<input type="checkbox" name="multi_languages" value="1" <?php if($admin["multi_languages"] == 1) echo 'checked="checked"'; ?> /> 
<?php } ?>
</div>
<div class="form-group">
<label class="field-title">Website URL:</label>
<input type="text" class="form-control" name="website" value="<?=$admin["website"]; ?>" />
</div>
<div class="form-group">
<label class="field-title">Phone Number:</label>
<input type="text" class="form-control" name="phone" value="<?=$admin["phone"]; ?>" />
</div>
<div class="form-group">
<label class="field-title">Fax Number:</label>
<input type="text" class="form-control" name="fax" value="<?=$admin["fax"]; ?>" />
</div>
<div class="form-group">
<label class="field-title">Mobile Number:</label>
<input type="text" class="form-control" name="mobile" value="<?=$admin["mobile"]; ?>" />
</div>
<div class="form-group">
<label class="field-title">E-mail<em>*</em>:</label>
<input type="text" class="form-control" name="email" value="<?= set_value('email',$admin["email"]); ?>" />
<?= form_error('email','<span class="text-error">','</span>'); ?>
<input type="hidden" name="id" value="<?= $admin["id_settings"]; ?>"  />
</div>
<div class="form-group">
<label class="field-title">Address:</label>
<textarea class="form-control" name="address" > <?=$admin["address"]; ?></textarea>
</div>
<div class="form-group">
<label class="field-title">FaceBook:</label>
<input type="text" class="form-control" name="facebook" value="<?= $admin["facebook"]; ?>" />
</div>
<div class="form-group">
<label class="field-title">Twitter:</label>
<input type="text" class="form-control" name="twitter" value="<?= $admin["twitter"]; ?>" />
</div>
<div class="form-group">
<label class="field-title">Linked In:</label>
<input type="text" class="form-control" name="linkedin" value="<?= $admin["linkedin"]; ?>" />
</div>
<div class="form-group">
<label class="field-title">Instagram:</label>
<input type="text" class="form-control" name="instagram" value="<?= $admin["instagram"]; ?>" />
</div>
<div class="form-group">
<label class="field-title">Pinterest:</label>
<input type="text" class="form-control" name="pinterest" value="<?= $admin["pinterest"]; ?>" />
</div>
<div class="form-group">
<label class="field-title">Google Plus:</label>
<input type="text" class="form-control" name="google_plus" value="<?= $admin["google_plus"]; ?>" />
</div>
<div class="form-group">
<label class="field-title">Skype:</label>
<input type="text" class="form-control" name="skype" value="<?= $admin["skype"]; ?>" />
</div>
<div class="form-group">
<label class="field-title">You Tube:</label>
<input type="text" class="form-control" name="youtube" value="<?= $admin["youtube"]; ?>" />
</div>
<div class="form-group">
<label class="field-title">Google Map:</label>
<textarea class="form-control" name="google_map" > <?=$admin["google_map"]; ?></textarea>
</div>
<div class="form-group">
<label class="field-title">Google Analytic ID:</label>
<input type="text" class="form-control" name="google_analytic" value="<?= $admin["google_analytic"]; ?>" />
<!--<textarea class="form-control" name="google_analytic" > <? //=$admin["google_analytic"]; ?></textarea>-->
</div>

<!--<div class="form-group">
<label class="field-title">Apply Store Link:</label>
<input type="text" class="form-control" name="apple_store" value="<? //= $admin["apple_store"]; ?>" />
</div>

<div class="form-group">
<label class="field-title">Google Play Link:</label>
<input type="text" class="form-control" name="google_play_store" value="<? //= $admin["google_play_store"]; ?>" />
</div>-->

<hr class="line-dashed line-full"/>
<div class="form-group">
<input type="submit" class="btn btn-rounded btn-success" value="Save changes" />
</div>
</fieldset>
</div>
</section>
</form>

</div>
</div>
</div>
<?php
$this->session->unset_userdata('success_message'); 
$this->session->unset_userdata('error_message');
?>