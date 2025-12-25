<?php
$active_page = 'students';
$page_title = 'Students Management';
ob_start();
?>

<style>
.student-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}

.student-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    text-decoration: none;
    color: inherit;
}

.student-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #f8f9fa;
}

.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
}

.search-input {
    border-radius: 25px;
    border: 2px solid #e9ecef;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
        <h4 class="mb-1"><i class="fas fa-users text-primary me-2"></i>Students Management</h4>
        <p class="text-muted mb-0">Manage student records and information</p>
    </div>
    <a href="/admin/students/create" class="btn btn-primary btn-lg">
        <i class="fas fa-plus me-2"></i>Add New Student
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
            <h5 class="mb-1">Student Overview</h5>
            <p class="mb-0 opacity-75">Total <?php echo count($students); ?> students registered in the system</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-flex justify-content-end">
                <div class="me-4">
                    <div class="h4 mb-0"><?php echo count(array_filter($students, fn($s) => $s['is_active'])); ?></div>
                    <small class="opacity-75">Active</small>
                </div>
                <div>
                    <div class="h4 mb-0"><?php echo count(array_filter($students, fn($s) => !$s['is_active'])); ?></div>
                    <small class="opacity-75">Inactive</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control search-input" id="searchInput" placeholder="Search students by name, scholar number, or class...">
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    <select class="form-select me-2" id="classFilter" style="width: auto;">
                        <option value="">All Classes</option>
                        <?php
                        $classes = array_unique(array_column($students, 'class_name'));
                        foreach ($classes as $class):
                            if ($class):
                        ?>
                            <option value="<?php echo $class; ?>"><?php echo $class; ?></option>
                        <?php endif; endforeach; ?>
                    </select>
                    <select class="form-select" id="statusFilter" style="width: auto;">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Students Grid -->
<div class="row" id="studentsGrid">
    <?php if (!empty($students)): ?>
        <?php foreach ($students as $student): ?>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 student-item"
                 data-name="<?php echo strtolower($student['first_name'] . ' ' . $student['last_name']); ?>"
                 data-scholar="<?php echo strtolower($student['scholar_number']); ?>"
                 data-class="<?php echo strtolower($student['class_name'] ?? ''); ?>"
                 data-status="<?php echo $student['is_active'] ? 'active' : 'inactive'; ?>">
                <div class="card student-card h-100">
                    <div class="card-body text-center p-4">
                        <!-- Student Photo -->
                        <div class="mb-3">
                            <?php if ($student['photo']): ?>
                                <img src="/uploads/<?php echo $student['photo']; ?>" alt="Photo" class="student-avatar">
                            <?php else: ?>
                                <div class="student-avatar bg-primary d-flex align-items-center justify-content-center mx-auto">
                                    <i class="fas fa-user text-white fa-2x"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Student Info -->
                        <h6 class="card-title mb-1"><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></h6>
                        <p class="text-muted small mb-2"><?php echo $student['scholar_number']; ?></p>

                        <!-- Class & Status -->
                        <div class="mb-3">
                            <span class="badge bg-light text-dark me-2">
                                <i class="fas fa-graduation-cap me-1"></i><?php echo $student['class_name'] ? $student['class_name'] . ' ' . $student['section'] : 'No Class'; ?>
                            </span>
                            <br>
                            <span class="status-badge <?php echo $student['is_active'] ? 'bg-success' : 'bg-danger'; ?> mt-2">
                                <?php echo $student['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </div>

                        <!-- Contact Info -->
                        <div class="small text-muted mb-3">
                            <div><i class="fas fa-phone me-1"></i><?php echo $student['mobile']; ?></div>
                            <?php if ($student['email']): ?>
                                <div><i class="fas fa-envelope me-1"></i><?php echo $student['email']; ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-center gap-2">
                            <a href="/admin/students/edit/<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-info" title="View Details" onclick="viewStudent(<?php echo $student['id']; ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="/admin/students/delete/<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Are you sure you want to delete this student?')" title="Delete">
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
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Students Found</h4>
                    <p class="text-muted">Start building your student database by adding the first student.</p>
                    <a href="/admin/students/create" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Add Your First Student
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Search and Filter Functionality
document.getElementById('searchInput').addEventListener('input', filterStudents);
document.getElementById('classFilter').addEventListener('change', filterStudents);
document.getElementById('statusFilter').addEventListener('change', filterStudents);

function filterStudents() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const classFilter = document.getElementById('classFilter').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;

    const students = document.querySelectorAll('.student-item');

    students.forEach(student => {
        const name = student.dataset.name;
        const scholar = student.dataset.scholar;
        const className = student.dataset.class;
        const status = student.dataset.status;

        const matchesSearch = name.includes(searchTerm) || scholar.includes(searchTerm);
        const matchesClass = !classFilter || className.includes(classFilter);
        const matchesStatus = !statusFilter || status === statusFilter;

        if (matchesSearch && matchesClass && matchesStatus) {
            student.style.display = '';
        } else {
            student.style.display = 'none';
        }
    });
}

function viewStudent(studentId) {
    // This could open a modal with detailed student information
    alert('View student details for ID: ' + studentId);
    // TODO: Implement detailed student view modal
}
</script>

<?php
$content = ob_get_clean();
include '../layout.php';
?>