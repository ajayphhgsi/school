<?php
$active_page = 'profile';
$page_title = 'My Profile';
ob_start();
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><i class="fas fa-user text-primary me-2"></i>My Profile</h4>
        <p class="text-muted mb-0">View and update your personal information</p>
    </div>
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

<div class="row">
    <!-- Profile Photo & Basic Info -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <?php if ($student['photo']): ?>
                    <img src="/uploads/<?php echo $student['photo']; ?>" alt="Profile Photo" class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                <?php else: ?>
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px;">
                        <i class="fas fa-user text-white fa-3x"></i>
                    </div>
                <?php endif; ?>

                <h5><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></h5>
                <p class="text-muted mb-1">Scholar Number: <?php echo $student['scholar_number']; ?></p>
                <p class="text-muted mb-0">Roll Number: <?php echo $student['roll_number'] ?? 'N/A'; ?></p>
            </div>
        </div>
    </div>

    <!-- Profile Details -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <p class="mb-0"><?php echo $student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name']; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Date of Birth</label>
                        <p class="mb-0"><?php echo date('M d, Y', strtotime($student['date_of_birth'])); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Gender</label>
                        <p class="mb-0"><?php echo ucfirst($student['gender']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Admission Date</label>
                        <p class="mb-0"><?php echo date('M d, Y', strtotime($student['admission_date'])); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Class & Section</label>
                        <p class="mb-0"><?php echo $student['class_name'] . ' ' . $student['section']; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Scholar Number</label>
                        <p class="mb-0"><?php echo $student['scholar_number']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Information -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Contact Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Mobile Number</label>
                        <p class="mb-0"><?php echo $student['mobile'] ?? 'Not provided'; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <p class="mb-0"><?php echo $student['email'] ?? 'Not provided'; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <p class="mb-0"><?php echo $student['address'] ?? 'Not provided'; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Permanent Address</label>
                        <p class="mb-0"><?php echo $student['permanent_address'] ?? 'Not provided'; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Guardian Information -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Guardian Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Father's Name</label>
                        <p class="mb-0"><?php echo $student['father_name'] ?? 'Not provided'; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Mother's Name</label>
                        <p class="mb-0"><?php echo $student['mother_name'] ?? 'Not provided'; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Guardian's Name</label>
                        <p class="mb-0"><?php echo $student['guardian_name'] ?? 'Not provided'; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Guardian Contact</label>
                        <p class="mb-0"><?php echo $student['guardian_contact'] ?? 'Not provided'; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Information -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Additional Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Caste/Category</label>
                        <p class="mb-0"><?php echo $student['caste_category'] ?? 'Not provided'; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Religion</label>
                        <p class="mb-0"><?php echo $student['religion'] ?? 'Not provided'; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Blood Group</label>
                        <p class="mb-0"><?php echo $student['blood_group'] ?? 'Not provided'; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Medical Conditions</label>
                        <p class="mb-0"><?php echo $student['medical_conditions'] ?? 'None'; ?></p>
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