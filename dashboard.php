<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Teknologi Informasi UM-TAPSEL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    ?>
    <div class="dashboard-container">
        <nav class="sidebar">
            <h3 class="sidebar-title">Menu</h3>
            <ul>
            <li><a href="dashboard.php" class="active">Home</a></li>
            <li><a href="berita.php">Berita</a></li>
            <li><a href="visi.php">Visi dan Misi</a></li>
            <li><a href="kalender.php">Kalender Akademik</a></li>
            <li><a href="akun.php">Akun Mahasiswa</a></li>
            <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <h2>Dashboard Home</h2>
            <div class="stats-container">
                <div class="stat-card">
                    <h4>Mahasiswa</h4>
                    <p>460 +</p>
                </div>
                <div class="stat-card">
                    <h4>Dosen</h4>
                    <p>5 +</p>
                </div>
                <div class="stat-card">
                    <h4>TU</h4>
                    <p>1</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>