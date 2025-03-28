<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Texteditor extends MY_Controller {

	public function index()
	{
		//echo 'testt';exit;
		$this->load->view('test/index');
	}
	public function upload()
	{
		$file1= $this->fct->uploadImage_texteditor("upload","images");
		if($file1 != '') {
			$data['created_date'] = date('Y-m-d h:i:s');
			$data['title'] = 'Uploaded from text editor';
			$data['image'] = $file1;
			$this->db->insert('images',$data);
			echo 'URL: '.base_url().'uploads/images/'.$file1;
		}
	}
	
	public function upload_froalaEditor($inputname = 'imageinput')
	{
		$path = 'images';
		if($inputname == 'fileinput')
		$path = 'files';
		
		if($inputname == 'imageinput')
		$file1= $this->upload_file_from_texteditor($inputname,$path);
		else
		$file1= $this->upload_file_from_texteditor($inputname,$path);
		
		if($file1 != '') {
			/*$data['created_date'] = date('Y-m-d h:i:s');
			$data['title'] = 'Image uploaded from text editor';
			$data['image'] = $file1;
			$this->db->insert('images',$data);*/
			$return = array('link'=>base_url().'uploads/images/'.$file1);
			header('Content-Type: application/json');
			echo json_encode($return);
		}
		else {exit('ERROR');}
	}
	
	
	
	function upload_file_from_texteditor($inputname,$path,$dir = 'uploads')
	{
		$config = array();
		$config['allowed_types'] = 'jpg|jpeg|png|gif|txt|pdf|docx|doc|csv|avi|flv|wmv|mpeg|mp3|mp4';
		$config['upload_path'] ='./'.$dir.'/'.$path;
		$config['max_size'] = '4096';
		//echo $config['upload_path'];exit;
		$this->load->library('upload');
		$this->upload->initialize($config);
		
		if(!$this->upload->do_upload($inputname,true)){
			exit( $this->upload_err = $this->upload->display_errors() );
		}
		else{
			$path = $this->upload->data();
			return  $path['file_name'];
		}
	}
	
	function load_images()
	{
		$files1 = scandir('./uploads/images/');
		array_shift($files1);
		array_shift($files1);
		$arr = array();
		if(!empty($files1)) {
			$i=0;
			foreach($files1 as $file) {
				$arr[$i]['url'] = base_url().'uploads/images/'.$file;
				$arr[$i]['thumb'] = base_url().'uploads/images/'.$file;
				$arr[$i]['tag'] = $file;
				$i++;
			}
		}
		header('Content-Type: application/json');
		echo json_encode($arr);
	}
	
	function delete_image()
	{
		$img = $this->input->post('src');
		$img = str_replace(base_url(),'./',$img);
		if(file_exists($img))
		unlink($img);
	}
	/* {"resourceType":"Images","currentFolder":{"path":"\/","acl":1023,"url":"\/ckfinder\/userfiles\/images\/"},"fileName":"test-img(2).jpg","uploaded":1,"error":{"number":201,"message":"A file with the same name already exists. The uploaded file was renamed to \u0022test-img(2).jpg\u0022."}}*/
}