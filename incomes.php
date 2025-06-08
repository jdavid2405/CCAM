<?php
// Define DB connection info
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'gcam_db';

// Create connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle GET request for fetching incomes JSON
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get') {
    header('Content-Type: application/json');

    $result = $conn->query("SELECT * FROM incomes ORDER BY id DESC");
    $incomes = [];
    if ($result) {
      while ($row = $result->fetch_assoc()) {
        $incomes[] = $row;
      }
    }

    echo json_encode($incomes);
    $conn->close();
    exit;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

  header('Content-Type: application/json');

  $action = $_POST['action'];

  if ($action === 'add') {
    $name = $conn->real_escape_string($_POST['name']);
    $date = $conn->real_escape_string($_POST['date']);
    $amount = floatval($_POST['amount']);

    $sql = "INSERT INTO incomes (name, date, amount) VALUES ('$name', '$date', $amount)";
    if ($conn->query($sql) === TRUE) {
      echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    } else {
      echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    exit;
  }

  if ($action === 'edit') {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $date = $conn->real_escape_string($_POST['date']);
    $amount = floatval($_POST['amount']);

    $sql = "UPDATE incomes SET name='$name', date='$date', amount=$amount WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    exit;
  }

  if ($action === 'delete') {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM incomes WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    exit;
  }
}

// If no AJAX, output full HTML page with embedded JS and initial data from DB

