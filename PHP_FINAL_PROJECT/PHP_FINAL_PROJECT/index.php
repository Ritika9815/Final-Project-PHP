<?php
session_start();
require_once 'includes/config.php';

// Fetch content for homepage
$stmt = $pdo->prepare("SELECT * FROM content WHERE page = 'home' ORDER BY created_at DESC LIMIT 1");
$stmt->execute();
$content = $stmt->fetch(PDO::FETCH_ASSOC);

$page_title = "Home";
include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Welcome to Our Platform</h1>
                <p class="lead mb-4">
                    <?php echo $content ? htmlspecialchars($content['content']) : 'A modern web platform built with PHP, featuring user management and content creation capabilities.'; ?>
                </p>
                <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="d-flex gap-3">
                    <a href="register.php" class="btn btn-light btn-lg">Get Started</a>
                    <a href="about.php" class="btn btn-outline-light btn-lg">Learn More</a>
                </div>
                <?php else: ?>
                <a href="dashboard.php" class="btn btn-light btn-lg">Go to Dashboard</a>
                <?php endif; ?>
            </div>
            <div class="col-lg-4 text-center">
                <!-- Using an SVG icon instead of image -->
                <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="100" r="80" stroke="white" stroke-width="4" fill="none"/>
                    <path d="M70 90L90 110L130 70" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="100" cy="100" r="15" fill="white"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container my-5">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i data-feather="users" class="mb-3" style="width: 48px; height: 48px; color: #0d6efd;"></i>
                    <h5 class="card-title">User Management</h5>
                    <p class="card-text">Complete user registration, authentication, and profile management system.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i data-feather="edit" class="mb-3" style="width: 48px; height: 48px; color: #0d6efd;"></i>
                    <h5 class="card-title">Content Management</h5>
                    <p class="card-text">Create, read, update, and delete content with full CRUD functionality.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i data-feather="shield" class="mb-3" style="width: 48px; height: 48px; color: #0d6efd;"></i>
                    <h5 class="card-title">Secure Platform</h5>
                    <p class="card-text">Built with security best practices including password hashing and input validation.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Section -->
<?php
// Get user count
$stmt = $pdo->prepare("SELECT COUNT(*) as user_count FROM users");
$stmt->execute();
$user_count = $stmt->fetch(PDO::FETCH_ASSOC)['user_count'];

// Get content count
$stmt = $pdo->prepare("SELECT COUNT(*) as content_count FROM content");
$stmt->execute();
$content_count = $stmt->fetch(PDO::FETCH_ASSOC)['content_count'];
?>

<div class="bg-light py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-6">
                <h3 class="display-6 fw-bold text-primary"><?php echo number_format($user_count); ?></h3>
                <p class="lead">Registered Users</p>
            </div>
            <div class="col-md-6">
                <h3 class="display-6 fw-bold text-primary"><?php echo number_format($content_count); ?></h3>
                <p class="lead">Content Pages</p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
