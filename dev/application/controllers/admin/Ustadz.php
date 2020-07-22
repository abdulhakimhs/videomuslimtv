<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ustadz extends MY_Controller {

	public function index()
	{
		$data['title']		= 'Video Muslim TV';
		$data['subtitle']	= 'Ustadz';
		$this->load->view('admin/template',[
			'content' => $this->load->view('admin/ustadz/data',$data,true)
		]);
	}

	public function ajax_list()
    {
        $list = $this->ustadz_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $ust) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $ust->nama;
 
            //add html for action
            $row[] = '<a class="btn btn-xs btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$ust->id."'".')"><i class="fa fa-edit"></i> Ubah</a>
                  <a class="btn btn-xs btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$ust->id."'".')"><i class="fa fa-trash"></i> Hapus</a>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ustadz_model->count_all(),
                        "recordsFiltered" => $this->ustadz_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->ustadz_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
    	$this->_validate();
        $data = array(
                'nama' => $this->input->post('nama')
            );
        $insert = $this->ustadz_model->save($data);
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
                'nama' => $this->input->post('nama')
            );
        $this->ustadz_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(
            array(
                "status"    => TRUE,
                "pesan"     => '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Well done!</b> Data Berhasil Diubah! </div>'
            )
        );
    }
 
    public function ajax_delete($id)
    {
        $this->ustadz_model->delete_by_id($id);
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
 
        if($this->input->post('nama') == '')
        {
            $data['inputerror'][] = 'nama';
            $data['error_string'][] = 'Nama Ustadz harus diisi.';
            $data['status'] = FALSE;
        }
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
}
