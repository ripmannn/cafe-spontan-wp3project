<div class="card p-4 shadow-sm">
    <h3 class="card-title mb-4 text-center text-primary">Detail Transaksi</h3>
    <div class="mb-3">
        <p class="fs-4"><strong>Nama:</strong> <span class="text-success"><?= $transaksi[0]['name']; ?></span></p>
        <p class="fs-4"><strong>Meja No:</strong> <span class="text-success"><?= $transaksi[0]['table_number']; ?></span></p>
    </div>

    <h4 class="card-subtitle mb-3 text-secondary">Daftar Produk:</h4>
    <ul class="list-group list-group-flush">
        <?php 
        $total_harga = 0; // Initialize total price
        foreach ($transaksi as $item): 
            $total_harga += $item['subtotal']; // Accumulate total price
        ?>
            <li class="list-group-item border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fs-5"><strong>Produk:</strong> <?= $item['product_name']; ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fs-5"><strong>Jumlah:</strong> <?= $item['quantity']; ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fs-5"><strong>Harga:</strong> Rp. <?= number_format($item['price'], 0, ',', '.'); ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fs-5"><strong>Subtotal:</strong> Rp. <?= number_format($item['subtotal'], 0, ',', '.'); ?></span>
                </div>
                <hr class="my-2">
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="mt-3">
        <p class="fs-5 text-danger"><strong>Total:</strong> Rp. <?= number_format($total_harga, 0, ',', '.'); ?></p>
    </div>

</div>
<a href="<?= base_url(); ?>pesanan_mobile" class="btn btn-secondary mt-3">Kembali</a>