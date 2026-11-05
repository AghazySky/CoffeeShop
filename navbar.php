<?php include_once 'auth.php'; ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">☕ Kopipiro</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Katalog Menu</a>
                </li>
                <?php if (is_admin()): ?>
                    <li class="nav-item"><a class="nav-link" href="products.php">Kelola Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="users.php">Kelola User</a></li>
                <?php endif; ?>
                <?php if (is_kasir()): ?>
                    <li class="nav-item"><a class="nav-link" href="orders.php">Pesanan Saya</a></li>
                <?php endif; ?>
                <?php if (is_owner()): ?>
                    <li class="nav-item"><a class="nav-link" href="orders.php">Semua Pesanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Statistik Penjualan</a></li>
                <?php endif; ?>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <a href="cart.php" class="btn btn-outline-warning btn-sm position-relative">
                    🛒 Keranjang
                    <?php 
                    $count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0;
                    if ($count > 0): 
                    ?>
                        <span class="badge bg-danger rounded-pill"><?= $count ?></span>
                    <?php endif; ?>
                </a>
                <?php if (is_logged_in()): ?>
                    <span class="text-white small ms-2">
                        <?= htmlspecialchars($_SESSION['username']) ?>
                        <span class="badge bg-secondary"><?= $_SESSION['role'] ?></span>
                    </span>
                    <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light btn-sm">Login</a>
                    <a href="register.php" class="btn btn-warning btn-sm">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
