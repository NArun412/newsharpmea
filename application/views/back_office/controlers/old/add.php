<script>
$(function(){
$("#thumb_val_gal").css('display','none');
$('#gallery').click(function () {
if(this.checked == true)
$("#thumb_val_gal").fadeIn('fast');
else
$("#thumb_val_gal").fadeOut('fast');
});	
});
</script>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view('back_office/includes/left_box'); ?>
</div>
<div class="span10" >
<div class="span10-fluid" >
<ul class="breadcrumb">
<li>
<a class="blue" href="<?= site_url('back_office/control');?>" data-original-title="">Manage Content Type</a>
<span class="divider">/</span>
</li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>

<div class="hundred pull-left control-group">
<form action="<?= base_url(); ?>back_office/control/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p  class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){
	echo '<input type="hidden" name="id" value="'.$id.'" />';
} else {	
$info=array('name'=>'','url_name'=>'','icon'=>'','gallery' =>'','thumb_val_gal' => '');
}
?>
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
</p>
<fieldset>
<legend></legend>

<label class="field-title">Content Type Name<em>*</em>:</label> <label>
<? if(isset($id)) { ?>
 <input type="hidden" class="input-xxlarge" name="name" value="<?= set_value('name',$info["name"]); ?>" />
<h3> <?= $info["name"]; ?></h3>
<?  } else { ?>
<input type="text" class="input-xxlarge" name="name" value="<?= set_value('name',$info["name"]); ?>" />
<? } ?>
<?= form_error('name','<span class="text-error">','</span>'); ?>
</label>
<label class="field-title">URL Name:</label> <label>
<input type="text" class="input-xxlarge" name="url_name" value="<?= set_value('url_name',$info["url_name"]); ?>" />
<?= form_error('url_name','<span class="text-error">','</span>'); ?>
</label>
<label class="field-title">ICON :</label> <label>
<input type="file"  class="input-xxlarge" name="icon" />
<span >
<?
if($info["icon"] != ""){ ?>	
<a href="<?= base_url(); ?>uploads/content_type/<?= $info["icon"]; ?>" class="blue gallery" >
show image
</a>
&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office/control/delete_image/icon/<?= $info["icon"]; ?>/<?= $info["id_content"] ?> ' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { echo "<span class='blue'>No Image Available</span>"; } ?>
</span>
</label>
<label>
<input type="checkbox" name="gallery" id="gallery" value="1" <? if($info["gallery"] == 1) echo 'checked="checked"'; ?> />
&nbsp;Add Gallery
</label>
<div id="thumb_val_gal">
<label class="field-title">Gallery Thumb:</label>
<label >
<input type="text" class="input-xxlarge"  name="thumb_val_gal" value="<?= $info["thumb_val_gal"]; ?>"  />
</label>

<input type="radio" name="resize_status" value="0"  checked="checked"  />&nbsp;Crop&nbsp;&nbsp;
<input type="radio" name="resize_status" value="1"  />&nbsp;Resize
<br clear="all"  />
<br clear="all"  />
</div>
</fieldset>
<p class="pull-left"><input type="image" src="<?= base_url(); ?>images/bt-send-form.png" value="Save Changes" class="btn btn-primary" /></p>
<span class="clearFix">&nbsp;</span>
</form>
</div>
</div>


</div>  
</div>
<? $this->session->unset_userdata('success_message'); ?> 
<? $this->session->unset_userdata('error_message'); ?> 