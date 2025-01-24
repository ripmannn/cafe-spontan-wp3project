<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pesanan_mobile extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mobile_model');
    }


    public function index()
    {
        $pesanan = $this->Mobile_model->getAllPesanan();

        $data = array(
            'judul_halaman' => 'Cafe Spontan | Data Pesanan Mobile',
            'title' => 'Pesanan Mobile',
            'pesanan' => $pesanan

        );

        $this->template->load('template', 'pesanan_mobile_index', $data);
    }
    public function detail($id)
    {
        $transaksi = $this->Mobile_model->getTransactionById($id);


        $data = array(
            'judul_halaman' => 'Cafe Spontan | Detail Pemesanan Mobile',
            'title' => 'Detail Pemesanan Mobile',
            'transaksi' => $transaksi

        );

        $this->template->load('template', 'pesanan_mobile_detail', $data);
    }

    public function delete($id)
    {
        // Call the delete method from the model
        if ($this->Mobile_model->deleteTransactionById($id)) {
            // If deletion is successful, set a success message and redirect
            $this->session->set_flashdata('pesan', ' <div class="alert alert-danger alert-dismissible" role="alert">pesanan berhasil di hapus !!!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');


            redirect('pesanan_mobile'); // Assuming 'menu' is the route for your menu listing page
        } else {
            // If deletion failed, set an error message and redirect
            $this->session->set_flashdata('error', 'Menu item could not be deleted');
            redirect('pesanan_mobile'); // Redirect back to the menu listing page
        }
    }


}
?>