<?php
include 'auth.php';
include 'config.php';
require_owner();

// Mengambil total omzet harian untuk Chart.js
$sql = "SELECT DATE(order_date) as tgl, SUM(total_price) as total_omzet 
        FROM orders 
        WHERE status = 'completed'
        GROUP BY DATE(order_date) 
        ORDER BY tgl ASC LIMIT 7";
$chart_data = mysqli_query($conn, $sql);

$labels = [];
$totals = [];
while ($row = mysqli_fetch_assoc($chart_data)) {
    $labels[] = $row['tgl'];
    $totals[] = (int)$row['total_omzet'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Owner - Kopipiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Statistik & Performa Penjualan Kopipiro</h2>
            <a href="export_excel.php" class="btn btn-success">📥 Unduh Laporan Excel</a>
        </div>

        <div class="card shadow-sm p-4">
            <h5 class="card-title mb-3">Tren Pendapatan Harian (Pesanan Sukses)</h5>
            <canvas id="salesChart" height="100"></canvas>
        </div>
    </div>

    <script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: <?= json_encode($totals) ?>,
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(val) { return 'Rp ' + val.toLocaleString('id-ID'); }
                    }
                }
            }
        }
    });
    </script>
</body>
</html>
