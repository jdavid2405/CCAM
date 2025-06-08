<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Expenses Dashboard</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Montserrat', sans-serif; }
    body { background: #fff; color: #000; }
    header { background-color: #4b612c; color: white; padding: 10px 20px; height: 50px; display: flex; align-items: center; font-weight: bold; font-size: 18px; }
    .container { display: flex; max-width: 1100px; margin: 30px auto; gap: 40px; }
    .sidebar { width: 220px; border: 2px solid #e0e0e0; border-radius: 10px; padding: 20px 15px; background: #f5f5f5; display: flex; flex-direction: column; gap: 15px; }
    .sidebar h2 { text-align: center; color: #4b612c; margin-bottom: 20px; }
    .menu-btn { background-color: #f5f5f5; border: none; padding: 12px 15px; font-size: 15px; border-radius: 6px; cursor: pointer; text-align: left; color: #333; transition: background-color 0.3s ease; }
    .menu-btn:hover { background-color: #d9e0bf; }
    .menu-btn.active { background-color: #4b612c; color: white; font-weight: bold; }
    main { flex: 1; }
    h1 { text-align: center; color: #4b612c; margin-bottom: 25px; }
    form { max-width: 650px; margin: 0 auto 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px 20px; }
    label { font-weight: 600; font-size: 14px; }
    input, select { padding: 8px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 14px; }
    .submit-btn { grid-column: 1 / -1; background-color: #4b612c; color: white; border: none; padding: 12px 0; font-weight: bold; font-size: 16px; border-radius: 6px; cursor: pointer; transition: background-color 0.3s ease; max-width: 200px; justify-self: center; }
    .submit-btn:hover { background-color: #3e4f24; }
    .filters { max-width: 650px; margin: 0 auto 20px; display: flex; gap: 10px; justify-content: center; }
    table { width: 100%; border-collapse: collapse; max-width: 900px; margin: 0 auto 15px; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: center; font-size: 14px; }
    th { background-color: #4b612c; color: white; cursor: pointer; }
    tbody tr:nth-child(even) { background-color: #f9f9f9; }
    .total { max-width: 900px; margin: 0 auto; font-weight: bold; font-size: 18px; text-align: right; color: #4b612c; }
    .action-btn { background: none; border: none; cursor: pointer; font-weight: bold; color: #4b612c; }
    .action-btn:hover { color: #d62828; }
  </style>
</head>
<body>

<header>🌍 GCAM Perso - Expenses</header>

<div class="container">
  <aside class="sidebar">
    <h2>Dashboard</h2>
    <button class="menu-btn" onclick="alert('Go to Accounts')">1. Accounts</button>
    <button class="menu-btn active">2. Expenses</button>
    <button class="menu-btn" onclick="alert('Go to Loans')">3. Loans</button>
    <button class="menu-btn" onclick="alert('Go to Incomes')">4. Incomes</button>
  </aside>

  <main>
    <h1>Expenses</h1>

    <form id="expenseForm">
      <div>
        <label for="name">Expense Name</label>
        <input type="text" id="name" required placeholder="e.g., Grocery, Electricity"/>
      </div>
      <div>
        <label for="date">Date</label>
        <input type="date" id="date" required />
      </div>
      <div>
        <label for="amount">Amount (₱)</label>
        <input type="number" id="amount" required min="0" step="0.01" placeholder="0.00" />
      </div>
      <button type="submit" class="submit-btn">Add Expense</button>
    </form>

    <div class="filters">
      <input type="date" id="filterStartDate" />
      <input type="date" id="filterEndDate" />
      <button onclick="filterExpenses()">Filter</button>
      <button onclick="resetFilter()">Reset</button>
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
      <tbody id="expenseTableBody"></tbody>
    </table>

    <div class="total" id="totalExpenses">Total Expenses: ₱0.00</div>
  </main>
</div>

<script>
 let expenses = [];
let sortKey = '';
let sortAsc = true;

const expenseForm = document.getElementById('expenseForm');
const expenseTableBody = document.getElementById('expenseTableBody');
const totalExpenses = document.getElementById('totalExpenses');

function formatCurrency(amount) {
  return '₱' + parseFloat(amount).toFixed(2);
}

function formatDate(dateStr) {
  const date = new Date(dateStr);
  return date.toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
}

// Fetch expenses from DB
function fetchExpenses() {
  fetch('fetch_expenses.php')
    .then(res => res.json())
    .then(data => {
      expenses = data;
      updateTable();
    })
    .catch(() => alert('Failed to load expenses'));
}

// Render table rows and total
function updateTable(data = expenses) {
  expenseTableBody.innerHTML = '';
  let total = 0;
  data.forEach((exp, i) => {
    total += parseFloat(exp.amount);
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${exp.name}</td>
      <td>${formatDate(exp.date)}</td>
      <td>${formatCurrency(exp.amount)}</td>
      <td>
        <button class="action-btn" onclick="editExpense(${exp.id})">✏️</button>
        <button class="action-btn" onclick="deleteExpense(${exp.id})">🗑️</button>
      </td>
    `;
    expenseTableBody.appendChild(row);
  });
  totalExpenses.textContent = `Total Expenses: ${formatCurrency(total)}`;
}

// Add new expense (POST to DB)
expenseForm.addEventListener('submit', e => {
  e.preventDefault();
  const name = expenseForm.name.value.trim();
  const date = expenseForm.date.value;
  const amount = expenseForm.amount.value;

  if (!name || !date || isNaN(amount) || amount < 0) return alert('Please fill all fields properly.');

  fetch('save_expense.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({name, date, amount})
  })
  .then(res => res.json())
  .then(response => {
    if (response.success) {
      fetchExpenses();  // Reload from DB after insert
      expenseForm.reset();
    } else {
      alert('Failed to save expense');
    }
  })
  .catch(() => alert('Failed to save expense'));
});

// Delete expense by id
function deleteExpense(id) {
  if (!confirm('Delete this expense?')) return;
  fetch('delete_expense.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({id})
  })
  .then(res => res.json())
  .then(response => {
    if (response.success) {
      fetchExpenses();  // Reload after delete
    } else {
      alert('Failed to delete expense');
    }
  })
  .catch(() => alert('Failed to delete expense'));
}

// For editing, you need to fetch the item, then update via your own logic.
// To keep it simple, I suggest delete + add again on edit for now:
function editExpense(id) {
  // Find expense by id
  const exp = expenses.find(e => e.id == id);
  if (!exp) return alert('Expense not found');
  if (!confirm(`Edit "${exp.name}"?`)) return;

  expenseForm.name.value = exp.name;
  expenseForm.date.value = exp.date;
  expenseForm.amount.value = exp.amount;

  // Delete the old one, then user submits new one
  deleteExpense(id);
}

// Sorting
function sortBy(key) {
  sortAsc = sortKey === key ? !sortAsc : true;
  sortKey = key;
  expenses.sort((a, b) => {
    if (key === 'amount') return sortAsc ? a.amount - b.amount : b.amount - a.amount;
    if (key === 'date') return sortAsc ? new Date(a.date) - new Date(b.date) : new Date(b.date) - new Date(a.date);
    return sortAsc ? a.name.localeCompare(b.name) : b.name.localeCompare(a.name);
  });
  updateTable();
}

// Filtering
function filterExpenses() {
  const start = document.getElementById('filterStartDate').value;
  const end = document.getElementById('filterEndDate').value;
  const filtered = expenses.filter(e => (!start || e.date >= start) && (!end || e.date <= end));
  updateTable(filtered);
}

function resetFilter() {
  document.getElementById('filterStartDate').value = '';
  document.getElementById('filterEndDate').value = '';
  updateTable();
}

// Initial load
fetchExpenses();

</script>


</body>
</html>
