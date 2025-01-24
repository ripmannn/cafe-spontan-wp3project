<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pesanan_produk extends CI_Controller
{

    public function simpan()
    {

        $data_pesanan = array();
        $keranjang = $this->cart->contents();
        $id_user = 10;

        foreach ($keranjang as $k) {
            $data_pesanan[] = array(
                'id_produk' => $k['id'],
                'nama_produk' => $k['name'],
                'jumlah' => $k['qty'],
                'harga' => $k['price'],
                'sub_total' => $k['subtotal'],
                'total' => $this->cart->total(),
                'nama_pelanggan' => $this->input->post('nama_pelanggan'),
                'id_pelanggan' => $this->input->post('id_pelanggan'),

            );
        }
//         echo '<pre>';
// var_dump($data_pesanan);

// echo '</pre>';
// die;


        $this->db->insert_batch('pesanan_produk', $data_pesanan);
        
        $temp_data = array();
        foreach ($data_pesanan as $item) {
            $temp_data[] = array(
                'id_produk' => $item['id_produk'],
                'id_pelanggan' => $item['id_pelanggan'],
                'jumlah' => $item['jumlah'],
                'id_user' => $id_user,
            );
        }

        // Insert into temp table
        $this->db->insert_batch('temp', $temp_data);
        $this->cart->destroy();
        $this->session->sess_destroy();

        redirect('menu');
    }


    // redirect('pesanan_produk/tampilkan_pesanan?nama_pelanggan=' . $this->session->userdata('pelanggan')['nama']);
    // public function tampilkan_pesanan()
    // {
    //     $nama_pelanggan = $this->input->get('nama_pelanggan');

    //     $this->db->select('*')->from('pesanan_produk');
    //     $this->db->where('nama_pelanggan', $nama_pelanggan);
    //     $pesanan_produk = $this->db->get()->result_array();

    //     $data = array(
    //         'judul_halaman' => 'Pesanan Produk',
    //         'title' => 'Data Pesanan Produk',
    //         'pesanan_produk' => $pesanan_produk,
    //     );

    //     $this->load->view('cetak_pesanan', $data);
    // }

    public function index($nama_pelanggan = null)
    {

        // Memulai query
        $this->db->select('*')->from('pesanan_produk');

        // Jika nama pelanggan diberikan, filter berdasarkan nama pelanggan
        if ($nama_pelanggan) {
            $this->db->where('nama_pelanggan', $nama_pelanggan);
        }

        // Mengambil data dari database
        $pesanan_produk = $this->db->get()->result_array();


        // Siapkan data untuk view
        $data = array(
            'judul_halaman' => 'Cafe Spontan | Data Pengguna',
            'title' => 'Data Pesanan',
            'pesanan_produk' => $pesanan_produk,
        );
        $this->template->load('template', 'pesanan_produk_index', $data);
    }


    public function edit($nama_pelanggan)
    {
        // Fetch the customer ID based on the customer name
        $this->db->where('nama', $nama_pelanggan); // Adjust 'nama' if your field is named differently
        $customer = $this->db->get('pelanggan')->row_array(); // Assuming 'pelanggan' is your customer table

        $id_pelanggan = $customer['id_pelanggan']; // Assuming 'id_pelanggan' is the correct field name

        // Fetch the customer's orders using the customer ID
        $this->db->where('id_pelanggan', $id_pelanggan);
        $pesanan_produk = $this->db->get('pesanan_produk')->result_array();

        // Fetch all available products
        $available_products = $this->db->get('produk')->result_array();

        // Load your view with the necessary data
        $data = [
            'judul_halaman' => 'Cafe Spontan | Edit Pesanan',
            'title' => 'Edit Pesanan',
            'pesanan_produk' => $pesanan_produk,
            'available_products' => $available_products,
            'nama_pelanggan' => $nama_pelanggan,
            'id_pelanggan' => $id_pelanggan // Include the customer ID
        ];
        $this->template->load('template', 'pesanan_produk_edit', $data);
    }


    public function update()
    {
        $id_pelanggan = $this->input->post('id_pelanggan'); // Retrieve customer ID
        $nama_pelanggan = $this->input->post('nama_pelanggan');
        $nama_produk = $this->input->post('nama_produk');
        $id_produk = $this->input->post('id_produk');
        $jumlah = $this->input->post('jumlah');
        $harga = $this->input->post('harga');

        $new_product_id = $this->input->post('new_product_id');
        $new_product_quantity = $this->input->post('new_product_quantity');

        // Initialize total
        $total = 0;
        $data_to_insert = [];
        $data_to_update = [];

        // Fetch existing orders to compare
        $existing_orders = $this->db->where('id_pelanggan', $id_pelanggan)->get('pesanan_produk')->result_array(); // Use id_pelanggan
        $existing_ids = array_column($existing_orders, 'id_produk');

        // Check for delete request
        if ($this->input->post('delete') !== null) {
            $delete_index = $this->input->post('delete');
            $id_to_delete = $id_produk[$delete_index];
            // Perform the deletion
            $this->db->where('id_produk', $id_to_delete);
            $this->db->where('id_pelanggan', $id_pelanggan); // Use id_pelanggan
            $this->db->delete('pesanan_produk');
        }

        // Process existing products
        foreach ($nama_produk as $index => $produk) {
            if ($this->input->post('delete') !== null && $index == $this->input->post('delete')) {
                continue; // Skip the deleted product
            }

            $sub_total = $jumlah[$index] * $harga[$index];
            $total += $sub_total; // Accumulate total

            // Check if product already exists and needs to be updated
            if (in_array($id_produk[$index], $existing_ids)) {
                // Prepare update data if values have changed
                $data_to_update[] = [
                    'id_pelanggan' => $id_pelanggan, // Include customer ID
                    'id_produk' => $id_produk[$index],
                    'jumlah' => $jumlah[$index],
                    'harga' => $harga[$index],
                    'sub_total' => $sub_total
                ];
            } else {
                // New product, prepare for insertion
                $data_to_insert[] = [
                    'id_pelanggan' => $id_pelanggan, // Include customer ID
                    'nama_pelanggan' => $nama_pelanggan,
                    'nama_produk' => $produk,
                    'id_produk' => $id_produk[$index],
                    'jumlah' => $jumlah[$index],
                    'harga' => $harga[$index],
                    'sub_total' => $sub_total
                ];
            }
        }

        // Check if a new product is being added
        if (!empty($new_product_id) && !empty($new_product_quantity)) {
            $new_product_price = $this->db->where('id_produk', $new_product_id)->get('produk')->row()->harga;
            $sub_total = $new_product_quantity * $new_product_price;
            $total += $sub_total;

            $data_to_insert[] = [
                'id_pelanggan' => $id_pelanggan, // Include customer ID
                'nama_pelanggan' => $nama_pelanggan,
                'nama_produk' => $this->db->where('id_produk', $new_product_id)->get('produk')->row()->nama,
                'id_produk' => $new_product_id,
                'jumlah' => $new_product_quantity,
                'harga' => $new_product_price,
                'sub_total' => $sub_total
            ];
        }

        // Perform batch insert for new products
        if (!empty($data_to_insert)) {
            $this->db->insert_batch('pesanan_produk', $data_to_insert);
        }

        // Update existing products
        foreach ($data_to_update as $update) {
            $this->db->where('id_pelanggan', $update['id_pelanggan']); // Use id_pelanggan
            $this->db->where('id_produk', $update['id_produk']);
            $this->db->update('pesanan_produk', $update);
        }

        // Update the total in the pesanan_produk table
        $this->db->where('id_pelanggan', $id_pelanggan); // Use id_pelanggan
        $this->db->update('pesanan_produk', ['total' => $total]);

        // Redirect after updating
        redirect('pesanan_produk');
    }


    public function delete($nama_pelanggan)
    {
        // Hapus data berdasarkan nama pelanggan
        $this->db->where('nama_pelanggan', $nama_pelanggan);
        $this->db->delete('pesanan_produk');

        // Redirect setelah delete
        redirect('pesanan_produk'); // Ganti dengan nama controller yang sesuai
    }






}
