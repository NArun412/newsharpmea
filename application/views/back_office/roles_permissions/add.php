
<?php
if(!isset($id)){

$info["id_roles"] = 0;
$info["title"] = "";
$info["status"] = "";
}
echo render_title(set_value("title",$info["title"]));
echo render_foreign("roles","id_roles",set_value('id_roles',$info['id_roles']),107);

//echo render_status(set_value("status",$info["status"]));
