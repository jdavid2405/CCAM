<?php
  session_start();
  /*
    check if the username is existing in the database, if valid
    the user's credential will be assigned to a SESSION superglobal variable and be relocated to the home page
    else, the users will be redirected back to the login page
  */
  if(isset($_POST['login'])) {
    include("../Database/connect.php");
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * from user WHERE username='{$username}';";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        if(password_verify($password, $row["password"])) {
          $_SESSION['user_id'] = $row['user_id'];
          $_SESSION['username'] = $row['username'];
          
          header("Location: ../Personal/DashPersonal.php");
        }
        else {
          echo "<script>alert('Invalid Credentials');</script>";
          header("Location: ../Home/homppage.php?InvalidCredentials");
        }
      }
    }
    else {
      header("Location: login.php");
    }
    mysqli_close($conn);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - CCAM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="loginstyle.css">
</head>
<body>

<div class="login-box">
  <div class="logo mb-3">
    <img src="../Photos/LOGO.png" alt="CCAM Logo">
  </div>

  <h2 class="mb-4">Welcome Back!</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form id="loginForm" action="login.php" method="POST" novalidate>
    <div class="mb-3 text-start">
      <label for="email" class="form-label">Username</label>
      <input type="text" class="form-control" id="email" name="username" required>
      <div class="invalid-feedback">Please enter your email or ID.</div>
    </div>

    <div class="mb-4 text-start">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
      <div class="invalid-feedback">Please enter your password.</div>
    </div>

    <button type="submit" class="btn btn-login w-100" name="login">LOG IN</button>
  </form>

  <div class="register-link mt-3">
    New to CCAM? <a href="../Signup/signup.php">Register here</a>
  </div>
</div>

<script src="login.js"></script>
</body>
</html>