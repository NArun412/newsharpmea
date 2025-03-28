<?php $view_only =  false;
$content_type = 'trash'; ?>
<div class="page page-tables-datatables">
  <div class="pageheader">
    <h2><?php echo $title; ?><span></span></h2>
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li> <a href="<?php echo admin_url("dashboard"); ?>"><i class="fa fa-home"></i> Dashboard</a> </li>
        <li> <?php echo $title; ?> </li>
      </ul>
    </div>
  </div>

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
					echo form_label('<b>Module</b>', 'Module');
					$options = array();
					$options[''] = '- select -';
					$modules = $this->fct->get_trash_modules();
					if(!empty($modules)) {
						foreach($modules as $mod) {
							$options[$mod['module']] = ucfirst($mod['module']);
						}
					}
$selected1 =$this->input->get("module");
echo form_dropdown("module", $options,$selected1, 'class="form-control"');
				?>
            </div>
  
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
        </div>
        <div class="tile-widget">
          <div class="row">
            <div class="col-sm-5">
              <? $search_array = array("25","100","200","500","1000","All"); ?>
              <form action="<?php echo admin_url($content_type); ?>" method="post"  id="show_items">
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
echo form_open_multipart(admin_url('trash/delete_all'), $attributes); 
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
                  <th> <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
                      <input type="checkbox" id="select-all">
                      <i></i> </label>
                  </th>
                   <th>#</th>
                  <th>Module</th>
                  <th>Title</th>
                  <th>JSON Content</th>
                  <th width="150" class="no-sort">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(count($info) > 0){
											$i=0;
											foreach($info as $val){ $i++;?>
                <tr id="<?php echo $val["id"]; ?>">
                  <td class="col-chk" id="<?php echo $val["id"]; ?>"><label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
                      <input type="checkbox" class="selectMe" name="cehcklist[]" value="<?php echo $val["id"] ; ?>">
                      <i></i></label></td>
                  <td><?php echo $val["id"]; ?>
                  </td>
                    <td><?php echo ucfirst($val["module"]); ?>
                  </td>
                   <td><?php echo $val["title"]; ?>
                  </td>
                   <td><a onclick="alert('<?php echo $val['id']; ?>')">view content</a></td>
                  <td>
    
                  <div class="btn-group" style="width:100px;">
                      <button class="btn btn-default btn-flat" type="button">Action</button>
                      <button data-toggle="dropdown" class="btn btn-default btn-flat dropdown-toggle" type="button"> <span class="caret"></span> <span class="sr-only"></span> </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a onclick="if(confirm('Are you sure you want delete this item ?')){ window.location = '<?php echo admin_url("trash/restore/".$val["id"]); ?>'; }" title="Restore From Trash" class="table-delete-link cur"> Restore</a></li>
                        <?php if (has_permission($content_type,"delete",$val["id"])){ ?>
                        <li> <a onclick="if(confirm('Are you sure you want delete this item ?')){ window.location = '<?php echo admin_url("trash/delete/".$val["id"]); ?>'; }" title="Delete From Trash" class="table-delete-link cur"> Delete</a></li>
                        <? } ?>
                      </ul>
                    </div></td>
                </tr>
                <? }  } else { ?>
                <tr>
                  <td colspan="10" style='text-align:center;'>No records available . </td>
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
                 <option value="restore_all">Restore selected</option>
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
