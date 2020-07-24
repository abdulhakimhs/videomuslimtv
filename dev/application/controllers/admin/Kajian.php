<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kajian extends MY_Controller {

    public function __construct(){
		parent::__construct();
		if($this->session->userdata('logged_in') != TRUE){
            redirect(base_url("admin"));
        }
	}

	public function index()
	{
		$data['title']		= 'Video Muslim TV';
        $data['subtitle']	= 'Kajian';
        $data['ustadz']     = $this->kajian_model->ustadz_list()->result_array();
        $data['kategori']   = $this->kajian_model->kategori_list()->result_array();
		$this->load->view('admin/template',[
			'content' => $this->load->view('admin/kajian/data',$data,true)
		]);
	}

	public function ajax_list()
    {
        $list = $this->kajian_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $kajian) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $kajian->judul.'<br><span class="badge badge-success">'.$kajian->nama_ustadz.'</span> | <span class="badge badge-info">'.$kajian->nama_kategori.'</span> | <span class="badge badge-secondary"><i class="fa fa-user"></i> '.$kajian->post_by. ' <i class="fa fa-clock"></i> '.$kajian->created_at.' </span> ';
            $row[] = $kajian->views;
 
            //add html for action
            $row[] = '<a class="btn btn-xs btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$kajian->id."'".')"><i class="fa fa-edit"></i> Ubah</a>
                  <a class="btn btn-xs btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$kajian->id."'".')"><i class="fa fa-trash"></i> Hapus</a>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->kajian_model->count_all(),
                        "recordsFiltered" => $this->kajian_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->kajian_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
    	$this->_validate();
        $data = array(
                'judul'         => $this->input->post('judul'),
                'slug'          => seo_title($this->input->post('judul')),
                'deskripsi'     => $this->input->post('deskripsi'),
                'url'           => $this->input->post('url'),
                'kategori_id'   => $this->input->post('kategori_id'),
                'ustadz_id'     => $this->input->post('ustadz_id'),
                'tags'          => $this->input->post('tags'),
                'pengguna_id'   => $this->session->userdata('id'),
                'created_at'    => date('Y-m-d H:i:s')
            );
        $insert = $this->kajian_model->save($data);
        echo json_encode(
            array(
                "status"    => TRUE,
                "pesan"     => '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Well done!</b> Data Berhasil Ditambahkan! </div>'
            )
        );
    }
 
    public function ajax_update()
    {
    	$this->_validate();
        $data = array(
            'judul'         => $this->input->post('judul'),
            'slug'          => seo_title($this->input->post('judul')),
            'deskripsi'     => $this->input->post('deskripsi'),
            'url'           => $this->input->post('url'),
            'kategori_id'   => $this->input->post('kategori_id'),
            'ustadz_id'     => $this->input->post('ustadz_id'),
            'tags'          => $this->input->post('tags'),
            'pengguna_id'   => $this->session->userdata('id')
        );
        $this->kajian_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(
            array(
                "status"    => TRUE,
                "pesan"     => '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Well done!</b> Data Berhasil Diubah! </div>'
            )
        );
    }
 
    public function ajax_delete($id)
    {
        $this->kajian_model->delete_by_id($id);
        echo json_encode(
            array(
                "status"    => TRUE,
                "pesan"     => '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Well done!</b> Data Berhasil Dihapus! </div>'
            )
        );
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('judul') == '')
        {
            $data['inputerror'][] = 'judul';
            $data['error_string'][] = 'Judul kajian harus diisi.';
            $data['status'] = FALSE;
        }

        if($this->input->post('url') == '')
        {
            $data['inputerror'][] = 'url';
            $data['error_string'][] = 'URL kajian harus diisi.';
            $data['status'] = FALSE;
        }

        if($this->input->post('kategori_id') == '')
        {
            $data['inputerror'][] = 'kategori_id';
            $data['error_string'][] = 'Kategori kajian harus dipilih.';
            $data['status'] = FALSE;
        }

        if($this->input->post('ustadz_id') == '')
        {
            $data['inputerror'][] = 'ustadz_id';
            $data['error_string'][] = 'Ustadz harus dipilih.';
            $data['status'] = FALSE;
        }
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
}
