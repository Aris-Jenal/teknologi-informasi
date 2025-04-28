<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch Vision and Mission
try {
    $stmt = $pdo->prepare("SELECT vision, mission FROM vision_mission WHERE id = 1");
    $stmt->execute();
    $vm = $stmt->fetch(PDO::FETCH_ASSOC);
    $vision = $vm['vision'] ?? 'No vision set.';
    $mission = $vm['mission'] ?? 'No mission set.';
} catch (PDOException $e) {
    $error = "Failed to fetch vision and mission: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visi dan Misi - Teknologi Informasi UM-TAPSEL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="sidebar">
            <h3 class="sidebar-title">Menu</h3>
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="berita.php">Berita</a></li>
                <li><a href="visi.php" class="active">Visi dan Misi</a></li>
                <li><a href="kalender.php">Kalender Akademik</a></li>
                <li><a href="akun.php">Akun Mahasiswa</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <h2>Visi dan Misi</h2>
            <?php if (isset($error)): ?>
                <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['success'])): ?>
                <div class="alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php endif; ?>
            <div class="vision-mission-container">
                <h3>Visi</h3>
                <p><?php echo nl2br(htmlspecialchars($vision)); ?></p>
                <h3>Misi</h3>
                <ul>
                    <?php
                    $mission_lines = explode("\n", $mission);
                    foreach ($mission_lines as $line) {
                        if (trim($line)) {
                            echo '<li>' . htmlspecialchars($line) . '</li>';
                        }
                    }
                    ?>
                </ul>
                <a href="add_vision.php" class="btn btn-edit">Edit</a>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</body>
</html>