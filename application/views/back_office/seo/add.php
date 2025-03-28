
<?php
if(!isset($id)){

$info["meta_title"] = "";
$info["meta_keywords"] = "";
$info["meta_description"] = "";
$info["url_route"] = "";
$info["url_index"] = "";
$info["title"] = "";
$info["status"] = "";
}
echo render_title(set_value("title",$info["title"]));
echo render_textbox("meta_title",set_value('meta_title',$info['meta_title']),92);
echo render_textbox("meta_keywords",set_value('meta_keywords',$info['meta_keywords']),93);
echo render_textbox("meta_description",set_value('meta_description',$info['meta_description']),94);
echo render_textbox("url_route",set_value('url_route',$info['url_route']),95);
echo render_textbox("url_index",set_value('url_index',$info['url_index']),96);

//echo render_status(set_value("status",$info["status"]));
