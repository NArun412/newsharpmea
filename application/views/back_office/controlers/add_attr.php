<div class="page page-forms-common">
  <div class="pageheader">
    <h2><?php echo $title; ?> <span></span></h2>
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li> <a href="<?php echo site_url('back_office/home/dashboard'); ?>"><i class="fa fa-home"></i> Dashboard</a> </li>
        <li> <a href="<?php echo site_url('back_office/control');?>">Manage Content Types</a> </li>
        <li> <a href="<?php echo site_url('back_office/control/manage/'.$con_type["id_content"]);?>">Attributes List</a> </li>
        <li> <?php echo $con_type["name"]; ?> </li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <?php
$attributes = array('class' => 'middle-forms');
echo form_open_multipart('back_office/control/submit_attr', $attributes); 
?>
      <section class="tile">
        <div class="tile-header dvd dvd-btm">
          <h1 class="custom-font text-cyan"><strong></strong> Please complete the form below. Mandatory fields marked <em>*</em></h1>
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
          <?php
if(isset($id)){
	echo '<input type="hidden" name="id" value="'.$id.'" />';
} else {	
$info=array('name'=>'','type'=>'', 'length' => '', 'thumb_val' => '','display'=>'','foreign_key' => '', 'hint' => '', 'translated' => 0,'enable_editor'=>0,'enable_filtration'=>0,'additional_attributes'=>'','display_name'=>'','min_length'=>'','max_length'=>'','matches'=>'','required'=>0,'unique_val'=>0);
}

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
?>


          
<?php if(!isset($id)){ ?>
          <div class="form-group">
            <label class="field-title">Attribute Name<em>*</em>:</label>
            <input type="text" class="form-control" name="name" value="<?= set_value('name',$info["name"]); ?>" />
            <?= form_error('name','<span class="text-lightred">','</span>'); ?>
          </div>
          <div class="form-group">
            <label class="field-title">Type <em>*</em>:</label>
            <select name="type" id="type" class="form-control" >
              <option value=""> - select attribute type - </option>
              <? foreach($attr_type as $val){ ?>
              <option value="<?= $val["id_type"]; ?>" <? if(set_value('type',$info["type"]) == $val["id_type"] ) echo 'selected="selected"'; ?>>
              <?= $val["name"]; ?>
              </option>
              <? } ?>
            </select>
            <?= form_error('type','<span class="text-lightred">','</span>'); ?>
          </div>
          <div class="form-group opt-type-hide opt-type-2 opt-type-8 opt-type-9 opt-type-10 opt-type-11">
            <div class="text_attr">
              <label class="field-title">Length <em>*</em>:</label>
              <input type="text" class="form-control" name="length" value="<?= set_value('length',$info["length"]); ?>" />
              <?= form_error('length','<span class="text-lightred">','</span>'); ?>
            </div>
          </div>
          <div class="form-group opt-type-hide opt-type-4">
            <div class="image_attr" >
              <label class="field-title">Thumbnails:</label>
              <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
                <input type="checkbox"  name="thumbs"  id="thumbs" value="1" />
                <i></i> Active </label>
            </div>
          </div>
          <div class="form-group opt-type-hide opt-type-4">
            <div class="thumb_attr">
              <label class="field-title">Thumbnails Values <em>*</em>:</label>
              <input type="text" class="form-control" name="thumb_val" value="<?= set_value('thumb_val',$info["thumb_val"]); ?>" />
              <?= form_error('thumb_val','<span class="text-lightred">','</span>'); ?>
              <br clear="all"  />
              <input type="radio" name="resize_status" value="0"  checked="checked"  />
              &nbsp;Crop&nbsp;&nbsp;
              <input type="radio" name="resize_status" value="1"  />
              &nbsp;Resize <br clear="all"  />
            </div>
          </div>
          <div class="form-group opt-type-hide opt-type-7 opt-type-12">
            <div class="foreign_attr">
              <label class="field-title">Content Type <em>*</em>:</label>
              <?
$q="SELECT * FROM `content_type` WHERE deleted = 0 AND id_content NOT IN ( SELECT  foreign_key FROM `content_type_attr` WHERE id_content='".$con_type["id_content"]."' AND deleted =0 ) ORDER BY sort_order";
$query=$this->db->query($q);
$foreign_content = $query->result_array();
?>
              <select name="foreign_key" class="form-control" >
                <option value="">- choose content type -</option>
                <? foreach($foreign_content as $val){ ?>
                <option value="<?= $val["id_content"]; ?>" <? if($info["foreign_key"] == $val["id_content"]) echo 'selected="selected"'; ?> >
                <?= $val["name"]; ?>
                </option>
                <? } ?>
              </select>
              <?= form_error('foreign_key','<span class="text-error">','</span>'); ?>
            </div>
          </div><?php } else {?>
          <h3> <?= $info["name"]; ?></h3>
          <?php }?>
          
          <div class="form-group">
            <label class="field-title">Attribute Display Name<em>*</em>:</label>
            <input type="text" class="form-control" name="display_name" value="<?= set_value('display_name',$info["display_name"]); ?>" />
            <?= form_error('display_name','<span class="text-lightred">','</span>'); ?>
          </div>
          
          
          <div class="form-group opt-type-hide opt-type-3">
            <div class="editor_attr">
              <label class="field-title">Enable Editor:</label>
              <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
                <input type="checkbox"  name="enable_editor"  value="1" <? if($info["enable_editor"]==1) echo 'checked="checked"'; ?>  />
                <i></i> Yes </label>
            </div>
          </div>
          <div class="form-group">
            <label class="field-title">Hint:</label>
            <input type="text" name="hint" value="<?= $info["hint"]; ?>" class="form-control" />
          </div>
          <div class="form-group">
            <?php
