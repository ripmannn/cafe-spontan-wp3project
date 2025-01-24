<div class="mt-1 mb-3 ">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary  " data-bs-toggle="modal" data-bs-target="#basicModal">
        <i class='bx bx-plus'></i> <span>Tambah Produk</span>
    </button>
    <button type="button" class="btn btn-warning  " data-bs-toggle="modal" data-bs-target="#printModal">
        <i class='bx bx-printer'></i> <span>Print</span>
    </button>
    <div class="mt-3"><?= $this->session->flashdata('pesan') ?></div>


    <!-- Modal -->
    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" style="color: white;" id="exampleModalLabel1">Tambah Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('produk/simpan') ?>" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-label" for="nama" >Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Produk" required>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label class="form-label" for="kode_produk">Kode Produk</label>
                                <input type="text" name="kode_produk" id="kode_produk" class="form-control" placeholder="Kode Produk" required>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label class="form-label" for="keterangan">Keterangan Produk</label>
                                <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan" required>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label class="form-label" for="jenis">Jenis Produk</label>
                                <select name="jenis" id="jenis" class="form-control" >
                                    <option value="makanan">Makanan</option>
                                    <option value="minuman">Minuman</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label class="form-label" for="stok" >Stok Barang</label>
                                <input type="number" name="stok" id="stok" class="form-control" placeholder="Stok" required>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label class="form-label" for="harga" >Harga Barang</label>
                                <input type="number" name="harga" id="harga" class="form-control" placeholder=Harga required>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label class="form-label" for="gambar" >gambar Barang</label>
                                <input type="file" name="gambar" id="gambar" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="card p-3">
    <h5 class="card-header">Produk</h5>
    <div class="table-responsive text-nowrap">
        <table id="myTable" class="table">
            <thead>
                <tr>
                    <th style="text-align: left;" >NO</th>
                    <th>Nama</th>
                    <th>Kode Produk</th>
                    <th style="text-align: left;" >Stok</th>
                    <th>Harga</th>
                    <th style="text-align: left;">Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php $no = 1;
                foreach ($produk as $p) {  ?>
                    <tr>
                        <td style="text-align: left;"><?= $no ?></td>
                        <td><?= $p['nama'] ?></td>
                        <td><?= $p['kode_produk'] ?></td>
                        <td style="text-align: left;" ><?= $p['stok'] ?></td>
                        <td>Rp.<?= number_format($p['harga']) ?></td>
                        <td style="text-align: left;"><img style="width: 100px; "  src="<?= base_url('assets/gambar/') .$p['gambar'] ?>" alt=""></td>
                        <td>
                            <a href="<?= base_url('produk/edit/' . $p['id_produk']) ?>" class="btn-sm btn-primary">Edit</a>
        
                            <a style="margin-left: 10px;" onclick="return confirm('Apakah anda yakin menghapus Data Ini?')" href="<?= base_url('produk/hapus/' . $p['id_produk']) ?>" class="btn-sm btn-danger">Hapus</a>
                        </td>
                    </tr>
                <?php $no++;
                } ?>
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="printModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" style="color:white;" id="exampleModalLabel1">Laporan Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('produk/print') ?>" method="get"  target="_blank" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label" for="status" >Stok</label>
                            <select name="status" id="status" class="form-control">
                                <option value="Ada">Ada</option>
                                <option value="Habis">Habis</option>
                                <option value="Semua">Semua</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-warning">Print</button>
                </div>
            </form>
        </div>
    </div>
</div>