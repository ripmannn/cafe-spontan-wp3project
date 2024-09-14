<?= $this->session->flashdata('pesan') ?>
<div class="row">
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                </div>
                <span class="fw-semibold d-block mb-1 text-primary">PENJUALAN HARI INI</span>
                <h3 class="card-title mb-2">Rp.<?= number_format($hari_ini) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                </div>
                <span class="fw-semibold d-block mb-1 text-info">PENJUALAN BULAN INI</span>
                <h3 class="card-title mb-2">Rp.<?= number_format($bulan_ini) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                </div>
                <span class="fw-semibold d-block mb-1 text-success">TRANSAKSI HARI INI </span>
                <h3 class="card-title mb-2"><?= number_format($transaksi) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                </div>
                <span class="fw-semibold d-block mb-1 text-warning">PRODUK</span>
                <h3 class="card-title mb-2"><?= number_format($produk) ?></h3>
            </div>
        </div>
    </div>
</div>