<?php
include 'auth.php';
include 'config.php';
require_admin();

$id = intval($_GET['id']);
$stmt = mysqli_prepare($conn, "SELECT * FROM product WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$p = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$p) {
    die("Produk tidak ditemukan.");
}

if (isset($_POST['update'])) {
    $name = trim($_POST['name']);
    $price = intval($_POST['price']);
    $description = trim($_POST['description']);
    $rating = floatval($_POST['rating']);
    $image_path = $p['url_image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $filename = uniqid('prod_', true) . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $filename)) {
                $image_path = 'uploads/' . $filename;
            }
        }
    }

    $stmt_up = mysqli_prepare($conn, "UPDATE product SET name = ?, price = ?, description = ?, rating = ?, url_image = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt_up, "sisdsi", $name, $price, $description, $rating, $image_path, $id);
    mysqli_stmt_execute($stmt_up);

    header("Location: products.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - Kopipiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Edit Produk</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Nama Menu</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($p['name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" name="price" class="form-control" value="<?= $p['price'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <input type="number" step="0.1" max="5" name="rating" class="form-control" value="<?= $p['rating'] ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($p['description']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ganti Gambar (Opsional)</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="products.php" class="btn btn-secondary">Batal</a>
                                <button type="submit" name="update" class="btn btn-warning">Update Produk</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
