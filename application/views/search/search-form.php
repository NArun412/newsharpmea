<?php /*<form id="searchform" action="<?php echo route_to("search"); ?>" method="get">*/ ?>
<?php echo form_open(route_to("search"),array(
'id'=>'searchform',
'method'=>'get'
)); 
echo form_hidden($this->security->get_csrf_token_name(),$this->security->get_csrf_hash());
?>
  <input type="text" name="keywords" autocomplete='off' class="search-input" data-url="<?php echo route_to("search/AutoComplete"); ?>" value="" placeholder="<?php echo lang("search"); ?>"/>
 <button class="search_submit"><i class="fa fa-search" aria-hidden="true"></i></button>
</form>
<div class="search-autocomplete-results ignore-click">
  <div class="searchAutoCompleteScroller"></div>
</div>
