<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 100px 0;
        }
        .carousel-item {
            height: 500px;
        }
        .carousel-image {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }
        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .btn-primary {
            background: var(--primary-color);
            border: none;
        }
        .btn-primary:hover {
            background: var(--secondary-color);
        }
        .testimonial-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin: 15px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-school"></i> School Management
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/courses">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="/events">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="/gallery">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Carousel -->
    <section class="hero-section">
        <div class="container">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php if (!empty($carousel)): ?>
                        <?php foreach ($carousel as $index => $slide): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <img src="/uploads/<?php echo $slide['image_path']; ?>" class="d-block w-100 carousel-image" alt="<?php echo $slide['title']; ?>">
                                <div class="carousel-caption d-none d-md-block">
                                    <h1><?php echo $slide['title']; ?></h1>
                                    <p><?php echo $slide['content']; ?></p>
                                    <?php if ($slide['link']): ?>
                                        <a href="<?php echo $slide['link']; ?>" class="btn btn-light">Learn More</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="carousel-item active">
                            <div class="d-flex align-items-center justify-content-center h-100 bg-primary">
                                <div class="text-center">
                                    <h1>Welcome to Our School</h1>
                                    <p>Excellence in Education</p>
                                    <a href="/login" class="btn btn-light">Get Started</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <?php if ($about): ?>
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2><?php echo $about['title']; ?></h2>
                    <p><?php echo $about['content']; ?></p>
                    <a href="/about" class="btn btn-primary">Read More</a>
                </div>
                <div class="col-lg-6">
                    <img src="/uploads/<?php echo $about['image_path']; ?>" class="img-fluid rounded" alt="About Us">
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
                <h2>Our Courses</h2>
                <p>Discover our comprehensive educational programs</p>
            </div>
            <div class="row">
                <?php foreach ($courses as $course): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <?php if ($course['image_path']): ?>
                                <img src="/uploads/<?php echo $course['image_path']; ?>" class="card-img-top" alt="<?php echo $course['title']; ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $course['title']; ?></h5>
                                <p class="card-text"><?php echo substr($course['content'], 0, 100) . '...'; ?></p>
                                <a href="/courses" class="btn btn-primary">Learn More</a>
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
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Upcoming Events</h2>
                <p>Stay updated with our latest activities and events</p>
            </div>
            <div class="row">
                <?php foreach ($events as $event): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $event['title']; ?></h5>
                                <p class="card-text"><?php echo substr($event['description'], 0, 100) . '...'; ?></p>
                                <p class="text-muted">
                                    <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($event['event_date'])); ?><br>
                                    <i class="fas fa-clock"></i> <?php echo $event['event_time']; ?>
                                </p>
                                <a href="/events" class="btn btn-primary">View Details</a>
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
                <h2>Photo Gallery</h2>
                <p>Capturing moments of learning and achievement</p>
            </div>
            <div class="row">
                <?php foreach ($gallery as $image): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <img src="/uploads/<?php echo $image['image_path']; ?>" class="card-img-top" alt="<?php echo $image['title']; ?>">
                            <div class="card-body">
                                <h6 class="card-title"><?php echo $image['title']; ?></h6>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center">
                <a href="/gallery" class="btn btn-primary">View All Photos</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Testimonials Section -->
    <?php if (!empty($testimonials)): ?>
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2>What Our Students Say</h2>
            </div>
            <div class="row">
                <?php foreach ($testimonials as $testimonial): ?>
                    <div class="col-md-6">
                        <div class="testimonial-card">
                            <p>"<?php echo $testimonial['content']; ?>"</p>
                            <h6>- <?php echo $testimonial['title']; ?></h6>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2>Ready to Join Our Community?</h2>
            <p>Contact us today to learn more about our programs and admission process.</p>
            <a href="/contact" class="btn btn-light btn-lg">Contact Us</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>School Management System</h5>
                    <p>Providing quality education for over 50 years.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/about" class="text-white">About Us</a></li>
                        <li><a href="/courses" class="text-white">Courses</a></li>
                        <li><a href="/events" class="text-white">Events</a></li>
                        <li><a href="/contact" class="text-white">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Info</h5>
                    <p><i class="fas fa-map-marker-alt"></i> 123 School Street, City, State</p>
                    <p><i class="fas fa-phone"></i> +1-234-567-8900</p>
                    <p><i class="fas fa-envelope"></i> info@school.com</p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2024 School Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>