<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Mahasiswa - Teknologi Informasi UM-TAPSEL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    session_start();
    require_once 'db_connect.php';

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Fetch students from database
    try {
        $stmt = $pdo->prepare("SELECT id, name FROM students ORDER BY created_at DESC");
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Data Error: " . $e->getMessage();
    }
    ?>
    <div class="dashboard-container">
        <nav class="sidebar">
            <h3 class="sidebar-title">Menu</h3>
            <ul>
                <?php include 'header_dashboard_1.php'?>
            </ul>
        </nav>
        <div class="main-content">
            <h2>Akun Mahasiswa</h2>
            <p>Buat Akun Mahasiswa</p>
            <div class="action-container">
                <a href="add_student.php"><button class="btn btn-add">Tambah</button></a>
            </div>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <table class="student-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Mahasiswa</th>
                        <th>Password</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="4">Daftar Akun Mahasiswa Kosong.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                <td>******</td>
                                <td>
                                    <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-edit">Edit</a>
                                    <a href="delete_student.php?id=<?php echo $student['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this student?');">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>