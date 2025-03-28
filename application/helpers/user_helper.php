<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('user_info')){
function user_info($id_user = 0)
{
	$CI =& get_instance();
	if($id_user == 0)
	$user_id = user_id();
	else
	$user_id = $id_user;
	
	$user = $CI->db->where("id_users",$user_id)->get("users")->row_array();
	return $user;
}
}


if ( ! function_exists('user_photo')){
function user_photo($id_user = 0)
{
	$CI =& get_instance();
	$photo = 0;
	if($id_user == 0)
	$user_id = user_id();
	else
	$user_id = $id_user;
	if($user_id) {
		$user = $CI->db->where("id_users",$user_id)->get("users")->row_array();
		if(!empty($user) && isset($user['photo']) && $user['photo'] != '')
		$photo = $user['photo'];
	}
	return $photo;
}
}

if ( ! function_exists('user_name')){
function user_name($id_user = 0)
{
	$CI =& get_instance();
	$name = 0;
	if($id_user == 0)
	$user_id = user_id();
	else
	$user_id = $id_user;
	if($user_id) {
		$user = $CI->db->where("id_users",$user_id)->get("users")->row_array();
		if(!empty($user) && isset($user['name']) && $user['name'] != '')
		$name = $user['name'];
	}
	return $name;
}
}

if ( ! function_exists('user_can_publish')){
function user_can_publish($id_user = 0)
{
	$CI =& get_instance();
	$name = 0;
	if($id_user == 0)
	$user_id = user_id();
	else
	$user_id = $id_user;
	$user_role = $CI->user_role;
	if($user_role == 1 || $user_role == 2)
	return true;
	else
	return false;
}
}


if ( ! function_exists('user_can_approve')){
function user_can_approve($id_user = 0)
{
	$CI =& get_instance();
	$name = 0;
	if($id_user == 0)
	$user_id = user_id();
	else
	$user_id = $id_user;
	$user_role = $CI->user_role;
	if($user_role == 1 || $user_role == 2 || $user_role == 3)
	return true;
	else
	return false;
}
}


if ( ! function_exists('user_role')){
function user_role($id_user = 0)
{
	$CI =& get_instance();
	$role = 0;
	if($id_user == 0)
	$user_id = user_id();
	else
	$user_id = $id_user;
	if($user_id) {
		$user = $CI->db->where("id_users",$user_id)->get("users")->row_array();
		if(!empty($user) && isset($user['id_roles']) && $user['id_roles'] != 0)
		$role = $user['id_roles'];
		
	}
	return $role;
}
}

if ( ! function_exists('user_id')){
function user_id($id_user = 0)
{
	$CI =& get_instance();
	$id = FALSE;
	if($CI->session->userdata('uid') != "") $id = $CI->session->userdata('uid');
	return $id;
}
}

if ( ! function_exists('validate_user_login')){
function validate_user_login($email,$password){
	$CI =& get_instance();
	$query = $CI->db->select("id_users, salt, password")->where(array(
		"email"=>$email,
		"status"=>1
	))->get("users");
	if($query->num_rows() > 0) {
		$user = $query->row_array();
		$user_salt = $user['salt'];
		$user_pass = $user['password'];
		$password = secure_password($password,$user_salt);
		if($password == $user_pass) {
			return $user['id_users'];
		}
		else {
			return false;
		}
	}
	else {
		return false;
	}
}
}


if ( ! function_exists('validate_user')){
function validate_user(){
	$user_id = user_id();
	$CI =& get_instance();
	$corrid = $CI->session->userdata("corrid");
	$user = $CI->db->where(array(
		"id_users"=>$user_id,
		"login_token"=>$corrid
	))->get("users")->num_rows();
	if($user > 0)
	return true;
	return false;
}
}


