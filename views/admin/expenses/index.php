<?php
$active_page = 'expenses';
$page_title = 'Expense Management';
ob_start();
?>

<style>
.expense-card {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.expense-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.category-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.expense-summary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><i class="fas fa-money-bill-wave text-danger me-2"></i>Expense Management</h4>
        <p class="text-muted mb-0">Track and manage school expenses</p>
    </div>
    <a href="/admin/expenses/create" class="btn btn-danger btn-lg">
        <i class="fas fa-plus me-2"></i>Record Expense
    </a>
</div>

<!-- Flash Messages -->
<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['flash']['success']; unset($_SESSION['flash']['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['flash']['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $_SESSION['flash']['error']; unset($_SESSION['flash']['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Expense Summary -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card expense-summary">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-calendar-month fa-2x opacity-75"></i>
                </div>
                <h5 class="mb-1">$<?php echo number_format($stats['monthly_total'] ?? 0, 2); ?></h5>
                <p class="mb-0 opacity-75 small">This Month</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card expense-summary">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-gas-pump fa-2x opacity-75"></i>
                </div>
                <h5 class="mb-1">$<?php echo number_format($stats['diesel_total'] ?? 0, 2); ?></h5>
                <p class="mb-0 opacity-75 small">Diesel/Fuel</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card expense-summary">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-users fa-2x opacity-75"></i>
                </div>
                <h5 class="mb-1">$<?php echo number_format($stats['staff_total'] ?? 0, 2); ?></h5>
                <p class="mb-0 opacity-75 small">Staff Salaries</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card expense-summary">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-tools fa-2x opacity-75"></i>
                </div>
                <h5 class="mb-1">$<?php echo number_format($stats['maintenance_total'] ?? 0, 2); ?></h5>
                <p class="mb-0 opacity-75 small">Maintenance</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-3">
                <label for="categoryFilter" class="form-label">Category</label>
                <select class="form-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="diesel">Diesel/Fuel</option>
                    <option value="staff">Staff Salaries</option>
                    <option value="bus">Bus Expenses</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="misc">Miscellaneous</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="monthFilter" class="form-label">Month</label>
                <input type="month" class="form-control" id="monthFilter" value="<?php echo date('Y-m'); ?>">
            </div>
            <div class="col-md-3">
                <label for="searchInput" class="form-label">Search</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Search expenses...">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button class="btn btn-outline-primary" onclick="applyFilters()">
                        <i class="fas fa-filter me-1"></i>Apply Filters
                    </button>
                    <button class="btn btn-outline-secondary ms-1" onclick="exportExpenses()">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Expenses Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Expense Records</h5>
        <div class="text-muted small">
            Total: <span id="totalRecords"><?php echo count($expenses); ?></span> records
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($expenses)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reason</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Payment Mode</th>
                            <th>Recorded By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="expensesTableBody">
                        <?php foreach ($expenses as $expense): ?>
                            <tr data-category="<?php echo $expense['category']; ?>" data-reason="<?php echo strtolower($expense['reason']); ?>">
                                <td><?php echo date('M d, Y', strtotime($expense['expense_date'])); ?></td>
                                <td><?php echo htmlspecialchars($expense['reason']); ?></td>
                                <td>
                                    <span class="badge bg-secondary category-badge">
                                        <?php echo ucfirst($expense['category']); ?>
                                    </span>
                                </td>
                                <td>$<?php echo number_format($expense['amount'], 2); ?></td>
                                <td><?php echo ucfirst($expense['payment_mode']); ?></td>
                                <td><?php echo $expense['recorded_by_name'] ?? 'System'; ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-info" onclick="viewExpense(<?php echo $expense['id']; ?>)" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="/admin/expenses/edit/<?php echo $expense['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/admin/expenses/delete/<?php echo $expense['id']; ?>" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this expense record?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Expense pagination" class="mt-3">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                <a class="page-link" href="/admin/expenses?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-money-bill-wave fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Expense Records Found</h4>
                <p class="text-muted">Start recording expenses to track your school's spending.</p>
                <a href="/admin/expenses/create" class="btn btn-danger btn-lg">
                    <i class="fas fa-plus me-2"></i>Record First Expense
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Category-wise Analysis Chart -->
<?php if (!empty($expenses)): ?>
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Expense Analysis by Category</h5>
    </div>
    <div class="card-body">
        <canvas id="expenseChart" width="400" height="200"></canvas>
    </div>
</div>
<?php endif; ?>

<script>
// Filter functionality
function applyFilters() {
    const categoryFilter = document.getElementById('categoryFilter').value;
    const monthFilter = document.getElementById('monthFilter').value;
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();

    const rows = document.querySelectorAll('#expensesTableBody tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const category = row.dataset.category;
        const reason = row.dataset.reason;
        const date = row.cells[0].textContent;

        const matchesCategory = !categoryFilter || category === categoryFilter;
        const matchesSearch = !searchTerm || reason.includes(searchTerm);
        const matchesMonth = !monthFilter || date.includes(new Date(monthFilter + '-01').toLocaleDateString('en-US', { month: 'short', year: 'numeric' }));

        if (matchesCategory && matchesSearch && matchesMonth) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('totalRecords').textContent = visibleCount;
}

function exportExpenses() {
    const categoryFilter = document.getElementById('categoryFilter').value;
    const monthFilter = document.getElementById('monthFilter').value;

    let url = '/admin/expenses/export?';
    if (categoryFilter) url += `category=${categoryFilter}&`;
    if (monthFilter) url += `month=${monthFilter}&`;

    window.open(url, '_blank');
}

function viewExpense(expenseId) {
    // This would open a modal with detailed expense information
    // For now, redirect to edit page
    window.location.href = `/admin/expenses/edit/${expenseId}`;
}

// Chart.js integration for expense analysis
<?php if (!empty($expenses)): ?>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('expenseChart').getContext('2d');

    // Prepare data for chart
    const categoryData = <?php echo json_encode($category_totals); ?>;

    const data = {
        labels: Object.keys(categoryData),
        datasets: [{
            label: 'Expenses by Category',
            data: Object.values(categoryData),
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40'
            ],
            borderWidth: 1
        }]
    };

    const config = {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': $' + context.parsed.toFixed(2);
                        }
                    }
                }
            }
        }
    };

    new Chart(ctx, config);
});
<?php endif; ?>
</script>

<?php
$content = ob_get_clean();
include '../layout.php';
?>