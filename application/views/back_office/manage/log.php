<?php $content_type = "log"; ?>
<div class="page page-tables-datatables">
  <div class="pageheader">
    <h2><?php echo $title; ?><span></span></h2>
 
    <a role="button" onclick="if(confirm('Are you sure?')) { window.location='<?php echo admin_url($content_type."/truncate"); ?>'; }" class="btn btn-success btn-ef btn-ef-3 btn-ef-3c mb-10 pull-right">Truncate</a>
    
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
          	<!--<div class="col-md-3">
            	<?php
					//echo form_label('<b>Title</b>', 'Title');
					//echo form_input(array("name" => "title", "value" => $this->input->get("title"),"class" =>"form-control" )); 
				?>
            </div>-->
            	<div class="col-md-3">
            	<?php
				$options = array();
				$get_content_types = $this->fct->get_content_types();
					echo form_label('<b>User</b>', 'User');
					$options[''] = '- select -';
					foreach($users as $us) {
						$options[$us['id_users']] = $us['name'];
					}
$selected1 =$this->input->get("uid");
echo form_dropdown("uid", $options,$selected1, 'class="form-control chosen-select"');
				?>
            </div>
            
            <div class="col-md-3">
            	<?php
				$options = array();
					echo form_label('<b>Module</b>', 'Module');
					$options[''] = '- select -';
					foreach($modules as $gct) {
						$options[str_replace(' ','_',$gct['name'])] = $gct['name'];
					}
$selected1 =$this->input->get("module");
echo form_dropdown("module", $options,$selected1, 'class="form-control chosen-select"');
				?>
            </div>

          </div>
          <div class="clearfix"></div>
          <div class="row">
          <div class="col-sm-3">
          <input type="submit" style="margin-top:15px;  margin-right:10px" class="pull-left btn btn-primary" value="Search" />
          <a class="btn btn-danger pull-left" style="margin-top:15px; margin-right:10px"  href="<?php echo admin_url($content_type); ?>">Reset</a>
          <a class="btn btn-warning pull-left" style="margin-top:15px;"  href="<?php echo admin_url($content_type); ?>">Export</a>
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
   <!--     <div class="tile-widget">
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
        </div>-->
        <?php 
			$attributes = array('name' => 'list_form');
			echo form_open_multipart('back_office/'.$content_type.'/delete_all', $attributes); 
		?>
        <div id="result"></div>
        <div class="tile-body p-0">
          <div class="table-responsive">
            <?php if($this->session->userdata("success_message")){ ?>
            <div class="alert alert-success"> <?php echo $this->session->userdata("success_message"); ?> </div>
            <?php } ?>
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <!--<th> <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
                      <input type="checkbox" id="select-all">
                      <i></i> </label>
                  </th>-->
                  <th>#</th>
                  <th>User</th>
                  <th>Module</th>
                  <th>Record</th>
                  <th>Action</th>
                  <th>Date</th>
                  <th>IP</th>
              <!--    <th width="150" class="no-sort">Action</th>-->
                </tr>
              </thead>
              <tbody>
                <?php if(count($info) > 0){
						$i=0;
				foreach($info as $val){ $i++;?>
                <tr id="<?php echo $val["id"]; ?>">
                 <!-- <td class="col-chk" id="<?php //echo $val["id"]; ?>"><label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
                      <input type="checkbox" class="selectMe" name="cehcklist[]" value="<?php //echo $val["id"] ; ?>">
                      <i></i></label></td>-->
                  <td>#<?php echo $val['id'];?></td>
                  <td><?php echo $val["user_name"]; ?></td>
                  <td><?php echo $val["module"]; ?></td>
                  <td><?php 
				  if(!empty($val['info'])) echo $val['info'];
				  elseif(!empty($val['module'])) echo get_cell($val['module'],'title','id_'.$val['module'],$val['record']);
				  else echo $val['id']; ?></td>
                  <td><?php echo $val["action"]; ?></td>
                  <td><?php echo $val["date"]; ?></td>
                  <td><?php echo $val["ip"]; ?></td>
                 <!-- <td><div class="btn-group" style="width:100px;">
                      <button class="btn btn-default btn-flat" type="button">Action</button>
                      <button data-toggle="dropdown" class="btn btn-default btn-flat dropdown-toggle" type="button"> <span class="caret"></span> <span class="sr-only"></span> </button>
                      <ul class="dropdown-menu" role="menu">
                        <?php //if (has_permission($content_type,"delete",$val["id"])){ ?>
                        <li> <a onclick="if(confirm('Are you sure you want delete this item ?')){ window.location='<?php echo admin_url('log/delete/'.$val['id']); ?>'; }" class="table-delete-link cur"> Delete</a></li>
                        <? //} ?>
                      </ul>
                    </div></td>-->
                </tr>
                <?php }  } else { ?>
                <tr>
                  <td colspan="<?php echo (count($attr)+3); ?>" style='text-align:center;'>No records available . </td>
                </tr>
                <?php  } ?>
              </tbody>
            </table>
          </div>
        </div>
       -<div class="tile-footer dvd dvd-top">
          <div class="row">
            <!-- <div class="col-sm-5 hidden-xs">
               <?php //if (has_permission($content_type,"delete")){ ?>
              <select name="check_option" class="input-sm form-control w-sm inline">
                <option value="">Bulk action</option>
                <option value="delete_all">Delete selected</option>
 
              </select>
              <button class="btn btn-sm btn-default">Apply</button>
              <?php //} ?>
            </div>-->
            <div class="col-sm-3 text-center"> <small class="text-muted"></small> </div>
            <div class="col-sm-4 text-right"> <?php echo $this->pagination->create_links(); ?> </div>
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