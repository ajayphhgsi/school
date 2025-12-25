<?php
$active_page = 'classes';
$page_title = 'Classes & Subjects Management';
ob_start();
?>

<style>
.class-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}

.class-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    text-decoration: none;
    color: inherit;
}

.stats-summary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><i class="fas fa-chalkboard text-primary me-2"></i>Classes & Subjects Management</h4>
        <p class="text-muted mb-0">Manage classes, subjects, and academic structure</p>
    </div>
    <a href="/admin/classes/create" class="btn btn-primary btn-lg">
        <i class="fas fa-plus me-2"></i>Add New Class
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

<!-- Stats Summary -->
<div class="stats-summary">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h5 class="mb-1">Classes Overview</h5>
            <p class="mb-0 opacity-75">Total <?php echo count($classes); ?> classes in the system</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-flex justify-content-end">
                <div class="me-4">
                    <div class="h4 mb-0"><?php echo count(array_filter($classes, fn($c) => $c['is_active'])); ?></div>
                    <small class="opacity-75">Active</small>
                </div>
                <div>
                    <div class="h4 mb-0"><?php echo count(array_filter($classes, fn($c) => !$c['is_active'])); ?></div>
                    <small class="opacity-75">Inactive</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Classes Grid -->
<div class="row">
    <?php if (!empty($classes)): ?>
        <?php foreach ($classes as $class): ?>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card class-card h-100">
                    <div class="card-body text-center p-4">
                        <!-- Class Icon -->
                        <div class="mb-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px;">
                                <i class="fas fa-chalkboard-teacher text-white fa-2x"></i>
                            </div>
                        </div>

                        <!-- Class Info -->
                        <h6 class="card-title mb-1"><?php echo $class['class_name']; ?></h6>
                        <p class="text-muted small mb-2">Section: <?php echo $class['section'] ?? 'N/A'; ?></p>

                        <!-- Student Count -->
                        <div class="mb-3">
                            <span class="badge bg-info">
                                <i class="fas fa-users me-1"></i><?php echo $class['student_count']; ?> Students
                            </span>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <span class="badge <?php echo $class['is_active'] ? 'bg-success' : 'bg-danger'; ?>">
                                <?php echo $class['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-center gap-2">
                            <a href="/admin/classes/edit/<?php echo $class['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-info" title="View Details" onclick="viewClass(<?php echo $class['id']; ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="/admin/classes/delete/<?php echo $class['id']; ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Are you sure you want to delete this class?')" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Classes Found</h4>
                    <p class="text-muted">Start by creating your first class.</p>
                    <a href="/admin/classes/create" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Add Your First Class
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function viewClass(classId) {
    // This could open a modal with detailed class information
    alert('View class details for ID: ' + classId);
    // TODO: Implement detailed class view modal
}
</script>

<?php
$content = ob_get_clean();
include '../layout.php';
?>