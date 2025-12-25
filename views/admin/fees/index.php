<?php
$active_page = 'fees';
$page_title = 'Fees Management';
ob_start();
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><i class="fas fa-money-bill-wave text-primary me-2"></i>Fees Management</h4>
        <p class="text-muted mb-0">Handle fee collection, payments, and financial records</p>
    </div>
    <a href="/admin/fees/create" class="btn btn-primary btn-lg">
        <i class="fas fa-plus me-2"></i>Record Payment
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

<!-- Fee Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Collected</h5>
                <h3>$<?php echo number_format($stats['total_collected'] ?? 0, 2); ?></h3>
                <small>This month</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Pending</h5>
                <h3>$<?php echo number_format($stats['total_pending'] ?? 0, 2); ?></h3>
                <small>Outstanding</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">This Month</h5>
                <h3>$<?php echo number_format($stats['monthly_target'] ?? 0, 2); ?></h3>
                <small>Target</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5 class="card-title">Overdue</h5>
                <h3>$<?php echo number_format($stats['overdue_amount'] ?? 0, 2); ?></h3>
                <small>Payments</small>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-plus-circle fa-2x text-primary mb-3"></i>
                <h6 class="card-title">Record Payment</h6>
                <a href="/admin/fees/create" class="btn btn-primary btn-sm">New Payment</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-file-invoice-dollar fa-2x text-success mb-3"></i>
                <h6 class="card-title">Generate Receipt</h6>
                <a href="/admin/fees/receipts" class="btn btn-success btn-sm">Print Receipts</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-chart-line fa-2x text-info mb-3"></i>
                <h6 class="card-title">Fee Structure</h6>
                <a href="/admin/fees/structure" class="btn btn-info btn-sm">Manage Structure</a>
            </div>
        </div>
    </div>
</div>

<!-- Fees Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Payment Records</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($fees)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Scholar No.</th>
                            <th>Fee Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fees as $fee): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($fee['created_at'])); ?></td>
                                <td><?php echo $fee['first_name'] . ' ' . $fee['last_name']; ?></td>
                                <td><?php echo $fee['scholar_number']; ?></td>
                                <td><?php echo $fee['fee_type'] ?? 'Tuition'; ?></td>
                                <td>$<?php echo number_format($fee['amount'], 2); ?></td>
                                <td>
                                    <span class="badge bg-success">Paid</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/admin/fees/view/<?php echo $fee['id']; ?>" class="btn btn-sm btn-outline-info" title="View Receipt">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/fees/edit/<?php echo $fee['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteFee(<?php echo $fee['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-money-bill-wave fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Fee Records Found</h4>
                <p class="text-muted">Start recording fee payments.</p>
                <a href="/admin/fees/create" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Record First Payment
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function deleteFee(feeId) {
    if (confirm('Are you sure you want to delete this fee record?')) {
        // TODO: Implement delete functionality
        alert('Delete fee record: ' + feeId);
    }
}
</script>

<?php
$content = ob_get_clean();
include '../layout.php';
?>