<?php
include 'auth.php';
include 'config.php';
require_owner();

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Laporan_Penjualan_Kopipiro_" . date('Ymd') . ".xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

$sql = "SELECT o.id, o.order_date, u.username AS customer, o.total_price, o.discount_applied, k.username AS cashier, o.status 
        FROM orders o 
        LEFT JOIN users u ON o.customer_id = u.id 
        LEFT JOIN users k ON o.cashier_id = k.id 
        ORDER BY o.order_date DESC";
$result = mysqli_query($conn, $sql);
?>
<table border="1">
    <thead>
        <tr style="background-color: #212529; color: white;">
            <th>ID Pesanan</th>
            <th>Tanggal & Jam</th>
            <th>Pelanggan</th>
            <th>Total Bayar (Rp)</th>
            <th>Diskon Terpakai</th>
            <th>Kasir</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($r = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td>#<?= $r['id'] ?></td>
            <td><?= $r['order_date'] ?></td>
            <td><?= $r['customer'] ? $r['customer'] : 'Guest' ?></td>
            <td><?= $r['total_price'] ?></td>
            <td><?= $r['discount_applied'] ? 'Ya (10%)' : 'Tidak' ?></td>
            <td><?= $r['cashier'] ? $r['cashier'] : '-' ?></td>
            <td><?= ucfirst($r['status']) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
