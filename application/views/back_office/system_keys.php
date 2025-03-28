<?php
$sego =$this->uri->segment(4);
$gallery_status="0";
?>
<div class="page page-tables-datatables">
                    <div class="pageheader">
                        <h2><?= $title; ?> <span></span></h2>
                        <div class="page-bar">

                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo site_url('back_office/home/dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
                                </li>
                                <li>
                                    <?= $title; ?>
                                </li>
                            </ul>

                        </div>

                    </div>                   
<div class="row">

<!-- col -->
<div class="col-md-3">
<section class="tile tile-simple tbox">
<!-- tile widget -->
<div class="tile-widget bg-warning text-center p-30 tcol">
<i class="fa fa-spoon fa-3x"></i>
</div>
<!-- /tile widget -->
<!-- tile body -->
<div class="tile-body text-center tcol">
<a href="<?php echo site_url('back_office/choosing_kitchen'); ?>" class="text-muted system-keys m-0">Choosing <br />kitchen</a>
</div>
<!-- /tile body -->
</section>  
</div>

<div class="col-md-3">   
<section class="tile tile-simple tbox">
<!-- tile widget -->
<div class="tile-widget bg-danger text-center p-30 tcol">
<i class="fa fa-suitcase fa-3x"></i>
</div>
<!-- /tile widget -->
<!-- tile body -->
<div class="tile-body text-center tcol">
<a href="<?php echo site_url('back_office/restaurant_services'); ?>" class="text-muted system-keys m-0">restaurant <br />services</a>
</div>
<!-- /tile body -->
</section>
</div>

<div class="col-md-3">     
<section class="tile tile-simple tbox">
<!-- tile widget -->
<div class="tile-widget bg-amethyst text-center p-30 tcol">
<i class="fa fa-cog fa-3x"></i>
</div>
<!-- /tile widget -->
<!-- tile body -->
<div class="tile-body text-center tcol">
<a href="<?php echo site_url('back_office/restaurant_settings'); ?>" class="text-muted system-keys m-0">Restaurant <br /> Settings</a>
</div>
<!-- /tile body -->
</section>                          
</div>

<div class="col-md-3">
   <section class="tile tile-simple tbox">
<!-- tile widget -->
<div class="tile-widget bg-dutch text-center p-30 tcol">
<i class="fa fa-dollar fa-3x"></i>
</div>
<!-- /tile widget -->
<!-- tile body -->
<div class="tile-body text-center tcol">
<a href="<?php echo site_url('back_office/currency'); ?>" class="text-muted system-keys m-0">Currency</a>
</div>
<!-- /tile body -->
</section>     
</div>

</div>
        
</div>
<?php
$this->session->unset_userdata("success_message"); 
$this->session->unset_userdata("error_message"); 
?>                    