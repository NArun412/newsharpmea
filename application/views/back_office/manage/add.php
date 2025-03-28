
<?php
if(!isset($id)){

$info["image_wide"] = "";
$info["image_small"] = "";
$info["promote_to_home_page"] = "";
$info["id_parent"] = 0;
$info["title"] = "";
$info["status"] = "";
}
echo render_title(set_value("title",$info["title"]));
if(!isset($id)) $fid = 0;
else
$fid = $id;
echo render_image("categories","image_wide",$fid,$info["image_wide"],23);
if(!isset($id)) $fid = 0;
else
$fid = $id;
echo render_image("categories","image_small",$fid,$info["image_small"],24);
echo render_active("promote_to_home_page",set_value('promote_to_home_page',$info['promote_to_home_page']),28);
echo render_foreign_parent("categories","id_parent",set_value('id_parent',$info['id_parent']),97);

//echo render_status(set_value("status",$info["status"]));
