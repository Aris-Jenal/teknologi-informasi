<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa - Teknologi Informasi UM-TAPSEL</title>
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

    // Check if student ID is provided
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: akun.php?error=" . urlencode("Invalid student ID."));
        exit();
    }

    $student_id = $_GET['id'];
    $error = '';

    // Fetch student data
    try {
        $stmt = $pdo->prepare("SELECT id, name, password FROM students WHERE id = ?");
        $stmt->execute([$student_id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            header("Location: akun.php?error=" . urlencode("Student not found."));
            exit();
        }
    } catch (PDOException $e) {
        $error = "Failed to fetch student: " . $e->getMessage();
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_student'])) {
        $name = htmlspecialchars(trim($_POST['name']));
        $password = $_POST['password'];

        // Validation
        if (empty($name)) {
            $error = "Nama Mahasiswa is required.";
        } elseif (!empty($password) && strlen($password) < 6) {
            $error = "Password harus 6 karakter.";
        } else {
            try {
                if (!empty($password)) {
                    // Update name and password
                    $stmt = $pdo->prepare("UPDATE students SET name = ?, password = ? WHERE id = ?");
                    $stmt->execute([$name, $password, $student_id]);
                } else {
                    // Update only name
                    $stmt = $pdo->prepare("UPDATE students SET name = ? WHERE id = ?");
                    $stmt->execute([$name, $student_id]);
                }
                header("Location: akun.php?success=" . urlencode("Student updated successfully."));
                exit();
            } catch (PDOException $e) {
                $error = "Failed to update student: " . $e->getMessage();
            }
        }
    }
    ?>
    <div class="login-container">
        <h2>Edit Mahasiswa</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $student_id; ?>">
            <div class="form-group">
                <label for="name">Nama Mahasiswa</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" placeholder="Masukkan nama mahasiswa" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($student['password']); ?>" placeholder="Masukkan password">
            </div>
            <button type="submit" name="edit_student" class="btn btn-add">Edit</button>
            <a href="akun.php"><button type="button" class="btn btn-cancel">Batal</button></a>
        </form>
    </div>
</body>
</html>