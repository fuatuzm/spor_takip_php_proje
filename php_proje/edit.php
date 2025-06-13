<?php
session_start();
require 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$id = $_GET["id"] ?? null;

// Kaydı getir
$stmt = $pdo->prepare("SELECT * FROM workouts WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION["user_id"]]);
$workout = $stmt->fetch();

if (!$workout) {
    echo "Kayıt bulunamadı veya yetkiniz yok.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exercise_type = $_POST["exercise_type"];
    $date = $_POST["date"];
    $duration = $_POST["duration"];
    $repetitions = $_POST["repetitions"];
    $notes = trim($_POST["notes"]);

    $stmt = $pdo->prepare("UPDATE workouts SET date=?, exercise_type=?, duration_minutes=?, repetitions=?, notes=? WHERE id=? AND user_id=?");
    $stmt->execute([$date, $exercise_type, $duration, $repetitions, $notes, $id, $_SESSION["user_id"]]);

    header("Location: list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kaydı Güncelle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Kaydı Güncelle</h2>

    <form method="post" class="card p-4 mt-3">
        <div class="mb-3">
            <label>Tarih</label>
            <input type="date" name="date" value="<?php echo $workout["date"]; ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Spor Türü</label>
            <select name="exercise_type" class="form-control" required>
                <?php
                $options = ['kardiyo', 'omuz', 'göğüs', 'karın', 'bacak', 'ön kol', 'arka kol', 'sırt'];
                foreach ($options as $op) {
                    $selected = $op == $workout["exercise_type"] ? "selected" : "";
                    echo "<option value='$op' $selected>$op</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Süre (dakika)</label>
            <input type="number" name="duration" value="<?php echo $workout["duration_minutes"]; ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label>Tekrar Sayısı</label>
            <input type="number" name="repetitions" value="<?php echo $workout["repetitions"]; ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label>Not</label>
            <textarea name="notes" class="form-control"><?php echo $workout["notes"]; ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Güncelle</button>
        <a href="list.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
</body>
</html>
