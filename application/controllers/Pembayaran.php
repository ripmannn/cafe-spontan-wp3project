<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'libraries/midtrans-php-master/Midtrans.php';

class Pembayaran extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        // Menambahkan header CORS
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type');
    }

    public function midtrans()
    {

        // Load pengaturan dari config
        $this->load->config('midtrans');

        // Set konfigurasi Midtrans
        \Midtrans\Config::$serverKey = $this->config->item('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$clientKey = $this->config->item('MIDTRANS_CLIENT_KEY');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Ambil data keranjang atau total dari session
        $total = $this->cart->total();


        date_default_timezone_set("Asia/Jakarta");
		$tanggal = date('Y-m');
		$this->db->from('penjualan');
		$this->db->where("DATE_FORMAT(tanggal,'%Y-%m')", $tanggal);

		$nota = date('ymd') . rand(100, 999);

        // Ambil data pelanggan
        $nama = $this->session->userdata('pelanggan')['nama'];
        $telp = $this->session->userdata('pelanggan')['telp'];

        // Ambil data keranjang
        $cart_items = $this->cart->contents();

        // Menyusun item untuk transaksi Midtrans
        $items = array();
        foreach ($cart_items as $k) {
            $items[] = array(
                'id' => $k['id'],
                'name' => $k['name'],
                'quantity' => $k['qty'],
                'price' => $k['price'],
            );
        }

        // Parameter untuk transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $nota,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $nama,
                'phone' => $telp,
            ],
            'item_details' => $items,

        ];

        try {
            // Membuat snap token
            $snapToken = \Midtrans\Snap::getSnapToken($params);


            // $items = [];
            // foreach ($this->cart->contents() as $k) {
            //     $items[] = [
            //         'id_produk' => $k['id'],
            //         'nama_produk' => $k['name'],
            //         'jumlah' => $k['qty'],
            //         'harga' => $k['price'],
            //         'sub_total' => $k['subtotal'],
            //         'total' => $total,
            //         'nama_pelanggan' => $this->session->userdata('pelanggan')['nama'],
            //         'id_pelanggan' => $this->session->userdata('pelanggan')['id_pelanggan'],
            //         'token' => $snapToken,
            //     ];
            // }



            // Mengirimkan snap_token ke frontend sebagai JSON
            echo json_encode(['snap_token' => $snapToken]);

        } catch (Exception $e) {
            // Tangani error dan kirim response JSON error
            echo json_encode(['error' => $e->getMessage()]);
        }

        $_SESSION['order_id'] = $params['transaction_details']['order_id'];
        
    }


    public function simpan_pesanan()
    {
        $order_id = $_SESSION['order_id'];
        // Cek jika data pesanan ada
        $items = $this->input->post('items');
        if (empty($items)) {
            echo json_encode(['status' => 'error', 'message' => 'Data pesanan tidak ditemukan']);
            return;
        }

        // Simpan pesanan ke database
        if ($this->db->insert_batch('pesanan_produk', $items)) {
            echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil disimpan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan pesanan']);
        }
        date_default_timezone_set("Asia/Jakarta");
        $penjualan_data = [ 
            'kode_penjualan' => $order_id,
            'tanggal' => date('Y-m-d'),
            'total_harga' => $this->cart->total(),
            'id_pelanggan' => $this->session->userdata('pelanggan')['id_pelanggan'],
        ];

        $this->db->insert('penjualan', $penjualan_data);

        foreach($items as $item) {
            $detail_penjualan[] = [
                'kode_penjualan' => $order_id,
                'id_produk' => $item['id_produk'],
                'jumlah' => $item['jumlah'],
                'sub_total' => $item['harga'] * $item['jumlah'],
            ];
           
        }

        $this->db->insert_batch('detail_penjualan', $detail_penjualan);  
                  
    }


    public function clear_cart()
    {
        // Menghapus semua item dalam cart
        $this->cart->destroy();

        // Menghapus sesi
        $this->session->sess_destroy();

        // Menanggapi dengan status sukses
        echo json_encode(['status' => 'success']);
    }

    public function clear_button(){
        $this->cart->destroy();
        echo json_encode(['status' => 'success']);
    }

    


}
?>