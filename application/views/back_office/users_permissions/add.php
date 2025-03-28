
<?php
if(!isset($id)){

$info["id_users"] = 0;
$info["title"] = "";
$info["status"] = "";
}
echo render_title(set_value("title",$info["title"]));
echo render_foreign("users","id_users",set_value('id_users',$info['id_users']),108);

//echo render_status(set_value("status",$info["status"]));
