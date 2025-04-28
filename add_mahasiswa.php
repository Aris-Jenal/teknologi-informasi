<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    session_start();
    require_once 'db_connect.php';
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['calendar_image']) && $_FILES['calendar_image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/png', 'image/jpeg', 'image/jpg'];
            $file_type = mime_content_type($_FILES['calendar_image']['tmp_name']);
            
            if (!in_array($file_type, $allowed_types)) {
                $error = "Hanya file PNG, JPG, atau JPEG yang diizinkan.";
            } else {
                $upload_dir = 'uploads/calendars/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_name = uniqid() . '_' . $_FILES['calendar_image']['name'];
                $file_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['calendar_image']['tmp_name'], $file_path)) {
                    $stmt = $pdo->prepare("INSERT INTO academic_calendars (image_path, uploaded_by) VALUES (?, ?)");
                    $stmt->execute([$file_path, $_SESSION['user_id']]);
                    header("Location: kalender.php");
                    exit();
                } else {
                    $error = "Gagal mengunggah file.";
                }
            }
        } else {
            $error = "Silakan pilih file gambar.";
        }
    }
    ?>
    <div class="dashboard-container">
        <nav class="sidebar">
            <h3 class="sidebar-title">Menu</h3>
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="berita.php">Berita</a></li>
                <li><a href="visi.php">Visi dan Misi</a></li>
                <li><a href="kalender.php" class="active">Kalender Akademik</a></li>
                <li><a href="akun.php">Akun Mahasiswa</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <h2>Tambah Kalender Akademik</h2>
            <div class="form-container">
                <?php if ($error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="calendar_image">Pilih Gambar Kalender (PNG/JPG/JPEG)</label>
                        <input type="file" id="calendar_image" name="calendar_image" accept=".png,.jpg,.jpeg" required>
                    </div>
                    <button type="submit" class="btn submit-btn">Buat</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>