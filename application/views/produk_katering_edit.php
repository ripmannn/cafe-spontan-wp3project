<form action="<?= base_url('produk_katering/update') ?>" method="post" enctype="multipart/form-data">
    <div class="col-md-8">
        <div class="card mb-4">
            <h5 class="card-header">Edit Data Produk Teman Katering</h5>
            <div class="card-body demo-vertical-spacing demo-only-element">

                <div class="alert alert-warning" role="alert">
                    <strong>Perhatian!</strong> Jika tidak ada perubahan pada data, Anda dapat langsung menekan tombol
                    <strong>Kembali</strong>.
                </div>
                <div class="input-group">
                    <span class="input-group-text">Nama Produk</span>
                    <input type="text" class="form-control" name="nama" value="<?= $produkid['name'] ?>"" required>
                    <input style=" display: none;" type=" hidden" name="id_produk" value="<?= $produkid['d_id'] ?>">
                </div>


                <div class="input-group">
                    <span class="input-group-text">Keterangan Produk</span>
                    <textarea class="form-control" name="keterangan" required><?= $produkid['about'] ?></textarea>
                </div>


                <div class="input-group">
                    <span class="input-group-text">Harga Produk</span>
                    <input type="number" class="form-control" name="harga" value="<?= $produkid['price'] ?>" required>
                </div>

                <a href="<?= base_url('produk_katering') ?>" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </div>
</form>