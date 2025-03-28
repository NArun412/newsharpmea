<?php
if(!isset($id)){
$info["name"] = "";
$info["email"] = "";
$info["phone"] = "";
$info["address"] = "";
$info["password"] = "";
$info["photo"] = "";
$info["id_roles"] = 0;
$info["title"] = "";
$info["status"] = "";
}
echo render_title(set_value("title",$info["title"]));
echo render_textbox("name",set_value('name',$info['name']),100);
echo render_textbox("email",set_value('email',$info['email']),101);
echo render_textbox("phone",set_value('phone',$info['phone']),102);
echo render_textarea("address",set_value('address',$info['address']),103);
echo render_password("password",set_value('password'),104);
if(!isset($id)) $fid = 0;
else
$fid = $id;
echo render_image("users","photo",$fid,$info["photo"],105);
echo render_foreign("roles","id_roles",set_value('id_roles',$info['id_roles']),106);
//echo render_status(set_value("status",$info["status"]));