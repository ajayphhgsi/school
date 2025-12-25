<?php
$active_page = 'settings';
$page_title = 'System Settings';
ob_start();
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><i class="fas fa-cog text-primary me-2"></i>System Settings</h4>
        <p class="text-muted mb-0">Configure system preferences and settings</p>
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

<!-- Settings Tabs -->
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">General</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="school-tab" data-bs-toggle="tab" data-bs-target="#school" type="button" role="tab">School Info</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab">Email</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">Security</button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="settingsTabContent">
            <!-- General Settings -->
            <div class="tab-pane fade show active" id="general" role="tabpanel">
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="siteName" class="form-label">Site Name</label>
                            <input type="text" class="form-control" id="siteName" value="School Management System">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="timezone" class="form-label">Timezone</label>
                            <select class="form-select" id="timezone">
                                <option value="UTC" selected>UTC</option>
                                <option value="America/New_York">Eastern Time</option>
                                <option value="America/Chicago">Central Time</option>
                                <option value="America/Denver">Mountain Time</option>
                                <option value="America/Los_Angeles">Pacific Time</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="language" class="form-label">Default Language</label>
                            <select class="form-select" id="language">
                                <option value="en" selected>English</option>
                                <option value="es">Spanish</option>
                                <option value="fr">French</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dateFormat" class="form-label">Date Format</label>
                            <select class="form-select" id="dateFormat">
                                <option value="Y-m-d" selected>YYYY-MM-DD</option>
                                <option value="m/d/Y">MM/DD/YYYY</option>
                                <option value="d/m/Y">DD/MM/YYYY</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save General Settings</button>
                </form>
            </div>

            <!-- School Information -->
            <div class="tab-pane fade" id="school" role="tabpanel">
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="schoolName" class="form-label">School Name</label>
                            <input type="text" class="form-control" id="schoolName" value="Example School">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="schoolCode" class="form-label">School Code</label>
                            <input type="text" class="form-control" id="schoolCode" value="EXS001">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="schoolAddress" class="form-label">Address</label>
                        <textarea class="form-control" id="schoolAddress" rows="3">123 School Street, City, State 12345</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="schoolPhone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="schoolPhone" value="+1 (555) 123-4567">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="schoolEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="schoolEmail" value="info@example-school.com">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save School Information</button>
                </form>
            </div>

            <!-- Email Settings -->
            <div class="tab-pane fade" id="email" role="tabpanel">
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="smtpHost" class="form-label">SMTP Host</label>
                            <input type="text" class="form-control" id="smtpHost" value="smtp.gmail.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="smtpPort" class="form-label">SMTP Port</label>
                            <input type="number" class="form-control" id="smtpPort" value="587">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="smtpUser" class="form-label">SMTP Username</label>
                            <input type="text" class="form-control" id="smtpUser">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="smtpPass" class="form-label">SMTP Password</label>
                            <input type="password" class="form-control" id="smtpPass">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fromEmail" class="form-label">From Email</label>
                        <input type="email" class="form-control" id="fromEmail" value="noreply@school.com">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Email Settings</button>
                </form>
            </div>

            <!-- Security Settings -->
            <div class="tab-pane fade" id="security" role="tabpanel">
                <form>
                    <div class="mb-3">
                        <label for="sessionTimeout" class="form-label">Session Timeout (minutes)</label>
                        <input type="number" class="form-control" id="sessionTimeout" value="60">
                    </div>
                    <div class="mb-3">
                        <label for="passwordMinLength" class="form-label">Minimum Password Length</label>
                        <input type="number" class="form-control" id="passwordMinLength" value="8">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="twoFactorAuth" checked>
                        <label class="form-check-label" for="twoFactorAuth">Enable Two-Factor Authentication</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="passwordExpiry" checked>
                        <label class="form-check-label" for="passwordExpiry">Enable Password Expiry (90 days)</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Security Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Handle form submissions
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        // TODO: Implement settings save functionality
        alert('Settings saved successfully - Feature to be implemented');
    });
});
</script>

<?php
$content = ob_get_clean();
include '../layout.php';
?>