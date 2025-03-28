<?php
$cl_b = 'no-banner';
if (isset($banners) && !empty($banners))
    $cl_b = '';
?>
<div class="content_inner <?php echo $cl_b; ?>" >
    <div class="centered2" >

        <h1 class="head-title with-border center">
            <?php echo lang('Support'); ?> - <?php echo lang("CodeUploader"); ?>
        </h1>

        <div class="span" >
            <div class="third">
                <?php if ($this->session->userdata("error_message")) { ?>
                    <div class="alert alert-failure" style="  color:red">
                        <?= $this->session->userdata("error_message"); ?>
                    </div>
                <?php } ?>
                <div class="custom-form-style service_center_form full "> 
                    <?php echo form_open(route_to("codeUploader/submit"), array("id" => "downloadCenterFilterForm", "method" => "POST", "enctype" => "multipart/form-data")); ?>
                    <fieldset>
                      <!--<legend><?php //echo lang("current location");    ?></legend>-->
                        <div class="form-item full">
                            <label><?php echo lang("FILENAME"); ?></label>
                            <input type="text" name="fileName" value="" placeholder="<?php echo lang("FILENAME"); ?>" />
                            <span class="form-error" id="fileName-error"></span> </div>
                        <div class="form-item full">
                            <label><?php echo lang("UPLOADFILE"); ?></label>
                            <input type="file" name="uploadFile" value=""/>
                            <span class="form-error" id="uploadFile-error"></span> </div>
                    </fieldset>
                    <div class="form-item full">
                        <input type="submit" name="uploadFileSubmit" value="<?php echo lang("UPLOAD"); ?>" />
                    </div>
                    <?php echo form_close(); ?> </div>
            </div>
            <div class="third-quarter" id="service_center_loader">
                <?php $this->load->view("content/uploadedCodeItems"); ?>

            </div>
        </div>

    </div>

</div>
<?php
$this->session->unset_userdata("error_message");
?> 
