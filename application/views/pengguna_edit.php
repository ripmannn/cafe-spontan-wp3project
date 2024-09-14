<form action="<?= base_url('pengguna/update') ?>" method="post">
    <div class="col-md-8">
        <div class="card mb-4">
            <h5 class="card-header">Edit Data Pengguna</h5>
            <div class="card-body demo-vertical-spacing demo-only-element">
                <div class="input-group">
                    <span class="input-group-text">Username</span>
                    <input type="text" class="form-control" value="<?= $user->username ?>" readonly>
                    <input type="hidden" name="id_user" value="<?= $user->id_user ?>">
                </div>

                <div class="input-group">
                    <span class="input-group-text">Nama</span>
                    <input type="text" class="form-control" value="<?= $user->nama ?>" name="nama" required>
                </div>

                <div class="input-group">
                    <span class="input-group-text">Level</span>
                    <select name="level" class="form-control" required>
                        <option value="Admin" <?php if($user->level == 'Admin'){echo "selected";} ?> >Admin</option>
                        <option value="Kasir" <?php if($user->level == 'Kasir'){echo "selected";} ?>>Kasir</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

