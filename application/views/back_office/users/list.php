<?php $content_type = "users"; ?>
<div class="page page-tables-datatables">
  <div class="pageheader">
    <h2><?php echo $title; ?><span></span></h2>
    <?php if(has_permission($content_type,'create')){ ?>
    <a role="button" href="<?php echo admin_url($content_type."/add"); ?>" class="btn btn-success btn-ef btn-ef-3 btn-ef-3c mb-10 pull-right"> <i class="fa fa-plus mr-5"></i> Add <?php echo str_replace("_"," ",$content_type); ?></a>
    <?php } ?>
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li> <a href="<?php echo admin_url("dashboard"); ?>"><i class="fa fa-home"></i> Dashboard</a> </li>
        <li> <?php echo $title; ?> </li>
      </ul>
    </div>
  </div>
  <?php $filters = $this->fct->get_module_fields($content_type,FALSE,TRUE);
 // print '<pre>'; print_r($filters); exit;
  ?>
   <div class="row">
    <div class="col-md-12">
    <section class="tile">
    <div class="tile-header dvd dvd-btm">
          <div class="custom-font text-cyan"><strong>Filter</strong><?php if(isset($count_records)) {?> (<?php echo $count_records; ?> records)<?php }?></div>
          <div class="row">
          <?php echo form_open(admin_url($content_type),array("method"=>"GET")); ?>
          	<div class="col-md-3">
            	<?php
					echo form_label('<b>Title</b>', 'Title');
					echo form_input(array("name" => "title", "value" => $this->input->get("title"),"class" =>"form-control" )); 
				?>
            </div>
            	<div class="col-md-3">
            	<?php
					echo form_label('<b>Status</b>', 'Status');
$options = array(
	"" => "- select -",
	1 => "Published",
	2 => "Under Review",
	0 => "UnPublished"
);
$selected1 =$this->input->get("status");
echo form_dropdown("status", $options,$selected1, 'class="form-control"');
				?>
            </div>
          <?php 
		  if(!empty($filters)) {
		  foreach($filters as $filter) {
			  $attr_name = str_replace(" ","_",$filter["name"]);
			  ?>
          			<div class="col-md-3">
                    <?php 
					if($filter['type'] == 1) {
						echo render_date($filter,$this->input->get($attr_name));
                    }
					elseif($filter['type'] == 6) {
						echo render_active($filter,$this->input->get($attr_name));
                    }
					elseif($filter['type'] == 7) {
						$f_table = get_foreign_table($filter['foreign_key']);
						$var = intval($filter['id_content']) - intval($filter['foreign_key']);
						if($var == 0)
						echo render_foreign_parent($filter,$this->input->get('id_parent'));
						else
						echo render_foreign($filter,$this->input->get('id_'.$filter['foreign_table']));
                    }
					else {
						echo render_textbox($filter,$this->input->get($attr_name));
					}
					 ?>
                    </div>
          <?php }
		  }?>
          </div>
          <div class="clearfix"></div>
          <div class="row">
          <div class="col-sm-3">
          <input type="submit" style="margin-top:15px;  margin-right:10px" class="pull-left btn btn-primary" value="Search" />
          <a class="btn btn-danger pull-left" style="margin-top:15px;"  href="<?php echo admin_url($content_type); ?>">Reset</a>
          </div>
          </div>
          <?php echo form_close(); ?>
        </div>
    </section>
    </div>
   </div>
   <div class="row">
    <div class="col-md-12">
      <section class="tile">
        <div class="tile-header dvd dvd-btm">
          <h1 class="custom-font text-cyan"><strong></strong><?php echo $title; ?></h1>
          <!--<ul class="controls">
            <li class="dropdown"> <a role="button" tabindex="0" class="dropdown-toggle settings" data-toggle="dropdown"> <i class="fa fa-cog"></i> <i class="fa fa-spinner fa-spin"></i> </a>
              <ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
                <li> <a role="button" tabindex="0" class="tile-toggle"> <span class="minimize"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;&nbsp;Minimize</span> <span class="expand"><i class="fa fa-angle-up"></i>&nbsp;&nbsp;&nbsp;Expand</span> </a> </li>
                <li> <a role="button" tabindex="0" class="tile-refresh"> <i class="fa fa-refresh"></i> Refresh </a> </li>
                <li> <a role="button" tabindex="0" class="tile-fullscreen"> <i class="fa fa-expand"></i> Fullscreen </a> </li>
              </ul>
            </li>
          </ul>-->
        </div>
        <div class="tile-widget">
          <div class="row">
            <div class="col-sm-5">
              <? $search_array = array("25","100","200","500","1000","All"); ?>
              <form action="<?php echo site_url("back_office/".$content_type); ?>" method="post"  id="show_items">
                show &nbsp;
                <select name="show_items" class="input-sm form-control inline w-xs">
                  <?php for($i =0 ; $i < count($search_array); $i++){ ?>
                  <option value="<?php echo $search_array[$i]; ?>" <?php if($show_items == $search_array[$i]) echo 'selected="selected"'; ?> > <?php echo ($search_array[$i] == "") ? "All" : $search_array[$i]; ?></option>
                  <? } ?>
                </select>
                &nbsp;&nbsp;items per page.
              </form>
            </div>
            <div class="col-sm-3"></div>
            <div class="col-sm-4">
              <div class="input-group w-xl  pull-right" id="example1_filter">
                <input type="text" name="search" class="input-sm form-control " placeholder="Search...">
              </div>
            </div>
          </div>
        </div>
        <? 
