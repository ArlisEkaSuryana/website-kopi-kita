<?php 
    // admin/menu.php 
    include 'header.php'; 
    include '../koneksi.php'; 
 
    $action  = $_GET['action'] ?? 'list'; // Tentukan aksi: list, add, edit, delete 
    $id      = $_GET['id'] ?? null; 
    $message = ''; 
 
    // --- Logika CRUD --- 
 
    // 1. CREATE (Tambah Menu) 
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_menu'])) { 
        $nama      = $_POST['nama']; 
        $deskripsi = $_POST['deskripsi']; 
        $harga     = $_POST['harga']; 
        $kategori  = $_POST['kategori']; 
        $nama_file = ''; 
 
        // Proses Upload Gambar 
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) { 
            $target_dir  = "../assets/"; // Sesuaikan dengan folder assets kamu 
            $file_name   = basename($_FILES["gambar"]["name"]); 
            $target_file = $target_dir . $file_name; 
 
            // Pindahkan file yang diupload 
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) { 
                $nama_file = $file_name; 
            } else { 
                $message = "<div class='alert alertdanger'>Gagal upload gambar.</div>"; 
            } 
        } 
 
        if (! $message) { 
            $stmt = $koneksi->prepare("INSERT INTO menu (nama, deskripsi, harga, kategori, gambar) VALUES (?, ?, ?, ?, ?)"); 
            $stmt->bind_param("ssdss", $nama, $deskripsi, $harga, $kategori, $nama_file); 
 
            if ($stmt->execute()) { 
                $message = "<div class='alert alertsuccess'>Menu **$nama** berhasil ditambahkan!</div>"; 
            } else { 
                $message = "<div class='alert alertdanger'>Error: " . $stmt->error . "</div>"; 
            } 
            $stmt->close(); 
        } 
        $action = 'list'; 
    } 
 
    // 2. UPDATE (Edit Menu) 
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_menu'])) { 
        $id_update      = $_POST['id']; 
        $nama           = $_POST['nama']; 
        $deskripsi      = $_POST['deskripsi']; 
        $harga          = $_POST['harga']; 
        $kategori       = $_POST['kategori']; 
        $nama_file_lama = $_POST['gambar_lama']; 
        $nama_file_baru = $nama_file_lama; 
 
        // Proses Upload Gambar Baru 
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) { 
            $target_dir  = "../assets/"; 
            $file_name   = basename($_FILES["gambar"]["name"]); 
            $target_file = $target_dir . $file_name; 
 
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) { 
                $nama_file_baru = $file_name; 
            } else { 
                $message = "<div class='alert alertdanger'>Gagal upload gambar baru.</div>"; 
            } 
        } 
 
        if (! $message) { 
            $stmt = $koneksi->prepare("UPDATE menu SET nama=?, deskripsi=?, harga=?, kategori=?, gambar=? WHERE id=?"); 
            $stmt->bind_param("ssdssi", $nama, $deskripsi, $harga, $kategori, $nama_file_baru, $id_update); 
 
            if ($stmt->execute()) { 
                $message = "<div class='alert alertsuccess'>Menu **$nama** berhasil diperbarui!</div>"; 
            } else { 
                $message = "<div class='alert alertdanger'>Error: " . $stmt->error . "</div>"; 
            } 
            $stmt->close(); 
        } 
        $action = 'list'; 
    } 
 
    // 3. DELETE (Hapus Menu) 
    if ($action == 'delete' && $id) { 
        // Ambil nama file gambar sebelum dihapus 
        $res            = $koneksi->query("SELECT gambar FROM menu WHERE id='$id'"); 
        $menu_item      = $res->fetch_assoc(); 
        $file_to_delete = $menu_item['gambar']; 
 
        $stmt = $koneksi->prepare("DELETE FROM menu WHERE id=?"); 
        $stmt->bind_param("i", $id); 
 
        if ($stmt->execute()) { 
            // Hapus file gambar dari folder assets (opsional, tapi disarankan) 
            if (! empty($file_to_delete) && file_exists("../assets/" . $file_to_delete)) { 
                unlink("../assets/" . $file_to_delete); 
            } 
            $message = "<div class='alert alert-success'>Menu berhasil dihapus.</div>"; 
        } else { 
            $message = "<div class='alert alert-danger'>Gagal menghapus menu.</div>"; 
        } 
        $stmt->close(); 
        $action = 'list'; 
    } 
 
    // 4. READ (Ambil Data untuk List atau Edit) 
    if ($action == 'list') { 
        $result = $koneksi->query("SELECT * FROM menu ORDER BY kategori, nama"); 
        $menus  = $result->fetch_all(MYSQLI_ASSOC); 
    } elseif ($action == 'edit' && $id) { 
        $result    = $koneksi->query("SELECT * FROM menu WHERE id='$id'"); 
        $menu_data = $result->fetch_assoc(); 
        if (! $menu_data) { 
            $message = "<div class='alert alert-warning'>Datamenu tidak ditemukan.</div>"; 
            $action  = 'list'; 
        } 
    } 
