<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Teknologi Informasi UM-TAPSEL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Register</h2>
        <?php
        require_once 'db_connect.php';
        $error = '';
        $success = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
            $name = htmlspecialchars(trim($_POST['name']));
            $phone = htmlspecialchars(trim($_POST['phone']));
            $password = $_POST['password'];

            if (empty($name) || empty($phone) || empty($password)) {
                $error = "All fields are required.";
            } elseif (!preg_match("/^[0-9]{10,13}$/", $phone)) {
                $error = "Invalid phone number. Use 10-13 digits.";
            } elseif (strlen($password) < 6) {
                $error = "Password Harus 6 Karakter.";
            } else {
                try {
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE phone = ?");
                    $stmt->execute([$phone]);
                    if ($stmt->fetch()) {
                        $error = "Nomor Telepon Harus Aktif.";
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("INSERT INTO users (name, phone, password) VALUES (?, ?, ?)");
                        $stmt->execute([$name, $phone, $hashed_password]);
                        $success = "Registration Sukses Ayo <a href='login.php'>login</a>.";
                    }
                } catch (PDOException $e) {
                    $error = "Registration failed: " . $e->getMessage();
                }
            }
        }
        ?>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label for="phone">Nomor Telepon</label>
                <input type="tel" id="phone" name="phone" placeholder="Masukkan nomor telepon" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="register" class="btn btn-register">Register</button>
            <a href="login.php"><button type="button" class="btn btn-login">Kembali</button></a>
        </form>
    </div>
</body>
</html>