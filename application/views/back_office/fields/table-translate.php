<?php $view_only =  view_only($content_type); ?>

   <div class="row"><div class="page-tables-datatables">
    <div class="col-md-12">
      <section class="tile">
        <div class="tile-header dvd dvd-btm">
          <h1 class="custom-font text-cyan"><strong></strong><?php echo $title; ?></h1>
        </div>
        <? 
$attributes = array('name' => 'list_form');
echo form_open_multipart(admin_url('manage/delete_all/'.$this->module.'/'.$this->id.'/'.$this->conmod.'/'.$this->conid), $attributes); 
$attr = $this->fct->get_module_fields($content_type,TRUE);
?>
        <div id="result"></div>
        <div class="tile-body p-0">
          <div class="table-responsive">
            <?php if($this->session->userdata("success_message")){ ?>
            <div class="alert alert-success"> <?php echo $this->session->userdata("success_message"); ?> </div>
            <?php } ?>
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  
                  <th>Language</th>
                  <th>Symbol</th>
                  <th width="150" class="no-sort">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(count($languages) > 0){
											$i=0;
											foreach($languages as $val){ 
											$i++;
							if($val['symbole'] != $this->default_lang) {
					$check_translate = $this->manage_m->get_translation($this->module,$this->id,$val['symbole']);
											?>
                <tr>
                  	<td><?php echo $val["title"]; ?></td>
                    <td><?php echo $val["symbole"]; ?></td>
                  	<td>
                    <?php if(empty($check_translate)) {?>
                    	<a href="<?php echo admin_url('manage/translate/'.$this->module.'/'.$this->id.'/'.$val["symbole"]); ?>">Translate</a><?php } else {?>
<?php if($this->users_m->check_user_record_access($this->module,$check_translate['id_'.$this->module],$this->user_info)) {?><a href="<?php echo admin_url('manage/translate/'.$this->module.'/'.$this->id.'/'.$val["symbole"].'/'.$check_translate['id_'.$this->module]); ?>">Edit</a><?php }?>
                        <?php }?>
                    </td>
                </tr>
                <?php }?>
                <? }  } else { ?>
                <tr>
                  <td colspan="4" style='text-align:center;'>No languages available . </td>
                </tr>
                <?  } ?>
              </tbody>
            </table>
          </div>
        </div>
        
		<?php
if(isset($this->id))
echo form_hidden('foreign_id', $this->id);
if(isset($this->conmod))
echo form_hidden('parent_table', $this->module);
		?>
		<? echo form_close();  ?> 
        </section>
    </div>
  </div>
</div>
<?php
$this->session->unset_userdata("success_message"); 
$this->session->unset_userdata("error_message"); 
?>
