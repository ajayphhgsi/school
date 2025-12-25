<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Admin Panel'; ?> - School Management System</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #343a40;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 0;
            text-align: left;
        }
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,.1);
        }
        .sidebar .nav-link.active {
            color: white;
            background: #0d6efd;
        }
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }
        .header {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        .content-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .content-overlay.show {
                display: block;
            }
        }
        @media (min-width: 769px) {
            .sidebar {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <!-- Content Overlay for Mobile -->
    <div class="content-overlay" id="contentOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column p-3" id="sidebar">
        <div class="mb-4">
            <h5 class="text-center">
                <i class="fas fa-school"></i><br>
                School Admin
            </h5>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'dashboard' ? 'active' : ''; ?>" href="/admin/dashboard">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'students' ? 'active' : ''; ?>" href="/admin/students">
                    <i class="fas fa-users"></i> Students
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'classes' ? 'active' : ''; ?>" href="/admin/classes">
                    <i class="fas fa-chalkboard"></i> Classes & Subjects
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'attendance' ? 'active' : ''; ?>" href="/admin/attendance">
                    <i class="fas fa-calendar-check"></i> Attendance
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'exams' ? 'active' : ''; ?>" href="/admin/exams">
                    <i class="fas fa-file-alt"></i> Exams & Results
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'fees' ? 'active' : ''; ?>" href="/admin/fees">
                    <i class="fas fa-money-bill-wave"></i> Fees
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'events' ? 'active' : ''; ?>" href="/admin/events">
                    <i class="fas fa-calendar"></i> Events
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'gallery' ? 'active' : ''; ?>" href="/admin/gallery">
                    <i class="fas fa-images"></i> Gallery
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'reports' ? 'active' : ''; ?>" href="/admin/reports">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'settings' ? 'active' : ''; ?>" href="/admin/settings">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
        </ul>

        <div class="mt-auto">
            <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['user']['first_name'] ?? 'Admin'; ?>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/admin/profile"><i class="fas fa-user-edit"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="/admin/change-password"><i class="fas fa-key"></i> Change Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="header d-flex justify-content-between align-items-center">
            <div>
                <button class="btn btn-outline-secondary d-md-none" type="button" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="mb-0 d-inline ms-2"><?php echo $page_title ?? 'Dashboard'; ?></h4>
            </div>
            <div>
                <span class="text-muted">
                    Welcome, <?php echo $_SESSION['user']['first_name'] ?? 'Admin'; ?> |
                    <?php echo date('M d, Y'); ?>
                </span>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4">
            <?php echo $content; ?>
        </main>
    </div>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('contentOverlay');

            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('contentOverlay');
            const toggleBtn = event.target.closest('.navbar-toggler');

            if (!sidebar.contains(event.target) && !toggleBtn && window.innerWidth <= 768) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('contentOverlay');

            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
    </script>
</body>
</html>