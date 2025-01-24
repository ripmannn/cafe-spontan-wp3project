<div class="row">
    <div class="mt-3"><?= $this->session->flashdata('pesan') ?></div>
    <?php if (empty($pesanan_produk)): ?>
        <p class="text-center">Pesanan belum ada.</p>
    <?php else: ?>
        <?php


        // Group products by customer
        $produkPerPelanggan = [];
        foreach ($pesanan_produk as $p) {
            $produkPerPelanggan[$p['nama_pelanggan']][] = $p;
        }

        // Display cards for each customer
        foreach ($produkPerPelanggan as $namaPelanggan => $produks): ?>
            <div class="col-md-4 ">
                <div class="card mb-5">
                    <div class="card-header">
                        <h5 class="fs-3 text-center text-uppercase fw-bold"><?= $namaPelanggan ?></h5>
                    </div>
                    <div class="card-body  ">
                        <table class="table fs-4">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($produks as $p): ?>
                                    <tr>
                                        <td><?= $p['nama_produk'] ?></td>
                                        <td><?= $p['jumlah'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="text-center mt-3">
                            <a href="<?= site_url('pesanan_produk/edit/' . urlencode($namaPelanggan)) ?>"
                                class="btn btn-primary">Edit</a>
                            <a href="<?= site_url('pesanan_produk/delete/' . urlencode($namaPelanggan)) ?>"
                                class="btn btn-danger" onclick="return confirm('Anda yakin ingin menghapus?');">Delete All</a>

                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>