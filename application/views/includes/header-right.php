<div class="languages_div" >
  <ul>
    <li class="relative web-view-only"> 
      <?php $this->load->view("search/search-form"); ?>
    </li>
    
<!--    <li class="web-view-only"> <span >UAE</span> </li>-->
    <li class="no-border">
      <div class="language">
        <?php
/*if($lang == "en"){
echo anchor($this->lang->switch_uri('en'),'EN', array('class' => 'lang active')); 
echo anchor($this->lang->switch_uri('ar'),'AR',array('class'=>'lang')); 
} else {
echo anchor($this->lang->switch_uri('en'),'EN', array('class' => 'lang')); 
echo anchor($this->lang->switch_uri('ar'),'AR',array('class'=>'lang active'));	
}*/

$cl_ar = '';
$cl_en = '';
if($lang == "ar") $cl_ar = 'active';
else
$cl_en = 'active';

if(isset($switch_links['en'])) $en_link = $switch_links['en'];
else $en_link = route_to_d($this->lang->switch_uri('en'),"",true);
if(isset($switch_links['ar'])) $ar_link = $switch_links['ar'];
else $ar_link = route_to_d($this->lang->switch_uri('ar'),"",true);

if($this->router->class == 'home' && $this->router->method == 'index') {
    $ar_link = base_url().'ar';
    $en_link = base_url().'en';
}

echo  '<a href="'.$en_link.'" class="lang en '.$cl_en.'">EN</a>';
echo  '<a href="'.$ar_link.'" class="lang ar '.$cl_ar.'">عربي</a>';
//echo  '<a href="'.base_url().'arabic" class="lang ar '.$cl_ar.'">عربي</a>';


?>
      </div>
    </li>
    <li class="responsive-search-icon"><a class="search_submit"><i class="fa fa-search" aria-hidden="true"></i></a></li>
  </ul>
</div>
