<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengguna extends MY_Controller {

	public function index()
	{
		$data['title']		= 'Video Muslim TV';
		$data['subtitle']	= 'Pengguna';
		$this->load->view('admin/template',[
			'content' => $this->load->view('admin/pengguna/data',$data,true)
		]);
	}

	public function ajax_list()
    {
        $list = $this->pengguna_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $pgn) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $pgn->nama;
            $row[] = $pgn->email;
            $row[] = $pgn->role == 'admin' ? '<span class="badge badge-danger">Admin</span>' : '<span class="badge badge-warning">User</span>' ;
 
            //add html for action
            $row[] = '<a class="btn btn-xs btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$pgn->id."'".')"><i class="fa fa-edit"></i> Ubah</a>
                  <a class="btn btn-xs btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$pgn->id."'".')"><i class="fa fa-trash"></i> Hapus</a>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->pengguna_model->count_all(),
                        "recordsFiltered" => $this->pengguna_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->pengguna_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        $this->_validate();
        $pass = 'vMt@'.date('His');
        $data = array(
                'nama'       => $this->input->post('nama'),
                'email'      => $this->input->post('email'),
                'username'   => strtolower(str_replace(" ", "_", $this->input->post('nama'))),
                'password'   => bCrypt($pass, 12),
                'role'       => $this->input->post('role'),
                'created_at' => date('Y-m-d H:i:s'),
            );
        $insert = $this->pengguna_model->save($data);
        echo json_encode(
            array(
                "status"    => TRUE,
                "pesan"     => '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Well done!</b> Data successfully added! <i> Password : '.$pass.' </i> </div>'
            )
        );
    }
 
    public function ajax_update()
    {
    	$this->_validate();
        $data = array(
                'nama'       => $this->input->post('nama'),
                'email'      => $this->input->post('email'),
                'username'   => strtolower(str_replace(" ", "_", $this->input->post('nama'))),
                'role'       => $this->input->post('role'),
            );
        $this->pengguna_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(
            array(
                "status"    => TRUE,
                "pesan"     => '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Well done!</b> Data successfully updated! </div>'
            )
        );
    }
 
    public function ajax_delete($id)
    {
        $this->pengguna_model->delete_by_id($id);
        echo json_encode(
            array(
                "status"    => TRUE,
                "pesan"     => '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Well done!</b> Data successfully deleted! </div>'
            )
        );
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $post = $this->input->post(NULL, TRUE);
        $cek  = $this->db->query("SELECT email FROM pengguna where email='".$post['email']."'")->num_rows();
 
        if($this->input->post('nama') == '')
        {
            $data['inputerror'][] = 'nama';
            $data['error_string'][] = 'Nama Pengguna harus diisi.';
            $data['status'] = FALSE;
        }

        if($this->input->post('email') == '')
        {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = 'Email Pengguna harus diisi.';
            $data['status'] = FALSE;
        }

        if ($cek > 0) {
        	$data['inputerror'][] = 'email';
            $data['error_string'][] = 'Email sudah digunakan, silahkan ganti dengan email yang lainnya.';
            $data['status'] = FALSE;
        }

        if($this->input->post('role') == '')
        {
            $data['inputerror'][] = 'role';
            $data['error_string'][] = 'Role Pengguna harus dipilih.';
            $data['status'] = FALSE;
        }
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
}
