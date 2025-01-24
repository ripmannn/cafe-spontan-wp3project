<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;

class Menu_model extends CI_model
{

    private $_client;

    public function __construct()
    {
        $this->_client = new Client([
            'base_uri' => 'https://api.temancateringg.store/api/',
            'auth' => ['admin', 'katering123'],

        ]);
    }

    public function getMenuCount()
    {
        // Make the GET request to the API
        $response = $this->_client->request('GET', 'dishesh');

        // Decode the JSON response into an associative array
        $result = json_decode($response->getBody()->getContents(), true);

        // Check if 'data' key exists and count the items in it
        if (isset($result['data']) && is_array($result['data'])) {
            return count($result['data']); // Return the count of items in the 'data' array
        }

        return 0; // Return 0 if 'data' is not available
    }

    public function getAllMenu()
    {

        $response = $this->_client->request('GET', 'dishesh');

        $result = json_decode($response->getBody()->getContents(), true);

        return $result['data'];

    }


    public function getMenuById($id)
    {

        $response = $this->_client->request('GET', 'dishesh', [
            'query' => [
                'd_id' => $id,
            ],
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result['data'][0];
    }

    public function ubahDataMenu($data)
    {
        // Pastikan Anda menggunakan parameter data yang sudah diterima dari controller
        $response = $this->_client->request('PUT', 'dishesh', [
            'form_params' => $data, // Mengirim data ke API
        ]);
        $result = json_decode($response->getBody()->getContents(), true);

        return $result;
    }




    public function deleteMenuById($id)
    {
        try {
            // Lakukan request DELETE ke API
            $response = $this->_client->request('DELETE', 'dishesh', [
                'form_params' => [
                    'd_id' => $id,
                ],
            ]);

            // Ambil isi dari response body
            $result = json_decode($response->getBody()->getContents(), true);

            // Cek apakah API mengembalikan status sukses
            if (isset($result['status']) && $result['status'] == 'success') {
                return true;  // Produk berhasil dihapus
            } else {
                return false;  // Produk gagal dihapus
            }
        } catch (\Exception $e) {
            // Tangani error jika ada masalah dengan request API
            return false;
        }
    }





}
