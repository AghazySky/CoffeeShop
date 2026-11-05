<?php
include 'auth.php';
include 'config.php';

$query = "SELECT * FROM product ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kopipiro - Menu Kopi & Makanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container my-5">
        <div class="p-4 mb-4 bg-white rounded-3 shadow-sm border text-center">
            <h1 class="display-6 fw-bold">Selamat Datang di Kopipiro</h1>
            <p class="lead mb-2">Nikmati aneka kopi pilihan, minuman non-kafein, toast, dan camilan lezat.</p>
            <?php if (!is_logged_in()): ?>
                <div class="alert alert-info d-inline-block py-1 px-3 mb-0">
                    💡 <strong>Tips:</strong> Register dan Login untuk mendapatkan <strong>Diskon 10%</strong> langsung saat pemesanan!
                </div>
            <?php else: ?>
                <div class="alert alert-success d-inline-block py-1 px-3 mb-0">
                    🎉 Anda berhak mendapatkan <strong>Diskon 10%</strong> untuk setiap pesanan!
                </div>
            <?php endif; ?>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="<?= htmlspecialchars($row['url_image']) ?>" class="card-img-top" style="height: 220px; object-fit: cover;" alt="<?= htmlspecialchars($row['name']) ?>">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0"><?= htmlspecialchars($row['name']) ?></h5>
                            <span class="badge bg-warning text-dark">⭐ <?= htmlspecialchars($row['rating'] ?? '4.8') ?></span>
                        </div>
                        <p class="card-text text-muted small flex-grow-1">
                            <?= htmlspecialchars($row['description'] ?? 'Pilihan menu nikmat dari Kopipiro.') ?>
                        </p>
                        
                        <div class="mb-3">
                            <?php if (is_logged_in() && $_SESSION['role'] === 'customer'): ?>
                                <span class="text-decoration-line-through text-muted small">Rp <?= number_format($row['price'], 0, ',', '.') ?></span><br>
                                <span class="fs-5 fw-bold text-success">Rp <?= number_format($row['price'] * 0.9, 0, ',', '.') ?></span>
                                <span class="badge bg-danger ms-1">-10%</span>
                            <?php else: ?>
                                <span class="fs-5 fw-bold text-dark">Rp <?= number_format($row['price'], 0, ',', '.') ?></span>
                            <?php endif; ?>
                        </div>

                        <form action="cart.php" method="POST" class="d-flex gap-2">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                            <input type="number" name="qty" value="1" min="1" class="form-control" style="width: 75px;">
                            <button type="submit" class="btn btn-primary flex-grow-1">+ Pesan</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
