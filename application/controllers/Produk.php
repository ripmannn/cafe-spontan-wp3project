<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('level') != 'Admin') {
            redirect('home');
        }
    }

    public function index()
    {
        $this->db->select('*')->from('produk');
        $this->db->order_by('nama', 'ASC');

        $produk = $this->db->get()->result_array();

        $data = array(
            'judul_halaman' => 'Cafe Spontan | Data Menu',
            'title' => 'Data Menu',
            'produk' => $produk
        );
        $this->template->load('template', 'produk_index', $data);
    }


    public function simpan()
    {
        $kode_produk = $this->input->post('kode_produk');
        $cek = $this->db->get_where('produk', array('kode_produk' => $kode_produk))->row_array();

        if (empty($cek)) {
            $gambar = $_FILES['gambar']['name'];

            // Check if 'gambar' is not empty
            if (!empty($gambar)) {
                // Configuring upload
                $config['upload_path'] = './assets/gambar';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = 2048; // 2MB
                $config['overwrite'] = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('gambar')) {
                    $gambar = $this->upload->data('file_name');
                    $this->db->set('gambar', $gambar);

                    $data = array(
                        'nama' => $this->input->post('nama'),
                        'kode_produk' => $kode_produk,
                        'keterangan' => $this->input->post('keterangan'),
                        'jenis' => $this->input->post('jenis'),
                        'stok' => $this->input->post('stok'),
                        'harga' => $this->input->post('harga'),
                        'gambar' => $gambar, // Saving only filename to database
                    );

                    $this->db->insert('produk', $data);
                    $this->session->set_flashdata('pesan', '
                    <div class="alert alert-success alert-dismissible" role="alert">Produk Berhasil Ditambahkan
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>');
                } else {
                    // If upload fails
                    $this->session->set_flashdata('pesan', '
                    <div class="alert alert-danger alert-dismissible" role="alert">Upload Gambar Gagal: ' . $this->upload->display_errors() . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>');
                }
            } else {
                $this->session->set_flashdata('pesan', '
                <div class="alert alert-danger alert-dismissible" role="alert">Gambar Produk Tidak Boleh Kosong !!!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>');
            }
        } else {
            $this->session->set_flashdata('pesan', '
            <div class="alert alert-danger alert-dismissible" role="alert">Kode Produk Sudah Digunakan !!!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        }
        redirect('produk');
    }




    public function hapus($id_produk)
    {
        $where = array('id_produk' => $id_produk);
        $this->db->delete('produk', $where);
        $this->session->set_flashdata('pesan', '
            <div class="alert alert-danger alert-dismissible" role="alert">Produk Berhasil Dihapus !!!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        redirect('produk');
    }

    public function edit($id_produk)
    {
        $this->db->from('produk')->where('id_produk', $id_produk);

        $produk = $this->db->get()->row();

        $data = array(
            'judul_halaman' => 'Cafe Spontan | Edit Produk',
            'title' => 'Produk edit',
            'produk' => $produk
        );
        $this->template->load('template', 'produk_edit', $data);
    }

    public function update()
    {
        // Memeriksa apakah ada gambar yang diunggah
        if (!empty($_FILES['gambar']['name'])) {
            $config['upload_path'] = './assets/gambar'; // Tentukan direktori tempat Anda ingin menyimpan gambar
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048; // Batas maksimum ukuran file (dalam kilobita)
            $config['overwrite'] = TRUE;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('gambar')) {
                $error = $this->upload->display_errors();
                // Handle kesalahan unggah, misalnya:
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">' . $error . '</div>');
                redirect('produk');
            } else {
                $data['gambar'] = $this->upload->data('file_name');
            }
        }

        $data['nama'] = $this->input->post('nama');
        $data['kode_produk'] = $this->input->post('kode_produk');
        $data['stok'] = $this->input->post('stok');
        $data['harga'] = $this->input->post('harga');
        $data['jenis'] = $this->input->post('jenis');
        $data['keterangan'] = $this->input->post('keterangan');

        $where = array('id_produk' => $this->input->post('id_produk'));

        // Memperbarui data produk
        $this->db->update('produk', $data, $where);

        $this->session->set_flashdata('pesan', '
        <div class="alert alert-primary alert-dismissible" role="alert">Produk Berhasil Diupdate !!!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        redirect('produk');
    }

    public function print()
    {
        $this->db->select('*')->from('produk');
        $this->db->order_by('nama', 'ASC');
        $status = $this->input->get('status');
        if ($status == 'Ada') {
            $this->db->where('stok >', 0);
        } else if ($status == 'Habis') {
            $this->db->where('stok', 0);
        }

        $produk = $this->db->get()->result_array();
        $data = array(

            'status' => $status,
            'produk' => $produk,
        );
        $this->load->view('print_produk', $data);
    }
}
