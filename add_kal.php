<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kalender Akademik</title>
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

    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['calendar_image']) && $_FILES['calendar_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['calendar_image'];
            $allowed_types = ['image/png', 'image/jpeg', 'image/jpg'];
            $max_size = 5 * 1024 * 1024; // 5MB

            if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
                $upload_dir = 'uploads/calendars/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $file_name = uniqid() . '_' . $file['name'];
                $file_path = $upload_dir . $file_name;

                // Check if there's an existing calendar image
                $stmt = $pdo->query("SELECT image_path FROM academic_calendars WHERE id = 1");
                $existing_calendar = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existing_calendar && file_exists($existing_calendar['image_path'])) {
                    // Delete the old image file
                    unlink($existing_calendar['image_path']);
                }

                if (move_uploaded_file($file['tmp_name'], $file_path)) {
                    // Update or insert the new image path
                    $stmt = $pdo->prepare("INSERT INTO academic_calendars (id, image_path) VALUES (1, ?) ON DUPLICATE KEY UPDATE image_path = ?");
                    if ($stmt->execute([$file_path, $file_path])) {
                        $success = 'Kalender berhasil diunggah!';
                        header("Location: kalender.php");
                        exit();
                    } else {
                        $error = 'Gagal menyimpan ke database.';
                    }
                } else {
                    $error = 'Gagal mengunggah file.';
                }
            } else {
                $error = 'File harus berupa PNG, JPG, atau JPEG dan tidak lebih dari 5MB.';
            }
        } else {
            $error = 'Silakan pilih file gambar.';
        }
    }
    ?>
    <div class="login-container">
        <h2>Tambah Kalender Akademik</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="calendar_image">Pilih Gambar Kalender (PNG, JPG, JPEG):</label>
                <input type="file" id="calendar_image" name="calendar_image" accept=".png,.jpg,.jpeg" required>
            </div>
            <button type="submit" class="btn btn-add">Buat</button>
            <a href="kalender.php"><button type="button" class="btn btn-cancel">Batal</button></a>
        </form>
    </div>
</body>
</html>