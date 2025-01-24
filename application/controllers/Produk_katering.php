<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk_Katering extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model');
        if ($this->session->userdata('level') != 'Admin') {
            redirect('home');
        }
    }

    public function index()
    {

        $produk = $this->Menu_model->getAllMenu();

        $data = array(
            'judul_halaman' => 'Cafe Spontan | Menu Teman Katering',
            'title' => 'Data Menu Teman Katering',
            'produk' => $produk
        );
        $this->template->load('template', 'produk_katering_index', $data);
    }

    public function edit($id)
    {
        $produkid = $this->Menu_model->getMenuById($id);



        $data = array(
            'judul_halaman' => 'Cafe Spontan | Edit Produk Teman Katering',
            'title' => 'Edit Produk Teman Katering',
            'produkid' => $produkid
        );


        $this->template->load('template', 'produk_katering_edit', $data);
    }

    public function update()
    {
        // Pastikan Anda menerima data dari form
        $d_id = $this->input->post('id_produk');
        $name = $this->input->post('nama');
        $about = $this->input->post('keterangan');
        $price = $this->input->post('harga');
   

        // Memanggil model untuk melakukan update

        $update_data = [
            'd_id' => $d_id,
            'name' => $name,
            'about' => $about,
            'price' => $price,
  
        ];

        $result = $this->Menu_model->ubahDataMenu($update_data); // Mengirim data ke model

        if ($result) {
            // Set flash message jika sukses
            $this->session->set_flashdata('pesan', '
        <div class="alert alert-primary alert-dismissible" role="alert">Produk Berhasil Diupdate !!!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        } else {
            // Set flash message jika gagal
            $this->session->set_flashdata('pesan', '
        <div class="alert alert-danger alert-dismissible" role="alert">Produk Gagal Diupdate !!!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        }

        redirect('produk_katering');
    }


    public function hapus($id)
    {
        $result = $this->Menu_model->deleteMenuById($id);

    if ($result) {
        // Jika berhasil, tampilkan pesan sukses
        $this->session->set_flashdata('pesan', '
            <div class="alert alert-success alert-dismissible" role="alert">Produk Berhasil Dihapus !!!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
    } else {
        // Jika gagal, tampilkan pesan error
        $this->session->set_flashdata('pesan', '
            <div class="alert alert-warning alert-dismissible" role="alert">Gagal menghapus Produk !!!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
    }
    redirect('produk_katering');
    }




}
