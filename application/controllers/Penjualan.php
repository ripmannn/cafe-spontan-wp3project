<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan extends CI_Controller
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
		$nota = date('ymd') . ($jumlah + 1);

		// PRODUK dari Database
		$this->db->from('produk')->where('stok > ', 0)->order_by('nama', 'ASC');
		$produkDb = $this->db->get()->result_array();

		// PRODUK dari API
		$produkApi = $this->Menu_model->getAllMenu();

		foreach ($produkApi as &$menu) {
			$menu['id_produk'] = $menu['d_id'];
			$menu['kode_produk'] = $menu['r_id'];
			$menu['nama'] = $menu['name'];
			$menu['keterangan'] = $menu['about'];
			$menu['harga'] = $menu['price'];
			$menu['gambar'] = $menu['img'];
			$menu['stok'] = '100';
			$menu['jenis'] = 'katering';
			$menu['new'] = '';
		}

		// Gabungkan Produk dari Database dan API
		$produk = array_merge($produkDb, $produkApi);

		// NAMA PELANGGAN
		$this->db->from('pelanggan')->where('id_pelanggan', $id_pelanggan);
		$namapelanggan = $this->db->get()->row()->nama;

		// DETAIL Penjualan
		$this->db->from('detail_penjualan a');
		$this->db->join('produk b', 'a.id_produk=b.id_produk', 'left');
		$this->db->where('a.kode_penjualan', $nota);
		$detail = $this->db->get()->result_array();

		// TEMP
		$this->db->from('temp');
		$this->db->where('id_user', $this->session->userdata('id_user'));
		$this->db->where('id_pelanggan', $id_pelanggan);
		$temp = $this->db->get()->result_array();

		log_message('debug', 'Data Temp (sebelum penggabungan): ' . print_r($temp, true));

		// Gabungkan Data Temp dengan Produk dari API dan Database
		foreach ($temp as &$item) {
			// Gabungkan dengan Produk dari Database
			// Gabungkan dengan Produk dari API
			foreach ($produkDb as $produks) {
				if ((string)$produks['id_produk'] === (string)$item['id_produk']) {
					$item = array_merge($item, $produks);  // Gabungkan data temp dengan produk API
					$stok_lama = (int) $produks['stok'];  // Stok dari database
            		log_message('debug', 'Stok produk dari database (ID: ' . $item['id_produk'] . '): ' . $stok_lama);  // Log stok dari DB
            		log_message('debug', 'Stok produk dari API (ID: ' . $item['id_produk'] . '): ' . $stok_lama);  // Log stok dari API
					break;
				}
			}
			foreach ($produkApi as $menu) {
				if ((string)$menu['id_produk'] === (string)$item['id_produk']) {
					$item = array_merge($item, $menu);  // Gabungkan data temp dengan produk API
					$stok_lama = 100;  // Stok dari API selalu 100
            		log_message('debug', 'Stok produk dari API (ID: ' . $item['id_produk'] . '): ' . $stok_lama);  // Log stok dari API
					break;
				}
			}

			// Cek stok sebelum ditambahkan ke keranjang
			if ($stok_lama < $item['jumlah']) {
				log_message('warning', 'Stok tidak mencukupi untuk produk (ID: ' . $item['id_produk'] . ') - Stok: ' . $stok_lama . ', Jumlah yang diminta: ' . $item['jumlah']);
			} else {
				log_message('debug', 'Stok mencukupi untuk produk (ID: ' . $item['id_produk'] . ') - Stok: ' . $stok_lama . ', Jumlah yang diminta: ' . $item['jumlah']);
			}

		}

		log_message('debug', 'Data Temp (setelah penggabungan dengan API dan DB): ' . print_r($temp, true));

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

		log_message('debug', 'Data yang Dikirim ke Views: ' . print_r($data, true));

		// Load Template dan Views
		$this->template->load('template', 'penjualan_transaksi', $data);
	}



	public function addtemp()
	{
		// Fetch product data from the database
		$this->db->from('produk')->where('id_produk', $this->input->post('id_produk'));
		$query = $this->db->get();

		// Check if the query returns a result
		if ($query->num_rows() > 0) {
			$stok_lama = $query->row()->stok;
		} else {
			// Handle the case where no product is found (optional)
			// You might want to log the error, throw an exception, or set a default value
			$stok_lama = 0;  // Set a default value or handle accordingly
			log_message('error', 'Product with id ' . $this->input->post('id_produk') . ' not found.');
		}


		// Fetch product data from the API
		$produkDb = $this->db->from('produk')->where('stok > ', 0)->order_by('nama', 'ASC')->get()->result_array();
		$produkApi = $this->Menu_model->getAllMenu();

		// Map API data to match the format of the database data
		foreach ($produkApi as &$menu) {
			$menu['id_produk'] = $menu['d_id'];  // API ID to match database ID
			$menu['kode_produk'] = $menu['r_id'];  // Same ID as kode_produk
			$menu['nama'] = $menu['name'];  // Product name
			$menu['keterangan'] = $menu['about'];  // Product description
			$menu['harga'] = $menu['price'];  // Ensure the price is an integer
			$menu['gambar'] = $menu['img'];  // Image URL
			$menu['stok'] = "100";  // Default stock for API products
			$menu['jenis'] = 'katering';  // Hardcoded product type 'katering'
			$menu['new'] = '';  // Default 'new' status, adjust if needed
		}

		// Merge the database and API products
		$produk = array_merge($produkDb, $produkApi);
		log_message('debug', 'Data Produk Gabungan: ' . print_r($produk, true));


		// Check if the product exists in the merged list
		$produkExist = null;
		foreach ($produk as $item) {
			// Pastikan untuk membandingkan ID produk dengan cara yang fleksibel
			if ((string) $item['id_produk'] === (string) $this->input->post('id_produk')) {
				$produkExist = $item;
				break;
			}
		}

		// If the product does not exist in the merged array, handle the error
		if ($produkExist == null) {
			$this->session->set_flashdata('pesan', '
            <div class="alert alert-danger alert-dismissible" role="alert">Produk tidak ditemukan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
			redirect($_SERVER["HTTP_REFERER"]);
		} else {
			// Akses properti stok jika produk ditemukan
			$stok = $produkExist['stok'];
			echo "Stok produk: " . $stok;

		}

		log_message('debug', 'Stok produk yang dipilih: ' . $produkExist['stok']);
		log_message('debug', 'Jumlah yang diminta: ' . $this->input->post('jumlah'));

		// Now you have the correct product data, including stock (from either the database or API)
		if ($produkExist['stok'] < $this->input->post('jumlah')) {
			log_message('warning', 'Produk yang dipilih tidak mencukupi stok. Stok tersedia: ' . $produkExist['stok'] . ', Jumlah yang diminta: ' . $this->input->post('jumlah'));
			$this->session->set_flashdata('pesan', '
			<div class="alert alert-danger alert-dismissible" role="alert">Produk yang di pilih tidak mencukupi stok
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>');
		} else {
			// Check if the product is already in the temp cart for the user and pelanggan
			$this->db->from('temp');
			$this->db->where('id_produk', $this->input->post('id_produk'));
			$this->db->where('id_user', $this->session->userdata('id_user'));
			$this->db->where('id_pelanggan', $this->input->post('id_pelanggan'));
			$cek = $this->db->get()->result_array();

		
			if ($cek != null) {
				// If product already exists in the cart
				$this->session->set_flashdata('pesan', '
                <div class="alert alert-warning alert-dismissible" role="alert">Produk Sudah Dipilih
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>');
			} else {
				// Add the product to the temp cart
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
		}

		// Redirect back to the previous page
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

		log_message('debug', 'Data yang akan diinsert: ' . print_r($data, true));

		if ($stok_lama >= $this->input->post('jumlah')) {

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

		$stok_sekarang = $jumlah + $stok_lama;

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

	public function bayarv2()
{
    // Nota
    date_default_timezone_set("Asia/Jakarta");
    $tanggal = date('Y-m');
    $this->db->from('penjualan');
    $this->db->where("DATE_FORMAT(tanggal,'%Y-%m')", $tanggal);
    $jumlah = $this->db->count_all_results();
    $nota = date('ymd') . ($jumlah + 1);

    // Temp
    $this->db->from('temp');
    $this->db->where('id_user', $this->session->userdata('id_user'));
    $this->db->where('id_pelanggan', $this->input->post('id_pelanggan'));
    $temp = $this->db->get()->result_array();

    // Produk dari Database
    $this->db->from('produk');
    $produkDb = $this->db->get()->result_array();

    // Produk dari API
    $produkApi = $this->Menu_model->getAllMenu();
    foreach ($produkApi as &$menu) {
        $menu['id_produk'] = $menu['d_id'];
        $menu['stok'] = 100; // Default stok dari API
        // Tambahkan data lain jika diperlukan
    }

    // Gabungkan Produk dari Database dan API
    $produk = array_merge($produkDb, $produkApi);

    log_message('debug', 'Produk Gabungan: ' . print_r($produk, true));

    $total = 0;

    // Proses Temp
    foreach ($temp as $t) {
        $produk_ditemukan = false;

        // Cari Produk di Database
        foreach ($produkDb as $produk) {
            if ((string)$produk['id_produk'] === (string)$t['id_produk']) {
                $t['stok'] = $produk['stok']; // Ambil stok dari database
                $t['nama'] = $produk['nama']; // Nama produk dari database
                $t['harga'] = $produk['harga']; // Nama produk dari database
                $produk_ditemukan = true;
                break;
            }
        }

        // Jika tidak ditemukan di Database, cari di API
        if (!$produk_ditemukan) {
            foreach ($produkApi as $menu) {
                if ((string)$menu['id_produk'] === (string)$t['id_produk']) {
                    $t['stok'] = $menu['stok']; // Ambil stok dari API
                    $t['nama'] = $menu['name']; // Nama produk dari API
                    $t['harga'] = $menu['price']; // Nama produk dari API
                    break;
                }
            }
        }

        // Cek stok produk
        if ($t['stok'] < $t['jumlah']) {
            $this->session->set_flashdata('pesan', '
            <div class="alert alert-danger alert-dismissible" role="alert">
                Produk yang dipilih tidak mencukupi stok
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
            redirect($_SERVER["HTTP_REFERER"]);
        }

        // Hitung total harga
        $total += $t['jumlah'] * $t['harga'];

        // Input ke tabel detail penjualan
        $data = array(
            'kode_penjualan' => $nota,
            'id_produk' => $t['id_produk'],
            'jumlah' => $t['jumlah'],
            'sub_total' => $t['jumlah'] * $t['harga'],
        );
        $this->db->insert('detail_penjualan', $data);

        // Update stok di Database (hanya jika produk berasal dari database)
        if (in_array($t['id_produk'], array_column($produkDb, 'id_produk'))) {
            $data2 = array('stok' => $t['stok'] - $t['jumlah']);
            $where = array('id_produk' => $t['id_produk']);
            $this->db->update('produk', $data2, $where);
        }
    }

    // Hapus dari table temp
    $where2 = array(
        'id_pelanggan' => $this->input->post('id_pelanggan'),
        'id_user' => $this->session->userdata('id_user'),
    );
    $this->db->delete('temp', $where2);

    // Input ke tabel penjualan
    $data = array(
        'kode_penjualan' => $nota,
        'id_pelanggan' => $this->input->post('id_pelanggan'),
        'total_harga' => $total,
        'tanggal' => date('Y-m-d'),
    );
    $this->db->insert('penjualan', $data);

    // Set pesan sukses
    $this->session->set_flashdata('pesan', '
        <div class="alert alert-success alert-dismissible" role="alert">
            Penjualan Berhasil !!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');

    // Redirect ke halaman invoice
    redirect('penjualan/invoice/' . $nota);
}


	public function invoice($kode_penjualan){
		// Ambil data penjualan
		$this->db->select('*');
		$this->db->from('penjualan a');
		$this->db->join('pelanggan b', 'a.id_pelanggan=b.id_pelanggan', 'left');
		$this->db->where('a.kode_penjualan', $kode_penjualan);
		$penjualan = $this->db->get()->row();

		// Ambil detail penjualan dari tabel detail_penjualan
		$this->db->from('detail_penjualan');
		$this->db->where('kode_penjualan', $kode_penjualan);
		$detailPenjualan = $this->db->get()->result_array();

		// Ambil semua produk dari database
		$produkDb = $this->db->get('produk')->result_array();

		// Ambil semua produk dari API
		$produkApi = $this->Menu_model->getAllMenu();
		foreach ($produkApi as &$menu) {
			$menu['id_produk'] = $menu['d_id'];
			$menu['kode_produk'] = $menu['r_id'];
			$menu['nama'] = $menu['name'];
			$menu['harga'] = $menu['price'];
			$menu['gambar'] = $menu['img'];
		}

		// Gabungkan produk dari database dan API
		$produkList = array_merge($produkDb, $produkApi);

		// Gabungkan detail penjualan dengan informasi produk
		$detail = [];
		foreach ($detailPenjualan as $item) {
			// Cari produk dari daftar yang sudah digabungkan
			foreach ($produkList as $produk) {
				if ((string)$produk['id_produk'] === (string)$item['id_produk']) {
					// Menambahkan informasi produk ke dalam item detail
					$item['kode_produk'] = $produk['kode_produk'];
					$item['nama'] = $produk['nama'];
					$item['harga'] = $produk['harga'];
					$item['gambar_produk'] = $produk['gambar'];
					break;
				}
			}
			$detail[] = $item;
		}

		// Debugging jika diperlukan
		log_message('debug', 'Detail Penjualan (setelah penggabungan): ' . print_r($detail, true));

		// Kirim data ke view
		$data = array(
			'judul_halaman' => 'Cafe Spontan | Invoice Penjualan',
			'title' => 'Invoice Penjualan',
			'nota' => $kode_penjualan,
			'penjualan' => $penjualan,
			'detail' => $detail, // Data detail dengan produk lengkap
		);

		$this->template->load('template', 'invoice', $data);
	}



	public function print($kode_penjualan){
		// Ambil data penjualan
		$this->db->select('*');
		$this->db->from('penjualan a');
		$this->db->join('pelanggan b', 'a.id_pelanggan=b.id_pelanggan', 'left');
		$this->db->where('a.kode_penjualan', $kode_penjualan);
		$penjualan = $this->db->get()->row();

		// Ambil detail penjualan dari tabel detail_penjualan
		$this->db->from('detail_penjualan a');
		$this->db->where('a.kode_penjualan', $kode_penjualan);
		$detailPenjualan = $this->db->get()->result_array();

		// Ambil semua produk dari database
		$produkDb = $this->db->get('produk')->result_array();

		// Ambil semua produk dari API
		$produkApi = $this->Menu_model->getAllMenu();
		foreach ($produkApi as &$menu) {
			$menu['id_produk'] = $menu['d_id'];
			$menu['kode_produk'] = $menu['r_id'];
			$menu['nama'] = $menu['name'];
			$menu['harga'] = $menu['price'];
			$menu['gambar'] = $menu['img'];
		}

		// Gabungkan produk dari database dan API
		$produkList = array_merge($produkDb, $produkApi);

		// Gabungkan detail penjualan dengan informasi produk
		$detail = [];
		foreach ($detailPenjualan as $item) {
			// Cari produk dari daftar yang sudah digabungkan
			foreach ($produkList as $produk) {
				if ((string)$produk['id_produk'] === (string)$item['id_produk']) {
					// Menambahkan informasi produk ke dalam item detail
					$item['kode_produk'] = $produk['kode_produk'];
					$item['nama'] = $produk['nama'];
					$item['harga'] = $produk['harga'];
					$item['gambar'] = $produk['gambar'];
					break;
				}
			}
			$detail[] = $item;
		}

		// Debugging jika diperlukan
		log_message('debug', 'Detail Penjualan (setelah penggabungan): ' . print_r($detail, true));

		// Kirim data ke view
		$data = array(
			'judul_halaman' => 'Cafe Spontan | Nota Penjualan',
			'title' => 'Nota Penjualan',
			'nota' => $kode_penjualan,
			'penjualan' => $penjualan,
			'detail' => $detail, // Data detail dengan produk lengkap
		);

		// Tampilkan struk/nota
		$this->load->view('struk', $data);
	}


	public function laporan()
	{
		$tanggal1 = $this->input->get('tanggal1');
		$tanggal2 = $this->input->get('tanggal2');

		date_default_timezone_set("Asia/Jakarta");
		$this->db->select('*');
		$this->db->from('penjualan a')->order_by('a.tanggal', 'DESC');
		$this->db->join('pelanggan b', 'a.id_pelanggan=b.id_pelanggan', 'left');
		$this->db->where('tanggal >=', $tanggal1);
		$this->db->where('tanggal <=', $tanggal2);
		$penjualan = $this->db->get()->result_array();
		$data = array(
			'tanggal1' => $tanggal1,
			'tanggal2' => $tanggal2,
			'penjualan' => $penjualan
		);
		$this->load->view('laporan', $data);
	}
}
