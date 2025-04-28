<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Mahasiswa - Teknologi Informasi UM-TAPSEL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    session_start();
    require_once 'db_connect.php';

    // Redirect if already logged in
    if (isset($_SESSION['student_id'])) {
        header("Location: student_dashboard.php");
        exit();
    }

    $error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
        $name = htmlspecialchars(trim($_POST['name']));
        $password = $_POST['password'];

        // Validation
        if (empty($name) || empty($password)) {
            $error = "Nama dan password harus diisi.";
        } else {
            try {
                // Check if student exists
                $stmt = $pdo->prepare("SELECT id, name, password FROM students WHERE name = ?");
                $stmt->execute([$name]);
                $student = $stmt->fetch();

                if ($student && password_verify($password, $student['password'])) {
                    // Successful login
                    $_SESSION['student_id'] = $student['id'];
                    $_SESSION['student_name'] = $student['name'];
                    header("Location: student_dashboard.php");
                    exit();
                } else {
                    $error = "Nama atau password salah.";
                }
            } catch (PDOException $e) {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
    ?>
    <div class="login-container">
        <h2>Login Mahasiswa</h2>
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
            <button type="submit" name="login" class="btn btn-login">Login</button>
        </form>
    </div>
</body>
</html>