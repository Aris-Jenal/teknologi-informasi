<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Berita - Teknologi Informasi UM-TAPSEL</title>
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

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_news'])) {
        $title = htmlspecialchars(trim($_POST['title']));
        $description = htmlspecialchars(trim($_POST['description']));
        $link = htmlspecialchars(trim($_POST['link']));
        
        // Handle file upload
        $photo = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["photo"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // Check if image file is an actual image
            $check = getimagesize($_FILES["photo"]["tmp_name"]);
            if ($check !== false) {
                // Check file size (5MB limit)
                if ($_FILES["photo"]["size"] <= 5000000) {
                    // Allow certain file formats
                    if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                            $photo = $target_file;
                        } else {
                            $error = "Sorry, there was an error uploading your file.";
                        }
                    } else {
                        $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    }
                } else {
                    $error = "File is too large.";
                }
            } else {
                $error = "File is not an image.";
            }
        } else {
            $error = "Photo is required.";
        }

        // Validation
        if (empty($title) || empty($description) || empty($link)) {
            $error = "All fields are required.";
        } elseif (!$error) {
            try {
                $stmt = $pdo->prepare("INSERT INTO news (photo, title, description, link) VALUES (?, ?, ?, ?)");
                $stmt->execute([$photo, $title, $description, $link]);
                header("Location: berita.php?success=" . urlencode("News added successfully."));
                exit();
            } catch (PDOException $e) {
                $error = "Failed to add news: " . $e->getMessage();
            }
        }
    }
    ?>
    <div class="login-container">
        <h2>Tambah Berita</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="photo">Foto</label>
                <input type="file" id="photo" name="photo" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="title">Judul Berita</label>
                <input type="text" id="title" name="title" placeholder="Masukkan judul berita" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" placeholder="Masukkan deskripsi berita" required rows="5" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
            </div>
            <div class="form-group">
                <label for="link">Link</label>
                <input type="url" id="link" name="link" placeholder="Masukkan link berita" required>
            </div>
            <button type="submit" name="add_news" class="btn btn-add">Buat</button>
            <a href="berita.php"><button type="button" class="btn btn-cancel">Batal</button></a>
        </form>
    </div>
</body>
</html>