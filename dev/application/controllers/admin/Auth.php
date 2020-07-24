<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

	public function __construct()
	{
 		parent::__construct();
	}

	public function login()
	{
		$this->load->view('admin/login');
	}

	public function getlogin()
	{

		$post = $this->input->post(NULL, TRUE);

		$cekemail = $this->db->query("SELECT email FROM pengguna where email='".$post['email']."'")->num_rows();
		$cekea 	  = $this->db->query("SELECT email FROM pengguna where email='".$post['email']."' AND role = 'admin'")->num_rows();
		if ($cekemail > 0) {
			if ($cekea > 0) {
				$user_detail = $this->db->get_where('pengguna', array('email' => $post['email'], 'role' => 'admin'), 1, NULL)->row();

				if (@$user_detail->password == crypt($post['password'], @$user_detail->password)) {
					
					$login_data = array(
							'id' 	    => $user_detail->id,
							'nama' 	    => $user_detail->nama,
					        'email'  	=> $post['email'],			        
					        'logged_in' => TRUE,
					        'role' 		=> $user_detail->role,
					);

					$this->session->set_userdata($login_data);
					redirect(base_url("admin/welcome"));	
				}
				else{
					
					$this->session->set_flashdata('info','<div class="alert alert-danger" role="alert"><strong>Maaf!</strong> kombinasi email dan password anda tidak tepat.</div>');
					redirect('admin');
				}
			}
			else{
				$this->session->set_flashdata('info','<div class="alert alert-danger" role="alert"><strong>Akses Ditolak!</strong> Anda tidak memiliki akses sebagai admin!</div>');
				redirect('admin');
			}
		}
		else{
			$this->session->set_flashdata('info','<div class="alert alert-danger" role="alert"><strong>Maaf!</strong> Email '.$post['email'].' tidak terdaftar.</div>');
			redirect('admin');
		}
	}

	public function logout()
	{
        $this->session->sess_destroy();
        redirect(base_url('admin'));
    }

	public function changePassword()
	{
		$data['title']		= 'Pengaturan';
		$data['subtitle']	= 'Ubah Password';
		if (isset($_POST['submit'])) {
			$this->form_validation->set_rules('oldpass', 'Old Password', 'required');
			$this->form_validation->set_rules('newpass', 'New Password', 'required');
			$this->form_validation->set_rules('repass', 'Re Password', 'required|matches[newpass]');
			if($this->form_validation->run() == FALSE){
				redirect('admin/changePassword');
			}
			else{
				$users_id		= $this->input->post('users_id');
				$old 			= $this->input->post('oldpass');
				$new 			= $this->input->post('newpass');

				$user_detail = $this->db->get_where('pengguna', array('id' => $users_id), 1, NULL)->row();
				if (@$user_detail->password == crypt($old, @$user_detail->password)) {
					$object = array(
						'password' 	 => bCrypt($new,12)
					);
					$this->db->where('id', $users_id);
					$this->db->update('pengguna', $object);
					$this->session->set_flashdata('info','<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Password Berhasil Diubah!</div>');
					redirect('admin/changePassword');
				}
				else{
					$this->session->set_flashdata('info','<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Password lama tidak sesuai!</div>');
					redirect('admin/changePassword');
				}
			}
		}
		$this->load->view('admin/template',[
			'content' => $this->load->view('admin/change_pass',$data,true)
		]);
	}

}

/* End of file admin/Auth.php */
/* Location: ./application/controllers/admin/Auth.php */