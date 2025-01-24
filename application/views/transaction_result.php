<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Transaksi</title>
</head>
<body>

    <h1>Status Transaksi</h1>
    
    <?php if (isset($result->transaction_status)): ?>
        <h2><?php echo ucfirst($result->transaction_status); ?></h2>
        <p><?php echo $result->status_message; ?></p>
        
        <?php if ($result->transaction_status === 'pending'): ?>
            <p>Silakan transfer ke nomor virtual account: <strong><?php echo $result->va_numbers[0]->va_number; ?></strong> untuk bank: <strong><?php echo $result->va_numbers[0]->bank; ?></strong>.</p>
            <p>Anda dapat mengunduh bukti transaksi di sini: <a href="<?php echo $result->pdf_url; ?>">Bukti Transaksi</a></p>
            <p>Setelah melakukan pembayaran, silakan kembali ke <a href="<?php echo $result->finish_redirect_url; ?>">sini</a>.</p>
            
           
        <?php endif; ?>

    <?php else: ?>
        <h2>Status Transaksi Tidak Ditemukan</h2>
    <?php endif; ?>

</body>
</html>
