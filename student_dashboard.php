<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - Teknologi Informasi UM-TAPSEL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    session_start();
    require_once 'db_connect.php';

    // Check if student is logged in
    if (!isset($_SESSION['student_id'])) {
        header("Location: student_login.php");
        exit();
    }

    $student_name = htmlspecialchars($_SESSION['student_name']);
    ?>
    <div class="dashboard-container">
        <nav class="sidebar">
            <h3 class="sidebar-title">Menu</h3>
            <ul>
                <li><a href="dashboard.php" class="active">Home</a></li>
                <li><a href="kalmahasiswa.php">Kalender Akademik</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <h2>Kalender Akademik</h2>
        </div>
    </div>
</body>
</html>