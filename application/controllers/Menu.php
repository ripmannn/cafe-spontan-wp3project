<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model');  // Memuat model Menu_model
    }

    public function index()
    {
        $this->db->select('*')->from('produk');
        $this->db->order_by('new', 'ASC');
        $this->db->order_by('promo', 'DESC');
        $this->db->order_by('nama', 'ASC');
        $produk = $this->db->get()->result_array();

        // Ambil menu dari API
        $produk_api = $this->Menu_model->getAllMenu();

        foreach ($produk_api as &$menu) {
            // Memetakan data API ke dalam format yang sesuai
            $menu['id_produk'] = $menu['d_id'];  // Menyesuaikan ID menu dari API
            $menu['nama'] = $menu['name'];  // Menyesuaikan nama produk
            $menu['keterangan'] = $menu['about'];  // Deskripsi menu
            $menu['harga'] = (int) $menu['price'];  // Pastikan harga dalam format integer
            $menu['gambar'] = $menu['img'];

            // Gambar menu
            $menu['stok'] = 100;  // Menetapkan stok default (misalnya 10)
            $menu['jenis'] = 'katering';  // Menetapkan jenis produk menjadi 'katering'
            $menu['new'] = '';  // Menetapkan status baru, jika ada kriteria lain bisa disesuaikan
        }

        // Gabungkan produk lokal dengan menu dari API
        $produk = array_merge($produk, $produk_api);


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
        $id_pelanggan = $this->input->post('id_pelanggan');

        // Periksa apakah nama sudah ada dalam database
        $this->db->where('nama', $nama);
        $query = $this->db->get('pelanggan');

        if ($query->num_rows() > 0) {
            // Nama sudah ada dalam database, tampilkan pesan kesalahan
            $this->session->set_flashdata(
                'pesan',
                '<div class="alert alert-danger alert-dismissible d-flex text-center justify-content-center" role="alert">Nama      pelanggan sudah silahkan ganti nama lainnya!!!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
            );
            redirect('menu');
        } else {
            // Nama belum ada dalam database, lanjutkan untuk menyimpan data

            $data = array(
                'nama' => $nama,
                'telp' => $telp,
                'alamat' => $alamat,

            );

            // Simpan data ke database
            $this->db->insert('pelanggan', $data);

            $id_pelanggan = $this->db->insert_id();

            // Setelah berhasil disimpan, tetapkan data ke dalam sesi
            $session_data = array(
                'nama' => $nama,
                'id_pelanggan' => $id_pelanggan,
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
