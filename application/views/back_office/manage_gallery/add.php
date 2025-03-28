<div class="page page-forms-common">
<div class="pageheader">
                        <h2><?php echo $title; ?> <span></span></h2>
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo site_url('back_office/home/dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a>
                                </li>
                                <li>
                                    <?php echo $title1; ?>
                                </li>
                                <li>
                                    <?php echo $title; ?>
                                </li>
                            </ul> 
                        </div>
</div> 
<div class="row">                  
<div class="col-md-12">
<?php
$attributes = array('class' => 'middle-forms');
echo form_open_multipart('back_office/control/submit_photos', $attributes); 
?>
<section class="tile">
<div class="tile-header dvd dvd-btm">
    <h1 class="custom-font text-cyan"><strong></strong> Please complete the form below. Mandatory fields marked <em>*</em></h1>
    <ul class="controls">
        <li class="dropdown">

            <a role="button" tabindex="0" class="dropdown-toggle settings" data-toggle="dropdown">
                <i class="fa fa-cog"></i>
                <i class="fa fa-spinner fa-spin"></i>
            </a>

            <ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
                <li>
                    <a role="button" tabindex="0" class="tile-toggle">
                        <span class="minimize"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;&nbsp;Minimize</span>
                        <span class="expand"><i class="fa fa-angle-up"></i>&nbsp;&nbsp;&nbsp;Expand</span>
                    </a>
                </li>
                <li>
                    <a role="button" tabindex="0" class="tile-refresh">
                        <i class="fa fa-refresh"></i> Refresh
                    </a>
                </li>
                <li>
                    <a role="button" tabindex="0" class="tile-fullscreen">
                        <i class="fa fa-expand"></i> Fullscreen
                    </a>
                </li>
            </ul>

        </li>
    </ul>
</div>
<div class="tile-body">
<?php
if(isset($id)){
echo form_hidden('id', $id);
} else {}
if($this->session->userdata("success_message")){ 
echo '<div class="alert alert-success">';
echo $this->session->userdata("success_message");
echo '</div>';
}
if($this->session->userdata("error_message")){
echo '<div class="alert alert-error">';
echo $this->session->userdata("error_message"); 
echo '</div>';
}

if(isset($sizes) && !empty($sizes)) {
    echo '<div class="alert alert-error">';
    echo '<p>Uploaded images will be resized to:</p>';
    $sizes = explode(',',$sizes);
    if(!empty($sizes)) {
        foreach($sizes as $size) {
            list($w,$h) = explode('x',$size);
            echo '<p><b>Width:</b>'.$w.'px; <b>Height:</b>'.$h.'px</p>';
        }
    }
    echo '</div>';
}

for($i=0; $i <5; $i++){
?>
<div class="row">
<div class="col-sm-6">
<div class="form-group">
<label>Image</label>
<input type="file" class="filestyle"  name="image<?=$i; ?>" data-buttonText="Find file" data-iconName= "fa fa-inbox" />
</div>
</div>
<div class="col-sm-6">
<div class="form-group">
<label>Title</label> 
<input type="text" class="form-control"  name="title<?= $i; ?>"/>
</div>
</div>
</div>

<?php } ?>
<hr class="line-dashed line-full"/>
<div class="form-group">
<?php if ($this->uri->segment(3) != "view" ){ ?>
<input type="hidden" name="content_type" value="<?= $content_type; ?>"  />
<input type="hidden" name="id_gallery" value="<?= $id_gallery; ?>"  />
<button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ><i class="fa fa-arrow-right"></i> Submit</button>
<?php } ?>
<a href="<?php echo site_url('back_office/control/manage_gallery/'.$content_type.'/'.$id_gallery); ?>" class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c">
<i class="fa fa-arrow-left"></i> Cancel</a>
</div>
</div>
</section>
<!-- SEO Details -->

<?php echo form_close(); ?>
</div>
</div>
</div>
<?php 
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message"); 
?>
