<?php
// loans.php
// DB config - change these to your actual credentials!
$dbHost = 'localhost';
$dbName = 'gcam_db';       // your actual DB name
$dbUser = 'root';          // your DB user
$dbPass = '';              // your DB password

header('Content-Type: text/html; charset=utf-8');

// PDO connection
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// Handle AJAX requests (add, edit, delete, fetch)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'fetch') {
        // Fetch all loans
        $stmt = $pdo->query("SELECT * FROM loans ORDER BY loan_date DESC");
        $loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($loans);
        exit;
    }

    if ($action === 'add') {
        $bank = $_POST['bank'] ?? '';
        $loan_date = $_POST['loanDate'] ?? '';
        $loan_amount = $_POST['loanAmount'] ?? '';

        if (!$bank || !$loan_date || !is_numeric($loan_amount)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO loans (bank, loan_date, loan_amount) VALUES (?, ?, ?)");
        $stmt->execute([$bank, $loan_date, $loan_amount]);

        echo json_encode(['status' => 'success']);
        exit;
    }

    if ($action === 'edit') {
        $id = $_POST['id'] ?? 0;
        $bank = $_POST['bank'] ?? '';
        $loan_date = $_POST['loanDate'] ?? '';
        $loan_amount = $_POST['loanAmount'] ?? '';

        if (!$id || !$bank || !$loan_date || !is_numeric($loan_amount)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE loans SET bank = ?, loan_date = ?, loan_amount = ? WHERE id = ?");
        $stmt->execute([$bank, $loan_date, $loan_amount, $id]);

        echo json_encode(['status' => 'success']);
        exit;
    }

    if ($action === 'delete') {
        $id = $_POST['id'] ?? 0;
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM loans WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['status' => 'success']);
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Loans Dashboard</title>
<style>
  /* Your original CSS copied exactly */
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
    justify-content: space-between;
  }

  .container {
    display: flex;
    max-width: 1100px;
    margin: 30px auto;
    gap: 40px;
  }

  /* Sidebar */
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

  /* Main content */
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

  input, select, textarea {
    padding: 8px 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
    width: 100%;
    font-family: 'Montserrat', Arial, sans-serif;
  }

  textarea {
    resize: vertical;
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
    margin: 0 auto 20px;
    font-weight: bold;
    font-size: 18px;
    text-align: right;
    color: #4b612c;
  }

  .filter-sort {
    max-width: 900px;
    margin: 0 auto 15px;
    display: flex;
    justify-content: flex-end;
    gap: 15px;
  }

  .filter-sort select, .filter-sort input {
    padding: 5px 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
  }

  /* Edit mode */
  .edit-mode input {
    width: 90%;
  }

  .action-btn {
    background-color: transparent;
    border: none;
    cursor: pointer;
    color: #4b612c;
    font-weight: bold;
    font-size: 14px;
    margin: 0 3px;
  }
  .action-btn.delete {
    color: #a83232;
  }
</style>
</head>
<body>

<header>
  🌍 GCAM Perso - Loans
</header>

<div class="container">

  <aside class="sidebar">
    <h2>Dashboard</h2>
    <button class="menu-btn" onclick="location.href='accounts.html'">1. Accounts</button>
    <button class="menu-btn" onclick="location.href='expenses.html'">2. Expenses</button>
    <button class="menu-btn active">3. Loans</button>
    <button class="menu-btn" onclick="location.href='incomes.html'">4. Incomes</button>
  </aside>

  <main>
    <h1>Loans</h1>

    <form id="loanForm">
      <input type="hidden" id="loanId" /> <!-- Hidden ID for edits -->
      <div>
        <label for="bank">Bank/Loan Source</label>
        <input type="text" id="bank" placeholder="Bank or lender name" required />
      </div>

      <div>
        <label for="loanDate">Loan Date</label>
        <input type="date" id="loanDate" required />
      </div>

      <div>
        <label for="loanAmount">Loan Amount (₱)</label>
        <input type="number" id="loanAmount" min="0" step="0.01" placeholder="0.00" required />
      </div>

      <button type="submit" class="submit-btn">Add Loan</button>
    </form>

    <div class="filter-sort">
      <label for="sortLoans">Sort By:</label>
      <select id="sortLoans">
        <option value="date-desc">Date Descending</option>
        <option value="date-asc">Date Ascending</option>
        <option value="amount-desc">Amount Descending</option>
        <option value="amount-asc">Amount Ascending</option>
      </select>

      <label for="filterFrom">From:</label>
      <input type="date" id="filterFrom" />

      <label for="filterTo">To:</label>
      <input type="date" id="filterTo" />

      <button id="clearFilters" class="menu-btn" style="max-width: 100px;">Clear</button>
    </div>

    <table>
      <thead>
        <tr>
          <th>Bank/Loan Source</th>
          <th>Loan Date</th>
          <th>Amount (₱)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="loanTableBody">
        <!-- Loans will appear here -->
      </tbody>
    </table>

    <div class="total" id="totalLoans">Total Loans: ₱0.00</div>
  </main>

</div>

<script>
  const loanForm = document.getElementById('loanForm');
  const loanTableBody = document.getElementById('loanTableBody');
  const totalLoansDiv = document.getElementById('totalLoans');
  const sortLoans = document.getElementById('sortLoans');
  const filterFrom = document.getElementById('filterFrom');
  const filterTo = document.getElementById('filterTo');
  const clearFilters = document.getElementById('clearFilters');

  let loans = [];

  function formatCurrency(value) {
    return '₱' + parseFloat(value).toFixed(2);
  }

  function formatDate(dateStr) {
    if (!dateStr) return '';
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    const d = new Date(dateStr + 'T00:00:00'); // avoid timezone shifts
    return d.toLocaleDateString(undefined, options);
  }

  function fetchLoans() {
    fetch('loans.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'action=fetch'
    })
    .then(res => res.json())
    .then(data => {
      loans = data;
      updateTable();
    });
  }

  function updateTable() {
    let filteredLoans = [...loans];

    // Filter by date range
    const fromDate = filterFrom.value ? new Date(filterFrom.value + 'T00:00:00') : null;
    const toDate = filterTo.value ? new Date(filterTo.value + 'T23:59:59') : null;

    if (fromDate) filteredLoans = filteredLoans.filter(l => new Date(l.loan_date + 'T00:00:00') >= fromDate);
    if (toDate) filteredLoans = filteredLoans.filter(l => new Date(l.loan_date + 'T00:00:00') <= toDate);

    // Sorting
    const sortVal = sortLoans.value;
    if (sortVal === 'date-desc') {
      filteredLoans.sort((a,b) => new Date(b.loan_date) - new Date(a.loan_date));
    } else if (sortVal === 'date-asc') {
      filteredLoans.sort((a,b) => new Date(a.loan_date) - new Date(b.loan_date));
    } else if (sortVal === 'amount-desc') {
      filteredLoans.sort((a,b) => parseFloat(b.loan_amount) - parseFloat(a.loan_amount));
    } else if (sortVal === 'amount-asc') {
      filteredLoans.sort((a,b) => parseFloat(a.loan_amount) - parseFloat(b.loan_amount));
    }

    loanTableBody.innerHTML = '';
    let total = 0;

    filteredLoans.forEach((loan) => {
      const row = document.createElement('tr');
      row.dataset.id = loan.id;

      row.innerHTML = `
        <td>${loan.bank}</td>
        <td>${formatDate(loan.loan_date)}</td>
        <td>${formatCurrency(loan.loan_amount)}</td>
        <td>
          <button class="action-btn edit">Edit</button>
          <button class="action-btn delete">Delete</button>
        </td>
      `;

      loanTableBody.appendChild(row);
      total += parseFloat(loan.loan_amount);
    });

    totalLoansDiv.textContent = `Total Loans: ${formatCurrency(total)}`;
  }

  function resetForm() {
    loanForm.reset();
    loanForm.loanId.value = '';
    loanForm.querySelector('.submit-btn').textContent = 'Add Loan';
  }

  // Add or update loan
  loanForm.addEventListener('submit', e => {
    e.preventDefault();

    const id = loanForm.loanId.value;
    const bank = loanForm.bank.value.trim();
    const loanDate = loanForm.loanDate.value;
    const loanAmount = loanForm.loanAmount.value;

    if (!bank || !loanDate || loanAmount === '') {
      alert('Please fill in all fields');
      return;
    }

    const formData = new URLSearchParams();
    formData.append('bank', bank);
    formData.append('loanDate', loanDate);
    formData.append('loanAmount', loanAmount);

    if (id) {
      formData.append('action', 'edit');
      formData.append('id', id);
    } else {
      formData.append('action', 'add');
    }

    fetch('loans.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: formData.toString()
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        fetchLoans();
        resetForm();
      } else {
        alert('Error: ' + (data.message || 'Unknown error'));
      }
    });
  });

  // Edit / Delete buttons
  loanTableBody.addEventListener('click', e => {
    if (e.target.classList.contains('edit')) {
      const row = e.target.closest('tr');
      const id = row.dataset.id;
      const loan = loans.find(l => l.id == id);
      if (!loan) return;

      loanForm.loanId.value = loan.id;
      loanForm.bank.value = loan.bank;
      loanForm.loanDate.value = loan.loan_date;
      loanForm.loanAmount.value = loan.loan_amount;
      loanForm.querySelector('.submit-btn').textContent = 'Update Loan';

      window.scrollTo({ top: 0, behavior: 'smooth' });
    } else if (e.target.classList.contains('delete')) {
      if (!confirm('Are you sure you want to delete this loan?')) return;

      const row = e.target.closest('tr');
      const id = row.dataset.id;

      const formData = new URLSearchParams();
      formData.append('action', 'delete');
      formData.append('id', id);

      fetch('loans.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: formData.toString()
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          fetchLoans();
          if (loanForm.loanId.value == id) resetForm();
        } else {
          alert('Failed to delete');
        }
      });
    }
  });

  sortLoans.addEventListener('change', updateTable);
  filterFrom.addEventListener('change', updateTable);
  filterTo.addEventListener('change', updateTable);
  clearFilters.addEventListener('click', e => {
    e.preventDefault();
    filterFrom.value = '';
    filterTo.value = '';
    updateTable();
  });

  // Initial load
  fetchLoans();

</script>

</body>
</html>
