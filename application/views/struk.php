<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nota Pelanggan</title>
</head>

<body>
    ===========================<br>
    <b>Cafe Spontan</b> <br>
    Jl. Latu Menten Season City <br>
    Telp 0815-1095-8001 (Azriel) <br>
    =========================== <br>
    
    <table>
        <tr>
            <td>No.Nota</td>
            <td> : #<?= $nota ?></td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td> : <?= $penjualan->nama; ?></td>
        </tr>
    </table>
    =========================== <br>
    <table>
        <?php $total = 0;
        $no = 1;
        $item = 0;
        foreach ($detail as $d) { ?>
            <tr>
                <td colspan="3"><?= $d['nama'] ?></td>
            </tr>
            <tr>
                <td><?= $d['jumlah'] ?> PCS</td>
                <td style="text-align: right;"> Rp.<?= number_format($d['harga']) ?></td>
                <td style="text-align: right;"> Rp.<?= number_format($d['jumlah'] * $d['harga']) ?></td>
            </tr>
        <?php $total = $total + $d['jumlah'] * $d['harga'];
            $item = $item + $d['jumlah'];
            $no++;
        } ?>
    </table>
    =========================== <br>
    <table>
        <tr>
            <td>Total Tagihan :</td>
            <td style="text-align: right;"> Rp.<?= number_format($total); ?></td>
        </tr>
    </table>
    =========================== <br>
    Jumlah Item : <?= $item ?> PCS <br>
    =========================== <br>
    -------TERIMA KASIH-------
</body>

<script>
    window.print();
</script>

</html>