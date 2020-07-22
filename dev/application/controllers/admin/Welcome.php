<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function index()
	{
		$data['title']		= 'Video Muslim TV';
		$data['subtitle']	= 'Dashboard';
		$this->load->view('admin/template',[
			'content' => $this->load->view('admin/dashboard',$data,true)
		]);
	}
}
