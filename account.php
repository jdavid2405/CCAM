<?php
session_start();

// Hardcoded user ID for now
$userId = 1;

// DB connection
$host = 'localhost';
$dbname = 'gcam_db';
$username = 'root';
$password = '';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['personalInfo'])) {
    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $contactNumber = $_POST['contactNumber'] ?? '';
    $address = $_POST['address'] ?? '';

    if ($fullName && $email && $birthdate && $contactNumber && $address) {
      $stmt = $pdo->prepare("INSERT INTO personal_info (full_name, email, birthdate, contact_number, address) VALUES (?, ?, ?, ?, ?)");
      if ($stmt->execute([$fullName, $email, $birthdate, $contactNumber, $address])) {
        $message = "Personal info saved successfully!";
      } else {
        $message = "Failed to save personal info.";
      }
    } else {
      $message = "Please fill out all personal info fields.";
    }
  }

  if (isset($_POST['businessInfo'])) {
    $businessEmail = $_POST['businessEmail'] ?? '';
    $businessStartDate = $_POST['businessStartDate'] ?? '';
    $businessContactNumber = $_POST['businessContactNumber'] ?? '';
    $businessAddress = $_POST['businessAddress'] ?? '';

    if ($businessEmail && $businessStartDate && $businessContactNumber && $businessAddress) {
      $stmt = $pdo->prepare("INSERT INTO business_info (business_email, business_start_date, contact_number, address) VALUES (?, ?, ?, ?)");
      if ($stmt->execute([$businessEmail, $businessStartDate, $businessContactNumber, $businessAddress])) {
        $message = "Business info saved successfully!";
      } else {
        $message = "Failed to save business info.";
      }
    } else {
      $message = "Please fill out all business info fields.";
    }
  }
}
?>


