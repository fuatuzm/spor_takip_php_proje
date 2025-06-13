<?php
session_start();
require 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$id = $_GET["id"] ?? null;

$stmt = $pdo->prepare("DELETE FROM workouts WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION["user_id"]]);

header("Location: list.php");
exit;
