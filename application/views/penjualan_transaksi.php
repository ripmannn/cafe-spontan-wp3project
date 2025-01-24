<?= $this->session->flashdata('pesan') ?>

<div class="row">
    <div class="col-md-12">
        <!-- pemilihan produk -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pilih Produk Yang Akan Di Jual</h5>
                <small class="text-muted float-end text-danger">(Pastikan Produk Yang Dipilih Benar)</small>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="name">Nama Pelanggan</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $namapelanggan ?>" readonly
                        autocomplete="name">
                </div>
                <form action="<?= base_url('penjualan/addtemp') ?>" method="post">
                    <input type="hidden" name="id_pelanggan" value="<?= $id_pelanggan ?>">
                    <div class="mb-3">
                        <span class="form-label">Produk</span>
                        <input type="hidden" name="kode_penjualan" id="kode_penjualan" value="<?= $nota ?>">
                        <small class=" float-end badge bg-primary">Pilih Produk</small>
                        <select name="id_produk" class="form-control">
                            <?php foreach ($produk as $p) { ?>
                                
                                <option value="<?php echo $p['id_produk'] ?>">
                                    <?php echo $p['nama'] ?> - <?php echo $p['kode_produk'] ?>
                                    (<?php echo $p['stok'] ?>)
                                    (Rp.<?php echo number_format($p['harga'], 0, ',', '.') ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah"
                            placeholder="Jumlah Yang Di Jual" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Tambah Keranjang</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <!-- detail apa saaj yg di belil -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Produk Yang Dipilih</h5>
                <small class="text-muted float-end text-danger">(Pembayaran Selain Cash)</small>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Jumlah Beli</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php $total = 0;
                            $cek = 0;
                            $no = 1;
                            foreach ($temp as $d) { ?>
                                <?php
                                ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $d['kode_produk'] ?></td>
                                    <td><?= $d['nama'] ?></td>
                                    <td>
                                        <?= $d['jumlah'] ?>
                                        <?php
                                        if ($d['jumlah'] > $d['stok']) {
                                            echo "<span class='badge bg-danger'>stok tidak mencukupi</span>";
                                            $cek = 1;
                                        }

                                        ?>
                                    </td>
                                    <td>Rp.<?= number_format($d['harga']) ?></td>
                                    <td>Rp.<?= number_format($d['jumlah'] * $d['harga']) ?></td>
                                    <td>
                                        <a onclick="return confirm('Apakah anda yakin menghapus Produk dari Keranjang Ini?')"
                                            href="<?= base_url('penjualan/hapus_temp/' . $d['id_temp']) ?>"
                                            class="btn-sm btn-danger">Hapus</a>
                                    </td>
                                </tr>
                                <?php $total = $total + $d['jumlah'] * $d['harga'];
                                $no++;
                            } ?>
                            <tr>
                                <td class="fw-bolder" colspan=5>Total Harga</td>
                                <td class="fw-bolder">Rp. <?= number_format($total); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= base_url('penjualan/bayarv2') ?>" method="post">
                    <input type="hidden" name="id_pelanggan" value="<?= $id_pelanggan; ?>">
                    <?php if (($temp != null) and ($cek == 0)) { ?>
                        <button type="submit" class="btn btn-primary">Bayar</button>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>

</div>