<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pelanggan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('level') == null) {
            redirect('auth');
        }
    }

    public function index()
    {
        $this->db->select('*')->from('pelanggan');
        $this->db->order_by('id_pelanggan', 'ASC');

        $pelanggan = $this->db->get()->result_array();

        $data = array(
            'judul_halaman' => 'Cafe Spontan | Data Pelanggan',
            'title' => 'Data Pelanggan',
            'pelanggan' => $pelanggan
        );
        $this->template->load('template', 'pelanggan_index', $data);
    }

    public function simpan()
    {
        $data = array(
            'nama' => $this->input->post('nama'),
            'alamat' => $this->input->post('alamat'),
            'telp' => $this->input->post('telp'),

        );
        $this->db->insert('pelanggan', $data);
        $this->session->set_flashdata('pesan', '
            <div class="alert alert-success alert-dismissible" role="alert">Pelanggann Berhasil Ditambahkan
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');

        redirect('pelanggan');
    }

    public function hapus($id_pelanggan)
    {
        $where = array('id_pelanggan' => $id_pelanggan);
        $this->db->delete('pelanggan', $where);
        $this->session->set_flashdata('pesan', '
            <div class="alert alert-danger alert-dismissible" role="alert">Pelanggan Berhasil Dihapus !!!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        redirect('pelanggan');
    }

    public function edit($id_pelanggan)
    {
        $this->db->from('pelanggan')->where('id_pelanggan', $id_pelanggan);

        $pelanggan = $this->db->get()->row();

        $data = array(
            'judul_halaman' => 'resto.png | Edit Pelanggan',
            'title' => 'Pelanggan Edit',
            'pelanggan' => $pelanggan
        );
        $this->template->load('template', 'pelanggan_edit', $data);
    }

    public function update()
    {
        $data = array(
            'nama' => $this->input->post('nama'),
            'alamat' => $this->input->post('alamat'),
            'telp' => $this->input->post('telp'),

        );
        $where = array('id_pelanggan' => $this->input->post('id_pelanggan'));
        $this->db->update('pelanggan', $data, $where);
        $this->session->set_flashdata('pesan', '
            <div class="alert alert-primary alert-dismissible" role="alert">Pelanggan Berhasil Diupdate !!!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        redirect('pelanggan');
    }
}
