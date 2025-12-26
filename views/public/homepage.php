<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Modern Theme */
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --text-color: #2b2d42;
            --bg-color: #f8f9fa;
            --card-bg: #ffffff;
            --shadow: 0 10px 30px rgba(0,0,0,0.08);
            --border-radius: 20px;
            --gradient: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            --hero-bg: var(--gradient);
            --card-shadow: var(--shadow);
            --btn-bg: var(--primary-color);
            --btn-hover: var(--secondary-color);
            --transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* Classic Theme */
        [data-theme="classic"] {
            --primary-color: #003366; /* Navy Blue */
            --secondary-color: #FFD700; /* Gold */
            --accent-color: #8B4513; /* Brown */
            --text-color: #2c3e50;
            --bg-color: #f8f9fa;
            --card-bg: #ffffff;
            --shadow: 0 2px 8px rgba(0,0,0,0.15);
            --border-radius: 8px;
            --gradient: var(--primary-color);
            --hero-bg: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            --card-shadow: var(--shadow);
            --btn-bg: var(--primary-color);
            --btn-hover: var(--secondary-color);
            --transition: all 0.3s ease;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Roboto', sans-serif;
            transition: var(--transition);
            line-height: 1.8;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
            color: var(--text-color);
        }

        [data-theme="classic"] h1,
        [data-theme="classic"] h2,
        [data-theme="classic"] h3,
        [data-theme="classic"] h4,
        [data-theme="classic"] h5,
        [data-theme="classic"] h6 {
            font-family: 'Playfair Display', serif;
        }

        .section-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .section-subtitle {
            color: var(--text-color);
            opacity: 0.8;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .hero-section {
            background: var(--hero-bg);
            color: white;
            padding: 150px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .carousel-item {
            height: 600px;
        }

        .carousel-image {
            object-fit: cover;
            height: 100%;
            width: 100%;
            filter: brightness(0.8);
        }

        .carousel-caption {
            bottom: 30%;
            left: 10%;
            right: 10%;
            text-align: left;
        }

        .carousel-caption h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
        }

        .carousel-caption p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
        }

        .btn-light {
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-light:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .card {
            border: none;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            background: var(--card-bg);
            border-radius: var(--border-radius);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: var(--transition);
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }

        .card-body {
            padding: 2rem;
        }

        .btn-primary {
            background: var(--btn-bg);
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: var(--btn-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .btn-lg {
            padding: 15px 40px;
            font-size: 1.1rem;
        }

        .testimonial-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 30px;
            margin: 15px;
            box-shadow: var(--card-shadow);
            border-left: 5px solid var(--primary-color);
            transition: var(--transition);
        }

        .testimonial-card:hover {
            transform: translateX(5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .testimonial-card p {
            font-style: italic;
            font-size: 1.1rem;
            color: var(--text-color);
            margin-bottom: 1.5rem;
        }

        .testimonial-card h6 {
            color: var(--primary-color);
            font-weight: 600;
        }

        .gallery-card {
            overflow: hidden;
            border-radius: var(--border-radius);
        }

        .social-icons a {
            font-size: 1.2rem;
            transition: var(--transition);
        }

        .social-icons a:hover {
            color: var(--primary-color) !important;
            transform: translateY(-3px);
        }

        .text-decoration-none:hover {
            color: var(--primary-color) !important;
        }

        .gallery-img {
            height: 180px;
            object-fit: cover;
            transition: var(--transition);
        }

        .gallery-card:hover .gallery-img {
            transform: scale(1.1);
        }

        .event-meta {
            background: rgba(233, 236, 239, 0.5);
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
        }

        .badge {
            font-size: 0.85rem;
            padding: 5px 12px;
        }

        .shadow-lg {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        }

        .rounded-4 {
            border-radius: 20px !important;
        }

        .navbar {
            background: var(--card-bg) !important;
            box-shadow: var(--card-shadow);
            padding: 15px 0;
            transition: var(--transition);
        }

        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--text-color) !important;
            font-weight: 500;
            margin: 0 10px;
            position: relative;
            transition: var(--transition);
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: var(--transition);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .bg-light {
            background: var(--bg-color) !important;
        }

        .bg-primary {
            background: var(--primary-color) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .theme-toggle {
            background: var(--primary-color);
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.6rem;
            border-radius: 50%;
            transition: var(--transition);
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .theme-toggle:hover {
            background: var(--secondary-color);
            transform: rotate(180deg);
        }

        [data-theme="classic"] .theme-toggle {
            background: var(--primary-color);
            color: white;
        }

        [data-theme="classic"] .theme-toggle:hover {
            background: var(--secondary-color);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-graduation-cap text-primary me-2"></i>
                <span class="text-primary">School</span><span class="text-dark">Management</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/courses">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="/events">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="/gallery">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-primary text-white px-3 py-1 rounded-pill" href="/login">Login</a></li>
                    <li class="nav-item ms-3">
                        <button class="theme-toggle" id="themeToggle" title="Toggle Theme">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Carousel -->
    <section class="hero-section">
        <div class="hero-content">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php if (!empty($carousel)): ?>
                        <?php foreach ($carousel as $index => $slide): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <img src="/uploads/<?php echo $slide['image_path']; ?>" class="d-block w-100 carousel-image" alt="<?php echo $slide['title']; ?>">
                                <div class="carousel-caption d-none d-md-block">
                                    <h1 class="display-4 fw-bold mb-4"><?php echo $slide['title']; ?></h1>
                                    <p class="lead mb-4"><?php echo $slide['content']; ?></p>
                                    <?php if ($slide['link']): ?>
                                        <a href="<?php echo $slide['link']; ?>" class="btn btn-light btn-lg">Learn More</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="carousel-item active">
                            <div class="d-flex align-items-center justify-content-center h-100 bg-primary">
                                <div class="text-center">
                                    <h1 class="display-4 fw-bold mb-4">Welcome to Our School</h1>
                                    <p class="lead mb-4">Excellence in Education</p>
                                    <a href="/login" class="btn btn-light btn-lg">Get Started</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <?php if ($about): ?>
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <img src="/uploads/<?php echo $about['image_path']; ?>" class="img-fluid rounded-4 shadow-lg mb-4 mb-lg-0" alt="About Us">
                </div>
                <div class="col-lg-6">
                    <h2 class="section-title"><?php echo $about['title']; ?></h2>
                    <p class="section-subtitle">Learn more about our institution</p>
                    <p class="lead mb-4"><?php echo $about['content']; ?></p>
                    <a href="/about" class="btn btn-primary btn-lg">Read More</a>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Courses Section -->
    <?php if (!empty($courses)): ?>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Our Courses</h2>
                <p class="section-subtitle">Discover our comprehensive educational programs</p>
            </div>
            <div class="row g-4">
                <?php foreach ($courses as $course): ?>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <?php if ($course['image_path']): ?>
                                <img src="/uploads/<?php echo $course['image_path']; ?>" class="card-img-top" alt="<?php echo $course['title']; ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3"><?php echo $course['title']; ?></h5>
                                <p class="card-text text-muted"><?php echo substr($course['content'], 0, 100) . '...'; ?></p>
                                <a href="/courses" class="btn btn-primary stretched-link">Learn More</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Events Section -->
    <?php if (!empty($events)): ?>
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Upcoming Events</h2>
                <p class="section-subtitle">Stay updated with our latest activities and events</p>
            </div>
            <div class="row g-4">
                <?php foreach ($events as $event): ?>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title fw-bold mb-0"><?php echo $event['title']; ?></h5>
                                    <span class="badge bg-primary">Upcoming</span>
                                </div>
                                <p class="card-text text-muted mb-4"><?php echo substr($event['description'], 0, 100) . '...'; ?></p>
                                <div class="event-meta mb-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        <span><?php echo date('M d, Y', strtotime($event['event_date'])); ?></span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <span><?php echo $event['event_time']; ?></span>
                                    </div>
                                </div>
                                <a href="/events" class="btn btn-primary stretched-link">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Gallery Section -->
    <?php if (!empty($gallery)): ?>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Photo Gallery</h2>
                <p class="section-subtitle">Capturing moments of learning and achievement</p>
            </div>
            <div class="row g-3">
                <?php foreach ($gallery as $image): ?>
                    <div class="col-md-3">
                        <div class="card gallery-card">
                            <img src="/uploads/<?php echo $image['image_path']; ?>" class="card-img-top gallery-img" alt="<?php echo $image['title']; ?>">
                            <div class="card-body">
                                <h6 class="card-title fw-bold mb-0"><?php echo $image['title']; ?></h6>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="/gallery" class="btn btn-primary btn-lg">View All Photos</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Testimonials Section -->
    <?php if (!empty($testimonials)): ?>
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">What Our Students Say</h2>
            </div>
            <div class="row g-4">
                <?php foreach ($testimonials as $testimonial): ?>
                    <div class="col-md-6">
                        <div class="testimonial-card">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-quote-left text-primary fa-2x me-3"></i>
                                <p class="mb-0">"<?php echo $testimonial['content']; ?>"</p>
                            </div>
                            <h6 class="mb-0">- <?php echo $testimonial['title']; ?></h6>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- CTA Section -->
    <section class="py-5" style="background: var(--gradient); color: white;">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-3">Ready to Join Our Community?</h2>
            <p class="lead mb-4">Contact us today to learn more about our programs and admission process.</p>
            <a href="/contact" class="btn btn-light btn-lg px-4 py-2">Contact Us</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-primary mb-3">School Management System</h5>
                    <p class="text-muted">Providing quality education for over 50 years.</p>
                    <div class="social-icons">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="/about" class="text-muted text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="/courses" class="text-muted text-decoration-none">Courses</a></li>
                        <li class="mb-2"><a href="/events" class="text-muted text-decoration-none">Events</a></li>
                        <li class="mb-2"><a href="/contact" class="text-muted text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Contact Info</h5>
                    <p class="text-muted mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i> 123 School Street, City, State</p>
                    <p class="text-muted mb-2"><i class="fas fa-phone text-primary me-2"></i> +1-234-567-8900</p>
                    <p class="text-muted mb-0"><i class="fas fa-envelope text-primary me-2"></i> info@school.com</p>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center py-3">
                <p class="text-muted mb-0">&copy; 2024 School Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme switching functionality
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'modern';
        html.setAttribute('data-theme', savedTheme);

        // Update toggle icon based on theme
        function updateToggleIcon() {
            const icon = themeToggle.querySelector('i');
            if (html.getAttribute('data-theme') === 'classic') {
                icon.className = 'fas fa-sun';
            } else {
                icon.className = 'fas fa-moon';
            }
        }

        updateToggleIcon();

        // Toggle theme on button click
        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'classic' ? 'modern' : 'classic';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateToggleIcon();
        });
    </script>
</body>
</html>