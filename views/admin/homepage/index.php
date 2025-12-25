<?php
$active_page = 'homepage';
$page_title = 'Homepage Management';
ob_start();
?>

<style>
.content-card {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.content-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.section-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.carousel-preview {
    max-height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.content-preview {
    max-height: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><i class="fas fa-home text-primary me-2"></i>Homepage Management</h4>
        <p class="text-muted mb-0">Manage homepage content and sections</p>
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

<!-- Homepage Sections -->
<div class="row">
    <!-- Carousel Management -->
    <div class="col-lg-6 mb-4">
        <div class="card content-card h-100">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="section-icon bg-white text-primary me-3">
                        <i class="fas fa-images"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Image Carousel</h5>
                        <small>Manage homepage slider images</small>
                    </div>
                </div>
                <a href="/admin/homepage/carousel" class="btn btn-light btn-sm">
                    <i class="fas fa-edit me-1"></i>Manage
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (!empty($carousel)): ?>
                        <?php foreach (array_slice($carousel, 0, 3) as $slide): ?>
                            <div class="col-md-4 mb-3">
                                <img src="/uploads/<?php echo $slide['image_path']; ?>" alt="<?php echo $slide['title']; ?>" class="img-fluid carousel-preview">
                                <div class="mt-2">
                                    <h6 class="mb-1"><?php echo $slide['title']; ?></h6>
                                    <p class="text-muted small mb-0 content-preview"><?php echo $slide['content']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-4">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No carousel images added yet</p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($carousel) && count($carousel) > 3): ?>
                    <div class="text-center mt-3">
                        <small class="text-muted">And <?php echo count($carousel) - 3; ?> more images...</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div class="col-lg-6 mb-4">
        <div class="card content-card h-100">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="section-icon bg-white text-success me-3">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">About Section</h5>
                        <small>School information and description</small>
                    </div>
                </div>
                <a href="/admin/homepage/about" class="btn btn-light btn-sm">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
            </div>
            <div class="card-body">
                <?php if ($about): ?>
                    <div class="row">
                        <div class="col-md-4">
                            <?php if ($about['image_path']): ?>
                                <img src="/uploads/<?php echo $about['image_path']; ?>" alt="About" class="img-fluid rounded">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                                    <i class="fas fa-image fa-2x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <h6><?php echo $about['title']; ?></h6>
                            <p class="text-muted small content-preview"><?php echo $about['content']; ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                        <p class="text-muted">About section not configured</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Courses Section -->
    <div class="col-lg-6 mb-4">
        <div class="card content-card h-100">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="section-icon bg-white text-info me-3">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Courses</h5>
                        <small>Academic programs and courses</small>
                    </div>
                </div>
                <a href="/admin/homepage/courses" class="btn btn-light btn-sm">
                    <i class="fas fa-edit me-1"></i>Manage
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (!empty($courses)): ?>
                        <?php foreach (array_slice($courses, 0, 2) as $course): ?>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <?php if ($course['image_path']): ?>
                                        <img src="/uploads/<?php echo $course['image_path']; ?>" alt="<?php echo $course['title']; ?>" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <?php endif; ?>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?php echo $course['title']; ?></h6>
                                        <p class="text-muted small mb-0 content-preview"><?php echo $course['content']; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-3">
                            <i class="fas fa-graduation-cap fa-2x text-muted mb-2"></i>
                            <p class="text-muted small">No courses added yet</p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($courses) && count($courses) > 2): ?>
                    <div class="text-center mt-2">
                        <small class="text-muted">And <?php echo count($courses) - 2; ?> more courses...</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Events Section -->
    <div class="col-lg-6 mb-4">
        <div class="card content-card h-100">
            <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="section-icon bg-white text-warning me-3">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Events</h5>
                        <small>School events and announcements</small>
                    </div>
                </div>
                <a href="/admin/events" class="btn btn-light btn-sm">
                    <i class="fas fa-edit me-1"></i>Manage
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (!empty($events)): ?>
                        <?php foreach (array_slice($events, 0, 2) as $event): ?>
                            <div class="col-md-6 mb-3">
                                <div class="border-start border-primary border-4 ps-3">
                                    <h6 class="mb-1"><?php echo $event['title']; ?></h6>
                                    <p class="text-muted small mb-1"><?php echo date('M d, Y', strtotime($event['event_date'])); ?> at <?php echo $event['event_time']; ?></p>
                                    <p class="text-muted small mb-0 content-preview"><?php echo $event['description']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-3">
                            <i class="fas fa-calendar-alt fa-2x text-muted mb-2"></i>
                            <p class="text-muted small">No events scheduled</p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($events) && count($events) > 2): ?>
                    <div class="text-center mt-2">
                        <small class="text-muted">And <?php echo count($events) - 2; ?> more events...</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Gallery Section -->
    <div class="col-lg-6 mb-4">
        <div class="card content-card h-100">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="section-icon bg-white text-secondary me-3">
                        <i class="fas fa-images"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Gallery</h5>
                        <small>Photo gallery and media</small>
                    </div>
                </div>
                <a href="/admin/gallery" class="btn btn-light btn-sm">
                    <i class="fas fa-edit me-1"></i>Manage
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (!empty($gallery)): ?>
                        <?php foreach (array_slice($gallery, 0, 4) as $item): ?>
                            <div class="col-3 mb-2">
                                <?php if ($item['image_path']): ?>
                                    <img src="/uploads/<?php echo $item['image_path']; ?>" alt="<?php echo $item['title']; ?>" class="img-fluid rounded" style="height: 60px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 60px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-3">
                            <i class="fas fa-images fa-2x text-muted mb-2"></i>
                            <p class="text-muted small">No gallery items</p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($gallery) && count($gallery) > 4): ?>
                    <div class="text-center mt-2">
                        <small class="text-muted">And <?php echo count($gallery) - 4; ?> more photos...</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Testimonials & Contact -->
    <div class="col-lg-6 mb-4">
        <div class="card content-card h-100">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="section-icon bg-white text-dark me-3">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Testimonials & Contact</h5>
                        <small>Student testimonials and contact info</small>
                    </div>
                </div>
                <div>
                    <a href="/admin/homepage/testimonials" class="btn btn-light btn-sm me-1">
                        <i class="fas fa-comments me-1"></i>Testimonials
                    </a>
                    <a href="/admin/homepage/contact" class="btn btn-light btn-sm">
                        <i class="fas fa-address-book me-1"></i>Contact
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Testimonials</h6>
                        <?php if (!empty($testimonials)): ?>
                            <div class="mb-2">
                                <small class="text-muted"><?php echo count($testimonials); ?> testimonials added</small>
                            </div>
                            <div class="text-muted small content-preview">
                                "<?php echo substr($testimonials[0]['content'], 0, 80); ?>..."
                            </div>
                        <?php else: ?>
                            <div class="text-muted small">No testimonials added</div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h6>Contact Information</h6>
                        <?php if ($contact): ?>
                            <div class="text-muted small">
                                <div><i class="fas fa-map-marker-alt me-1"></i><?php echo substr($contact['address'] ?? 'Not set', 0, 30); ?>...</div>
                                <div><i class="fas fa-phone me-1"></i><?php echo $contact['phone'] ?? 'Not set'; ?></div>
                                <div><i class="fas fa-envelope me-1"></i><?php echo $contact['email'] ?? 'Not set'; ?></div>
                            </div>
                        <?php else: ?>
                            <div class="text-muted small">Contact info not configured</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2 col-sm-4">
                        <a href="/admin/homepage/carousel" class="btn btn-outline-primary w-100">
                            <i class="fas fa-images d-block mb-2 fa-2x"></i>
                            <small>Manage Carousel</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <a href="/admin/homepage/about" class="btn btn-outline-success w-100">
                            <i class="fas fa-info-circle d-block mb-2 fa-2x"></i>
                            <small>Edit About</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <a href="/admin/homepage/courses" class="btn btn-outline-info w-100">
                            <i class="fas fa-graduation-cap d-block mb-2 fa-2x"></i>
                            <small>Manage Courses</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <a href="/admin/events" class="btn btn-outline-warning w-100">
                            <i class="fas fa-calendar-alt d-block mb-2 fa-2x"></i>
                            <small>Manage Events</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <a href="/admin/gallery" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-images d-block mb-2 fa-2x"></i>
                            <small>Manage Gallery</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <a href="/admin/homepage/testimonials" class="btn btn-outline-dark w-100">
                            <i class="fas fa-comments d-block mb-2 fa-2x"></i>
                            <small>Testimonials</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layout.php';
?>