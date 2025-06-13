<?php
session_start();
require 'config.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = "Tüm alanlar zorunludur.";
    } elseif ($password !== $confirm) {
        $errors[] = "Şifreler uyuşmuyor.";
    } else {
        // Şifreyi hashle
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Veritabanına kaydet
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $hash]);
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Kayıt başarısız: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Kayıt Ol</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="card p-4">
        <div class="mb-3">
            <label>Kullanıcı Adı</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>E-posta</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Şifre</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Şifre (Tekrar)</label>
            <input type="password" name="confirm" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Kayıt Ol</button>
    </form>
</div>
</body>
</html>
