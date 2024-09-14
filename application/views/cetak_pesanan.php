<!DOCTYPE html>
<html>
<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
    }
    th{
        background-color: grey;
        color: white;
    }
    th,td{
        font-size: 20px;
    }

</style>

<head>
    <title>Cetak Pesanan</title>
    <!-- Anda dapat menambahkan stylesheet atau tag style di sini untuk merancang tampilan tabel -->
</head>

<body>
    <center>
        <h1>SCREENSHOT PESANAN ANDA</h1>
        <h2>Segera Ke Kasir Untuk Pembayaran</h2> 
        <table border="1px" cellpadding="10" >
            <thead>
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $first = true; // Untuk menandai bahwa ini adalah baris pertama
                $total_rows = count($pesanan_produk);
                foreach ($pesanan_produk as $index => $k) {
                ?>
                    <tr>
                        <!-- Tampilkan nama pelanggan hanya pada baris pertama -->
                        <?php if ($first) { ?>
                            <td style="text-align: center; font-size:20px; font-weight:bold;" rowspan="<?= $total_rows; ?>"><?php echo $k['nama_pelanggan']; ?></td>
                            <td><?= $k['nama_produk']; ?></td>
                            <td><?= $k['jumlah']; ?></td>
                            <td>Rp. <?= number_format($k['harga']); ?></td>
                            <td>Rp. <?= number_format($k['sub_total']); ?></td>
                        <?php $first = false; // Setel $first menjadi false setelah baris pertama 
                        } else { ?>
                            <td><?= $k['nama_produk']; ?></td>
                            <td><?= $k['jumlah']; ?></td>
                            <td>Rp. <?= number_format($k['harga']); ?></td>
                            <td> Rp. <?= number_format($k['sub_total']); ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <!-- Tampilkan grand total hanya pada baris terakhir -->
                <tr>
                    <td colspan="4" style="text-align: left; font-size: 20px; background-color: #fcc309; "><strong>Grand total</strong></td>
                    <!-- Ambil total dari baris pertama, karena hanya itu yang menampilkan nama pelanggan -->
                    <td style="text-align: right; font-weight: bolder; font-size: 20px; background-color: #fcc309; ">Rp. <?= number_format($pesanan_produk[0]['total']); ?></td>
                </tr>
            </tfoot>
        </table>
        <h1>-------------------Terima Kasih-------------------</h1>
    </center>
    


</body>

<!-- <script>
    window.onload = function() {
        // Cek apakah session storage memiliki flag 'refreshed'
        if (sessionStorage.getItem('refreshed') === 'true') {
            // Hapus flag 'refreshed' dari session storage
            sessionStorage.removeItem('refreshed');
            
            // Kembali ke halaman sebelumnya
            history.back();
        } else {
            // Set flag 'refreshed' di session storage
            sessionStorage.setItem('refreshed', 'true');
        }
    };
</script> -->


<script>
    window.onload = function() {
        // Cek apakah session storage memiliki flag 'refreshed'
        if (sessionStorage.getItem('refreshed') === 'true') {
            // Hapus flag 'refreshed' dari session storage
            sessionStorage.removeItem('refreshed');
            
            // Kembali ke halaman sebelumnya
            history.back();
        } else {
            // Set flag 'refreshed' di session storage
            sessionStorage.setItem('refreshed', 'true');

            // Menampilkan prompt untuk mengambil tangkapan layar
            if (confirm("Jangan Lupa Screenshot Pesanannya Ya Ka")) {
                // Mengarahkan pengguna untuk mengambil tangkapan layar
                // Anda dapat menambahkan kode di sini untuk menangani pengambilan tangkapan layar
            }
        }
    };
</script>





</html>