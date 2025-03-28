<?php
$sego =$this->uri->segment(4);
$gallery_status="0";
?>
<div class="page page-tables-datatables">
<div class="pageheader">
                        <h2><?= $title; ?><span></span></h2>
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
<div class="col-md-12">
	
<section class="tile">

<div class="tile-header dvd dvd-btm">
<h1 class="custom-font text-cyan"><strong></strong><?php echo $title; ?></h1>
</div>

<?php
$cond=array('id_content'=>$id_content);
$pos=$this->fct->getonecell('content_type','position',$cond);
$attributes = array('name' => 'list_form', 'id' => 'form_validate', 'class' => 'middle-forms');
echo form_open_multipart('back_office/install/finish/'.$id_content, $attributes); 
?>     
<div id="result"></div>                            
<div class="tile-body">
<?php if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success">
<?php $this->session->userdata("success_message"); ?>
</div>
<?php } ?>

<div class="form-group">
<label>Set Content Location</label>
<br clear="all"  />
<input type="radio" name="position" value="2" <? if($pos == 2) echo 'checked="checked"'; ?>  />&nbsp;Left Menu 
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="position" value="3" <? if($pos == 3) echo 'checked="checked"'; ?>   />&nbsp;Bottom Menu
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="position" value="0" <? if($pos == 0) echo 'checked="checked"'; ?>   />&nbsp;None
</div>
<input type="hidden" name="id_content" value="<?= $id_content; ?>" />

</div>
<div class="tile-footer">
<input type="hidden" name="id_content" value="<?= $id_content; ?>" />
    <ul class="pager m0">
    <li class="previous"><a href="<?=base_url();?>back_office/install/settings/<?=$id_content;?>" class="btn btn-rounded-10 btn-ef btn-ef-2 btn-ef-2-amethyst btn-ef-2a mb-10" >Prev</a></li>
    <li class="next">
    <a id="submit_validate" class="btn btn-rounded-10 btn-ef btn-ef-2 btn-ef-2-amethyst btn-ef-2a mb-10">Finish&nbsp;&raquo;</a>
    </li>
    </ul>
</div>
<? echo form_close();  ?>
</section>

</div>
</div>

</div>
<?php
$this->session->unset_userdata("success_message"); 
$this->session->unset_userdata("error_message"); 
?>