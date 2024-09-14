<div class="card">

    <div class="card-body">
        <section>
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        Cafe Spontan <span class="badge bg-primary"><?= date_format(date_create($penjualan->tanggal), "d M Y") ?></span>
                    </h2>
                </div>
            </div>
            <div class="row ">
                <div class="col-sm-4">
                    From :
                    <address>
                        <strong>Cafe Spontan</strong><br>
                        Jl. Latu Menten Season City<br>
                        Phone : (0815-1095-8001)<br>
                        Email : spontancafe@gmail.com
                    </address>
                </div>
                <div class="col-sm-4 ">
                    To :
                    <address>
                        <strong><?= !empty($penjualan->nama) ? $penjualan->nama : 'fulan' ?></strong><br>
                        Alamat : <?= !empty($penjualan->alamat) ? $penjualan->alamat : 'jakarta' ?><br>
                        Contact person : <?= !empty($penjualan->telp) ? $penjualan->telp : '+62' ?> <br>
                    </address>
                </div>
                <div class="col-sm-4">
                    <b>Nomor Nota #<?= $nota ?></b><br>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Jumlah Beli</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php $total = 0;
                            $no = 1;
                            foreach ($detail as $d) {  ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $d['kode_produk'] ?></td>
                                    <td><?= $d['nama'] ?></td>
                                    <td><?= $d['jumlah'] ?></td>
                                    <td>Rp.<?= number_format($d['harga']) ?></td>
                                    <td>Rp.<?= number_format($d['jumlah'] * $d['harga']) ?></td>
                                </tr>
                            <?php $total = $total + $d['jumlah'] * $d['harga'];
                                $no++;
                            } ?>
                            <tr>
                                <td colspan=5>Total Harga</td>
                                <td>Rp. <?= number_format($total); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                <a class="btn btn-warning" target="_blank" href="<?= base_url('penjualan/print/' . $nota); ?>">Cetak Nota</a>
            </div>
        </section>
    </div>
</div>