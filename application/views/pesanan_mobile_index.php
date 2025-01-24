<div class="mt-1 mb-3">
    <!-- Display Flash Message -->
    <div class="mt-3"><?= $this->session->flashdata('pesan') ?></div>
</div>

<div class="card">
    <h5 class="card-header">Pesanan Mobile</h5>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th class="fs-6" >NO</th>
                    <th class="fs-6" >Nama</th>
                    <th class="fs-6" >No Meja</th>
                    <th class="fs-6" >Total Harga</th>
                    <th class="fs-6" >Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <!-- Loop through the $pesanan array -->
                <?php
                $no = 1;
                foreach ($pesanan as $key => $value) {
                    $total = $value['total']; // Assuming the total is already calculated
                    echo "<tr style='font-size: 18px;'>  <!-- Increase font size for the entire row -->
                    <td>{$no}</td>
                    <td>{$value['name']}</td>
                    <td>{$value['table_number']}</td>
                    <td>Rp. " . number_format($total, 0, ',', '.') . "</td>
                    <td>
                        <a href='" . base_url('pesanan_mobile/detail/' . $value['transaction_id']) . "' class='btn btn-warning m-3' style='font-size: 18px;'>Detail</a> <!-- Increase font size for the button -->
                        <a href='" . base_url('pesanan_mobile/delete/' . $value['transaction_id']) . "' class='btn btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\");' style='font-size: 18px;'>Delete</a> <!-- Increase font size for the button -->
                    </td>
                  </tr>";
;
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