if(multi_languages()){ ?>
            <label class="field-title">Translated:</label>
            <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
              <input type="checkbox"  name="translated"  value="1" <? if($info["translated"]==1) echo 'checked="checked"'; ?>  />
              <i></i> Yes </label>
            <?php } ?>
            <label class="field-title">Enable Filtration:</label>
            <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
              <input type="checkbox"  name="enable_filtration"  value="1" <? if($info["enable_filtration"]==1) echo 'checked="checked"'; ?>  />
              <i></i> Yes </label>
          </div>
          <div class="form-group">
            <label class="field-title">Additional Attributes <em>*</em>:</label>
            <input type="text" class="form-control" name="additional_attributes" value="<?= set_value('additional_attributes',$info["additional_attributes"]); ?>" />
            <?= form_error('additional_attributes','<span class="text-lightred">','</span>'); ?>
          </div>
          <div class="form-group">
            <label class="field-title">List in table:</label>
            <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
              <input type="checkbox"  name="display"  value="1" <? if($info["display"]==1) echo 'checked="checked"'; ?>  />
              <i></i> Display </label>
          </div>
          
          <hr />
          <h4>Validations</h4>
          <div class="form-group">
             <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
              <input type="checkbox"  name="required"  value="1" <? if($info["required"]==1) echo 'checked="checked"'; ?>  />
              <i></i> Required </label>
          </div>
          
          <div class="form-group">
             <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
              <input type="checkbox"  name="unique_val"  value="1" <? if($info["unique_val"]==1) echo 'checked="checked"'; ?>  />
              <i></i> Force Unique </label>
          </div>
          
          <div class="form-group">
            <label class="field-title">Max Length:</label>
            <input type="integer" class="form-control" name="max_length" value="<?= set_value('max_length',$info["max_length"]); ?>" />
            <?= form_error('max_length','<span class="text-lightred">','</span>'); ?>
          </div>
          
          
          <div class="form-group">
            <label class="field-title">Min Length:</label>
            <input type="integer" class="form-control" name="min_length" value="<?= set_value('min_length',$info["min_length"]); ?>" />
            <?= form_error('min_length','<span class="text-lightred">','</span>'); ?>
          </div>
          
          <div class="form-group">
            <label class="field-title">Matches:</label>
            <select class="form-control" name="matches">
            	<option value="">- select -</option>
                <?php foreach($fields as $field) {
					$fld = str_replace(' ','_',$field['name']);
					$cl = '';
					if($fld == $info['matches']) $cl = 'selected="selected"';
					?>
                <option value="<?php echo $fld; ?>"><?php echo $field['name']; ?></option>
                <?php }?>
            </select>
            <?= form_error('matches','<span class="text-lightred">','</span>'); ?>
          </div>
          
        
          
          <input type="hidden" name="id_content" value="<?= $con_type["id_content"]; ?>" />
          <hr class="line-dashed line-full"/>
          <div class="form-group">
            <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" ><i class="fa fa-arrow-right"></i> Submit</button>
            <a href="<?php echo site_url('back_office/control/manage/'.$con_type["id_content"]); ?>" class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c"><i class="fa fa-arrow-left"></i> Cancel</a> </div>
        </div>
      </section>
      <?php echo form_close(); ?> </div>
  </div>
</div>
<?php
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message"); 
?>