if ( ! function_exists('set_user_session')){
function set_user_session($user_id){
	$CI =& get_instance();
	$user = $CI->db->where("id_users",$user_id)->get("users")->row_array();
	if(!empty($user)) {
		logout();
		//$salt = generate_salt();
		$login_token = create_login_token($user['id_users'],$user);
		$update['login_token'] = $login_token;
		//$update['salt'] = $salt;
		$CI->db->where('id_users', $user['id_users'])->update('users', $update);
		$CI->session->set_userdata('uid', $user['id_users']);
		$CI->session->set_userdata('corrid', $login_token);
		$CI->fct->insert_log("login");
		return $user;
	}
	return false;
}
}

if ( ! function_exists('logout')){
function logout(){
	$CI =& get_instance();
	$user_id = user_id();
	$CI->fct->insert_log("logout",'',0,$user_id);
	$CI->session->unset_userdata('uid');
	$CI->session->unset_userdata('corrid');
	return true;
}
}


if ( ! function_exists('destroy_user_session')){
function destroy_user_session(){
	$CI =& get_instance();
	$CI->session->sess_destroy();
	return true;
}
}


if ( ! function_exists('secure_password')){
function secure_password($pass,$salt){
	$algo = 'SPTRE_@#$@!';
	return hash('sha256',$salt.$pass.$algo);
}
}

if ( ! function_exists('create_login_token')){
function create_login_token($user_id,$user = array()){
	$CI =& get_instance();
	if(empty($user))
	$user = $CI->db->where("id_users",$user_id)->get("users")->row_array();
	//$token = hash('sha256',$user_id.$user['email'].time() );
	$token = crypt($user_id.$user['email'].time(),CRYPT_EXT_DES);
	return $token;
}
}

function generate_salt()
{
	//$size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
   // $iv = mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
	return crypt(random_str().time(),CRYPT_EXT_DES);
}

function random_str() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
/////////////////////////////////////////////////////////////////////////////////////////////////////
// used when checking permissions before rendering objects
if ( ! function_exists('has_permission')){
function has_permission($module,$method,$id = 0){
	//$CI =& get_instance();
	if(user_role() == 1)
	return true;
	else {
		$CI =& get_instance();
		$role_permissions = $CI->role_permissions;
		//print '<pre>'; print_r($role_permissions); exit;
		$user_permissions = $CI->user_permissions;
		//print '<pre>'; print_r($user_permissions); exit;
		if(!empty($user_permissions)) {
			if(isset($user_permissions[$module][$method]) && $user_permissions[$module][$method] == 1) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
		else {
			//print '11<pre>'; print_r($role_permissions); exit;
			//echo $module;exit;
			if(isset($role_permissions[$module][$method]) && $role_permissions[$module][$method] == 1) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}
}
}
// used when validating controllers methods
if ( ! function_exists('check_permission')){
function check_permission($module,$method,$id = 0){
	$valid = FALSE;
	if(user_role() == 1)
	$valid = TRUE;
	else {
		$CI =& get_instance();
		$role_permissions = $CI->role_permissions;
		$user_permissions = $CI->user_permissions;
		if(!empty($user_permissions)) {
			if(isset($user_permissions[$module][$method]) && $user_permissions[$module][$method] == 1) {
				$valid = TRUE;
			}
		}
		else {
			if(isset($role_permissions[$module][$method]) && $role_permissions[$module][$method] == 1) {
				$valid = TRUE;
			}
		}
	}
	if(!$valid) {
		redirect(admin_url("access_denied"));
	}
	return true;
}
}

if ( ! function_exists('role_has_permission')){
function role_has_permission($permissions,$module,$method){
	if(isset($permissions[$module][$method]) && $permissions[$module][$method] == 1) {
		return true;
	}
	else {
		return false;
	}
}
}

// used when updating roles permissions
if ( ! function_exists('role_has_permission')){
function role_has_permission($permissions,$module,$method){
	if(isset($permissions[$module][$method]) && $permissions[$module][$method] == 1) {
		return true;
	}
	else {
		return false;
	}
}
}
// used when updating users permissions
if ( ! function_exists('user_has_permission')){
function user_has_permission($permissions,$module,$method){
	if(empty($permissions)) return FALSE;
	else {
		if(isset($permissions[$module][$method]) && $permissions[$module][$method] == 1) {
			return true;
		}
		else {
			return false;
		}
	}
}
}
