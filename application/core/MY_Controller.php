<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Loader
 *
 * @author Simon Emms
 */
class MY_Controller extends CI_Controller {
     public function __construct()
 {
  	parent::__construct();
	$admin_dir = admin_dir();
	$class = $this->router->class;
	$this->load->model($admin_dir.'/fct');
	if($this->uri->segment(1) == $admin_dir) {
		if(!validate_user() && $class != 'login') {
			logout();
			redirect(admin_url("login"));
		}
	}
	else {
		$this->load->model('website_fct');
		$this->divisions_social = $this->website_fct->get_divisions_social();
	}
	$this->page_class = $class;
	$this->page_method = $this->router->method;
	
	$this->settings = $this->fct->get_settings();
	$this->user_id = user_id();
	$this->user_role = user_role();
	
	$this->user_info = user_info($this->user_id);

	
	
	if(validate_user()) {
		$this->role_permissions = $this->fct->get_role_permissions($this->user_role);
		$this->user_permissions = $this->fct->get_user_permissions($this->user_id);	
	}
 }
}