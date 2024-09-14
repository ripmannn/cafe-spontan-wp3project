<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Laporan | Cafe Spontan</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
      integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="<?= base_url('assets/css/stylelaporan.css') ?>" />
  </head>
  <body>
    <div class="invoice-wrapper" id="print-area">
      <div class="invoice">
        <div class="invoice-container">
          <div class="invoice-head">
            <div class="invoice-head-top">
              <div class="invoice-head-top-left text-start">
                <h1>Cafe Spontan</h1>
              </div>
              <div class="invoice-head-top-right text-end">
                <h3>Laporan Penjualan</h3>
              </div>
            </div>
            <div class="hr"></div>
            <div class="invoice-head-middle">
              <div class="invoice-head-middle-left text-start">
                <p><span class="text-bold">Tanggal</span>: <span class="span"><?= date_format(date_create($tanggal1), "d F Y") ?></span> Sampai <span class="span"> <?= date_format(date_create($tanggal2), "d F Y") ?></span> </p>
              </div>
            </div>
            <div class="hr"></div>
            <div class="invoice-head-bottom">
              <div class="invoice-head-bottom-left">
                <ul>
                  <li class="text-bold">Cafe Spontan</li>
                  <li>Jl. Latu Menten Season City</li>
                  <li>Phone : (0815-1095-8001)</li>
                  <li>Email : spontancafe@gmail.com</li>
                  <li>Jakarta Barat</li>
                </ul>
              </div>
            </div>
          </div>

          <div class="invoice-body">
            <table>
              <thead>
                <tr>
                  <td class="text-bold">No</td>
                  <td class="text-bold">Tanggal</td>
                  <td class="text-bold">No Nota</td>
                  <td class="text-bold">Pelanggan</td>
                  <td class="text-bold">Nominal</td>
                </tr>
              </thead>
              <tbody>
              <?php $total = 0;
                $no = 1;
                foreach ($penjualan as $p) {  ?>
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $p['kode_penjualan'] ?></td>
                        <td><?= date_format(date_create($p['tanggal']), "d M Y") ?></td>
                        <td><?= !empty($p['nama']) ? $p['nama'] : 'fulan' ?></td>
                        <td style="text-align: right;">Rp.<?= number_format($p['total_harga']) ?></td>

                    </tr>
                <?php $total = $total + $p['total_harga'];
                    $no++;
                } ?>
              </tbody>
            </table>
            <div class="invoice-body-bottom">
              <div class="invoice-body-info-item">
                <div class="info-item-td text-start text-bold">Total:</div>
                <div class="info-item-td text-end">Rp.<?= number_format($total) ?></div>
              </div>
            </div>
          </div>

          <div class="invoice-foot text-center">
            <div class="invoice-btns">
              <button type="button" class="invoice-btn" onclick="printInvoice()">
                <span>
                  <i class="fa-solid fa-print"></i>
                </span>
                <span>Print</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      function printInvoice() {
        window.print();
      }
    </script>
  </body>
</html>
