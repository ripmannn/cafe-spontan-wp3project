<form action="<?= base_url('pelanggan/update') ?>" method="post">
    <div class="col-md-8">
        <div class="card mb-4">
            <h5 class="card-header">Edit Data Pelanggan</h5>
            <div class="card-body demo-vertical-spacing demo-only-element">
                <div class="input-group">
                    <span class="input-group-text">Nama</span>
                    <input type="text" name="nama" class="form-control" value="<?= $pelanggan->nama ?>" required>
                    <input type="hidden" name="id_pelanggan" value="<?= $pelanggan->id_pelanggan ?>">
                </div>

                <div class="input-group">
                    <span class="input-group-text">Alamat</span>
                    <input type="text" class="form-control" value="<?= $pelanggan->alamat ?>" name="alamat" required>
                </div>

                <div class="input-group">
                    <span class="input-group-text">Telp</span>
                    <input type="text" class="form-control" value="<?= $pelanggan->telp ?>" name="telp" required>
                </div>

                
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>