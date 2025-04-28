<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita - Teknologi Informasi UM-TAPSEL</title>
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

    // Fetch news from database
    try {
        $stmt = $pdo->prepare("SELECT id, photo, title, description, link FROM news ORDER BY created_at DESC");
        $stmt->execute();
        $news_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Failed to fetch news: " . $e->getMessage();
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
            <h2>Berita</h2>
            <p>Kelola Berita</p>
            <div class="action-container">
                <a href="add_berita.php"><button class="btn btn-add">Tambah</button></a>
            </div>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <table class="student-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Foto</th>
                        <th>Judul Berita</th>
                        <th>Deskripsi</th>
                        <th>Link</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($news_items)): ?>
                        <tr>
                            <td colspan="6">No news found.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; ?>
                        <?php foreach ($news_items as $news): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><img src="<?php echo htmlspecialchars($news['photo']); ?>" alt="News Photo" style="max-width: 100px;"></td>
                                <td><?php echo htmlspecialchars($news['title']); ?></td>
                                <td><?php echo htmlspecialchars(substr($news['description'], 0, 100)) . (strlen($news['description']) > 100 ? '...' : ''); ?></td>
                                <td><a href="<?php echo htmlspecialchars($news['link']); ?>" target="_blank">Lihat</a></td>
                                <td>
                                    <a href="edit_berita.php?id=<?php echo $news['id']; ?>" class="btn btn-edit">Edit</a>
                                    <a href="delete_berita.php?id=<?php echo $news['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this news?');">Hapus</a>
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