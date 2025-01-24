<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Snap extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $params = array('server_key' => 'SB-Mid-server-0Y-fNFkKwdEOVj9uZlY4TOuO', 'production' => false);
        $this->load->library('midtrans');
        $this->midtrans->config($params);
        $this->load->helper('url');
    }

    public function index()
    {
        $this->load->view('checkout_snap');
    }

    public function token()
    {
        $order_id = date('dmY') . rand(1000, 9999);

        // Required
        $transaction_details = array(
            'order_id' => $order_id,
            'gross_amount' => 94000, // no decimal allowed for creditcard
        );

        // Optional
        $item1_details = array(
            'id' => 'a1',
            'price' => 18000,
            'quantity' => 3,
            'name' => "Apple",
        );

        // Optional
        $item2_details = array(
            'id' => 'a2',
            'price' => 20000,
            'quantity' => 2,
            'name' => "Orange",
        );

        // Optional
        $item_details = array($item1_details, $item2_details);

        // Optional
        $billing_address = array(
            'first_name' => "Andri",
            'last_name' => "Litani",
            'address' => "Mangga 20",
            'city' => "Jakarta",
            'postal_code' => "16602",
            'phone' => "081122334455",
            'country_code' => 'IDN',
        );

        // Optional
        $shipping_address = array(
            'first_name' => "Obet",
            'last_name' => "Supriadi",
            'address' => "Manggis 90",
            'city' => "Jakarta",
            'postal_code' => "16601",
            'phone' => "08113366345",
            'country_code' => 'IDN',
        );

        // Optional
        $customer_details = array(
            'first_name' => "Andri",
            'last_name' => "Litani",
            'email' => "andri@litani.com",
            'phone' => "081122334455",
            'billing_address' => $billing_address,
            'shipping_address' => $shipping_address,
        );

        // Data yang akan dikirim untuk request redirect_url.
        $credit_card['secure'] = true;
        //ser save_card true to enable oneclick or 2click
        //$credit_card['save_card'] = true;

        $time = time();
        $custom_expiry = array(
            'start_time' => date("Y-m-d H:i:s O", $time),
            'unit' => 'minute',
            'duration' => 15,
        );

        $transaction_data = array(
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details,
            'credit_card' => $credit_card,
            'expiry' => $custom_expiry,
        );

        error_log(json_encode($transaction_data));
        $snapToken = $this->midtrans->getSnapToken($transaction_data);
        error_log($snapToken);
        echo $snapToken;
    }



    public function finish()
{
    $result = json_decode($this->input->post('result_data'));

    // Simpan data ke tabel midtrans
    $data = [
        'order_id' => $result->order_id,
        'transaction_id' => $result->transaction_id,
        'transaction_status' => $result->transaction_status,
        'gross_amount' => $result->gross_amount,
        'payment_type' => $result->payment_type,
        'transaction_time' => $result->transaction_time,
    ];

    // Insert data ke tabel midtrans
    $this->db->insert('midtrans', $data);

    // Mengirim data ke view
    $data['result'] = $result;
    $this->load->view('transaction_result', $data);
	
}


}
