<?php
include 'auth.php';
include 'config.php';
require_staff_or_owner();

$user_id = $_SESSION['user_id'];
$is_kasir = is_kasir();

// Aksi Kasir Mengambil atau Menyelesaikan Order
if (isset($_GET['action']) && $is_kasir) {
    $order_id = intval($_GET['order_id']);
    if ($_GET['action'] === 'claim') {
        mysqli_query($conn, "UPDATE orders SET cashier_id = $user_id, status = 'processed' WHERE id = $order_id AND cashier_id IS NULL");
    } elseif ($_GET['action'] === 'complete') {
        mysqli_query($conn, "UPDATE orders SET status = 'completed' WHERE id = $order_id AND cashier_id = $user_id");
    }
    header("Location: orders.php");
    exit;
}

// Filter Query: Kasir hanya melihat order miliknya atau order pending yang belum diambil
if ($is_kasir) {
    $sql = "SELECT o.*, u.username AS customer_name, k.username AS cashier_name 
            FROM orders o 
            LEFT JOIN users u ON o.customer_id = u.id 
            LEFT JOIN users k ON o.cashier_id = k.id 
            WHERE o.cashier_id = $user_id OR (o.cashier_id IS NULL AND o.status = 'pending')
            ORDER BY o.order_date DESC";
} else {
    // Owner dan Admin melihat SEMUA order
    $sql = "SELECT o.*, u.username AS customer_name, k.username AS cashier_name 
            FROM orders o 
            LEFT JOIN users u ON o.customer_id = u.id 
            LEFT JOIN users k ON o.cashier_id = k.id 
            ORDER BY o.order_date DESC";
}
$orders = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pesanan - Kopipiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= $is_kasir ? 'Kelola Pesanan Kasir' : 'Semua Pesanan Masuk' ?></h5>
                <?php if (is_owner()): ?>
                    <a href="export_excel.php" class="btn btn-success btn-sm">📥 Unduh Rekap Excel</a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Order</th>
                            <th>Waktu</th>
                            <th>Pelanggan</th>
                            <th>Total Bayar</th>
                            <th>Diskon</th>
                            <th>Kasir Penanggung Jawab</th>
                            <th>Status</th>
                            <?php if ($is_kasir): ?><th>Aksi</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($o = mysqli_fetch_assoc($orders)): ?>
                        <tr>
                            <td>#<?= $o['id'] ?></td>
                            <td><?= $o['order_date'] ?></td>
                            <td><?= $o['customer_name'] ? htmlspecialchars($o['customer_name']) : '<em>Guest (Tanpa Akun)</em>' ?></td>
                            <td>Rp <?= number_format($o['total_price'], 0, ',', '.') ?></td>
                            <td><?= $o['discount_applied'] ? '<span class="badge bg-success">10%</span>' : '-' ?></td>
                            <td><?= $o['cashier_name'] ? htmlspecialchars($o['cashier_name']) : '<span class="text-muted">Belum Ada</span>' ?></td>
                            <td>
                                <span class="badge <?= $o['status'] === 'completed' ? 'bg-success' : ($o['status'] === 'processed' ? 'bg-primary' : 'bg-warning text-dark') ?>">
                                    <?= ucfirst($o['status']) ?>
                                </span>
                            </td>
                            <?php if ($is_kasir): ?>
                            <td>
                                <?php if ($o['status'] === 'pending' && is_null($o['cashier_id'])): ?>
                                    <a href="orders.php?action=claim&order_id=<?= $o['id'] ?>" class="btn btn-sm btn-primary">Ambil Order</a>
                                <?php elseif ($o['status'] === 'processed' && $o['cashier_id'] == $user_id): ?>
                                    <a href="orders.php?action=complete&order_id=<?= $o['id'] ?>" class="btn btn-sm btn-success">Selesaikan</a>
                                <?php else: ?>
                                    <span class="text-muted small">Selesai</span>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
