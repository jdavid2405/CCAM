<?php 
    if (isset($_POST["submit"])) {
        $fullname = $_POST["fullname"];
        $username = $_POST["username"];
        $email = $_POST["email"];
        $contact = $_POST["contact"];
        $password = $_POST["pass"];
        $confirmPassword = $_POST["confirm"];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $erros = array();
        $success_message = '';

        // Validate inputs
        if (empty($fullname) || empty($username) || empty($email) || empty($contact) || empty($password) || empty($confirmPassword)) {
            array_push($erros, "All fields are required.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($erros, "Invalid email format.");
        }

        if ($password !== $confirmPassword) {
            array_push($erros, "Passwords do not match.");
        }

        require_once "../Database/connect.php";
        $sql = "SELECT * FROM user WHERE email = ?";
        $result = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($result, "s", $email);
        mysqli_stmt_execute($result);
        $rowCount = mysqli_stmt_get_result($result);
        if (mysqli_num_rows($rowCount) > 0) {
            array_push($erros, "Email already exists.");
        }
        
        if (count($erros) === 0) {
            $sql = "INSERT INTO user (fullname, username, email, contact, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            $preparestmt = mysqli_stmt_prepare($stmt, $sql);

            if ($preparestmt) {
                mysqli_stmt_bind_param($stmt, "sssss", $fullname, $username, $email, $contact, $hashedPassword);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                $success_message = "<div class='alert alert-success mt-3'>Registration successful! You can now <a href='../Login/login.php'>log in</a>.</div>";
            } else {
                array_push($erros, "Error: Could not prepare statement.");
            }
        }
    }
  ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up - CCAM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="signup.css">
</head>
<body>

  <div class="signup-box">
    <div class="logo mb-3">
       <a href="../Home/homepage.php"><img src="../Photos/LOGO.png" alt="CCAM Logo" style="width: 50px; height: 50px; border-radius: 50%;"></a>
    </div>

    <h3 class="mb-4">Let's get you in to CCAM</h3>

    <form id="signupForm" action="signup.php" method="POST">
      <div class="row g-3 text-start">
        <div class="col-md-6">
          <label for="firstName" class="form-label">Full Name</label>
          <input type="text" class="form-control" id="firstName" name="fullname" required>
          <div class="invalid-feedback">First name is required.</div>
        </div>
        <div class="col-md-6">
          <label for="lastName" class="form-label">Username</label>
          <input type="text" class="form-control" id="lastName" name="username" required>
          <div class="invalid-feedback">Last name is required.</div>
        </div>
        <div class="col-md-6">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" required>
          <div class="invalid-feedback">Valid email is required.</div>
        </div>
        <div class="col-md-6">
          <label for="contact" class="form-label">Contact No.</label>
          <input type="text" class="form-control" id="contact" name="contact" required>
          <div class="invalid-feedback">Contact number is required.</div>
        </div>
        <div class="col-md-6">
          <label for="password" class="form-label">Create a Password</label>
          <input type="password" class="form-control" id="password" name="pass" required>
          <div class="invalid-feedback">Password is required.</div>
        </div>
        <div class="col-md-6">
          <label for="confirmPassword" class="form-label" name="confirm">Confirm Password</label>
          <input type="password" class="form-control" id="confirmPassword" name="confirm" required>
          <div class="invalid-feedback">Passwords must match.</div>
        </div>
      </div>

      <button type="submit" class="btn btn-submit mt-4" name="submit">SUBMIT</button>
      <?php 
        if (isset($erros) && count($erros) > 0) {
            foreach ($erros as $error) {
                echo "<div class='alert alert-danger mt-3'>$error</div>";
            }
        }
        if (isset($success_message)) {
            echo $success_message;
        }
      ?>
    
    </form>
  </div>

  <script src="sujs.js"></script>
</body>
</html>