<?php
include 'auth.php';
include 'config.php';
require_admin();

$result = mysqli_query($conn, "SELECT * FROM product ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk - Kopipiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Produk & Menu</h5>
                <a href="product_create.php" class="btn btn-success btn-sm">+ Tambah Produk Baru</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Rating</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><img src="<?= htmlspecialchars($row['url_image']) ?>" width="60" height="60" style="object-fit:cover; border-radius:4px;"></td>
                            <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                            <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                            <td>⭐ <?= htmlspecialchars($row['rating'] ?? '5.0') ?></td>
                            <td class="text-center">
                                <a href="product_edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="product_delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
