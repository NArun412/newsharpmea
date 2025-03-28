
<?php
if(!isset($id)){

$info["title"] = "";
$info["status"] = "";
}
echo render_title(set_value("title",$info["title"]));

//echo render_status(set_value("status",$info["status"]));
