<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('logged_in') != TRUE){
            redirect(base_url("admin"));
        }
	}

	public function index()
	{
		$data['title']		= 'Video Muslim TV';
		$data['subtitle']	= 'Dashboard';
		$data['kategori']   = $this->db->query("SELECT * FROM kategori")->num_rows();
		$data['ustadz']     = $this->db->query("SELECT * FROM ustadz")->num_rows();
		$data['pengguna']   = $this->db->query("SELECT * FROM pengguna")->num_rows();
		$data['kajian']     = $this->db->query("SELECT * FROM kajian")->num_rows();
		$this->load->view('admin/template',[
			'content' => $this->load->view('admin/dashboard',$data,true)
		]);
	}
}
