<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pesanan_produk extends CI_Controller
{

    public function simpan()
    {

        $data_pesanan = array();
        $keranjang = $this->cart->contents();

        foreach ($keranjang as $k) {
            $data_pesanan[] = array(
                'id_produk' => $k['id'],
                'nama_produk' => $k['name'],
                'jumlah' => $k['qty'],
                'harga' => $k['price'],
                'sub_total' => $k['subtotal'],
                'total' => $this->cart->total(),
                'nama_pelanggan' => $this->input->post('nama_pelanggan'),

            );
        }

        $this->db->insert_batch('pesanan_produk', $data_pesanan);
        $this->cart->destroy();
        $this->session->sess_destroy();
        redirect('pesanan_produk/tampilkan_pesanan?nama_pelanggan=' . $this->session->userdata('pelanggan')['nama']);


        // $this->db->select('*')->from('pesanan_produk');
        // $this->db->where('nama_pelanggan', $this->session->userdata('pelanggan')['nama']);
        // $pesanan_produk = $this->db->get()->result_array();

        // $data = array(
        //     'judul_halaman' => 'Pesanan Produk',
        //     'title' => 'Data Pesanan Produk',
        //     'pesanan_produk' => $pesanan_produk,

        // );
        // $this->load->view('cetak_pesanan',$data);
    }
    public function tampilkan_pesanan()
    {
        $nama_pelanggan = $this->input->get('nama_pelanggan');

        $this->db->select('*')->from('pesanan_produk');
        $this->db->where('nama_pelanggan', $nama_pelanggan);
        $pesanan_produk = $this->db->get()->result_array();

        $data = array(
            'judul_halaman' => 'Pesanan Produk',
            'title' => 'Data Pesanan Produk',
            'pesanan_produk' => $pesanan_produk,
        );

        $this->load->view('cetak_pesanan', $data);
    }
}
