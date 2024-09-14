<div class="mt-1 mb-3 ">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary  " data-bs-toggle="modal" data-bs-target="#basicModal">
        <i class='bx bx-plus'></i> <span>Tambah Pelanggan</span>
    </button>
    <div class="mt-3"><?= $this->session->flashdata('pesan') ?></div>


    <!-- Modal -->
    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" style="color: white;" id="exampleModalLabel1">Tambah Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('pelanggan/simpan') ?>" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-label">Nama Pelanggan</label>
                                <input type="text" name="nama" class="form-control" placeholder="Nama Pelanggan" pattern="[a-zA-Z\s]+" required>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label class="form-label">Alamat</label>
                                <input type="text" name="alamat" class="form-control" placeholder="Alamat" value="Jakarta" required>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label class="form-label">No Telp (WA)</label>
                                <input type="number" id="telp" name="telp" class="form-control" placeholder="Telpon" required>
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
    <h5 class="card-header">Pelanggan</h5>
    <div class="table-responsive text-nowrap">
        <table id="myTable" class="table">
            <thead>
                <tr>
                    <th style="text-align: left;">NO</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th style="text-align: left;">Telp</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php $no = 1;
                foreach ($pelanggan as $p) {  ?>
                    <tr>
                        <td style="text-align: left;"><?= $no ?></td>
                        <td><?= $p['nama'] ?></td>
                        <td><?= $p['alamat'] ?></td>
                        <td style="text-align: left;"><?= $p['telp'] ?></td>
                        
                        <td>
                            <a href="<?= base_url('pelanggan/edit/' . $p['id_pelanggan']) ?>" class="btn-sm btn-primary">Edit</a>

                            <a style="margin:10px;"onclick="return confirm('Apakah anda yakin menghapus Data Ini?')"
                            href="<?= base_url('pelanggan/hapus/' . $p['id_pelanggan']) ?>" class="btn-sm btn-danger">Hapus</a>
                        </td>
                    </tr>
                <?php $no++;
                } ?>
            </tbody>
        </table>
    </div>
</div>