$attributes = array('name' => 'list_form');
echo form_open_multipart('back_office/'.$content_type.'/delete_all', $attributes); 
$attr = $this->fct->get_module_fields($content_type,TRUE);
//print '<pre>'; print_r($attr);exit;
?>
        <div id="result"></div>
        <div class="tile-body p-0">
          <div class="table-responsive">
            <?php if($this->session->userdata("success_message")){ ?>
            <div class="alert alert-success"> <?php echo $this->session->userdata("success_message"); ?> </div>
            <?php } ?>
            <table class="table table-hover mb-0" id="usersList">
              <thead>
                <tr>
                  <th> <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
                      <input type="checkbox" id="select-all">
                      <i></i> </label>
                  </th>
                   <th>#</th>
                  <th>Title</th>
                  <?php

foreach($attr as $v1){
$field = str_replace(" ","_",$v1["name"]);
if($v1["type"] != 4 && $v1["type"] != 5 && $v1["type"] != 7) {?>
                  <th><?php echo ucfirst($v1['name']); ?></th>
                  <?php } else  {?>
                  <th><?php echo ucfirst($v1['name']); ?></th>
                  <?php
}
}
?>

                  <th>Status</th>
                  <th width="150" class="no-sort">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(count($info) > 0){
											$i=0;
											foreach($info as $val){ $i++;?>
                <tr id="<?php echo $val["id_".$content_type]; ?>">
                  <td class="col-chk" id="<?php echo $val["id_".$content_type]; ?>"><label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
                      <input type="checkbox" class="selectMe" name="cehcklist[]" value="<?php echo $val["id_".$content_type] ; ?>">
                      <i></i></label></td>
                  <td><?php echo $val["id_".$content_type]; ?>
                  </td>
                    <td><?php echo $val["title"]; ?>
                  </td>
                  <?php
foreach($attr as $v){
	$field = str_replace(" ","_",$v["name"]);
?>
                  <td><?php
if($v["type"] == 7){
$content001 = get_cell("content_type","name","id_content",$v["foreign_key"]);
if(str_replace(" ","_",$content001) == $content_type) {
	$frn1 = "parent";
	$frn2 = str_replace(" ","_",$content001);
	if($val["id_".$frn1] != 0) {
		echo get_cell(str_replace(" ","_",$content001),"title","id_".$frn2,$val["id_".$frn1]); 
	} else { 
		echo "<small>Not available</small>"; 
	} 
	//if(has_sub_levels($frn2,$val["id_".$frn2])) {
		echo  '  -  <a href="'.admin_url($frn2).'?id_parent='.$val["id_".$frn2].'">view sub levels</a>';
	//}
}
else {
	$frn1 = str_replace(" ","_",$content001);
	$frn2 = str_replace(" ","_",$content001);
	if($val["id_".$frn1] != 0) {
		echo get_cell($frn2,"title","id_".$frn2,$val["id_".$frn1]); 
	} else { 
		echo "<small>Not available</small>"; 
	} 
} 
}
elseif($v["type"] == 6 ){ 
	if($val[$field] == 1)
		echo "<span class=\' label label-success\' >Active</span>";
	else
		echo "<span class=\' label label-important \' >InActive</span>";
}
elseif($v["type"] == 4){ 
if($val[$field] != ""){ ?>
                    <a href="<?= base_url(); ?>uploads/<?php echo $content_type; ?>/<?=$val[$field];?>" class="blue gallery" title="<?=$val["title"];?>" > show image</a>&nbsp;&nbsp;&nbsp; <a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= admin_url(); ?><?php echo $content_type; ?>/delete_image/<?= $field;?>/<?= $val[$field];?>/<?=$val["id_".$content_type]; ?>' } " > <img src="<?= base_url(); ?>assets/images/delete.png"  /></a>
                    <? } else { 
echo "<small class=\'blue\'>No Image Available</small>"; }
} 
elseif($v["type"] == 5){
if( $val[$field] != ""){ ?>
                    <a href="<?= base_url(); ?>uploads/<?php echo $content_type; ?>/<?=$val[$field]; ?>" class="blue" title="<?=$val["title"];?>" target="_balnk" > show file</a>&nbsp;&nbsp;&nbsp; <a class="cur" onclick="if(confirm('Are you sure you want to delete this file ?')){ window.location='<?php echo admin_url().$content_type; ?>/delete_image/<?= $field;?>/<?=$val[$field];?>/<?=$val["id_".$content_type];?>' } " > <img src="<?= base_url(); ?>assets/images/delete.png"  /></a>
                    <? } else { echo "<small class=\'blue\'>No File Available</small>"; } 
}
elseif($v["type"] == 1){?>
                    <small><?php echo dateformat($val[$field],display_dateformat());?></small>
                    <?php
}
else { 
echo $val[$field];
}
?></td>
                  <?php } ?>
                  <td><?php echo display_status($content_type,$val["id_".$content_type],$val["status"],FALSE); ?></td>
                  <td><div class="btn-group" style="width:100px;">
                      <button class="btn btn-default btn-flat" type="button">Action</button>
                      <button data-toggle="dropdown" class="btn btn-default btn-flat dropdown-toggle" type="button"> <span class="caret"></span> <span class="sr-only"></span> </button>
                      <ul class="dropdown-menu" role="menu">
                        <?php
if(has_gallery($content_type)){
?>
                        <li><a href="<?= base_url(); ?>back_office/control/manage_gallery/<?php echo $content_type; ?>/<?= $val["id_".$content_type] ?>" title="Add Photos"> <i class="icon-film"></i> Manage Gallery</a></li>
                        <?php
}
if(multi_languages() && has_permission($content_type,"translate")){  ?>
                        <li><a href="<?= base_url(); ?>back_office/translation/section/<?php echo $content_type; ?>/<?= $val["id_".$content_type] ?>"> Translate</a></li>
                        <?php
}
if (has_permission($content_type,"edit",$val["id_".$content_type])){ ?>
                        <li> <a href="<?= admin_url($content_type.'/edit/'.$val["id_".$content_type]);?>" class="table-edit-link" > Edit</a></li>
                        <? } ?>
                        <?php if (has_permission($content_type,"delete",$val["id_".$content_type])){ ?>
                        <li> <a onclick="if(confirm('Are you sure you want delete this item ?')){ delete_row('<?=$val["id_".$content_type];?>'); }" class="table-delete-link cur"> Delete</a></li>
                        <? } ?>
                      </ul>
                    </div></td>
                </tr>
                <? }  } else { ?>
                <tr>
                  <td colspan="<?php echo (count($attr)+3); ?>" style='text-align:center;'>No records available . </td>
                </tr>
                <?  } ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="tile-footer dvd dvd-top">
          <div class="row">
            <div class="col-sm-5 hidden-xs">
               <?php if (has_permission($content_type,"delete")){ ?>
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
            <div class="col-sm-3 text-center"> <small class="text-muted"></small> </div>
            <div class="col-sm-4 text-right"> <? echo $this->pagination->create_links(); ?> </div>
          </div>
        </div>
        <? echo form_close();  ?> </section>
    </div>
  </div>
</div>
<?php
$this->session->unset_userdata("success_message"); 
$this->session->unset_userdata("error_message"); 
?>
