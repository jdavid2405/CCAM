<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CCAM</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav id="top-navbar">
        <div class="navbar-left">
            <span class="navbar-user"><i class="fa-regular fa-user"></i> Admin Panel</span>
        </div>
        <div class="navbar-right">
            <a href="#" title="Search"><i class="fa-solid fa-magnifying-glass"></i></a>
            <a href="#" title="Notifications"><i class="fa-regular fa-bell"></i></a>
            <a href="#" title="Profile"><i class="fa-regular fa-user-circle"></i></a>
            <a href="/Login/login.php" title="Logout"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </nav>
    <nav id="sidebar">
        <div class="logo">
            <a href="../Home/homepage.php"><img src="../Photos/logo.png" alt="Logo"></a>
            <hr>
            <h2>ADMIN MENU</h2>
        </div>
        <ul>
            <li><a href="admin.php"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
            <li><a href="manage_users.php"><i class="fa-solid fa-users"></i> Manage Users</a></li>
            <li><a href="manage_transactions.php"><i class="fa-solid fa-money-check-dollar"></i> Transactions</a></li>
            <li><a href="manage_sales.php"><i class="fa-solid fa-chart-line"></i> Sales</a></li>
            <li><a href="manage_expenses.php"><i class="fa-solid fa-receipt"></i> Expenses</a></li>
            <li><a href="manage_customers.php"><i class="fa-solid fa-user-tie"></i> Customers</a></li>
            <li><a href="manage_employees.php"><i class="fa-solid fa-user-group"></i> Employees</a></li>
            <li><a href="manage_budget.php"><i class="fa-solid fa-wallet"></i> Budget</a></li>
            <li><a href="manage_taxes.php"><i class="fa-solid fa-file-invoice-dollar"></i> Taxes</a></li>
        </ul>
    </nav>
    <main class="admin-main">
        <header class="admin-header">
            <h1>Welcome, Admin</h1>
            <p>Use the menu to manage users, transactions, and more.</p>
        </header>
        <section class="admin-dashboard">
            <div class="admin-cards">
                <div class="admin-card">
                    <i class="fa-solid fa-users"></i>
                    <h3>Users</h3>
                    <p>View and manage all users.</p>
                </div>
                <div class="admin-card">
                    <i class="fa-solid fa-money-check-dollar"></i>
                    <h3>Transactions</h3>
                    <p>Monitor all transactions.</p>
                </div>
                <div class="admin-card">
                    <i class="fa-solid fa-chart-line"></i>
                    <h3>Sales</h3>
                    <p>Track sales performance.</p>
                </div>
                <div class="admin-card">
                    <i class="fa-solid fa-user-group"></i>
                    <h3>Employees</h3>
                    <p>Manage employee records.</p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>