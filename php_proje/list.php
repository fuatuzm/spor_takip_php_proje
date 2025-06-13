<?php
session_start();
require 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Kendi kayıtlarını çek
$stmt = $pdo->prepare("SELECT * FROM workouts WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$_SESSION["user_id"]]);
$workouts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Spor Kayıtları</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2><?php echo htmlspecialchars($_SESSION["username"]); ?> - Spor Kayıtların</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Geri Dön</a>

    <?php if (count($workouts) > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tarih</th>
                    <th>Tür</th>
                    <th>Süre (dk)</th>
                    <th>Tekrar</th>
                    <th>Not</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($workouts as $w): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($w["date"]); ?></td>
                        <td><?php echo htmlspecialchars($w["exercise_type"]); ?></td>
                        <td><?php echo htmlspecialchars($w["duration_minutes"]); ?></td>
                        <td><?php echo htmlspecialchars($w["repetitions"]); ?></td>
                        <td><?php echo htmlspecialchars($w["notes"]); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $w["id"]; ?>" class="btn btn-sm btn-warning">Güncelle</a>
                            <a href="delete.php?id=<?php echo $w["id"]; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu kaydı silmek istediğine emin misin?')">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Henüz hiç spor kaydın yok. Hadi ekle!</div>
    <?php endif; ?>
</div>
</body>
</html>
