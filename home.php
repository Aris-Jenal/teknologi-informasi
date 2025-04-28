<?php
include 'header.php';
require_once 'db_connect.php';

// Fetch news from database
try {
    $stmt = $pdo->prepare("SELECT photo, title, description, link, created_at FROM news ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $news_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Failed to fetch news: " . $e->getMessage();
}

// Fetch Vision and Mission
try {
    $stmt = $pdo->prepare("SELECT vision, mission FROM vision_mission WHERE id = 1");
    $stmt->execute();
    $vm = $stmt->fetch(PDO::FETCH_ASSOC);
    $vision = $vm['vision'] ?? 'No vision set.';
    $mission = $vm['mission'] ?? 'No mission set.';
} catch (PDOException $e) {
    $error = "Failed to fetch vision and mission: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teknologi Informasi UM-TAPSEL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="hero">
        <img src="gambar/wal1.jpg" alt="Hero Image">
    </section>
    <div class="main-content">
        <h1 style="margin-bottom: 35px;">Profil Singkat Prodi</h1>
        <p style="line-height: 28px;">Program Studi Teknologi Informasi merupakan bagian dari Fakultas Sains dan Teknologi
            yang berada di bawah naungan Universitas Muhammadiyah Tapanuli Selatan. Program studi
            ini resmi disahkan pada tahun 2021 dan kini telah memiliki kurang lebih 400 mahasiswa
            aktif. Bersama dengan dua program studi lainnya, yaitu Agroteknologi dan Peternakan,
            Program Studi Teknologi Informasi berkomitmen untuk menghasilkan lulusan yang unggul
            dan berdaya saing di era digital. Dengan kurikulum berbasis teknologi terkini, kami
            mempersiapkan mahasiswa untuk menguasai keterampilan di bidang Software Developer,
            Multimedia Director, Design Network Project dan IT Consultant, sambil menanamkan nilai
            -nilai keislaman dan kepekaan sosial agar lulusan dapat berkontribusi positif bagi masyarakat.</p>
    </div>
    <div class="rectangle">
        <h1>Berita</h1>
        <div class="news-section">
            <?php if (empty($news_items)): ?>
                <p>No news available.</p>
            <?php else: ?>
                <?php foreach ($news_items as $news): ?>
                    <div class="news-card">
                        <img src="<?php echo htmlspecialchars($news['photo']); ?>" alt="News Image">
                        <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                        <p class="date"><?php echo date('d/m/Y', strtotime($news['created_at'])); ?></p>
                        <p><?php echo htmlspecialchars(substr($news['description'], 0, 100)) . (strlen($news['description']) > 100 ? '...' : ''); ?></p>
                        <a href="<?php echo htmlspecialchars($news['link']); ?>" target="_blank">Lihat</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="pagination">
            <a href="#" class="page-number">2</a>
            <a href="#" class="page-number">3</a>
            <a href="#" class="page-number">4</a>
            <a href="#" class="more">selengkapnya</a>
        </div>
    </div>
    <div class="vision">
        <h1 style="margin-bottom: 10px;">Visi dan Misi Prodi</h1>
        <h2>Visi Prodi Teknologi Informasi</h2>
        <p><?php echo nl2br(htmlspecialchars($vision)); ?></p>
        <h2>Misi Prodi Teknologi Informasi</h2>
        <ul>
            <?php
            $mission_lines = explode("\n", $mission);
            foreach ($mission_lines as $line) {
                if (trim($line)) {
                    echo '<p style="line-height: 28px;">' . htmlspecialchars($line) . '</p>';
                }
            }
            ?>
        </ul>
    </div>
<?php
include 'footer.php';
?>
</body>
</html>