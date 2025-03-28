<div class="page page-forms-common">
<div class="pageheader">

                        <h2><?php echo $title; ?> <span></span></h2>

                        <div class="page-bar">

                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo site_url('back_office/home/dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('back_office/control');?>">Manage Content Type</a>
                                </li>
                                <li>
                                    <?php echo $title; ?>
                                </li>
                            </ul>
                            
                        </div>

</div>
                    
<div class="row">                  
<div class="col-md-12">
<?php
$attributes = array('class' => 'middle-forms');
echo form_open_multipart('back_office/control/submit', $attributes); 
?>
<section class="tile">
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
<div class="tile-body">
<?php
if(isset($id)){
	echo '<input type="hidden" name="id" value="'.$id.'" />';
} else {	
$info=array('name'=>'','url_name'=>'','icon'=>'','gallery' =>'','thumb_val_gal' => '','menu_group' => '','enable_seo'=>0,'perm_help'=>'');
foreach(permissions() as $per)
$info['perm_'.$per] = 1;
}

if($this->session->userdata("success_message")){ 
echo '<div class="alert alert-success">';
echo $this->session->userdata("success_message");
echo '</div>';
}
if($this->session->userdata("error_message")){
echo '<div class="alert alert-error">';
echo $this->session->userdata("error_message"); 
echo '</div>';
}
?>
<div class="form-group">
<label class="field-title">Content Type Name<em>*</em>:</label>
<? if(isset($id)) { ?>
<input type="hidden" class="form-control" name="name" value="<?= set_value('name',$info["name"]); ?>" />
<h3> <?= $info["name"]; ?></h3>
<?  } else { ?>
<input type="text" class="form-control" name="name" value="<?= set_value('name',$info["name"]); ?>" />
<? } ?>
<?= form_error('name','<span class="text-lightred">','</span>'); ?>
</div>
<div class="form-group">
<label class="field-title">URL Name:</label>
<input type="text" class="form-control" name="url_name" value="<?= set_value('url_name',$info["url_name"]); ?>" />
<?= form_error('url_name','<span class="text-lightred">','</span>'); ?>
</div>

<div class="form-group">
<?php 

//$ck = '';
//if(set_value('enable_seo',$info['enable_seo']) == 1) $ck = 'checked="checked"';
?>
<!--<label class="field-title"><input type="checkbox" name="enable_seo" <?php echo $ck; ?> value="1" /> Enable SEO</label>-->
</div>

<div class="form-group">
<label class="field-title">Menu Group:</label>
<select class="form-control" name="menu_group">
<option value="0">- keep no group -</option>
<?php foreach(admin_menu_groups() as $k => $grp) {
	$cl = '';
	if($info['menu_group'] == $k) $cl = 'selected="selected"';
	?>
<option value="<?php echo $k; ?>" <?php echo $cl; ?>><?php echo ucfirst($grp); ?></option>
<?php }?>
</select>
<?= form_error('menu_group','<span class="text-lightred">','</span>'); ?>
</div>
<div class="form-group">
<label class="field-title">ICON :</label> 
<input type="file"  class="filestyle" data-buttonText="Find file" data-iconName="fa fa-inbox" name="icon" />
<span>
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
</div>
<div class="form-group">
<label>
<input type="checkbox" name="gallery" id="gallery" value="1" <? if($info["gallery"] == 1) echo 'checked="checked"'; ?> />
&nbsp;Add Gallery
</label>
<div id="thumb_val_gal">
<div class="form-group">
<label class="field-title">Gallery Thumb:</label>
<input type="text" class="form-control"  name="thumb_val_gal" value="<?= $info["thumb_val_gal"]; ?>"  />
</div>
<input type="radio" name="resize_status" value="0"  checked="checked"  />&nbsp;Crop&nbsp;&nbsp;
<input type="radio" name="resize_status" value="1"  />&nbsp;Resize
<br clear="all"  />
</div>
</div>

<?php 
$selected_ids = array();
$connected_modules = $this->fct->get_content_types();
if(isset($id)){	
$selected_ids = $this->fct->get_connected_modules_ids($id);
}
?>
<hr class="line-dashed line-full"/>
<h3>Connect with Modules</h3>
<select name="connectedmodules[]" multiple="multiple" class="form-control searchable">
<?php
if(!empty($connected_modules)) {
foreach($connected_modules as $v) { 
$cl = '';
if(in_array($v['id_content'],$selected_ids)) $cl = 'selected="selected"';
?>
<option value="<?php echo $v['id_content']; ?>" <?php echo $cl; ?>><?php echo $v['name']; ?></option>
<?
}
}
?>
</select>



<?php 
$selected_ids = array();
if(isset($id)){	
$selected_ids = $this->fct->get_module_connections_ids($id);
}
?>
<hr class="line-dashed line-full"/>
<h3>Connect Modules with this module</h3>
<select name="moduleconnections[]" multiple="multiple" class="form-control searchable">
<?php
if(!empty($connected_modules)) {
foreach($connected_modules as $v) { 
$cl = '';
if(in_array($v['id_content'],$selected_ids)) $cl = 'selected="selected"';
?>
<option value="<?php echo $v['id_content']; ?>" <?php echo $cl; ?>><?php echo $v['name']; ?></option>
<?
}
}
?>
</select>

<hr class="line-dashed line-full"/>
<h3>Permissions</h3>
<div class="row">
<?php foreach(permissions() as $perm) {?>
<div class="col-md-3">
<div class="form-group">
<?php 
$ck = '';
if(set_value('perm_'.$perm,$info['perm_'.$perm]) == 1) $ck = 'checked="checked"';?>
<label class="field-title"><input type="checkbox" name="perm_<?php echo $perm; ?>" <?php echo $ck; ?> value="1" /> Enable <?php echo ucfirst($perm); ?> Permission</label>
</div>
</div>

<?php }?>


<div class="col-md-12">
<div class="form-group">
<label class="field-title">Help Text <small>(For Permissions)</small>:</label>
<input type="text" class="form-control" name="perm_help" value="<?= set_value('perm_help',$info["perm_help"]); ?>" />
<?= form_error('perm_help','<span class="text-lightred">','</span>'); ?>
</div>
</div>

</div>

<hr class="line-dashed line-full"/>
<div class="form-group">
<button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ><i class="fa fa-arrow-right"></i> Submit</button>
<a href="<?php echo site_url('back_office/control/'); ?>" class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c"><i class="fa fa-arrow-left"></i> Cancel</a>
</div>

</div>
</section>
<?php echo form_close(); ?>
</div>
</div>
                  
</div>
<?php
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message"); 
?>