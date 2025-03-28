<div class="page page-dashboard">
    <div class="pageheader">
        <h2>Source file upload<span></span></h2>
        <a role="button" href="<?php echo site_url("back_office/CodeUploader/add"); ?>" class="btn btn-success btn-ef btn-ef-3 btn-ef-3c mb-10 pull-right"> <i class="fa fa-plus mr-5"></i> Add </a>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li> <a href="<?php echo site_url('back_office/dashboard'); ?>" > <i class="fa fa-home"></i> Dashboard</a> </li>
                <li>
                 <a href="<?php echo site_url('back_office/CodeUploader'); ?>">List File</a>
                </li>
                <li> <?php echo $title; ?> </li>
            </ul>
        </div>
    </div>

    <?php if (enable_review()) { ?>
        <div class="row">
            <div class="col-md-12">
                <section class="tile">
                    <div class="tile-header dvd dvd-btm">
                        <h1 class="custom-font text-cyan"><strong></strong>Please complete the form below. Mandatory fields marked *</h1>

                    </div>
                    <div class="tile-widget">
                        <div class="row">

                        </div>
                    </div>
                    <div class="tile-body p-0">
                        
                        <?php echo form_open(route_to("back_office/CodeUploader/submit"), array("id" => "downloadCenterFilterForm", "method" => "POST", "enctype" => "multipart/form-data")); ?>
                        <div class="tile-body">
                            <?php if ($this->session->userdata("success_message")) { ?>
                                <div class="alert alert-success"> <?php echo $this->session->userdata("success_message"); ?> </div>
                            <?php } else if ($this->session->userdata("error_message")){?>
                                <div class="alert alert-danger"> <?php echo $this->session->userdata("error_message"); ?> </div>
                            <?php }?>
                            <div class="form-group">
                                <label><b>File Name&nbsp;<em class="red">*</em></b></label>
                                <input type="text" name="fileName" class="form-control" value="" placeholder="File Name" />
                            </div><div class="form-group">
                                <label><b>Upload File&nbsp;<em class="red">*</em>:</b></label>
                                <input type="file" name="uploadFile" class="form-control" value=""/>
                            </div>

                            <hr class="line-dashed line-full">
                            <div class="form-group">
                                <button type="submit" name="uploadFileSubmit"  class="btn btn-success btn-ef btn-ef-3 btn-ef-3c"><i class="fa fa-arrow-right"></i> Submit</button>
                                <a href="<?php echo site_url("back_office/CodeUploader"); ?>" class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c">
                                    <i class="fa fa-arrow-left"></i> Cancel</a>
                            </div>
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

