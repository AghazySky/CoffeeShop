<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function is_owner() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'owner';
}

function is_kasir() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'kasir';
}

function is_customer() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'customer';
}

function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

function require_admin() {
    require_login();
    if (!is_admin()) {
        http_response_code(403);
        die("403 - Akses ditolak. Halaman khusus Admin.");
    }
}

function require_owner() {
    require_login();
    if (!is_owner()) {
        http_response_code(403);
        die("403 - Akses ditolak. Halaman khusus Owner.");
    }
}

function require_staff_or_owner() {
    require_login();
    if (!is_kasir() && !is_owner() && !is_admin()) {
        http_response_code(403);
        die("403 - Akses ditolak.");
    }
}
?>
