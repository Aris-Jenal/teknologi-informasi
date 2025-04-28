<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch existing Vision and Mission
try {
    $stmt = $pdo->prepare("SELECT vision, mission FROM vision_mission WHERE id = 1");
    $stmt->execute();
    $vm = $stmt->fetch(PDO::FETCH_ASSOC);
    $vision = $vm['vision'] ?? '';
    $mission = $vm['mission'] ?? '';
} catch (PDOException $e) {
    $error = "Failed to fetch vision and mission: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $new_vision = trim($_POST['vision']);
    $new_mission = trim($_POST['mission']);

    if (empty($new_vision) || empty($new_mission)) {
        $error = "Vision and Mission cannot be empty.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO vision_mission (id, vision, mission) VALUES (1, ?, ?) ON DUPLICATE KEY UPDATE vision = ?, mission = ?");
            $stmt->execute([$new_vision, $new_mission, $new_vision, $new_mission]);
            header("Location: visi.php?success=Vision and Mission updated successfully");
            exit();
        } catch (PDOException $e) {
            $error = "Failed to save vision and mission: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Visi dan Misi - Teknologi Informasi UM-TAPSEL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <div class="login-container">
            <h2>Edit Visi dan Misi</h2>
            <?php if (isset($error)): ?>
                <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <div class="form-container">
                <form method="POST">
                    <label for="vision">Visi</label>
                    <textarea id="vision" name="vision" required><?php echo htmlspecialchars($vision); ?></textarea>
                    <label for="mission">Misi</label>
                    <textarea id="mission" name="mission" required style="height: 188px;"><?php echo htmlspecialchars($mission); ?></textarea>
                        <button type="submit" name="create" class="btn btn-add">Buat</button>
                        <a href="visi.php"><button type="button" class="btn btn-cancel">Batal</button></a>
                </form>
            </div>
        </div>
</body>
</html>