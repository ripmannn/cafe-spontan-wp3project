<form action="<?= base_url('produk/update') ?>" method="post" enctype="multipart/form-data">
    <div class="col-md-8">
        <div class="card mb-4">
            <h5 class="card-header">Edit Data Produk</h5>
            <div class="card-body demo-vertical-spacing demo-only-element">
                <div class="input-group">
                    <span class="input-group-text">Nama Produk</span>
                    <input type="text" class="form-control" name="nama" value="<?= $produk->nama ?>"" required>
                    <input type="hidden" name="id_produk" value="<?= $produk->id_produk ?>">
                </div>

                <div class="input-group">
                    <span class="input-group-text">Kode Produk</span>
                    <input type="text" class="form-control" name="kode_produk" value="<?= $produk->kode_produk ?>" required>
                </div>

                <div class="input-group">
                    <span class="input-group-text">Keterangan Produk</span>
                    <input type="text" class="form-control" name="keterangan" value="<?= $produk->keterangan ?>" required>
                </div>

                <div class="input-group">
                    <span class="input-group-text">Jenis Produk</span>
                    <select name="jenis" class="form-control">
                        <option value="makanan" <?php if ($produk->jenis === 'makanan') echo 'selected'; ?>>Makanan</option>
                        <option value="minuman" <?php if ($produk->jenis === 'minuman') echo 'selected'; ?>>Minuman</option>
                    </select>
                </div>

                <div class="input-group">
                    <span class="input-group-text">Stok Produk</span>
                    <input type="number" class="form-control" name="stok" value="<?= $produk->stok ?>" required>
                </div>

                <div class="input-group">
                    <span class="input-group-text">Harga Produk</span>
                    <input type="number" class="form-control" name="harga" value="<?= $produk->harga ?>" required>
                </div>

                <div class="input-group">
                    <span class="input-group-text">Gambar Produk</span>
                    <input type="file" class="form-control" name="gambar" value="<?= $produk->gambar ?>">
                </div>
                
                <div class="input-group">
                    <span class="input-group-text">Promo</span>
                    <select name="promo" class="form-control">
                        <option value="null" <?php if ($produk->promo === 'null') echo 'selected'; ?>>Tidak Ada Promo</option>
                        <option value="1" <?php if ($produk->promo === '1') echo 'selected'; ?>>Ada Promo</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>