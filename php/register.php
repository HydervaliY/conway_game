<?php
include 'db.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $checkSql = "SELECT id FROM users WHERE email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $error = "That email is already registered. <a href='login.php'>Log in</a>.";
    } else {
        $insertSql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("sss", $name, $email, $password);

        if ($insertStmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Registration error: " . htmlspecialchars($insertStmt->error);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - Game of Life</title>
  <style>
    body {
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: Arial, sans-serif;
      background: linear-gradient(to bottom right, #f9f9f9, #e3f2fd);
    }

    .container {
      background-color: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.2);
      width: 300px;
      text-align: center;
    }

    form {
      margin-top: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      text-align: left;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 8px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .auth-btn {
  padding: 10px 20px;
  background-color: #4CAF50; /* Green */
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 16px;
}

.auth-btn:hover {
  background-color: #45a049;
}


    .error {
      color: red;
      margin-top: 10px;
    }

    footer {
      margin-top: 15px;
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>Register</h1>
      <p>Fill in the details to create your account.</p>
    </header>

    <?php if (!empty($error)): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
      <label for="name">Full Name:</label>
      <input type="text" name="name" id="name" required>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required>

      <button type="submit" class="auth-btn">Register</button>
    </form>

    <footer>
      <p>Already have an account? <a href="login.php">Login here</a></p>
    </footer>
  </div>
</body>
</html>
