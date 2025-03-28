<?php
$sego =$this->uri->segment(4);
$gallery_status="0";
?>
<div class="page page-tables-datatables">
                    <div class="pageheader">

                        <h2><?php echo $title; ?> 
                        <span></span></h2>
                        <div class="page-bar">

                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo site_url('back_office/home/dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('back_office/roles/'.$this->session->userdata("back_link")); ?>">Levels</a>
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
                                    <h1 class="custom-font"><strong><?php echo $title; ?></strong> </h1>
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
<form action="<?= base_url(); ?>back_office/roles/submit_roles" method="post" class="middle-forms" enctype="multipart/form-data">
<?php
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["title"] = "";
}
?>
<? if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success">
<?= $this->session->userdata("success_message"); ?>
</div>
<? } ?>
<table class="table table-striped" id="table-1">
<thead>
<tr class="">
<th class="mini"><input type="checkbox" id="checkAllAuto" /></th>
<th class="big">Levels Resource </th>
<th class="small"><input type="checkbox" id="read" class="head_check" />&nbsp;&nbsp;Read</th>
<th class="small"><input type="checkbox" id="write" class="head_check" />&nbsp;&nbsp;Write</th>
<th class="small"><input type="checkbox" id="edit" class="head_check" />&nbsp;&nbsp;Edit</th>
<th class="small"><input type="checkbox" id="delete" class="head_check" />&nbsp;&nbsp;Delete</th>
<th class="small"><input type="checkbox" id="delete_all" class="head_check" />&nbsp;&nbsp;Delete All</th>
<th class="small"><input type="checkbox" id="manage" class="head_check" />&nbsp;&nbsp;Manage</th>
<th class="small"><input type="checkbox" id="export" class="head_check" />&nbsp;&nbsp;Export</th>
<th class="small"><input type="checkbox" id="print" class="head_check" />&nbsp;&nbsp;Print</th>
<th class="small"><input type="checkbox" id="activate" class="head_check" />&nbsp;&nbsp;Activate</th>
</tr>
</thead>
<tfoot><tr><td colspan="11">
<?
$config['roles'] = array();
foreach($roles as $val){
	$config['roles'][] = $val["title"];
}
//$this->load->helper('file');
//1$path= './application/config/';
//$string = read_file($path.'acl.php');
//write_file($path.'acl.php', $data1);
?>
<input type="hidden" name="count_resources" value="<?= count($resources); ?>"  />
<input type="hidden" name="current_role" value="<?= $current_role; ?>"  />
</td></tr></tfoot>
<tbody>
<? 
$i=0;
foreach($resources as $val){ 
$index = explode(',',$val["view"]);
$add = explode(',',$val["add"]);
$edit = explode(',',$val["edit"]);
$delete = explode(',',$val["delete"]);
$delete_all = explode(',',$val["delete_all"]);
$manage = explode(',',$val["manage"]);
$export = explode(',',$val["export"]);
$print = explode(',',$val["print"]);
$activate = explode(',',$val["activate"]);
?>
<tr>
<td class="mini"><input type="checkbox" class="checkAllAuto" id="<?=$i;?>"  /></td>
<td class="capitalize big">
<?= str_replace('_',' ',$val["title"]); ?>
<input type="hidden" name="resources[]" value="<?= $val["title"]; ?>"  />
</td>
<td class="small"><input type="checkbox" name="cehcklist<?=$i;?>[]" class="checkAuto<?=$i;?> read" value="view" <? if(in_array($current_role, $index)) echo 'checked="checked"'; ?> /></td>
<td class="small"><input type="checkbox" name="cehcklist<?=$i;?>[]" class="checkAuto<?=$i;?> write" value="add" <? if(in_array($current_role, $add)) echo 'checked="checked"'; ?> /></td>
<td class="small"><input type="checkbox" name="cehcklist<?=$i;?>[]" class="checkAuto<?=$i;?> edit" value="edit" <? if(in_array($current_role, $edit)) echo 'checked="checked"'; ?> /></td>
<td class="small"><input type="checkbox" name="cehcklist<?=$i;?>[]" class="checkAuto<?=$i;?> delete" value="delete" <? if(in_array($current_role, $delete)) echo 'checked="checked"'; ?> /></td>
<td class="small"><input type="checkbox" name="cehcklist<?=$i;?>[]" class="checkAuto<?=$i;?> delete_all" value="delete_all" <? if(in_array($current_role, $delete_all)) echo 'checked="checked"'; ?> /></td>
<td class="small"><input type="checkbox" name="cehcklist<?=$i;?>[]" class="checkAuto<?=$i;?> manage" value="manage" <? if(in_array($current_role, $manage)) echo 'checked="checked"'; ?> /></td>
<td class="small"><input type="checkbox" name="cehcklist<?=$i;?>[]" class="checkAuto<?=$i;?> export" value="export" <? if(in_array($current_role, $export)) echo 'checked="checked"'; ?> /></td>
<td class="small"><input type="checkbox" name="cehcklist<?=$i;?>[]" class="checkAuto<?=$i;?> print" value="print" <? if(in_array($current_role, $print)) echo 'checked="checked"'; ?> /></td>
<td class="small"><input type="checkbox" name="cehcklist<?=$i;?>[]" class="checkAuto<?=$i;?> activate" value="activate" <? if(in_array($current_role, $activate)) echo 'checked="checked"'; ?> /></td>
</tr>
<? 
$i++; 
}
?>
<tr style="width:100%"><td colspan="11">
<br clear="all"  />
<div class="pull-right" >
<input type="image" src="<?= base_url(); ?>images/bt-send-form.png" value="Save Changes" class="btn btn-primary" />
</div>
<span class="clearFix">&nbsp;</span>
</td></tr>
</tbody>
</table>
</form>
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