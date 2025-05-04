<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $email;
            header("Location: ../index.php");  // Redirect to the game page (index.php)
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No user found. <a href='register.php'>Register</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Game of Life</title>
  <style>
    body {
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to bottom right, #f0f8ff, #dbe9f4);
    }

    .container {
      background-color: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.2);
      text-align: center;
      width: 300px;
    }

    form {
      margin-top: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      text-align: left;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 8px;
      margin-bottom: 15px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .auth-btn {
      padding: 10px 20px;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      background-color: #4CAF50;
      color: white;
      cursor: pointer;
    }

    .auth-btn:hover {
      background-color: #388e3c;
    }

    footer {
      margin-top: 15px;
    }

    .error {
      color: red;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>Login</h1>
      <p>Please enter your credentials.</p>
    </header>

    <?php if (!empty($error)): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required>

      <button type="submit" class="auth-btn">Log In</button>
    </form>

    <footer>
      <p>Don't have an account? <a href="register.php">Register here</a></p>
    </footer>
  </div>
</body>
</html>
