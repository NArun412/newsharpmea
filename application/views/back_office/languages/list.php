<?php
$sego =$this->uri->segment(4);
$gallery_status="0";
?>
<div class="page page-tables-datatables">
                    <div class="pageheader">

                        <h2><?php echo $title; ?> 
                        <span></span></h2>
                        <?php if ($this->acl->has_permission('languages','add')){ ?>
                        <a role="button" href="<?= site_url('back_office/languages/add'); ?>" class="btn btn-success btn-ef btn-ef-3 btn-ef-3c mb-10 pull-right"><i class="fa fa-plus mr-5"></i> Add Language</a>
                        <?php } ?>
                        <div class="page-bar">

                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo site_url('back_office/home/dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
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
                                    <h1 class="custom-font"><strong></strong><?php echo $title; ?></h1>
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
<div class="table-responsive">
<? if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success">
<?= $this->session->userdata("success_message"); ?>
</div>
<? } ?>
<table class="table table-custom" id="usersList">
                                            <thead>
                                            <tr>
                                           		<th></th>
                                                <th>TITLE</th>
                                                <th>SYMBOLE</th>
                                                <th>DIRECTION</th>
                                                <th>DEFAULT</th>
                                                <th>STATUS</th>
                                                <th style="width: 160px;" class="no-sort">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
											if(count($info) > 0){
											$i=0;
											foreach($info as $val){ $i++;?>
<tr id="<?=$val["id_languages"]; ?>" class="odd gradeX">
<td class="col-chk"></td>
<td><?php echo $val["title"]; ?></td>
<td><?php echo $val["symbole"]; ?></td>
<td>
<?php
if($val["direction"] == 1)
echo "<span class=' label' >rtl</span>";
else
echo "<span class=' label' >ltr</span>";
?>
</td>
<td>
<?php 
if($val["default"] == 1)
echo "<b class='yellow' >Default</b>";
else
echo "<b class='' >-</b>";
?>
</td>
<td>
<?php 
if($val["status"] == 1)
echo "<span class=' label label-success' >Active</span>";
else
echo "<span class=' label label-important ' >InActive</span>";
?>
</td>
<td style="text-align:center"><? if ($this->acl->has_permission('languages','index')){ ?>
<a href="<?= site_url('back_office/languages/view/'.$val["id_languages"]);?>" class="table-edit-link" title="View" >
<i class="icon-search" ></i> View</a> <span class="hidden">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
<? } ?>
<? if ($this->acl->has_permission('languages','edit')){ ?>
<a href="<?= site_url('back_office/languages/edit/'.$val["id_languages"]);?>" class="table-edit-link" title="Edit" >
<i class="icon-edit" ></i> Edit</a> <span class="hidden">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
<? } ?>
<? if ($this->acl->has_permission('languages','delete')){ ?>
<a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?=$val["id_languages"];?>'); }" class="table-delete-link cur" title="Delete" >
<i class="icon-remove-sign" ></i> Delete</a>
<? } ?>
</td>
</tr>
                                            <?php } 
											} else { ?>
                                            <tr>
                                            <td colspan="7">No records available .</td>
                                            </tr>
                                            <?php } ?>
                                            
                                            </tbody>
                                        </table>
</div>
</div>
</section>

        </div>
        </div>
        
</div>
<?php
$this->session->unset_userdata("success_message"); 
$this->session->unset_userdata("error_message"); 
?>                    