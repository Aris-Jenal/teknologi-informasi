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
    require_once 'db_connect.php';
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Fetch the calendar image
    $stmt = $pdo->query("SELECT * FROM academic_calendars WHERE id = 1");
    $calendar = $stmt->fetch(PDO::FETCH_ASSOC);
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
            <h2>Kalender Akademik</h2>
            <div class="action-container">
                <a href="add_kal.php"><button class="btn btn-add">Edit</button></a>
            </div>
            <div class="calendar-grid">
                <?php if ($calendar): ?>
                    <div class="calendar-item">
                        <a href="<?php echo htmlspecialchars($calendar['image_path']); ?>" target="_blank">
                            <img src="<?php echo htmlspecialchars($calendar['image_path']); ?>" alt="Kalender Akademik">
                        </a>
                        <p>Ditambahkan: <?php echo date('d M Y', strtotime($calendar['created_at'])); ?></p>
                    </div>
                <?php else: ?>
                    <p>Belum ada kalender akademik yang diunggah.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>