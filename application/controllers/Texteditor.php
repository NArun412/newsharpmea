<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Texteditor extends CI_Controller {

	public function index()
	{
		//echo 'testt';exit;
		$this->load->view('test/index');
	}
	public function upload()
	{
		$file1= $this->fct->uploadImage_texteditor("upload");
		if($file1 != '') {
			$data['created_date'] = date('Y-m-d h:i:s');
			$data['title'] = 'Uploaded from text editor';
			$data['image'] = $file1;
			$this->db->insert('images',$data);
			echo 'URL: '.base_url().'uploads/images/'.$file1;
		}
	}
}