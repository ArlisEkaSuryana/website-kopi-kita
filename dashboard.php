<?php
    // admin/dashboard.php
    include 'header.php';
    include '../koneksi.php';

    // Hitung Statistik Sederhana
    $total_menu = $koneksi->query("SELECT COUNT(*) as total FROM menu")->fetch_assoc()['total'];
    $total_ulasan = $koneksi->query("SELECT COUNT(*) as total FROM ulasan")->fetch_assoc()['total'];
?>
<style>
    body {
            background-color: #e8b7e7ff;
    }

    /* Animasi muncul halus untuk konten dashboard */
    .fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Kartu utama */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    /* Gradasi warna untuk mempercantik tampilan */
    .bg-primary {
        background: #d173b3ff !important;
    }
    .bg-success {
        background: #388e9bff !important;
    }

    /* Judul kartu */
    .card-title {
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .card-text h1, .card-text {
        font-size: 3rem;
        font-weight: bold;
        margin: 0;
    }

    hr {
        border-top: 2px solid #eee;
        margin-bottom: 25px;
    }

    h1.h2 {
        font-weight: 700;
        color: #222;
        margin-top: 10px;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .card-text h1, .card-text {
            font-size: 2rem;
    }

    .main-content {
        margin-left: 220px;   /* agar tidak tertutup sidebar */
        padding-top: 70px;    /* agar tidak tertutup navbar */
        min-height: 100vh;
    }
}
</style>

        <img src="../assets/logo.png" alt="" width="40" class="me-2">Kopi Kita Aja

<h1 class="h2">Dashboard</h1>
<p>Selamat datang, **</b><?php echo htmlspecialchars($_SESSION['username']) ?>**</b>.</p>
<hr>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Menu</h5>
                <p class="card-text h1"><?php echo $total_menu ?></p>
                <a href="menu.php" class="text-white">Lihat Detail &raquo;</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Ulasan Pelanggan</h5>
                <p class="card-text h1"><?php echo $total_ulasan ?></p>
                <a href="ulasan.php" class="text-white">Lihat Detail &raquo;</a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>