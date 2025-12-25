<?php
$active_page = 'dashboard';
$page_title = 'Dashboard';
ob_start();
?>

<style>
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 15px;
    color: white;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    transform: translate(30px, -30px);
}

.stats-card .card-body {
    position: relative;
    z-index: 2;
}

.stats-icon {
    opacity: 0.8;
    font-size: 2.5rem;
}

.quick-action-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}

.quick-action-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-decoration: none;
    color: inherit;
}

.quick-action-card .card-body {
    padding: 2rem;
    text-align: center;
}

.quick-action-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.7;
}

.recent-activity {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 12px;
    color: white;
}

.welcome-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    color: white;
    padding: 2rem;
    margin-bottom: 2rem;
}
</style>

<!-- Welcome Section -->
<div class="welcome-section">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2>Welcome back, <?php echo $_SESSION['user']['first_name'] ?? 'Admin'; ?>! 👋</h2>
            <p class="mb-0">Here's what's happening with your school management system today.</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-flex align-items-center justify-content-end">
                <div class="me-3">
                    <small class="d-block opacity-75">Today's Date</small>
                    <strong><?php echo date('F j, Y'); ?></strong>
                </div>
                <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Total Students</h6>
                        <h2 class="mb-0"><?php echo number_format($stats['total_students']); ?></h2>
                        <small class="opacity-75">Enrolled</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Active Classes</h6>
                        <h2 class="mb-0"><?php echo number_format($stats['total_classes']); ?></h2>
                        <small class="opacity-75">Running</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">School Events</h6>
                        <h2 class="mb-0"><?php echo number_format($stats['total_events']); ?></h2>
                        <small class="opacity-75">Scheduled</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Gallery Items</h6>
                        <h2 class="mb-0"><?php echo number_format($stats['total_gallery']); ?></h2>
                        <small class="opacity-75">Photos</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-images"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Quick Actions -->
<div class="row mb-4">
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users text-primary me-2"></i>Recent Students</h5>
                <a href="/admin/students" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <?php if (!empty($stats['recent_students'])): ?>
                    <div class="row">
                        <?php foreach (array_slice($stats['recent_students'], 0, 6) as $student): ?>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="flex-shrink-0 me-3">
                                        <?php if ($student['photo']): ?>
                                            <img src="/uploads/<?php echo $student['photo']; ?>" alt="Photo" class="rounded-circle" width="50" height="50">
                                        <?php else: ?>
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></h6>
                                        <small class="text-muted">
                                            <i class="fas fa-id-card me-1"></i><?php echo $student['scholar_number']; ?> |
                                            <i class="fas fa-graduation-cap me-1"></i><?php echo $student['class_name'] ?? 'N/A'; ?>
                                        </small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="badge <?php echo $student['is_active'] ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo $student['is_active'] ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Students Yet</h5>
                        <p class="text-muted">Start by adding your first student to the system.</p>
                        <a href="/admin/students/create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add First Student
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-calendar-alt text-success me-2"></i>Upcoming Events</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($stats['upcoming_events'])): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($stats['upcoming_events'] as $event): ?>
                            <div class="list-group-item px-0 border-0">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-calendar text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?php echo $event['title']; ?></h6>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i><?php echo date('M d, Y', strtotime($event['event_date'])); ?> at <?php echo $event['event_time']; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-alt fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No upcoming events</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-bolt text-warning me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <a href="/admin/students/create" class="quick-action-card card h-100">
                            <div class="card-body">
                                <div class="quick-action-icon text-primary">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <h6 class="card-title mb-0">Add Student</h6>
                                <small class="text-muted">Register new student</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <a href="/admin/classes" class="quick-action-card card h-100">
                            <div class="card-body">
                                <div class="quick-action-icon text-success">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <h6 class="card-title mb-0">Manage Classes</h6>
                                <small class="text-muted">Create & organize classes</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <a href="/admin/attendance" class="quick-action-card card h-100">
                            <div class="card-body">
                                <div class="quick-action-icon text-info">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <h6 class="card-title mb-0">Mark Attendance</h6>
                                <small class="text-muted">Track student attendance</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <a href="/admin/exams" class="quick-action-card card h-100">
                            <div class="card-body">
                                <div class="quick-action-icon text-warning">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <h6 class="card-title mb-0">Manage Exams</h6>
                                <small class="text-muted">Create & schedule exams</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <a href="/admin/fees" class="quick-action-card card h-100">
                            <div class="card-body">
                                <div class="quick-action-icon text-danger">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <h6 class="card-title mb-0">Fee Management</h6>
                                <small class="text-muted">Handle payments & fees</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <a href="/admin/events" class="quick-action-card card h-100">
                            <div class="card-body">
                                <div class="quick-action-icon text-secondary">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <h6 class="card-title mb-0">Add Event</h6>
                                <small class="text-muted">Schedule school events</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>