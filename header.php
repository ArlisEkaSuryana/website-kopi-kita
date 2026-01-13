<?php
//admin/header.php
session_start();

//cek autentikasi untuk semua halaman admin (kecuali login)
$current_page = basename($_SERVER['PHP_SELF']);
if($current_page != 'login.php' && ! isset($_SESSION['user_logged_in'])) {
    header('location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <style>

        .navbar {
            background: linear-gradient(90deg, #d173b3ff, #388e9bff);
            box-shadow: 0 2px 8px rgba(49, 46, 46, 0.2);
            border-bottom: 3px solid #d7ccc8;
        }
        .sidebar { 
            height: 100vh;
            position: fixed;
            top: 56px; /* Tinggi navbar */
            left: 0;
            background-color: #f7f3ee;
            padding-top: 20px;
            border-right: 2px solid #d3c7b3;
        /* Tinggi navbar */ 
        } 
 
        .main-content { 
        padding-top: 70px;
        margin-left: 220px;   /* agar tidak tertutup sidebar */
        min-height: 100vh;  
        }

        .sidebar a {
        display: block;
        color: #cb2882ff;
        font-weight: 500;
        padding: 10px 20px;
        text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #ee8ee1ff;
            color: white;
            border-radius: 10px;
        }


    </style>
</head>
<body>
     <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top"> 
        <a class="navbar-brand" href="dashboard.php">Kopi Kita Admin</a> 
        <button class="navbar-toggler" type="button" datatoggle="collapse" data-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" arialabel="Toggle navigation"> 
            <span class="navbar-toggler-icon"></span> 
        </button> 
        <div class="collapse navbar-collapse" id="navbarNav"> 
            <ul class="navbar-nav ml-auto"> 
                <?php if (isset($_SESSION['user_logged_in'])): ?> 
                <li class="nav-item"> 
                    <a class="nav-link" href="logout.php">Logout</a> 
                </li> 
                <?php endif; ?> 
            </ul> 
        </div> 
    </nav>

    <?php if (isset($_SESSION['user_logged_in'])): ?> 
    <div class="container-fluid"> 
        <div class="row"> 
            <nav class="col-md-2 d-none d-md-block bg-light sidebar"> 
                <div class="sidebar-sticky"> 
                    <ul class="nav flex-column"> 
                        <li class="nav-item"> 
                            <a class="nav-link active" href="dashboard.php">Dashboard</a> 
                        </li> 
                        <li class="nav-item"> 
                            <a class="nav-link" href="menu.php">Kelola Menu</a> 
                        </li> 
                        <li class="nav-item"> 
                            <a class="nav-link" href="ulasan.php">Kelola Ulasan</a> 
                        </li> 
                    </ul> 
                </div> 
            </nav> 
 
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 main-content"> 
                <?php endif; ?>
</body>
</html>
