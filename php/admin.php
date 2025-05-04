<?php
include 'db.php';
session_start();

// Replace with actual admin check
$isAdmin = true;

if (!$isAdmin) {
    echo "Access denied.";
    exit();
}

$result = $conn->query("SELECT * FROM users");
echo "<h2>All Users</h2><table><tr><th>ID</th><th>Username</th><th>Email</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['id']}</td><td>{$row['username']}</td><td>{$row['email']}</td></tr>";
}
echo "</table>";
