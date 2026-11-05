<?php
include 'auth.php';
include 'config.php';

if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit;
}

$is_member = is_logged_in() && $_SESSION['role'] === 'customer';
$discount_rate = $is_member ? 0.10 : 0.00;

$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += ($item['price'] * $item['qty']);
}

$discount_amount = $subtotal * $discount_rate;
$final_total = $subtotal - $discount_amount;
$success_order_id = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    $cust_id = is_logged_in() ? $_SESSION['user_id'] : null;
    $disc_flag = $is_member ? 1 : 0;
    
    $stmt = mysqli_prepare($conn, "INSERT INTO orders (customer_id, total_price, discount_applied, status) VALUES (?, ?, ?, 'pending')");
    mysqli_stmt_bind_param($stmt, "iii", $cust_id, $final_total, $disc_flag);
    mysqli_stmt_execute($stmt);
    $order_id = mysqli_insert_id($conn);

    $stmt_item = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, qty, price_at_checkout) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $p_id => $item) {
        $price_recorded = $is_member ? round($item['price'] * 0.9) : $item['price'];
        mysqli_stmt_bind_param($stmt_item, "iiii", $order_id, $p_id, $item['qty'], $price_recorded);
        mysqli_stmt_execute($stmt_item);
    }

    unset($_SESSION['cart']);
    $success_order_id = $order_id;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Kopipiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <?php if ($success_order_id): ?>
            <div class="card text-center p-5 shadow-sm">
                <h2 class="text-success fw-bold">Pesanan Berhasil Dibuat!</h2>
                <p class="lead">ID Pesanan Anda: <strong>#<?= $success_order_id ?></strong></p>
                <p class="text-muted">Kasir kami akan segera memproses pesanan Anda.</p>
                <div>
                    <a href="index.php" class="btn btn-primary">Kembali ke Halaman Utama</a>
                </div>
            </div>
        <?php else: ?>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h4 class="mb-0">Ringkasan Checkout</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush mb-3">
                                <?php foreach ($_SESSION['cart'] as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="my-0"><?= htmlspecialchars($item['name']) ?></h6>
                                        <small class="text-muted"><?= $item['qty'] ?>x @ Rp <?= number_format($item['price'], 0, ',', '.') ?></small>
                                    </div>
                                    <span class="text-muted">Rp <?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?></span>
                                </li>
                                <?php endforeach; ?>

                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Subtotal</span>
                                    <strong>Rp <?= number_format($subtotal, 0, ',', '.') ?></strong>
                                </li>
                                
                                <li class="list-group-item d-flex justify-content-between text-success">
                                    <div>
                                        <h6 class="my-0">Diskon Pelanggan Login (10%)</h6>
                                        <small><?= $is_member ? 'Diskon Aktif' : 'Login diperlukan' ?></small>
                                    </div>
                                    <span>- Rp <?= number_format($discount_amount, 0, ',', '.') ?></span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between fs-5 bg-light">
                                    <span>Total Bayar</span>
                                    <strong class="text-primary">Rp <?= number_format($final_total, 0, ',', '.') ?></strong>
                                </li>
                            </ul>

                            <?php if (!$is_member): ?>
                                <div class="alert alert-warning small">
                                    Anda memesan sebagai <strong>Tamu (Guest)</strong> tanpa diskon. Ingin hemat 10%? <a href="login.php">Login di sini</a>.
                                </div>
                            <?php endif; ?>

                            <form method="POST">
                                <button type="submit" name="confirm_order" class="btn btn-success w-100 py-2">Konfirmasi & Pesan Sekarang</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
