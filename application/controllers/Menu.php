<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
    public function index()
    {
        $this->db->select('*')->from('produk');
        $this->db->order_by('new','ASC');
        $this->db->order_by('nama', 'ASC');


        $produk = $this->db->get()->result_array();

        $data = array(
            'judul_halaman' => 'Cafe Spontan | Menu',
            'title' => 'Data Produk Barang',
            'produk' => $produk,

        );
        $this->load->view('menu/index', $data);
    }

    public function simpanpelanggan()
    {
        $nama = $this->input->post('nama');
        $telp = $this->input->post('telp');
        $alamat = $this->input->post('alamat');

        // Periksa apakah nama sudah ada dalam database
        $this->db->where('nama', $nama);
        $query = $this->db->get('pelanggan');

        if ($query->num_rows() > 0) {
            // Nama sudah ada dalam database, tampilkan pesan kesalahan
            $this->session->set_flashdata(
                'pesan',
                '<div class="alert alert-danger alert-dismissible d-flex text-center justify-content-center" role="alert">Nama pelanggan sudah silahkan ganti nama lainnya!!!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>'
            );
            redirect('menu');   
        } else {
            // Nama belum ada dalam database, lanjutkan untuk menyimpan data
            $data = array(
                'nama' => $nama,
                'telp' => $telp,
                'alamat' => $alamat
            );

            // Simpan data ke database
            $this->db->insert('pelanggan', $data);

            // Setelah berhasil disimpan, tetapkan data ke dalam sesi
            $session_data = array(
                'nama' => $nama,
                'telp' => $telp,
                'alamat' => $alamat
            );
            $this->session->set_userdata('pelanggan', $session_data);

            // Set pesan flash
            $this->session->set_flashdata(
                'pesan',
                '<div class="alert alert-success alert-dismissible d-flex text-center justify-content-center" role="alert">Berhasil Daftar Silahkan Pesan Makanan Anda!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>'
            );

            // Redirect pengguna ke halaman 'menu'
            redirect('menu');
        }
    }
}
