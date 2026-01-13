<?php 
                              // admin/login.php 
    include 'header.php';     // Ambil header (tanpa sidebar) 
    include '../koneksi.php'; // Ambil data admin 
 
    $error = ''; 
 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
        $username = $_POST['username']; 
        $password = $_POST['password']; 
 
        // Cek kredensial 
        if ($username === ADMIN_USER && $password === ADMIN_PASS) { 
            $_SESSION['user_logged_in'] = true; 
            $_SESSION['username']       = $username; 
            header('Location: dashboard.php'); 
            exit(); 
        } else { 
            $error = 'Username atau Password salah!'; 
        } 
    } 
?> 
 
<div class="container" style="max-width: 400px; margin-top: 100px;"> 
    <h3 class="text-center mb-4">Admin Login</h3> 
    <?php if ($error): ?> 
    <div class="alert alert-danger" role="alert"> 
        <?php echo $error ?> 
    </div> 
    <?php endif; ?> 
    <form method="POST" action=""> 
        <div class="form-group"> 
            <label for="username">Username</label> 
            <input type="text" class="form-control" id="username" name="username" required> 
        </div> 
        <div class="form-group"> 
            <label for="password">Password</label> 
            <input type="password" class="form-control" id="password" name="password" required> 
        </div> 
        <button type="submit" class="btn btn-primary btnblock">Login</button> 
    </form> 
</div> 
 
<?php include 'footer.php'; ?> 