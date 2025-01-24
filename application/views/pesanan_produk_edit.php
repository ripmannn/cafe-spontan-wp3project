<div class="row">
    <h1><?php echo $title; ?></h1>

    <form action="<?php echo site_url('pesanan_produk/update'); ?>" method="post" class="container mt-4">
    <input type="hidden" name="id_pelanggan" value="<?php echo $id_pelanggan; ?>">
    <input type="hidden" name="nama_pelanggan" value="<?php echo $nama_pelanggan; ?>">


    <div class="row">
        <?php
        $total = 0; // Initialize total
        $existing_product_ids = array_column($pesanan_produk, 'id_produk'); // Get existing product IDs

        foreach ($pesanan_produk as $index => $produk):
            $total += $produk['sub_total'];
        ?>
            <div class="col-md-4 mb-3">
                <div class="card p-3">
                    <div class="form-group">
                        <label for="nama_produk_<?php echo $index; ?>" class="form-label">Nama Produk</label>
                        <input class="form-control fs-4" type="text" id="nama_produk_<?php echo $index; ?>" name="nama_produk[]"
                            value="<?php echo $produk['nama_produk']; ?>" readonly>
                    </div>

                    <div class="form-group mt-2">
                        <label for="jumlah_<?php echo $index; ?>" class="form-label">Jumlah</label>
                        <input class="form-control fs-4" type="number" id="jumlah_<?php echo $index; ?>" name="jumlah[]"
                            value="<?php echo $produk['jumlah']; ?>" required>
                    </div>

                    <input type="hidden" name="id_produk[]" value="<?php echo $produk['id_produk']; ?>">
                    <input type="hidden" name="harga[]" value="<?php echo $produk['harga']; ?>">
                    <input type="hidden" name="sub_total[]" value="<?php echo $produk['sub_total']; ?>">

                    <button type="submit" name="delete" value="<?php echo $index; ?>" class="btn btn-danger mt-3">Delete</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <input type="hidden" id="total" name="total" value="<?php echo $total; ?>" readonly>

    <div class="form-group">
        <label for="product_select">Pilih Produk:</label>
     
        <select name="new_product_id" id="product_select" class="form-select fs-5">
            <option value="">Select a product</option>
            <?php foreach ($available_products as $product): ?>
                <?php if (!in_array($product['id_produk'], $existing_product_ids)): ?>
                    <option value="<?php echo $product['id_produk']; ?>" data-price="<?php echo $product['harga']; ?>">
                        <?php echo $product['nama']; ?> - <?php echo $product['harga']; ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group mt-2">
        <label for="new_product_quantity">Jumlah:</label>
        <input type="number" name="new_product_quantity" id="new_product_quantity" class="form-control fs-5">
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-success">Update</button>
        <a href="<?php echo site_url('pesanan_produk'); ?>" class="btn btn-secondary">Batal</a>
    </div>
</form>

</div>