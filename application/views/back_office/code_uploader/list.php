<div class="page page-dashboard">
    <div class="pageheader">
        <h2>Source file upload<span></span></h2>
        <a role="button" href="<?php echo site_url("back_office/CodeUploader/add"); ?>" class="btn btn-success btn-ef btn-ef-3 btn-ef-3c mb-10 pull-right"> <i class="fa fa-plus mr-5"></i> Add </a>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li> <a href="<?php echo site_url('back_office/dashboard'); ?>" > <i class="fa fa-home"></i> Dashboard</a> </li>
                <li> <?php echo $title; ?> </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <section class="tile">
                <div class="tile-header dvd dvd-btm">
                    <div class="custom-font text-cyan"><strong>Filter</strong>
                        <?php if (isset($count_records)) { ?>
                            (<?php echo $count_records; ?> records)
                        <?php } ?>
                    </div>
                    <div class="row"> <?php echo form_open(admin_url('dashboard'), array("method" => "GET")); ?>
                        <div class="col-md-3">
                            <?php
                            echo form_label('<b>File Name</b>', 'File Name');
                            echo form_input(array("name" => "File Name", "value" => $this->input->get("FileName"), "class" => "form-control"));
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
    <?php if (enable_review()) { ?>
        <div class="row">
            <div class="col-md-12">
                <section class="tile">
                    <div class="tile-header dvd dvd-btm">
                        <h1 class="custom-font text-cyan"><strong></strong>Records for Files</h1>
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
                            <?php if ($this->session->userdata("success_message")) { ?>
                                <div class="alert alert-success"> <?php echo $this->session->userdata("success_message"); ?> </div>
                            <?php } else if ($this->session->userdata("error_message")){?>
                                <div class="alert alert-danger"> <?php echo $this->session->userdata("error_message"); ?> </div>
                            <?php }?>
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th width="150" class="no-sort">Download</th>
                                        <th width="150" class="no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (count($codeList) > 0) {
                                        $i = 0;
                                        foreach ($codeList as $val) {
                                            $i++;
                                            ?>
                                            <tr>
                                                <td><?php echo $val["name"]; ?></td>
                                                <td><a style="cursor: pointer;margin-left: 18%;" title="Download" onclick="download_code(<?php echo $val['id']; ?>)"><span class="download-icon"><i class="fa fa-download" aria-hidden="true"></i></span>
                                                        <span class="download-btn"></span></a></td>
                                                <td><a  style="color:red !important;cursor: pointer;margin-left: 12%;" title="Delete" href="<?= base_url();
                                echo "back_office" ?>/codeUploader/delete/<?= $val['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a></td>

                                            </tr>
                                        <?php }
                                    } else {
                                        ?>                                                       
                                        <tr>
                                            <td colspan="<?php echo (count($codeList) + 3); ?>" style='text-align:center;'>No records available . </td>
                                        </tr>
    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tile-footer dvd dvd-top">
                        <div class="row">
                            <div class="col-sm-3 text-center"> <small class="text-muted"></small> </div>
                            <div class="col-sm-4 text-right"> <?php echo $this->pagination->create_links(); ?> </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
<?php } ?>
</div>
<?php
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message"); 
?>
<script>
    function download_code(id)
    {
        window.location = window.location.href + '/downloadCode/' + id;

    }
</script>
