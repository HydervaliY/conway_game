<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch user game stats
$sql = "SELECT COUNT(*), SUM(TIMESTAMPDIFF(SECOND, start_time, end_time)) FROM Game_Sessions WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($games_played, $total_time_spent);
$stmt->fetch();

// Fetch current session details if any
// Assuming you have logic to track current game session:
$current_generation = 10; // Example
$current_population = 20; // Example

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h1>Welcome, <?php echo $email; ?>!</h1>

    <h3>Game Stats</h3>
    <p>Games Played: <?php echo $games_played; ?></p>
    <p>Total Time Spent (seconds): <?php echo $total_time_spent; ?></p>

    <h3>Current Game Session</h3>
    <p>Generation: <?php echo $current_generation; ?></p>
    <p>Current Population: <?php echo $current_population; ?></p>

    <h3>Load Game Pattern</h3>
    <form action="load_pattern.php" method="POST">
        <select name="pattern">
            <option value="block">Block</option>
            <option value="boat">Boat</option>
            <option value="blinker">Blinker</option>
            <option value="beacon">Beacon</option>
            <!-- Add more patterns -->
        </select>
        <button type="submit">Load Pattern</button>
    </form>

    <h3>Previous Sessions</h3>
    <table>
        <tr><th>Start Time</th><th>End Time</th><th>Generations</th></tr>
        <!-- Fetch and display past sessions here -->
    </table>

    <form action="start_game.php" method="POST">
        <button type="submit">Start New Game</button>
    </form>

    <form action="stop_game.php" method="POST">
        <button type="submit">Stop
