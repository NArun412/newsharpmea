<?php
$sego =$this->uri->segment(4);
$gallery_status="0";
?>
<div class="page page-tables-datatables">
<div class="pageheader">
                        <h2><?= $title; ?><span></span></h2>
                        <a role="button" href="<?= base_url(); ?>back_office/control/add_attr/<?= $con_type["id_content"]; ?>" class="btn btn-success btn-ef btn-ef-3 btn-ef-3c mb-10 pull-right">
                        <i class="fa fa-plus mr-5"></i> Create Attribute</a>
                        <div class="page-bar">

                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo site_url('back_office/home/dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('back_office/control');?>">Manage Content Types</a>
                                </li>
                                <li>
                                    <?php echo $title; ?>
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
echo form_open_multipart('back_office/control/delete_all_attr', $attributes); 
?>     
<div id="result"></div>                            
<div class="tile-body p-0">
<div class="table-responsive">
<?php if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success">
<?php $this->session->userdata("success_message"); ?>
</div>
<?php } ?>
<table class="table table-hover mb-0" id="attributes_sort">
                                            <thead>
                                            <tr>
                                            	<th width="5%">
                                                <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
                                                        <input type="checkbox" id="select-all"><i></i>
                                                </label>
                                                </th>
<td>Display Name</td> 
<td>Attribute Name</td> 
<td>Type</td>
<td>Display</td>
<th style=" width:160px; " class="no-sort">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
											if(count($info) > 0){
											$i=0;
											foreach($info as $val){ $i++;?>
<tr id="<?=$val["id_attr"]; ?>">
<td class="col-chk">
<label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
<input type="checkbox" class="selectMe" name="cehcklist[]" value="<?= $val["id_attr"] ; ?>">
<i></i></label>
</td>
<td>
<?php
echo $val["display_name"]; ?>
</td>
<td>
<?php
echo str_replace("_"," ",$val["name"]); ?>
</td>
<td>
<?php
$this->db->where('id_type',$val["type"]);
$query=$this->db->get('attr_type');
$res=$query->row_array();
echo $res["name"];
?>
</td>
<td>
<?php
if($val["display"] == 1)
echo "yes";
else
echo "No";
?>
</td>
<td>
<a href="<?php echo admin_url("control/edit_attr/".$con_type["id_content"]."/".$val["id_attr"]); ?>" class="table-delete-link cur" >
<i class="icon-edit-sign"></i> Edit</a>
 | 
<a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_attr('<?=$val["id_attr"];?>'); }" class="table-delete-link cur" >
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

                                       <!-- <div class="col-sm-3 text-center">
                                            <small class="text-muted"></small>
<a class="btn btn-warning" href="<? //= base_url(); ?>back_office/install/settings/<? //= $this->uri->segment(4); ?>">
<span>Install Conetnt Type</span>
</a>
                                        </div>-->

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