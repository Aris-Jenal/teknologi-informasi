<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita - Teknologi Informasi UM-TAPSEL</title>
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

ACTION: // Check if ID is provided
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: berita.php?error=" . urlencode("Invalid news ID."));
        exit();
    }

    $news_id = $_GET['id'];
    $error = '';
    $success = '';

    // Fetch existing news item
    try {
        $stmt = $pdo->prepare("SELECT photo, title, description, link FROM news WHERE id = ?");
        $stmt->execute([$news_id]);
        $news = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$news) {
            header("Location: berita.php?error=" . urlencode("News item not found."));
            exit();
        }
    } catch (PDOException $e) {
        $error = "Failed to fetch news: " . $e->getMessage();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_news'])) {
        $title = htmlspecialchars(trim($_POST['title']));
        $description = htmlspecialchars(trim($_POST['description']));
        $link = htmlspecialchars(trim($_POST['link']));
        $photo = $news['photo']; // Keep existing photo by default

        // Handle file upload if a new photo is provided
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $target_dir = "Uploads/";
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
                            // Delete old photo if it exists and is different
                            if (file_exists($news['photo']) && $news['photo'] !== $target_file) {
                                unlink($news['photo']);
                            }
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
        }

        // Validation
        if (empty($title) || empty($description) || empty($link)) {
            $error = "All fields are required.";
        } elseif (!$error) {
            try {
                $stmt = $pdo->prepare("UPDATE news SET photo = ?, title = ?, description = ?, link = ? WHERE id = ?");
                $stmt->execute([$photo, $title, $description, $link, $news_id]);
                header("Location: berita.php?success=" . urlencode("News updated successfully."));
                exit();
            } catch (PDOException $e) {
                $error = "Failed to update news: " . $e->getMessage();
            }
        }
    }
    ?>
    <div class="login-container">
        <h2>Edit Berita</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $news_id; ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="photo">Foto</label>
                <input type="file" id="photo" name="photo" accept="image/*">
                <p>Current photo: <img src="<?php echo htmlspecialchars($news['photo']); ?>" alt="Current Photo" style="max-width: 100px; margin-top: 10px;"></p>
            </div>
            <div class="form-group">
                <label for="title">Judul Berita</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" placeholder="Masukkan judul berita" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" placeholder="Masukkan deskripsi berita" required rows="5" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"><?php echo htmlspecialchars($news['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="link">Link</label>
                <input type="url" id="link" name="link" value="<?php echo htmlspecialchars($news['link']); ?>" placeholder="Masukkan link berita" required>
            </div>
            <button type="submit" name="edit_news" class="btn btn-add">Simpan</button>
            <a href="berita.php"><button type="button" class="btn btn-cancel">Batal</button></a>
        </form>
    </div>
</body>
</html>