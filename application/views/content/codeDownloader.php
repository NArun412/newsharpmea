<?php
$cl_b = 'no-banner';
if (isset($banners) && !empty($banners))
    $cl_b = '';
?>
<div class="content_inner <?php echo $cl_b; ?>" >
    <div class="centered2" >
        <?php if (validate_user()) $this->load->view("includes/manage"); ?>
        <h1 class="head-title with-border center">
             <?php echo lang('CodeDownloader'); ?>  
        </h1>
        <?php //$this->load->view("content/support-menu");  ?>
        <div class="span" >
            <div class="third">

                <div class="custom-form-style service_center_form full "> 
                   

                    										
                </div>
            </div>
            <div class="four-quarter" id="service_center_loader" style="padding-left: 3%;">

                <?php $this->load->view("content/download_code_items"); ?>
      <!--<span class="results-will-show-here"><?php //echo lang("search results will appear here");    ?></span>-->
            </div>
        </div>
    </div>
</div>
