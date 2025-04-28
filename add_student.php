<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa - Teknologi Informasi UM-TAPSEL</title>
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

    $error = '';
    $success = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
        $name = htmlspecialchars(trim($_POST['name']));
        $password = $_POST['password'];

        // Validation
        if (empty($name) || empty($password)) {
            $error = "All fields are required.";
        } elseif (strlen($password) < 6) {
            $error = "Password harus 6 karakter.";
        } else {
            try {
                // Hash password and insert student
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO students (name, password) VALUES (?, ?)");
                $stmt->execute([$name, $hashed_password]);
                header("Location: akun.php?success=" . urlencode("Student added successfully."));
                exit();
            } catch (PDOException $e) {
                $error = "Failed to add student: " . $e->getMessage();
            }
        }
    }
    ?>
    <div class="login-container">
        <h2>Tambah Mahasiswa</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="name">Nama Mahasiswa</label>
                <input type="text" id="name" name="name" placeholder="Masukkan nama mahasiswa" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="add_student" class="btn btn-add">Buat</button>
            <a href="akun.php"><button type="button" class="btn btn-cancel">Batal</button></a>
        </form>
    </div>
</body>
</html>