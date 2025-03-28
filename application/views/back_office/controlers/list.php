<?php
$sego =$this->uri->segment(4);
$gallery_status="0";
?>
<div class="page page-tables-datatables">
<div class="pageheader">
                        <h2><?= $title; ?><span></span></h2>
                        <a role="button" href="<?= admin_url('control/add'); ?>" class="btn btn-success btn-ef btn-ef-3 btn-ef-3c mb-10 pull-right">
                        <i class="fa fa-plus mr-5"></i> Create New Content Type</a>
                        <div class="page-bar">

                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo admin_url('dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
                                </li>
                                <li>
                                    <?= $title; ?>
                                </li>
                            </ul>

                        </div>
                    </div>                   
<div class="row">
<!-- col -->
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
<div class="tile-widget">
<div class="row">
<div class="col-sm-5"></div>
<div class="col-sm-3"></div>
<div class="col-sm-4">
<div class="input-group w-xl  pull-right" id="example1_filter">
<input type="text" name="search" class="input-sm form-control " placeholder="Search...">
</div>
</div>
</div>
</div>
<?php
$attributes = array('name' => 'list_form');
echo form_open_multipart('back_office/control/delete_all', $attributes);
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
                                            	<th width="5%">
                                                <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
                                                        <input type="checkbox" id="select-all"><i></i>
                                                </label>
                                                </th>
                                                <th>Content Type Name</th>
                                                <th>url Name</th>
                                                <th>Icon</th>
                                                <th style="width: 160px;" class="no-sort">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
											if(count($info) > 0){
											$i=0;
											foreach($info as $val){ $i++;?>
                                            <tr id="<?=$val["id_content"]; ?>">
<td class="col-chk" id="<?=$val["id_content"]; ?>">
<label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
<input type="checkbox" class="selectMe" name="cehcklist[]" value="<?= $val["id_content"] ; ?>">
<i></i></label>
</td>
<td>
<a href="<?= base_url(); ?>back_office/control/manage/<?= $val["id_content"]; ?>" class="blue" style=" text-transform:capitalize">
<?php
echo str_replace("_"," ",$val["name"]); ?>
</a>
</td>
<td>
<?=$val["url_name"]; ?>
</td>
<td>
<a class="cur" onclick="completed(<?=$val["id_content"]; ?>);" style="color:#74d24c;">
<? if(!empty($val["icon"])){ ?>
<img src="<?= base_url(); ?>uploads/content_type/32x32/<?= $val["icon"]; ?>"  /> <? } else { echo "No Icon "; } ?>
</a>
</td>
<td>
<a href="<?= base_url();?>back_office/control/edit/<?= $val["id_content"]; ?>" class="table-edit-link cur cur" >
<i class="icon-edit" ></i> Edit</a>
<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
<a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?=$val["id_content"];?>'); }" class="table-delete-link cur" >
<i class="icon-remove-sign"></i> Delete</a>
</td>
</tr>
                                            <?php } 
											} else { ?>
                                            <tr>
                                            <td colspan="5">No records available .</td>
                                            </tr>
                                            <?php } ?>
                                            
                                            </tbody>
                                        </table>
</div>
</div>
<div class="tile-footer dvd dvd-top">
                                    <div class="row">

                                        <div class="col-sm-5 hidden-xs">
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
										<?php // echo $this->pagination->create_links(); ?>
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