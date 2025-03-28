<?php
$sego =$this->uri->segment(4);
$gallery_status="0";
?>

<div class="page page-tables-datatables">
  <div class="pageheader">
    <h2>
      <?= $title; ?>
      <span></span></h2>
    <a role="button" href="<?= admin_url('control/create_group'); ?>" class="btn btn-success btn-ef btn-ef-3 btn-ef-3c mb-10 pull-right"> <i class="fa fa-plus mr-5"></i> Create Group</a>
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li> <a href="<?php echo admin_url('dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a> </li>
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
            <li class="dropdown"> <a role="button" tabindex="0" class="dropdown-toggle settings" data-toggle="dropdown"> <i class="fa fa-cog"></i> <i class="fa fa-spinner fa-spin"></i> </a>
              <ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
                <li> <a role="button" tabindex="0" class="tile-toggle"> <span class="minimize"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;&nbsp;Minimize</span> <span class="expand"><i class="fa fa-angle-up"></i>&nbsp;&nbsp;&nbsp;Expand</span> </a> </li>
                <li> <a role="button" tabindex="0" class="tile-refresh"> <i class="fa fa-refresh"></i> Refresh </a> </li>
                <li> <a role="button" tabindex="0" class="tile-fullscreen"> <i class="fa fa-expand"></i> Fullscreen </a> </li>
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
        <div id="result"></div>
        <div class="tile-body p-0">
          <div class="table-responsive">
            <?php if($this->session->userdata("success_message")){ ?>
            <div class="alert alert-success"> <?php echo $this->session->userdata("success_message"); ?> </div>
            <?php } ?>
            <table class="table table-hover mb-0" id="menu_groups_order">
              <thead>
                <tr>
                  <th>Group Name</th>
                  <th style="width: 160px;" class="no-sort">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
											if(count($info) > 0){
											$i=0;
											foreach($info as $val){ $i++;?>
                <tr id="<?=$val["id"]; ?>">
                  <td><?php
echo $val["name"]; ?></td>
                  <td><a href="<?= admin_url("control/edit_group/".$val["id"]);?>" class="table-edit-link cur cur" > <i class="icon-edit" ></i> Edit</a> <span>&nbsp;&nbsp;|&nbsp;&nbsp;</span> <a onclick="if(confirm('Are you sure you want delete this item ?')){ window.location='<?php echo admin_url("control/delete_group/".$val['id']); ?>'; }" class="table-delete-link cur" > <i class="icon-remove-sign"></i> Delete</a></td>
                </tr>
                <?php } } else { ?>
                <tr>
                  <td colspan="5">No records available .</td>
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
