<?php
$sego =$this->uri->segment(4);
$gallery_status="";
?>
<div class="page page-tables-datatables">
<div class="pageheader">
                        <h2><?php echo $title; ?><span></span></h2>
                        
                        <a role="button" href="<?php echo site_url("back_office/control/add_photos/".$content_type."/".$id_gallery); ?>" class="btn btn-success btn-ef btn-ef-3 btn-ef-3c mb-10 pull-right">
                        <i class="fa fa-plus mr-5"></i> Add Photos</a>
                        
                        <div class="page-bar">

                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo site_url("back_office/home/dashboard"); ?>"><i class="fa fa-home"></i> Dashboard</a>
                                </li>
                                <li>
                                    <?php echo $title1; ?>
                                </li>
                            </ul>

                        </div>
                    </div>  
<div class="row">
<div class="col-md-12">
<section class="tile">
<div class="tile-header dvd dvd-btm">
<h1 class="custom-font text-cyan"><strong></strong><?php echo $title; ?></h1>
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

<? 
$attributes = array('name' => 'list_form');
echo form_open_multipart('back_office/control/delete_all_photos', $attributes); 
?>
<div id="result"></div>                            
<div class="tile-body p-0">
<div class="table-responsive">
<?php if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success">
<?php echo $this->session->userdata("success_message"); ?>
</div>
<?php } ?>
<table class="table table-hover mb-0" id="usersList">
                                            <thead>
<tr>
<th width="5%"><label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
                                                        <input type="checkbox" id="select-all"><i></i>
                                                </label></th>
<th>Title</th>
<th>Image</th>
<th style="text-align:right; padding-right:50px;">Action</th>
</tr>
                                            </thead>
                                            <tbody> 
											<?php
											if(count($info) > 0){
											$i=0;
											foreach($info as $val){ $i++;?>
                                            <tr id="<?=$val["id_gallery"]; ?>">
<td class="col-chk">
<label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
<input type="checkbox" class="selectMe" name="cehcklist[]" value="<?php echo $val["id_gallery"] ; ?>">
<i></i></label>
</td>
<td><?= $val["title"]; ?></td>
<td>
<?
if( $val["image"] != ""){
?>	
<a href="<?= base_url(); ?>uploads/<?=$content_type; ?>/gallery/<?= $val["image"] ?>" class="blue gallery" title="<?= $val["title"]; ?>" >
<img src="<?= base_url(); ?>uploads/<?= $content_type;?>/gallery/120x120/<?= $val["image"] ?>" border="0"  />
</a>
<? } else { echo "<span class='blue'>No Image Available</span>"; } 
?>
<td style="text-align:right">
<div class="btn-group" style="width:100px;">
<button class="btn btn-default btn-flat" type="button">Action</button>
<button data-toggle="dropdown" class="btn btn-default btn-flat dropdown-toggle" type="button">
<span class="caret"></span>
<span class="sr-only"></span>
</button>
<ul class="dropdown-menu" role="menu">
<li>
<a href="<?php echo site_url('back_office/control/edit_photo/'.$content_type.'/'.$id_gallery.'/'.$val["id_gallery"]); ?>" class="table-edit-link" >
Edit</a></li>
<li>
<a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?= $content_type; ?>','<?=$val["id_gallery"];?>'); }" class="table-delete-link cur">
Delete</a></li>
</ul>
</div>
</td>
</tr>
<? }  } else { ?>
<tr><td colspan="4" style='text-align:center;'>No records available . </td></tr>
<?  } ?>
</tbody>
</table>
</div>
</div>
<div class="tile-footer dvd dvd-top">
                                    <div class="row">

                                        <div class="col-sm-5 hidden-xs">
                                        <input type="hidden" name="content_type" value="<?php echo $content_type; ?>"  />
										<input type="hidden" name="id_gallery" value="<?= $id_gallery; ?>"  />
                                            <select name="check_option" class="input-sm form-control w-sm inline">
                                                <option value="">Bulk action</option>
                                                <option value="delete_all">Delete selected</option>
                                                <!--
                                                <option value="2">Archive selected</option>
                                                <option value="3">Copy selected</option>
                                                -->
                                            </select>
                                            <button class="btn btn-sm btn-default">Apply</button>
                                        </div>

                                        <div class="col-sm-3 text-center">
                                            <small class="text-muted"></small>
                                        </div>

                                        <div class="col-sm-4 text-right">
										<? //echo $this->pagination->create_links(); ?>
                                        </div>

                                    </div>
                                </div>
<? echo form_close();  ?>
</section>
</div>
</div>
</div>
<?php
$this->session->unset_userdata("success_message"); 
$this->session->unset_userdata("error_message"); 
?>
