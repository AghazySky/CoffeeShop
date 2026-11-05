<?php
include 'auth.php';
include 'config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tambah Produk ke Keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $product_id = intval($_POST['product_id']);
    
    if ($_POST['action'] === 'add') {
        $qty = max(1, intval($_POST['qty']));
        
        $stmt = mysqli_prepare($conn, "SELECT id, name, price, url_image FROM product WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $prod = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if ($prod) {
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['qty'] += $qty;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'id' => $prod['id'],
                    'name' => $prod['name'],
                    'price' => $prod['price'],
                    'url_image' => $prod['url_image'],
                    'qty' => $qty
                ];
            }
        }
    } elseif ($_POST['action'] === 'remove') {
        unset($_SESSION['cart'][$product_id]);
    }
    
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja - Kopipiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h3 class="mb-4">Keranjang Pemesanan</h3>
        <?php if (empty($_SESSION['cart'])): ?>
            <div class="card p-4 text-center">
                <p class="text-muted mb-3">Keranjang Anda masih kosong.</p>
                <div>
                    <a href="index.php" class="btn btn-primary">Lihat Menu</a>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Produk</th>
                                <th>Harga Satuan</th>
                                <th style="width: 100px;">Jumlah</th>
                                <th>Subtotal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $subtotal_all = 0;
                            foreach ($_SESSION['cart'] as $id => $item): 
                                $subtotal = $item['price'] * $item['qty'];
                                $subtotal_all += $subtotal;
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= htmlspecialchars($item['url_image']) ?>" width="50" height="50" style="object-fit: cover; border-radius: 6px;">
                                        <span class="fw-bold"><?= htmlspecialchars($item['name']) ?></span>
                                    </div>
                                </td>
                                <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                <td><?= $item['qty'] ?></td>
                                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <form action="cart.php" method="POST">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="product_id" value="<?= $id ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center bg-white p-3">
                    <a href="index.php" class="btn btn-outline-secondary">+ Tambah Item Lain</a>
                    <div>
                        <span class="me-3 fs-5">Subtotal: <strong>Rp <?= number_format($subtotal_all, 0, ',', '.') ?></strong></span>
                        <a href="checkout.php" class="btn btn-success">Lanjut ke Checkout &rarr;</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
