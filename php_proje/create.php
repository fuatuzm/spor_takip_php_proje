<?php
session_start();
require 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exercise_type = $_POST["exercise_type"];
    $date = $_POST["date"];
    $duration = $_POST["duration"];
    $repetitions = $_POST["repetitions"];
    $notes = trim($_POST["notes"]);

    if (!$date || !$exercise_type) {
        $errors[] = "Tarih ve spor türü zorunludur.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO workouts (user_id, date, exercise_type, duration_minutes, repetitions, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION["user_id"],
            $date,
            $exercise_type,
            $duration,
            $repetitions,
            $notes
        ]);
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Yeni Spor Kaydı</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Yeni Spor Kaydı Ekle</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="card p-4 mt-3">
        <div class="mb-3">
            <label>Tarih</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Spor Türü</label>
            <select name="exercise_type" class="form-control" required>
                <option value="">Seçiniz</option>
                <option value="kardiyo">Kardiyo</option>
                <option value="omuz">Omuz</option>
                <option value="göğüs">Göğüs</option>
                <option value="karın">Karın</option>
                <option value="bacak">Bacak</option>
                <option value="ön kol">Ön Kol</option>
                <option value="arka kol">Arka Kol</option>
                <option value="sırt">Sırt</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Süre (dakika)</label>
            <input type="number" name="duration" class="form-control">
        </div>

        <div class="mb-3">
            <label>Tekrar Sayısı</label>
            <input type="number" name="repetitions" class="form-control">
        </div>

        <div class="mb-3">
            <label>Not</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Kaydet</button>
        <a href="dashboard.php" class="btn btn-secondary">Geri Dön</a>
    </form>
</div>
</body>
</html>
