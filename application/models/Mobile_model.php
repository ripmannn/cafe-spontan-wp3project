<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;

class Mobile_model extends CI_model
{

    private $_client;

    public function __construct()
    {
        $this->_client = new Client([
            'base_uri' => 'https://api.codeafive.com/v1/',
        ]);
    }


    public function getAllPesanan()
    {
        $response = $this->_client->request('GET', 'transaction');

        $result = json_decode($response->getBody()->getContents(), true);

        // Mengambil data dan menyaring berdasarkan nama untuk menghindari duplikat
        $uniquePesanan = [];
        foreach ($result['data'] as $item) {
            if (!isset($uniquePesanan[$item['name']])) {
                $uniquePesanan[$item['name']] = $item;
            }
        }

        // Mengubah array menjadi satu dimensi
        $uniquePesanan = array_values($uniquePesanan); // Mengembalikan array tanpa kunci

        // Mengurutkan berdasarkan ID jika ID lebih besar menunjukkan data yang lebih baru
        usort($uniquePesanan, function ($a, $b) {
            return $b['transaction_id'] - $a['transaction_id'];  // Asumsi 'id' lebih besar berarti lebih baru
        });

        return $uniquePesanan;
    }


    public function getTransactionById($id)
    {
        // $response = $this->_client->request('GET', "transaction/{$id}");
        $response = $this->_client->request('GET', 'transaction/' . $id);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result['data']; // Mengembalikan data transaksi
    }



    public function deleteTransactionById($id)
    {
        // $response = $this->_client->request('DELETE', "transaction/{$id}");
        $response = $this->_client->request('DELETE', 'transaction/' . $id);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result; // Mengembalikan hasil dari response
    }
}