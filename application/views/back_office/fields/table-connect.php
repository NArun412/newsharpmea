<?php $view_only =  view_only($content_type); ?>

   <div class="row"><div class="page-tables-datatables">
    <div class="col-md-12">
      <section class="tile">
        <div class="tile-header dvd dvd-btm">
          <h1 class="custom-font text-cyan"><strong></strong><?php echo $title; ?></h1>
        </div>
        <? 
$attributes = array('name' => 'list_form');
echo form_open_multipart(admin_url('manage/delete_all/'.$this->module.'/'.$this->id.'/'.$this->conmod.'/'.$this->conid), $attributes); 
$attr = $this->fct->get_module_fields($content_type,TRUE);
?>
        <div id="result"></div>
        <div class="tile-body p-0">
          <div class="table-responsive">
            <?php if($this->session->userdata("success_message")){ ?>
            <div class="alert alert-success"> <?php echo $this->session->userdata("success_message"); ?> </div>
            <?php } ?>
            <table class="table table-hover mb-0" <?php if(!$view_only) {?>data-mod="<?php echo $content_type; ?>" id="usersList"<?php }?>>
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
<?php if(!$view_only) {?>
 <th>Created On</th>
 <th>Created By</th>
 <?php if(enable_review()) {?>
                  <th>Admin</th>
                  <th>Super Admin</th><?php }?>
                  <th>Status</th><?php }?>
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
		echo  '  -  <a href="'.admin_url('manage/display/'.$frn2).'?id_parent='.$val["id_".$frn2].'">view sub levels</a>';
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
if($val[$field] != ""){ ?>  <span>
                    <a href="<?= base_url(); ?>uploads/<?php echo $content_type; ?>/<?=$field;?>/<?=$val[$field];?>" class="blue gallery" title="<?=$val["title"];?>" > show image</a>&nbsp;&nbsp;&nbsp; <a onclick="delete_image(this,'<?= $content_type;?>','<?= $field;?>','<?=$val[$field];?>',<?=$val["id_".$content_type];?>)" class="cur"><?php echo img('assets/images/delete.png'); ?></a>
                 </span>
                    <? } else { 
echo "<small class=\'blue\'>No Image Available</small>"; }
} 
elseif($v["type"] == 5){
if( $val[$field] != ""){ ?><span>
                    <a href="<?= base_url(); ?>uploads/<?php echo $content_type; ?>/<?=$field;?>/<?=$val[$field]; ?>" class="blue" title="<?=$val["title"];?>" target="_balnk" > show file</a>&nbsp;&nbsp;&nbsp; <a onclick="delete_file(this,'<?= $content_type;?>','<?= $field;?>','<?=$val[$field];?>',<?=$val["id_".$content_type];?>)" class="cur"><?php echo img('assets/images/delete.png'); ?></a>> </span>
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
                  <?php if(!$view_only) {?>
                  <td><?php 
				//  echo $val["created_on"].'<br />';
				  echo dateformat($val["created_on"],display_dateformat()); ?></td>
                   <td><?php echo created_by($content_type,$val["id_".$content_type]); ?></td>
                  <?php if(enable_review()) {?>
                 	 <td><?php echo render_approval($content_type,$val["id_".$content_type],4); ?></td>
                 	 <td><?php echo render_approval($content_type,$val["id_".$content_type],2); ?></td>
                  <?php }?>
                  <td><?php echo display_status($content_type,$val["id_".$content_type],$val["status"],FALSE); ?></td><?php }?>
                  <td>
                  <?php if($view_only) {?>
                  <a href="<?= admin_url('manage/edit/'.$content_type.'/'.$val["id_".$content_type]); ?>"> View</a>
                  <?php } else {?>
                  <div class="btn-group" style="width:100px;">
                      <button class="btn btn-default btn-flat" type="button">Action</button>
                      <button data-toggle="dropdown" class="btn btn-default btn-flat dropdown-toggle" type="button"> <span class="caret"></span> <span class="sr-only"></span> </button>
                      <ul class="dropdown-menu" role="menu">
                        <?php
if(has_gallery($content_type)){
?>
                        <li><a href="<?= admin_url("control/manage_gallery/".$content_type."/".$val["id_".$content_type]); ?>" title="Add Photos"> <i class="icon-film"></i> Manage Gallery</a></li>
                        <?php
}
if(multi_languages() && has_permission($content_type,"translate") && 	!in_array($content_type,static_modules())){  ?>
                        
                         <li><a href="<?= admin_url("manage/translate/".$content_type."/".$val["id_".$content_type]); ?>"> Translate</a></li>
                        <?php
}
if (has_permission($content_type,"edit",$val["id_".$content_type])){
	    
	$link = admin_url('manage/mod/'.$this->module.'/'.$this->id.'/'.$this->conmod.'/'.$val["id_".$content_type]);
?>
                        <li> <a href="<?= $link;?>" class="table-edit-link" > Edit</a></li>
                        <? } ?>
                        <?php if (has_permission($content_type,"delete",$val["id_".$content_type])){
                        
	$link = admin_url('manage/delete/'.$content_type.'/'.$val["id_".$content_type]);
    if($this->router->method == 'mod' || $this->router->method == 'submit_connect'){
		$link = admin_url('manage/delete/'.$this->module.'/'.$this->id.'/'.$this->conmod.'/'.$val["id_".$content_type]);
	}
	if(in_array($content_type,static_modules()))
	$link = admin_url($content_type.'/delete/'.$val["id_".$content_type]);
                        ?>
                        <li> <a onclick="if(confirm('Are you sure you want delete this item ?')){ window.location = '<?php echo $link; ?>'; }" class="table-delete-link cur"> Delete</a></li>
                        <? } ?>
                      </ul>
                    </div><?php }?></td>
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
		<?php
if(isset($this->id))
echo form_hidden('foreign_id', $this->id);
if(isset($this->conmod))
echo form_hidden('parent_table', $this->module);
		?>
		<? echo form_close();  ?> 
        </section>
    </div>
  </div>
</div>
<?php
$this->session->unset_userdata("success_message"); 
$this->session->unset_userdata("error_message"); 
?>
