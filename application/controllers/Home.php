<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct(); 
		$this->load->model('Menu_model');
		if ($this->session->userdata('level') == null) {
			redirect('auth');
		}
	}

	public function index()
	{
		date_default_timezone_set("Asia/Jakarta");
		$tanggal = date('Y-m-d');
		$this->db->select('sum(total_harga) as total');
		$this->db->from('penjualan')->where("DATE_FORMAT(tanggal,'%Y-%m-%d')", $tanggal);
		$hari_ini = $this->db->get()->row()->total;

		$this->db->from('penjualan')->where("DATE_FORMAT(tanggal,'%Y-%m-%d')", $tanggal);
		$transaksi = $this->db->count_all_results();

		date_default_timezone_set("Asia/Jakarta");
		$tanggal = date('Y-m');
		$this->db->select('sum(total_harga) as total');
		$this->db->from('penjualan')->where("DATE_FORMAT(tanggal,'%Y-%m')", $tanggal);
		$bulan_ini = $this->db->get()->row()->total;

		$produk = $this->db->from('produk')->count_all_results();
		$katering = $this->Menu_model->getMenuCount();

		if($hari_ini == null){$hari_ini = 0;}
		if($bulan_ini == null){$bulan_ini = 0;}
		if($transaksi == null){$transaksi = 0;}

		$data = array(
			'judul_halaman' => 'Cafe Spontan | Dashboard',
			'title' => 'Dashboard',
			'hari_ini' => $hari_ini,
			'transaksi' => $transaksi,
			'bulan_ini' => $bulan_ini,
			'produk' => $produk,
			'katering' => $katering
		);
		$this->template->load('template', 'beranda', $data);
	}
}
