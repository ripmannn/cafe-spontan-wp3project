<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('level') == null) {
			redirect('auth');
		}
	}

	public function index()
	{
		// data penjualan hari ini 
		date_default_timezone_set("Asia/Jakarta");
		$tanggal = date('Y-m-d');
		$this->db->select('*');
		$this->db->from('penjualan a')->order_by('a.tanggal', 'DESC')->where("DATE_FORMAT(tanggal,'%Y-%m-%d')", $tanggal);
		$this->db->join('pelanggan b', 'a.id_pelanggan = b.id_pelanggan', 'left');
		$penjualan = $this->db->get()->result_array();

		// data pelanggan
		$this->db->from('pelanggan')->order_by('id_pelanggan', 'ASC');
		$pelanggan = $this->db->get()->result_array();
		$data = array(
			'judul_halaman' => 'Cafe Spontan | Data Transaksi',
			'title' => 'Data Transaksi',
			'penjualan' => $penjualan,
			'pelanggan' => $pelanggan
		);
		$this->template->load('template', 'penjualan_index', $data);
	}

	public function transaksi($id_pelanggan)
	{
		// NOTA
		date_default_timezone_set("Asia/Jakarta");
		$tanggal = date('Y-m');
		$this->db->from('penjualan');
		$this->db->where("DATE_FORMAT(tanggal,'%Y-%m')", $tanggal);
		$jumlah = $this->db->count_all_results();
		$nota = date('ymd') . $jumlah + 1;

		// PRODUK
		$this->db->from('produk')->where('stok > ', 0)->order_by('nama', 'ASC');
		$produk = $this->db->get()->result_array();

		// NAMA PELANGGAN
		$this->db->from('pelanggan')->where('id_pelanggan', $id_pelanggan);
		$namapelanggan = $this->db->get()->row()->nama;

		// DETAIL
		$this->db->from('detail_penjualan a');
		$this->db->join('produk b', 'a.id_produk=b.id_produk', 'left');
		$this->db->where('a.kode_penjualan', $nota);
		$detail = $this->db->get()->result_array();

		// temp
		$this->db->from('temp a');
		$this->db->join('produk b', 'a.id_produk=b.id_produk', 'left');
		$this->db->where('a.id_user', $this->session->userdata('id_user'));
		$this->db->where('a.id_pelanggan', $id_pelanggan);
		$temp = $this->db->get()->result_array();


		$data = array(
			'judul_halaman' => 'Cafe Spontan | Transaksi Penjualan',
			'title' => 'Transaksi Penjualan',
			'nota' => $nota,
			'produk' => $produk,
			'id_pelanggan' => $id_pelanggan,
			'namapelanggan' => $namapelanggan,
			'detail' => $detail,
			'temp' => $temp,

		);
		$this->template->load('template', 'penjualan_transaksi', $data);
	}
	

	public function addtemp()
	{
		$this->db->from('produk')->where('id_produk', $this->input->post('id_produk'));
		$stok_lama = $this->db->get()->row()->stok;

		$this->db->from('temp');
		$this->db->where('id_produk', $this->input->post('id_produk'));
		$this->db->where('id_user', $this->session->userdata('id_user'));
		$this->db->where('id_pelanggan', $this->input->post('id_pelanggan'));
		$cek = $this->db->get()->result_array();

		if ($stok_lama <  $this->input->post('jumlah')) {
			$this->session->set_flashdata('pesan', '
				<div class="alert alert-danger alert-dismissible" role="alert">Produk yang di pilih tidak mencukupi stok
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>');
		} else if ($cek != null) {
			$this->session->set_flashdata('pesan', '
				<div class="alert alert-warning alert-dismissible" role="alert">Produk Sudah Dipilih
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>');
		} else {
			$data = array(
				'id_pelanggan' => $this->input->post('id_pelanggan'),
				'id_user' => $this->session->userdata('id_user'),
				'id_produk' => $this->input->post('id_produk'),
				'jumlah' => $this->input->post('jumlah'),
			);
			$this->db->insert('temp', $data);
			$this->session->set_flashdata('pesan', '
				<div class="alert alert-primary alert-dismissible" role="alert">Produk berhasil ditambah ke keranjang
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>');
		}
		redirect($_SERVER["HTTP_REFERER"]);
	}

	public function tambahkeranjang()
	{

		$this->db->from('detail_penjualan');
		$this->db->where('id_produk', $this->input->post('id_produk'));
		$this->db->where('kode_penjualan', $this->input->post('kode_penjualan'));
		$cek = $this->db->get()->result_array();
		if ($cek != null) {
			$this->session->set_flashdata('pesan', '
				<div class="alert alert-warning alert-dismissible" role="alert">Produk Sudah Dipilih
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>');
			redirect($_SERVER["HTTP_REFERER"]);
		}

		// ambil harga dari table produk
		$this->db->from('produk')->where('id_produk', $this->input->post('id_produk'));
		$harga = $this->db->get()->row()->harga;

		// ambil stok dari tabel produk
		$this->db->from('produk')->where('id_produk', $this->input->post('id_produk'));
		$stok_lama = $this->db->get()->row()->stok;

		// stoknya di kurang dari stock awal
		$stok_sekarang = $stok_lama - $this->input->post('jumlah');

		// total jumlah dari barang beli kali harga
		$sub_total = $this->input->post('jumlah') * $harga;

		$data = array(
			'kode_penjualan' => $this->input->post('kode_penjualan'),
			'id_produk' => $this->input->post('id_produk'),
			'jumlah' => $this->input->post('jumlah'),
			'sub_total' => $sub_total,
		);

		if ($stok_lama >=  $this->input->post('jumlah')) {

			$this->db->insert('detail_penjualan', $data);
			$data2 = array('stok' => $stok_sekarang);
			$where = array('id_produk' => $this->input->post('id_produk'));

			$this->db->update('produk', $data2, $where);
			$this->session->set_flashdata('pesan', '
				<div class="alert alert-primary alert-dismissible" role="alert">Produk berhasil ditambah ke keranjang
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>');
		} else {
			$this->session->set_flashdata('pesan', '
				<div class="alert alert-danger alert-dismissible" role="alert">Produk yang di pilih tidak mencukupi stok
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>');
		}

		redirect($_SERVER["HTTP_REFERER"]);
	}

	public function hapus($id_detail, $id_produk)
	{
		$this->db->from('detail_penjualan')->where('id_detail', $id_detail);
		$jumlah = $this->db->get()->row()->jumlah;

		$this->db->from('produk')->where('id_produk', $id_produk);
		$stok_lama = $this->db->get()->row()->stok;

		$stok_sekarang  = $jumlah + $stok_lama;

		$data2 = array('stok' => $stok_sekarang);
		$where = array('id_produk' => $id_produk);
		$this->db->update('produk', $data2, $where);

		$where = array('id_detail' => $id_detail);
		$this->db->delete('detail_penjualan', $where);
		$this->session->set_flashdata('pesan', '
            <div class="alert alert-danger alert-dismissible" role="alert">Produk berhasil Dihapus dari keranjang
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
		redirect($_SERVER["HTTP_REFERER"]);
	}

	public function hapus_temp($id_temp)
	{

		$where = array('id_temp' => $id_temp);
		$this->db->delete('temp', $where);
		$this->session->set_flashdata('pesan', '
            <div class="alert alert-danger alert-dismissible" role="alert">Produk berhasil Dihapus dari keranjang
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
		redirect($_SERVER["HTTP_REFERER"]);
	}

	// public function bayar()
	// {
	// 	$data = array(
	// 		'kode_penjualan' => $this->input->post('kode_penjualan'),
	// 		'id_pelanggan' => $this->input->post('id_pelanggan'),
	// 		'total_harga' => $this->input->post('total_harga'),
	// 		'tanggal' => date('Y-m-d')
	// 	);
	// 	$this->db->insert('penjualan', $data);

	// 	$this->session->set_flashdata('pesan', '
    //         <div class="alert alert-success alert-dismissible" role="alert">Penjualan Berhasil !!
    //             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //         </div>');
	// 	redirect('penjualan');
	// }
	public function bayarv2()
	{
		// nota
		date_default_timezone_set("Asia/Jakarta");
		$tanggal = date('Y-m');
		$this->db->from('penjualan');
		$this->db->where("DATE_FORMAT(tanggal,'%Y-%m')", $tanggal);
		$jumlah = $this->db->count_all_results();
		$nota = date('ymd') . $jumlah + 1;

		// temp
		$this->db->from('temp a');
		$this->db->join('produk b', 'a.id_produk=b.id_produk', 'left');
		$this->db->where('a.id_user', $this->session->userdata('id_user'));
		$this->db->where('a.id_pelanggan', $this->input->post('id_pelanggan'));
		$temp = $this->db->get()->result_array();
		$total = 0;
		foreach ($temp as $t) {
			if ($t['stok'] < $t['jumlah']) {
				$this->session->set_flashdata('pesan', '
				<div class="alert alert-danger alert-dismissible" role="alert">Produk yang di pilih tidak mencukupi stok
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>');
				redirect($_SERVER["HTTP_REFERER"]);
			}
			$total = $total +$t['jumlah']*$t['harga'];
			// input ke tabel detail
			$data = array(
				'kode_penjualan' => $nota,
				'id_produk' => $t['id_produk'],
				'jumlah' => $t['jumlah'],
				'sub_total' => $t['jumlah'] * $t['harga'],
			);
			$this->db->insert('detail_penjualan', $data);
			// update tabel produk stoknya
			$data2 = array('stok' => $t['stok'] - $t['jumlah']);
			$where = array('id_produk' => $t['id_produk']);
			$this->db->update('produk', $data2, $where);

			// hapus dari table temp
			$where2 = array(
				'id_pelanggan' => $this->input->post('id_pelanggan'),
				'id_user' => $this->session->userdata('id_user'),
			);
			$this->db->delete('temp', $where2);
		}


		// input ke tabel penjualan
		date_default_timezone_set("Asia/Jakarta");
		$tanggal = date('Y-m');
		$this->db->from('penjualan')->where("DATE_FORMAT(tanggal,'%Y-%m')", $tanggal);
		$jumlah = $this->db->count_all_results();
		$nota = date('ymd') . $jumlah + 1;

		$data = array(
			'kode_penjualan' => $nota,
			'id_pelanggan' => $this->input->post('id_pelanggan'),
			'total_harga' => $total,
			'tanggal' => date('Y-m-d')
		);
		$this->db->insert('penjualan', $data);

		$this->session->set_flashdata('pesan', '
            <div class="alert alert-success alert-dismissible" role="alert">Penjualan Berhasil !!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
		redirect('penjualan/invoice/'.$nota);
	}

	public function invoice($kode_penjualan)
	{
		$this->db->select('*');
		$this->db->from('penjualan a')->order_by('a.tanggal', 'DESC')->where('a.kode_penjualan', $kode_penjualan);
		$this->db->join('pelanggan b', 'a.id_pelanggan=b.id_pelanggan', 'left');
		$penjualan = $this->db->get()->row();

		$this->db->from('detail_penjualan a');
		$this->db->join('produk b', 'a.id_produk=b.id_produk', 'left');
		$this->db->where('a.kode_penjualan', $kode_penjualan);
		$detail = $this->db->get()->result_array();

		
		$data = array(
			'judul_halaman' => 'Cafe Spontan | Invoice Penjualan',
			'title' => 'Invoice Penjualan',
			'nota' => $kode_penjualan,
			'penjualan' => $penjualan,
			'detail' => $detail,
			
		);
		$this->template->load('template', 'invoice', $data);
	}
	public function print($kode_penjualan)
	{
		$this->db->select('*');
		$this->db->from('penjualan a')->order_by('a.tanggal', 'DESC')->where('a.kode_penjualan', $kode_penjualan);
		$this->db->join('pelanggan b', 'a.id_pelanggan=b.id_pelanggan', 'left');
		$penjualan = $this->db->get()->row();

		$this->db->from('detail_penjualan a');
		$this->db->join('produk b', 'a.id_produk=b.id_produk', 'left');
		$this->db->where('a.kode_penjualan', $kode_penjualan);
		$detail = $this->db->get()->result_array();

		$data = array(
			'judul_halaman' => 'Cafe Spontan | Invoice Penjualan',
			'title' => 'Invoice Penjualan',
			'nota' => $kode_penjualan,
			'penjualan' => $penjualan,
			'detail' => $detail,
		);
		$this->load->view('struk', $data);
	}

	public function laporan(){
		$tanggal1 = $this->input->get('tanggal1');
		$tanggal2 = $this->input->get('tanggal2');

		date_default_timezone_set("Asia/Jakarta");
		$this->db->select('*');
		$this->db->from('penjualan a')->order_by('a.tanggal', 'DESC');
		$this->db->join('pelanggan b','a.id_pelanggan=b.id_pelanggan','left');
		$this->db->where('tanggal >=',$tanggal1);
		$this->db->where('tanggal <=',$tanggal2);
		$penjualan = $this->db->get()->result_array();
		$data = array  (
			'tanggal1' => $tanggal1,
			'tanggal2' => $tanggal2,
			'penjualan' => $penjualan
		);
		$this->load->view('laporan',$data);
	}
}
