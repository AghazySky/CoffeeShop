<?php
include 'auth.php';
include 'config.php';
require_admin();

$error = "";

if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $price = intval($_POST['price']);
    $description = trim($_POST['description']);
    $rating = floatval($_POST['rating']);
    $image_path = "";

    // Logika Upload Gambar File
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            $filename = uniqid('prod_', true) . '.' . $ext;
            $destination = 'uploads/' . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image_path = $destination;
            }
        }
    }

    if (empty($image_path) && !empty($_POST['url_image'])) {
        $image_path = trim($_POST['url_image']);
    }

    if (!empty($name) && $price > 0 && !empty($image_path)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO product (name, price, description, rating, url_image) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sisds", $name, $price, $description, $rating, $image_path);
        mysqli_stmt_execute($stmt);

        header("Location: products.php");
        exit;
    } else {
        $error = "Semua field bertanda wajib diisi (termasuk upload gambar).";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - Kopipiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Tambah Produk Baru</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Nama Menu / Produk</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" name="price" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rating Awal (Contoh: 4.8)</label>
                                <input type="number" step="0.1" max="5" name="rating" value="4.8" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Unggah Gambar (File)</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Atau URL Gambar Eksternal (Opsional)</label>
                                <input type="url" name="url_image" class="form-control">
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="products.php" class="btn btn-secondary">Kembali</a>
                                <button type="submit" name="submit" class="btn btn-success">Simpan Produk</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
