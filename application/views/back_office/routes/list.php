<?php
$sego =$this->uri->segment(4);
$gallery_status="0";
?>
<div class="page page-tables-datatables">
<div class="pageheader">
                        <h2><?= $title; ?><span></span></h2>
                        <?php if($this->acl->has_permission('routes','add')){ ?>
                        <a role="button" href="<?= site_url('back_office/routes/add'); ?>" class="btn btn-success btn-ef btn-ef-3 btn-ef-3c mb-10 pull-right">
                        <i class="fa fa-plus mr-5"></i> Add Route</a>
                        <?php } ?>
                        <?php if($this->acl->has_permission('routes','manage')){ ?>
                        <a role="button" href="<?= site_url('back_office/routes/synchronization'); ?>" class="btn btn-warning btn-ef btn-ef-3 btn-ef-3c mb-10 pull-right">
                        <i class="fa fa-refresh mr-5"></i> synchronization</a>
                        <?php } ?>
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo site_url('back_office/home/dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
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

                                        <div class="col-sm-5">
<? $search_array = array("25","100","200","500","1000","All"); ?>
<form action="<?= site_url("back_office/routes"); ?>" method="post"  id="show_items">
show &nbsp;
<select name="show_items" class="input-sm form-control inline w-xs">
<? for($i =0 ; $i < count($search_array); $i++){ ?>
<option value="<?= $search_array[$i]; ?>" <? if($show_items == $search_array[$i]) echo 'selected="selected"'; ?> >
<?php echo ($search_array[$i] == "") ? 'All' : $search_array[$i]; ?></option>
<? } ?>
</select>&nbsp;&nbsp;items per page.
</form>                                      
</div>

                                        <div class="col-sm-3"></div>

                                        <div class="col-sm-4">
                                            <div class="input-group w-xl  pull-right" id="example1_filter">
                                                <input type="text" name="search" class="input-sm form-control " placeholder="Search...">
                                                  <!--
                                                  <span class="input-group-btn">
                                                    <button class="btn btn-sm btn-default" type="button">Go!</button>
                                                  </span>
                                                  -->
                                            </div>
                                        </div>

                                    </div>

                                </div>
<? 
$attributes = array('name' => 'list_form');
echo form_open_multipart('back_office/routes/delete_all', $attributes); 
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
                                            	<th width="10%">
                                                <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
                                                        <input type="checkbox" id="select-all"><i></i>
                                                </label>
                                                </th>
                                                <th>TITLE</th>
                                                <th>INDEX</th>
                                                <th style="width: 160px;" class="no-sort">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
											if(count($info) > 0){
											$i=0;
											foreach($info as $val){ $i++;?>
                                            <tr id="<?=$val["id_routes"]; ?>">
<td>
<label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
<input type="checkbox" class="selectMe" name="cehcklist[]" value="<?= $val["id_routes"] ; ?>">
<i></i></label>
</td>
<td><? echo $val["title"]; ?></td>
<td><? echo $val["index"]; ?></td>
<td>
<div class="btn-group" style="width:100px;">
<button class="btn btn-default btn-flat" type="button">Action</button>
<button data-toggle="dropdown" class="btn btn-default btn-flat dropdown-toggle" type="button">
<span class="caret"></span>
<span class="sr-only"></span>
</button>
<ul class="dropdown-menu" role="menu"><li><a href="<?= base_url(); ?>back_office/routes/translate/<?= $val["id_routes"] ?>">
Translate</a></li>
<? if ($this->acl->has_permission('routes','index')){ ?>
<li><a href="<?= site_url('back_office/routes/view/'.$val["id_routes"]);?>" class="table-edit-link">
View</a></li>
<? } ?>
<? if ($this->acl->has_permission('routes','edit')){ ?>
<li>
<a href="<?= site_url('back_office/routes/edit/'.$val["id_routes"]);?>" class="table-edit-link" >
Edit</a></li>
<? } ?>
<? if ($this->acl->has_permission('routes','delete')){ ?>
<li>
<a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?=$val["id_routes"];?>'); }" class="table-delete-link cur">
Delete</a></li>
<? } ?>
</ul>
</div>
</td>
</tr>
                                            <?php } 
											} else { ?>
                                            <tr>
                                            <td colspan="4">No records available .</td>
                                            </tr>
                                            <?php } ?>
                                            
                                            </tbody>
                                        </table>
</div>
</div>
<div class="tile-footer dvd dvd-top">
                                    <div class="row">

                                        <div class="col-sm-5 hidden-xs">
                                            <?php
											if ($this->acl->has_permission('routes','delete_all')){ ?>
                                            <select name="check_option" class="input-sm form-control w-sm inline">
                                                <option value="">Bulk action</option>
                                                <option value="delete_all">Delete selected</option>
                                                <!--
                                                <option value="2">Archive selected</option>
                                                <option value="3">Copy selected</option>
                                                -->
                                            </select>
                                            <button class="btn btn-sm btn-default">Apply</button>
                                            <?php } ?>
                                        </div>

                                        <div class="col-sm-3 text-center">
                                            <small class="text-muted"></small>
                                        </div>

                                        <div class="col-sm-4 text-right">
										<?php echo $this->pagination->create_links(); ?>
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