<!-- HTML layout remains unchanged; skip to avoid long paste -->
<!-- Use your original HTML/CSS below here, exactly as is -->


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Accounts - Profile</title>
  <style>
    /* Your CSS exactly as you gave it */
    @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Montserrat', Arial, sans-serif;
    }

    body {
      background-color: #fff;
      color: #000;
      min-height: 100vh;
    }

    header {
      background-color: #4b612c;
      color: white;
      padding: 10px 20px;
      height: 50px;
      display: flex;
      align-items: center;
      font-weight: bold;
      font-size: 18px;
      gap: 10px;
    }

    header .back-button {
      margin-left: auto;
      color: white;
      text-decoration: none;
      font-size: 14px;
      cursor: pointer;
    }

    .container {
      display: flex;
      max-width: 1100px;
      margin: 30px auto;
      gap: 40px;
    }

    .dashboard-sidebar {
      width: 220px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      padding: 20px 15px;
      background: #f5f5f5;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .dashboard-sidebar h2 {
      text-align: center;
      color: #4b612c;
      margin-bottom: 20px;
    }

    .dashboard-btn {
      background-color: #f5f5f5;
      border: none;
      padding: 12px 15px;
      font-size: 15px;
      border-radius: 6px;
      cursor: pointer;
      text-align: left;
      color: #333;
      transition: background-color 0.3s ease;
    }

    .dashboard-btn:hover {
      background-color: #d9e0bf;
    }

    .dashboard-btn.active {
      background-color: #4b612c;
      color: white;
      font-weight: bold;
    }

    .sidebar {
      width: 300px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      padding: 30px 20px;
      text-align: center;
      height: fit-content;
    }

    .photo-box {
      margin-bottom: 30px;
    }

    .photo-placeholder {
      width: 100px;
      height: 100px;
      background-color: #ccc;
      border-radius: 50%;
      margin: 0 auto 10px;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .add-photo-btn {
      padding: 5px 10px;
      border: none;
      background-color: #e0e0e0;
      cursor: pointer;
      font-size: 12px;
      border-radius: 3px;
    }

    .nav-buttons {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-top: 30px;
    }

    .nav-btn {
      padding: 10px;
      border: none;
      background-color: #f5f5f5;
      cursor: pointer;
      text-align: left;
      font-size: 14px;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    .nav-btn.active {
      background-color: #ccc;
    }

    .nav-btn.logout {
      color: #b00;
    }

    .form-section {
      flex: 1;
      margin-left: 50px;
    }

    .form-section h2 {
      margin: 0 auto 30px;
      font-size: 24px;
      display: block;
      text-align: center;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
      max-width: 500px;
      margin: auto;
    }

    label {
      font-size: 14px;
      font-weight: 600;
    }

    input {
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 5px;
      transition: border-color 0.3s ease;
    }

    input:focus {
      border-color: #4b612c;
      outline: none;
    }

    .submit-btn {
      width: 150px;
      margin: 20px auto 0;
      padding: 10px;
      background-color: #4b612c;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    .submit-btn:hover {
      background-color: #3e4f24;
    }

    /* Message box */
    #message-box {
      max-width: 500px;
      margin: 10px auto;
      padding: 12px;
      background-color: #d4edda;
      border: 1px solid #c3e6cb;
      color: #155724;
      border-radius: 5px;
      text-align: center;
      font-weight: 600;
    }
  </style>
</head>
<body>

  <header>
    <div class="logo">🌍 GCAM Perso</div>
    <div>Account Information</div>
    <a href="#" class="back-button" onclick="history.back()">← Back</a>
  </header>

  <div class="container">

    <aside class="dashboard-sidebar">
      <h2>Dashboard</h2>
      <button class="dashboard-btn active" onclick="alert('Go to Accounts')">1. Accounts</button>
      <button class="dashboard-btn" onclick="alert('Go to Expenses')">2. Expenses</button>
      <button class="dashboard-btn" onclick="alert('Go to Loans')">3. Loans</button>
      <button class="dashboard-btn" onclick="alert('Go to Incomes')">4. Incomes</button>
    </aside>

    <aside class="sidebar">
      <div class="photo-box">
        <div class="photo-placeholder" id="photoPreview" style="background-image: url('<?= htmlspecialchars($businessInfo['photo_path'] ?? '') ?>');"></div>
        <form method="POST" enctype="multipart/form-data" id="photoUploadForm">
          <input type="file" name="profilePhoto" id="profilePhoto" accept="image/*" required />
          <button type="submit" class="add-photo-btn">Add Photo</button>
        </form>
      </div>
      <nav class="nav-buttons">
        <button class="nav-btn active" onclick="showSection('account', this)">👤 Personal Info</button>
        <button class="nav-btn" onclick="showSection('business', this)">🏢 Business Info</button>
        <button class="nav-btn logout" onclick="logout()">🚪 Log Out</button>
      </nav>
    </aside>

    <section class="form-section" id="account-form">
      <h2>Personal Information</h2>

      <?php if ($message): ?>
        <div id="message-box"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <form method="POST" id="personalInfoForm">
        <input type="hidden" name="personalInfo" value="1" />
        <label for="fullName">Full Name</label>
        <input type="text" id="fullName" name="fullName" placeholder="Your full name" required />

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required />

        <label for="birthdate">Birthdate</label>
        <input type="date" id="birthdate" name="birthdate" required />

        <label for="contactNumber">Contact Number</label>
        <input type="tel" id="contactNumber" name="contactNumber" placeholder="09xxxxxxxxx" required />

        <label for="address">Address</label>
        <input type="text" id="address" name="address" placeholder="Your address" required />

        <button type="submit" class="submit-btn">Save</button>
      </form>
    </section>

    <section class="form-section" id="business-form" style="display:none;">
      <h2>Business Information</h2>
      <form method="POST" id="businessInfoForm">
        <input type="hidden" name="businessInfo" value="1" />

        <label for="businessEmail">Business Email</label>
        <input type="email" id="businessEmail" name="businessEmail" placeholder="business@example.com" value="<?= htmlspecialchars($businessInfo['business_email'] ?? '') ?>" required />

        <label for="businessStartDate">Business Start Date</label>
        <input type="date" id="businessStartDate" name="businessStartDate" value="<?= htmlspecialchars($businessInfo['business_start_date'] ?? '') ?>" required />

        <label for="businessContactNumber">Business Contact Number</label>
        <input type="tel" id="businessContactNumber" name="businessContactNumber" placeholder="09xxxxxxxxx" value="<?= htmlspecialchars($businessInfo['contact_number'] ?? '') ?>" required />

        <label for="businessAddress">Business Address</label>
        <input type="text" id="businessAddress" name="businessAddress" placeholder="Business address" value="<?= htmlspecialchars($businessInfo['address'] ?? '') ?>" required />

        <button type="submit" class="submit-btn">Save</button>
      </form>
    </section>

  </div>

  <script>
    // Simple tabs switching for forms
    function showSection(section, btn) {
      document.querySelectorAll('.form-section').forEach(s => s.style.display = 'none');
      document.getElementById(section + '-form').style.display = 'block';

      document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    }

    function logout() {
      alert('Logging out...');
      // Redirect to logout or homepage here if needed
    }
  </script>

</body>
</html>
