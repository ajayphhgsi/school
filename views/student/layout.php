<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Student Portal'; ?> - School Management System</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            /* Modern Theme */
            --sidebar-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-hover: rgba(255,255,255,0.1);
            --sidebar-active: rgba(255,255,255,0.2);
            --header-bg: white;
            --card-shadow: 0 2px 4px rgba(0,0,0,.1);
            --border: 1px solid #e9ecef;
        }

        /* Classic Theme */
        [data-theme="classic"] {
            --sidebar-bg: #2c3e50;
            --sidebar-hover: rgba(255,255,255,0.1);
            --sidebar-active: rgba(255,255,255,0.2);
            --header-bg: #f8f9fa;
            --card-shadow: 0 1px 3px rgba(0,0,0,.1);
            --border: 1px solid #dee2e6;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: var(--sidebar-bg);
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
            background: var(--sidebar-hover);
        }
        .sidebar .nav-link.active {
            color: white;
            background: var(--sidebar-active);
        }
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }
        .header {
            background: var(--header-bg);
            box-shadow: var(--card-shadow);
            padding: 1rem;
            border-bottom: var(--border);
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
                <i class="fas fa-graduation-cap"></i><br>
                Student Portal
            </h5>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'dashboard' ? 'active' : ''; ?>" href="/student/dashboard">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'profile' ? 'active' : ''; ?>" href="/student/profile">
                    <i class="fas fa-user"></i> My Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'attendance' ? 'active' : ''; ?>" href="/student/attendance">
                    <i class="fas fa-calendar-check"></i> Attendance
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'results' ? 'active' : ''; ?>" href="/student/results">
                    <i class="fas fa-chart-line"></i> Exam Results
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'fees' ? 'active' : ''; ?>" href="/student/fees">
                    <i class="fas fa-money-bill-wave"></i> Fees
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'events' ? 'active' : ''; ?>" href="/student/events">
                    <i class="fas fa-calendar"></i> Events
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_page === 'resources' ? 'active' : ''; ?>" href="/student/resources">
                    <i class="fas fa-book"></i> Resources
                </a>
            </li>
        </ul>

        <div class="mt-auto">
            <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['user']['first_name'] ?? 'Student'; ?>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/student/change-password"><i class="fas fa-key"></i> Change Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <button class="btn btn-outline-secondary d-md-none" type="button" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 class="mb-0 d-inline ms-2"><?php echo $page_title ?? 'Dashboard'; ?></h4>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary me-2" id="themeToggle" title="Toggle Theme">
                        <i class="fas fa-palette"></i>
                    </button>
                    <span class="text-muted">
                        Welcome, <?php echo $_SESSION['user']['first_name'] ?? 'Student'; ?> |
                        <?php echo date('M d, Y'); ?>
                    </span>
                </div>
            </div>

            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="/student/dashboard"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <?php if ($active_page !== 'dashboard'): ?>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php
                            $page_names = [
                                'profile' => 'My Profile',
                                'attendance' => 'Attendance',
                                'results' => 'Exam Results',
                                'fees' => 'Fees',
                                'events' => 'Events',
                                'change-password' => 'Change Password'
                            ];
                            echo $page_names[$active_page] ?? ucfirst(str_replace('-', ' ', $active_page));
                            ?>
                        </li>
                    <?php endif; ?>
                </ol>
            </nav>
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
</html></content>
</xai:function_call"> 

<xai:function_call name="apply_diff">
<parameter name="path">views/student/dashboard.php