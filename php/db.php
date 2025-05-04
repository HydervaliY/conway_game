<?php
$host = "localhost";
$user = "root";
$pass = "root"; // MAMP default
$db = "conway_game";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
