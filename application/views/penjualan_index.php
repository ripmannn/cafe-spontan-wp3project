<div class="mt-1 mb-3 ">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary  " data-bs-toggle="modal" data-bs-target="#basicModal">
        <i class='bx bx-plus'></i> <span>Tambah Transaksi</span>
    </button>

    <button type="button" class="btn btn-warning  " data-bs-toggle="modal" data-bs-target="#laporanModal">
        <i class='bx bx-printer'></i> <span>Cetak Laporan</span>
    </button>
    <div class="mt-3"><?= $this->session->flashdata('pesan') ?></div>


    <!-- Modal  -->
    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" style="color: white;" id="exampleModalLabel1">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Telp</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <?php $no = 1;
                                    foreach ($pelanggan as $p) { ?>
                                        <tr>
                                            <td><?= $no ?></td>
                                            <td><?= $p['nama'] ?></td>
                                            <td><?= $p['alamat'] ?></td>
                                            <td><?= $p['telp'] ?></td>
                                            <td>
                                                <a href="<?= base_url('penjualan/transaksi/' . $p['id_pelanggan']) ?>"
                                                    class="btn-sm btn-primary">Pilih</a>
                                            </td>
                                        </tr>
                                        <?php $no++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <h5 class="card-header">Transaksi Hari Ini</h5>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>No Nota</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Nominal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php $total = 0;
                $no = 1;
                foreach ($penjualan as $p) { ?>
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $p['kode_penjualan'] ?></td>
                        <td><?= $p['tanggal'] ?></td>
                        <td><?= !empty($p['nama']) ? $p['nama'] : 'fulan' ?></td>

                        <td>Rp.<?= number_format($p['total_harga']) ?></td>
                        <td>
                            <a href="<?= base_url('penjualan/invoice/' . $p['kode_penjualan']) ?>"
                                class="btn-sm btn-info">Cek</a>
                        </td>
                    </tr>
                    <?php $total = $total + $p['total_harga'];
                    $no++;
                } ?>
                <tr>
                    <td class="fw-bolder" colspan="4">Total</td>
                    <td class="fw-bolder">Rp.<?= number_format($total) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- modal laporan -->
<div class="modal fade" id="laporanModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" style="color:white;" id="exampleModalLabel1">Cetak Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('penjualan/laporan') ?>" method="get" target="_blank">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label" for="tanggal1">Dari:</label>
                            <input type="date" id="tanggal1" name="tanggal1" class="form-control" required>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label" for="tanggal2">Sampai : </label>
                            <input type="date" name="tanggal2" id="tanggal2" class="form-control" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-warning">Cetak</button>
                </div>
            </form>
        </div>
    </div>
</div>