// Fetch incomes from DB
$result = $conn->query("SELECT * FROM incomes ORDER BY id DESC");
$incomes = [];
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $incomes[] = $row;
  }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Incomes Dashboard</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

    * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: 'Montserrat', sans-serif;
    }

    body {
      background: #fff;
      color: #000;
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
    }

    .container {
      display: flex;
      max-width: 1100px;
      margin: 30px auto;
      gap: 40px;
    }

    .sidebar {
      width: 220px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      padding: 20px 15px;
      background: #f5f5f5;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .sidebar h2 {
      text-align: center;
      color: #4b612c;
      margin-bottom: 20px;
    }

    .menu-btn {
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

    .menu-btn:hover {
      background-color: #d9e0bf;
    }

    .menu-btn.active {
      background-color: #4b612c;
      color: white;
      font-weight: bold;
    }

    main {
      flex: 1;
    }

    h1 {
      text-align: center;
      color: #4b612c;
      margin-bottom: 25px;
    }

    form {
      max-width: 650px;
      margin: 0 auto 30px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px 20px;
    }

    label {
      font-weight: 600;
      font-size: 14px;
    }

    input, select {
      padding: 8px 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    .submit-btn {
      grid-column: 1 / -1;
      background-color: #4b612c;
      color: white;
      border: none;
      padding: 12px 0;
      font-weight: bold;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      max-width: 200px;
      justify-self: center;
    }

    .submit-btn:hover {
      background-color: #3e4f24;
    }

    .filters {
      max-width: 650px;
      margin: 0 auto 20px;
      display: flex;
      gap: 10px;
      justify-content: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      max-width: 900px;
      margin: 0 auto 15px;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
      font-size: 14px;
    }

    th {
      background-color: #4b612c;
      color: white;
      cursor: pointer;
    }

    tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .total {
      max-width: 900px;
      margin: 0 auto;
      font-weight: bold;
      font-size: 18px;
      text-align: right;
      color: #4b612c;
    }

    .action-btn {
      background: none;
      border: none;
      cursor: pointer;
      font-weight: bold;
      color: #4b612c;
    }

    .action-btn:hover {
      color: #d62828;
    }
  </style>
</head>
<body>

<header>🌍 GCAM Perso - Incomes</header>

<div class="container">
  <aside class="sidebar">
    <h2>Dashboard</h2>
    <button class="menu-btn" onclick="alert('Go to Accounts')">1. Accounts</button>
    <button class="menu-btn" onclick="alert('Go to Expenses')">2. Expenses</button>
    <button class="menu-btn" onclick="alert('Go to Loans')">3. Loans</button>
    <button class="menu-btn active">4. Incomes</button>
  </aside>

  <main>
    <h1>Incomes</h1>

    <form id="incomeForm">
      <input type="hidden" id="incomeId" />
      <div>
        <label for="name">Income Name</label>
        <input type="text" id="name" required placeholder="e.g., Salary, Bonus"/>
      </div>

      <div>
        <label for="date">Date</label>
        <input type="date" id="date" required />
      </div>

      <div>
        <label for="amount">Amount (₱)</label>
        <input type="number" id="amount" required min="0" step="0.01" placeholder="0.00" />
      </div>

      <button type="submit" class="submit-btn">Add Income</button>
    </form>

    <div class="filters">
      <input type="date" id="filterStartDate" />
      <input type="date" id="filterEndDate" />
      <button type="button" onclick="filterIncomes()">Filter</button>
      <button type="button" onclick="resetFilter()">Reset</button>
    </div>

    <table>
      <thead>
        <tr>
          <th onclick="sortBy('name')">Name</th>
          <th onclick="sortBy('date')">Date</th>
          <th onclick="sortBy('amount')">Amount</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="incomeTableBody"></tbody>
    </table>

    <div class="total" id="totalIncomes">Total Income: ₱0.00</div>
  </main>
</div>

<script>
  let incomes = <?php echo json_encode($incomes, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
  let sortKey = '';
  let sortAsc = true;

  const incomeForm = document.getElementById('incomeForm');
  const incomeTableBody = document.getElementById('incomeTableBody');
  const totalIncomes = document.getElementById('totalIncomes');
  const incomeIdInput = document.getElementById('incomeId');

  function formatCurrency(amount) {
    return '₱' + parseFloat(amount).toFixed(2);
  }

  function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
  }

  function updateTable(data = incomes) {
    incomeTableBody.innerHTML = '';
    let total = 0;

    data.forEach((inc) => {
      total += parseFloat(inc.amount);
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${inc.name}</td>
        <td>${formatDate(inc.date)}</td>
        <td>${formatCurrency(inc.amount)}</td>
        <td>
          <button class="action-btn" onclick="editIncome(${inc.id})">✏️</button>
          <button class="action-btn" onclick="deleteIncome(${inc.id})">🗑️</button>
        </td>
      `;
      incomeTableBody.appendChild(row);
    });

    totalIncomes.textContent = `Total Income: ${formatCurrency(total)}`;
  }

  function saveData(data) {
    fetch('incomes.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(data)
    })
    .then(res => res.json())
    .then(response => {
      if (!response.success) {
        alert('Error: ' + (response.error || 'Unknown error'));
      } else {
        loadData();
      }
    })
    .catch(() => alert('Request failed'));
  }

  incomeForm.addEventListener('submit', e => {
    e.preventDefault();
    const id = incomeIdInput.value;
    const name = incomeForm.name.value.trim();
    const date = incomeForm.date.value;
    const amount = incomeForm.amount.value;

    if (!name || !date || isNaN(amount) || amount < 0) {
      alert('Please fill out the form correctly.');
      return;
    }

    if (id) {
      saveData({ action: 'edit', id, name, date, amount });
    } else {
      saveData({ action: 'add', name, date, amount });
    }

    incomeForm.reset();
    incomeIdInput.value = '';
    incomeForm.querySelector('button[type="submit"]').textContent = 'Add Income';
  });

  function editIncome(id) {
    const income = incomes.find(i => i.id == id);
    if (!income) return;
    incomeIdInput.value = income.id;
    incomeForm.name.value = income.name;
    incomeForm.date.value = income.date;
    incomeForm.amount.value = income.amount;
    incomeForm.querySelector('button[type="submit"]').textContent = 'Update Income';
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function deleteIncome(id) {
    if (!confirm('Are you sure you want to delete this income?')) return;
    saveData({ action: 'delete', id });
  }

  function loadData() {
    fetch('incomes.php?action=get')
      .then(res => res.json())
      .then(data => {
        incomes = data;
        applyFilterSort();
      })
      .catch(() => alert('Failed to load incomes from server'));
  }

  // Sorting and Filtering

  function sortBy(key) {
    if (sortKey === key) {
      sortAsc = !sortAsc;
    } else {
      sortKey = key;
      sortAsc = true;
    }
    applyFilterSort();
  }

  function filterIncomes() {
    applyFilterSort();
  }

  function resetFilter() {
    document.getElementById('filterStartDate').value = '';
    document.getElementById('filterEndDate').value = '';
    applyFilterSort();
  }

  function applyFilterSort() {
    let filtered = incomes;

    const startDate = document.getElementById('filterStartDate').value;
    const endDate = document.getElementById('filterEndDate').value;

    if (startDate) {
      filtered = filtered.filter(i => i.date >= startDate);
    }
    if (endDate) {
      filtered = filtered.filter(i => i.date <= endDate);
    }

    if (sortKey) {
      filtered = filtered.sort((a, b) => {
        if (sortKey === 'amount') {
          return sortAsc ? a.amount - b.amount : b.amount - a.amount;
        }
        if (sortKey === 'date') {
          return sortAsc ? a.date.localeCompare(b.date) : b.date.localeCompare(a.date);
        }
        // default: name
        return sortAsc ? a.name.localeCompare(b.name) : b.name.localeCompare(a.name);
      });
    }

    updateTable(filtered);
  }

  // Initialize table
  updateTable();

</script>

</body>
</html>
