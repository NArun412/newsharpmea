<div class="page page-dashboard">
  <div class="pageheader">
    <h2>Dashboard <span>// You can place subtitle here</span></h2>
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li> <a href="<?php echo site_url('back_office/home/dashboard'); ?>" > <i class="fa fa-home"></i> Dashboard</a> </li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <section class="tile">
        <div class="tile-header dvd dvd-btm">
          <div class="custom-font text-cyan"><strong>Filter</strong>
            <?php if(isset($count_records)) {?>
            (<?php echo $count_records; ?> records)
            <?php }?>
          </div>
          <div class="row"> <?php echo form_open(admin_url('dashboard'),array("method"=>"GET")); ?>
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
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-sm-3">
              <input type="submit" style="margin-top:15px;  margin-right:10px" class="pull-left btn btn-primary" value="Search" />
              <a class="btn btn-danger pull-left" style="margin-top:15px;"  href="<?php echo admin_url('dashboard'); ?>">Reset</a> </div>
          </div>
          <?php echo form_close(); ?> </div>
      </section>
    </div>
  </div>
  <?php if(enable_review()) {?>
  <div class="row">
    <div class="col-md-12">
      <section class="tile">
        <div class="tile-header dvd dvd-btm">
          <h1 class="custom-font text-cyan"><strong></strong>Records for Review</h1>
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
            <div class="col-sm-8"></div>
            <div class="col-sm-4">
              <div class="input-group w-xl  pull-right" id="example1_filter">
                <input type="text" name="search" class="input-sm form-control " placeholder="Search...">
              </div>
            </div>
          </div>
        </div>
        <div class="tile-body p-0">
          <div class="table-responsive">
            <?php if($this->session->userdata("success_message")){ ?>
            <div class="alert alert-success"> <?php echo $this->session->userdata("success_message"); ?> </div>
            <?php } ?>
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Done on </th>
                  <th>Module</th>
                  <th>Record</th>
                  <th>Lang</th>
                  <th>Admin</th>
                  <th>Super Admin</th>
                  <th width="150" class="no-sort">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                if(count($reviews) > 0){
											$i=0;
											foreach($reviews as $val){ $i++;
											$content_type = $val['module'];
											?>
                <tr>
                  <td><?php echo $val["user_name"]; ?></td>
                  <td><?php echo dateformat($val["created_on"],"d,M Y"); ?></td>
                  <td><?php echo ucfirst(str_replace("_"," ",$val["module"])); ?></td>
                  <td><?php echo get_cell($val["module"],'title','id_'.$val['module'],$val["record"]); ?></td>
                  <td><?php echo $val["lang"]; ?></td>
                  <td><?php echo render_approval($content_type,$val["record"],4); ?></td>
                  <td><?php echo render_approval($content_type,$val["record"],2); ?></td>
                  <td><?php if(has_permission($content_type,"edit",$val['record']) && $this->user_role <= 3) {?>
                    <a href="<?= admin_url('reviews_status/manage/'.$content_type.'/'.$val["record"].'/'.$val['lang']);?>" class="table-edit-link" > View/Edit</a>
                    <?php }?></td>
                </tr>
                <? }  } else { ?>
                <tr>
                  <td colspan="<?php echo (count($reviews)+3); ?>" style='text-align:center;'>No records available . </td>
                </tr>
                <?  } ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="tile-footer dvd dvd-top">
          <div class="row">
            <div class="col-sm-3 text-center"> <small class="text-muted"></small> </div>
            <div class="col-sm-4 text-right"> <? echo $this->pagination->create_links(); ?> </div>
          </div>
        </div>
      </section>
    </div>
  </div>
  <?php }?>
</div>
