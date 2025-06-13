<?php
session_start();

// Eğer giriş yapılmamışsa login ekranına yönlendir
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Hoş geldin, <?php echo htmlspecialchars($_SESSION["username"]); ?> 👋</h2>
    <p class="mt-3">Spor takibini buradan yapabilirsin.</p>

    <a href="create.php" class="btn btn-success mt-3">Yeni Spor Kaydı Ekle</a>
    <a href="list.php" class="btn btn-primary mt-3">Kayıtları Görüntüle</a>
    <a href="logout.php" class="btn btn-danger mt-3">Çıkış Yap</a>
</div>
</body>
</html>
