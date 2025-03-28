<?php $content_type = "users_permissions"; 
$all_content_types = $this->fct->get_content_types();
?>
<div class="page page-tables-datatables">
  <div class="pageheader">
    <h2><?php echo $title; ?><span></span></h2>
    <?php if($this->acl->has_permission($content_type,'add')){ ?>
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
  ?>
   <div class="row">
    <div class="col-md-12">
    <section class="tile">
    <div class="tile-header dvd dvd-btm">
          <div class="custom-font text-cyan"><strong>Filter</strong><?php if(isset($count_records)) {?> (<?php echo $count_records; ?> records)<?php }?></div>
          <div class="row">
          <?php echo form_open(admin_url($content_type),array("method"=>"GET")); ?>
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
<?php if($this->input->get("id_users") != "") {
	$user_id = $this->input->get("id_users");
	if(!is_numeric($user_id)) exit("access denied");
	$permissions = $this->fct->get_user_permissions($user_id);
	if(empty($permissions))
	$permissions_role = $this->fct->get_role_permissions(user_role($user_id));
	?>
  <div class="row">
    <div class="col-md-12">
      <section class="tile">
        <div class="tile-header dvd dvd-btm">
          <h1 class="custom-font text-cyan"><strong></strong><?php echo $title; ?></h1>
        </div>
        
        <? 
$attributes = array('name' => 'list_form');
echo form_open_multipart(admin_url($content_type.'/submit_permissions'), $attributes); 
//$attr = $this->fct->get_module_fields($content_type,TRUE);
//print '<pre>'; print_r($attr);exit;
echo form_hidden("user_id",$user_id);
?>
        <div id="result"></div>
        <div class="tile-body p-0">
          <div class="table-responsive">
            <?php if($this->session->userdata("success_message")){ ?>
            <div class="alert alert-success"> <?php echo $this->session->userdata("success_message"); ?> </div>
            <?php } ?>
            
                      <div class="col-md-12">
            <?php $i=0;foreach(admin_menu_groups() as $k => $grp) {$i++;
				$all_content_types = $this->fct->get_content_types($k);
				?>
                <?php if($i > 1){?><hr class="col-md-12" /><?php }?>
                <div class="col-md-12">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th width="10"> 
                    <input type="checkbox" class="select-part-all" id="select-perm-all-<?php echo $k; ?>">
                  </th>
				  <th width="200"><?php echo $grp; ?></th>
   						<?php 
							$i=0;
							foreach(permissions() as $perm){ $i++;?>
							<th width="10"><?php echo ucfirst($perm); ?></th>
						<?php }?>              
                </tr>
              </thead>
              <tbody>
                <?php if(count($all_content_types) > 0){
											$i=0;
											foreach($all_content_types as $val){ $i++;?>
                <tr id="<?php echo $val["id_content"]; ?>">
                  <td class="col-chk" width="10">
                      <input type="checkbox" id="select-perm-all-<?php echo $val["id_content"] ; ?>" class="select-perm-all select-perm-all-<?php echo $k; ?>" value="<?php echo $val["id_content"] ; ?>">
                      </td>
                      <td width="150"><?php echo $val['name']; ?></td>
               			<?php 
							$i=0;
							foreach(permissions() as $perm){ $i++;
							$c = '';
							if(empty($permissions)) {
								if(role_has_permission($permissions_role,$val['used_name'],$perm))
								$c = 'checked="checked"';
							}
							else {
								if(user_has_permission($permissions,$val['used_name'],$perm))
								$c = 'checked="checked"';
							}
							?>
							<td width="10">  <?php if($val['perm_'.$perm] == 1) {?><input <?php echo $c; ?> type="checkbox" class="select-perm-them-all select-perm-them-<?php echo $val["id_content"] ; ?>  select-perm-all-<?php echo $k; ?>" name="perm_<?php echo $perm; ?>[<?php echo $val['id_content']; ?>]" value="1" /><?php }?></td>
						<?php }?>   
                </tr>
                <?php }}?>
              </tbody>
            </table>
            </div>
            <?php }?></div>
            <div class="row"><div class="col-sm-12"><input type="submit" class="pull-right btn btn-primary" value="Submit" /></div></div>
          </div>
        </div>
        
        <? echo form_close();  ?> </section>
    </div>
  </div>
  <?php }?>
</div>
<?php
$this->session->unset_userdata("success_message"); 
$this->session->unset_userdata("error_message"); 
?>
