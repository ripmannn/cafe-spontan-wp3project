<div class="mt-1 mb-3 ">

    <div class="mt-3"><?= $this->session->flashdata('pesan') ?></div>

</div>

<div class="card p-3">
    <h5 class="card-header">Produk Teman Katering</h5>
    <div class="table-responsive text-nowrap">
        <table id="myTable" class="table">
            <thead>
                <tr>
                    <th style="text-align: left;">NO</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th style="text-align: center;">Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php $no = 1;
                foreach ($produk as $p) { ?>
                    <tr>
                        <td style="text-align: left;"><?= $no ?></td>
                        <td><?= $p['name'] ?></td>
                        <td>Rp.<?= number_format($p['price']) ?></td>
                        <td style="text-align: center;">
                            <img style="width: 100px;"
                                src="<?= base_url('assets/gambar/' . $p['img']) ?>" alt="">
                        </td>

                        <td>
                            <a href="<?= base_url('produk_katering/edit/' . $p['d_id']) ?>"
                                class="btn-sm btn-primary">Edit</a>

                            <a style="margin-left: 10px;" onclick="return confirm('Apakah anda yakin menghapus Data Ini?')"
                                href="<?= base_url('produk_katering/hapus/' . $p['d_id']) ?>"
                                class="btn-sm btn-danger">Hapus</a>
                        </td>
                    </tr>
                    <?php $no++;
                } ?>
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="printModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" style="color:white;" id="exampleModalLabel1">Laporan Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('produk/print') ?>" method="get" target="_blank">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label" for="status">Stok</label>
                            <select name="status" id="status" class="form-control">
                                <option value="Ada">Ada</option>
                                <option value="Habis">Habis</option>
                                <option value="Semua">Semua</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-warning">Print</button>
                </div>
            </form>
        </div>
    </div>
</div>