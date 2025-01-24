<div class="mt-1 mb-3 ">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary  " data-bs-toggle="modal" data-bs-target="#basicModal">
        <i class='bx bx-plus'></i> <span>Tambah Pengguna</span>
    </button>
    <div class="mt-3"><?= $this->session->flashdata('pesan') ?></div>


    <!-- Modal -->
    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" style="color: white;" id="exampleModalLabel1">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('pengguna/simpan') ?>" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-label" for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Username" autocomplete="username" required>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label class="form-label" for="password" >Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="col mb-0">
                                <label class="form-label" for="level" >Level</label>
                                <select name="level" id="level" class="form-control">
                                    <option value="Admin">Admin</option>
                                    <option value="Kasir">Kasir</option>
                                    <option value="Dapur">Dapur</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-label" for="nama">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Anda" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <h5 class="card-header">Data Pengguna</h5>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php $no = 1;
                foreach ($user as $u) {  ?>
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $u['nama'] ?></td>
                        <td><?= $u['username'] ?></td>
                        <td><?= $u['level'] ?></td>
                        <td>
                            <a href="<?= base_url('pengguna/edit/' . $u['id_user']) ?>" class="btn-sm btn-primary">Edit</a>

                            <a onclick="return confirm('Apakah anda yakin Mereset Password Ini?')" href="<?= base_url('pengguna/reset/' . $u['id_user']) ?>" class="btn-sm btn-warning">Reset</a>

                            <a onclick="return confirm('Apakah anda yakin menghapus Data Ini?')" href="<?= base_url('pengguna/hapus/' . $u['id_user']) ?>" class="btn-sm btn-danger">Hapus</a>
                        </td>
                    </tr>
                <?php $no++;
                } ?>
            </tbody>
        </table>
    </div>
</div>