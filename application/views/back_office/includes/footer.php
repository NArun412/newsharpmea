<?
$seg_foot=$this->uri->segment(2);
$cond=array('id_settings'=>1);
$title_site=getonerow('settings',$cond); ?>
<div id="footer-wrap" class="hundred">
<div id="footer">
<?
$cond=array('position'=>3);
$menu_bottom=getAll_cond('content_type','sort_order',$cond);
if(count($menu_bottom) >0){
?>
<div id="footer-top">
<div class="pull-left hundred"> 
<ul class="nav nav-pills" style="margin-bottom:0">
<?
$k=0;
$user_id=$this->session->userdata('user_id');
foreach($menu_bottom as $val){
$k++;
if ($this->acl->has_permission(str_replace(" ","_",$val["name"]),'index')){
?>
<li <? if($seg_foot == str_replace(" ","_",$val["name"])){ echo 'class="active"'; } ?>>
<a href="<?= base_url(); ?>back_office/<?= str_replace(" ","_",$val["name"]); ?>"  style="text-transform:capitalize" >Manage <?= str_replace("_"," ",$val["name"]); ?>
</a></li>
<? } } ?></ul>
</div>
</div>
<? } ?>
<div class="clearFix"></div>
<!-- end of div#footer-top -->
<div id="footer-bottom" class="pull-left">
<div>&copy; <?= date("Y"); ?> Dow Group Admin Section . Powered By  
<a href="http://www.dowgroup.com" target="_blank">Dow Group</a></div>
</div>
</div>
</div>
<script src="<?= base_url(); ?>js/bootstrap.min.js"></script>
<script src="<?= base_url(); ?>js/bootstrap-datepicker.js"></script>