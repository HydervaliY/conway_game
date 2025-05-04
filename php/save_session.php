<?php
include 'db.php';
session_start();

$user_id = $_SESSION["user_id"];
$generation = $_POST["generation"];
$start_time = $_POST["start"];
$end_time = date("Y-m-d H:i:s");

$stmt = $conn->prepare("INSERT INTO game_sessions (user_id, start_time, end_time, generations) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $user_id, $start_time, $end_time, $generation);
$stmt->execute();