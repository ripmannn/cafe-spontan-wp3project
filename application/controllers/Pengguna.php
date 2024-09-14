<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengguna extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('level') != 'Admin') {
            redirect('home');
        }
    }
    public function index()
    {
        $this->db->select('*')->from('user');
        $this->db->order_by('nama', 'ASC');

        $user = $this->db->get()->result_array();

        $data = array(
            'judul_halaman' => 'Cafe Spontan | Data Pengguna',
            'title' => 'Data Pengguna',
            'user' => $user
        );
        $this->template->load('template', 'pengguna_index', $data);
    }

    public function simpan()
    {
        $this->db->from('user')->where('username', $this->input->post('username'));
        $cek = $this->db->get()->result_array();

        if ($cek == null) {
            $data = array(
                'username' => $this->input->post('username'),
                'password' => md5($this->input->post('password')),
                'nama' => $this->input->post('nama'),
                'level' => $this->input->post('level'),
            );
            $this->db->insert('user', $data);
            $this->session->set_flashdata('pesan', '
            <div class="alert alert-success alert-dismissible" role="alert">Pengguna Berhasil Ditambahkan
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        } else {
            $this->session->set_flashdata('pesan', '
            <div class="alert alert-danger alert-dismissible" role="alert">Username Sudah Digunakan !!!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        }


        redirect('pengguna');
    }

    public function hapus($id_user)
    {
        $where = array('id_user' => $id_user,);
        $this->db->delete('user', $where);
        $this->session->set_flashdata('pesan', '
            <div class="alert alert-danger alert-dismissible" role="alert">Pengguna Berhasil Dihapus !!!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        redirect('pengguna');
    }


    public function edit($id_user)
    {
        $this->db->from('user')->where('id_user', $id_user);

        $user = $this->db->get()->row();

        $data = array(
            'judul_halaman' => 'Cafe Spontan | Edit Data Pengguna',
            'title' => 'Pengguna edit',
            'user' => $user
        );
        $this->template->load('template', 'pengguna_edit', $data);
    }

    public function update()
    {
        $data = array(
            'nama' => $this->input->post('nama'),
            'level' => $this->input->post('level'),
        );
        $where = array('id_user' => $this->input->post('id_user'));
        $this->db->update('user', $data, $where);
        $this->session->set_flashdata('pesan', '
            <div class="alert alert-primary alert-dismissible" role="alert">Data Berhasil Diupdate !!!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        redirect('pengguna');
    }

    public function reset($id_user)
    {
        $data = array(
            'password' => md5('1234'),
        );
        $where = array('id_user' => $id_user);
        $this->db->update('user', $data, $where);
        $this->session->set_flashdata('pesan', '
            <div class="alert alert-primary alert-dismissible" role="alert">Password direset jadi 1234
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        redirect('pengguna');
    }
}
