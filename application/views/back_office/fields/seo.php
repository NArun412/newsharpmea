<?php
if(has_permission("seo","edit")) {
if(!isset($info['meta_title'])) $info['meta_title'] = '';
if(!isset($info['meta_description'])) $info['meta_description'] = '';
if(!isset($info['meta_keywords'])) $info['meta_keywords'] = '';
if(!isset($info['url_route'])) $info['url_route'] = '';
?>
<section class="tile">
  <div class="tile-header dvd dvd-btm">
    <h1 class="custom-font text-cyan"><strong>SEO</strong> Details</h1>
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
  <div class="tile-body">
    <div class="form-group">
      <?php 
echo form_label('<b>Page Title&nbsp;<small class="text-cyan">(Max 65 Characters)</small>:</b>', 'Page Title');
echo form_input(array("name" => "meta_title", "value" => set_value("meta_title",$info["meta_title"]),"class" =>"form-control" ));
echo form_error("meta_title","<span class='text-lightred'>","</span>");
?>
    </div>
    <div class="form-group">
      <?php 
/*echo form_label('<b>TITLE URL&nbsp;<em></em>:</b>', 'TITLE URL');
echo form_input(array("name" => "title_url", "value" => set_value("title_url",$info["title_url"]),"class" =>"form-control" ));
echo form_error("title_url","<span class='text-lightred'>","</span>");*/
?>
    </div>
    <div class="form-group">
      <?php
echo form_label('<b>META DESCRIPTION&nbsp;<small class="text-cyan">(Max 160 Characters)</small>:</b>', 'META DESCRIPTION');
echo form_textarea(array("name" => "meta_description", "value" => set_value("meta_description",$info["meta_description"]),"class" =>"form-control","rows" => 3, "cols" =>100));
echo form_error("meta_description","<span class=\'text-lightred\'>","</span>");
?>
    </div>
    <div class="form-group">
      <?php
echo form_label('<b>META KEYWORDS&nbsp;<small class="text-cyan">(Max 160 Characters)</small>:</b>', 'META KEYWORDS');
echo form_textarea(array("name" => "meta_keywords", "value" => set_value("meta_keywords",$info["meta_keywords"]),"class" =>"form-control","rows" => 3, "cols" =>100 ));
echo form_error("meta_keywords","<span class='text-lightred'>","</span>");
?>
    </div>
    
      <div class="form-group">
       <?php 
echo form_label('<b>URL Route:</b>', 'URL Route');
echo form_input(array("name" => "url_route", "value" => set_value("url_route",$info["url_route"]),"class" =>"form-control" ));
echo form_error("url_route","<span class='text-lightred'>","</span>");
?>
 </div>

    <hr class="line-dashed line-full"/>
    <div class="form-group">
      <?php if ($this->uri->segment(3) != "view" ){ ?>
      <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ><i class="fa fa-arrow-right"></i> Submit</button>
      <?php } ?>
    <!--  <a href="<?php //echo site_url('back_office/'.$content_type.'/'.$this->session->userdata("back_link")); ?>" class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c"> <i class="fa fa-arrow-left"></i> Cancel</a>--> </div>
  </div>
</section>
<?php }?>