?> 
 
<h1 class="h2">Kelola Menu</h1> 
<hr> 
 
<?php echo $message?> 
 
<?php if ($action == 'list'): ?> 
<a href="?action=add" class="btn mb-3" style="background-color:palevioletred; color:white;">Tambah Menu Baru</a>

<div class="table-responsive"> 
    <table class="table table-striped table-sm"> 
        <thead> 
            <tr> 
                <th>#</th> 
                <th>Gambar</th> 
                <th>Nama</th> 
                <th>Kategori</th> 
                <th>Harga</th> 
                <th>Aksi</th> 
            </tr> 
        </thead> 
        <tbody> 
            <?php 
            $no = 1;
            foreach ($menus as $menu): ?> 
            <tr> 
                <td><?php echo $no++; ?></td> 
                <td><img src="../assets/<?php echo htmlspecialchars($menu['gambar'])?>" alt="<?php echo $menu['nama']?>" width="50"> 
                </td> 
                <td><?php echo htmlspecialchars($menu['nama'])?></td> 
                <td><?php echo htmlspecialchars($menu['kategori'])?></td> 
                <td>Rp. <?php echo number_format($menu['harga'], 0, ',', '.')?></td> 
                <td> 
                    <a href="?action=edit&id=<?php echo $menu['id']?>" class="btn btn-sm btn-info">Edit</a> 
                    <a href="?action=delete&id=<?php echo $menu['id']?>" class="btn btn-sm btn-danger" 
                        onclick="return confirm('Yakin ingin menghapus menu ini?')">Hapus</a> 
                </td> 
            </tr> 
            <?php endforeach; ?> 
        </tbody> 
    </table> 
</div> 
 
<?php elseif ($action == 'add' || $action == 'edit'): ?> 
<h3 class="mt-4"><?php echo $action == 'add' ? 'Tambah' : 
'Edit'?> Menu</h3> 
<form method="POST" action="?action=<?php echo $action?>" 
enctype="multipart/form-data"> 
    <?php if ($action == 'edit'): ?> 
    <input type="hidden" name="id" value="<?php echo $menu_data['id']?>"> 
    <input type="hidden" name="gambar_lama" value="<?php echo $menu_data['gambar']?>"> 
    <?php endif; ?> 
 
    <div class="form-group"> 
        <label for="nama">Nama Menu</label> 
        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $menu_data['nama'] ?? ''?>" required> 
    </div> 
    <div class="form-group"> 
        <label for="kategori">Kategori</label> 
        <select class="form-control" id="kategori" name="kategori" required> 
            <option value="Minuman" <?php echo ($menu_data['kategori'] ?? '') == 'Minuman' ? 'selected' : ''?>>Minuman 
            </option> 
            <option value="Makanan" <?php echo ($menu_data['kategori'] ?? '') == 'Makanan' ? 'selected' : ''?>>Makanan 
            </option> 
        </select> 
    </div> 
    <div class="form-group"> 
        <label for="deskripsi">Deskripsi</label> 
        <textarea class="form-control" id="deskripsi" name="deskripsi" 
            rows="3"><?php echo $menu_data['deskripsi'] ?? ''?></textarea> 
    </div> 
    <div class="form-group"> 
        <label for="harga">Harga (Rp)</label> 
        <input type="number" step="0.01" class="form-control" id="harga" name="harga" 
            value="<?php echo $menu_data['harga'] ?? ''?>" required> 
    </div> 
    <div class="form-group"> 
        <label for="gambar">Gambar Menu</label> 
        <input type="file" class="form-control-file" id="gambar" name="gambar"> 
        <?php if ($action == 'edit' && ! empty($menu_data['gambar'])): ?> 
        <small class="form-text text-muted">Gambar saat ini: **<?php echo $menu_data['gambar']?>**</small> 
        <?php endif; ?> 
    </div> 
 
    <button type="submit" name="<?php echo $action == 'add' ? 'tambah_menu' : 'edit_menu'?>" 
        class="btn btn-success">Simpan</button> 
    <a href="menu.php" class="btn btn-secondary">Batal</a> 
</form> 
<?php endif; ?> 
 
<?php include 'footer.php'; ?> 