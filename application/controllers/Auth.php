<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
 

    public function index()
    {
        $data = array(
            'judul_halaman' => 'Cafe Spontan | Login',

        );
        $this->load->view('login', $data);
    }

    public function login()
    {
        
        
        $username = $this->input->post('username');
        $password = md5($this->input->post('password'));

        $this->db->from('user')->where('username', $username);
        $cek = $this->db->get()->row();

        if ($cek == null) {
            $this->session->set_flashdata('pesan', '
            <div class="alert alert-danger alert-dismissible" role="alert">Username Tidak Ditemukan !
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
            redirect('auth');
        } else if ($cek->password == $password) {
            $data = array(
                'id_user' => $cek->id_user,
                'username' => $cek->username,
                'nama' => $cek->nama,
                'level' => $cek->level,
                
            );
       
            $this->session->set_userdata($data);
            if ($cek->level == 'Admin') {
                $this->session->set_flashdata('pesan', '
                <div class="alert alert-primary alert-dismissible" role="alert">Selamat datang, Admin ' . $cek->nama . ' ! 
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>');
                
            } else if ($cek->level == 'Kasir') {
                $this->session->set_flashdata('pesan', '
                <div class="alert alert-primary alert-dismissible" role="alert">Selamat datang, Kasir ' . $cek->nama . ' !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>');
                
            } else if ($cek->level == 'Dapur') {
                $this->session->set_flashdata('pesan', '
                <div class="alert alert-primary alert-dismissible" role="alert">Selamat datang, Koki ' . $cek->nama . ' !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>');
            }
            
            redirect('home');
            
            } else {
                $this->session->set_flashdata('pesan', '
                <div class="alert alert-danger alert-dismissible" role="alert">Password Salah !!!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>');
                redirect('auth');
            }
            
    }



    public function logout()
    {
        $this->session->sess_destroy();
        // $this->session->unset_userdata('username');
        // $this->session->unset_userdata('level');
        $this->session->set_flashdata('pesan', '
            <div class="alert alert-success alert-dismissible" role="alert">Berhasil Log Out !!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        redirect('auth');
    }
}
