<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Teknologi Informasi UM-TAPSEL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php
        session_start();
        require_once 'db_connect.php';
        $error = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
            $phone = htmlspecialchars(trim($_POST['phone']));
            $password = $_POST['password'];

            if (empty($phone) || empty($password)) {
                $error = "Phone number and password are required.";
            } else {
                try {
                    $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE phone = ?");
                    $stmt->execute([$phone]);
                    $user = $stmt->fetch();

                    if ($user && password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $error = "Nomor Telepon atau Password Salah.";
                    }
                } catch (PDOException $e) {
                    $error = "Login failed: " . $e->getMessage();
                }
            }
        }

        ?>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="phone">Nomor Telepon</label>
                <input type="tel" id="phone" name="phone" placeholder="Masukkan nomor telepon" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            <div class="forgot-password">
                <a href="#">Lupa Password?</a>
            </div>
            <button type="submit" name="login" class="btn btn-login">Login</button>
            <a href="student_login.php"><button type="button" class="btn btn-student">Login Mahasiswa</button></a>
            <a href="register.php"><button type="button" class="btn btn-register">Register</button></a>
        </form>
    </div>
</body>
</html>