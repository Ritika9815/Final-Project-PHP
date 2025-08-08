<?php
session_start();
require_once 'includes/config.php';

// Fetch content for about page
$stmt = $pdo->prepare("SELECT * FROM content WHERE page = 'about' ORDER BY created_at DESC LIMIT 1");
$stmt->execute();
$content = $stmt->fetch(PDO::FETCH_ASSOC);

$page_title = "About";
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-5 fw-bold mb-4">About Our Platform</h1>
            
            <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Edit button for logged-in users -->
            <div class="mb-3">
                <a href="edit_content.php?page=about" class="btn btn-primary">
                    <i data-feather="edit" style="width: 16px; height: 16px;"></i>
                    Edit Content
                </a>
            </div>
            <?php endif; ?>

            <div class="content-area">
                <?php if ($content): ?>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($content['title']); ?></h5>
                            <div class="card-text">
                                <?php echo nl2br(htmlspecialchars($content['content'])); ?>
                            </div>
                            <small class="text-muted">
                                Last updated: <?php echo date('F j, Y', strtotime($content['updated_at'])); ?>
                            </small>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Default content when no content is found -->
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Welcome to Our Platform</h5>
                            <div class="card-text">
                                <p>This is a comprehensive web application built with PHP that demonstrates modern web development practices including:</p>
                                
                                <h6 class="mt-4">Key Features:</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i data-feather="check-circle" style="width: 16px; height: 16px; color: #28a745;"></i> User Registration and Authentication</li>
                                    <li class="mb-2"><i data-feather="check-circle" style="width: 16px; height: 16px; color: #28a745;"></i> Secure Password Hashing</li>
                                    <li class="mb-2"><i data-feather="check-circle" style="width: 16px; height: 16px; color: #28a745;"></i> Profile Image Upload</li>
                                    <li class="mb-2"><i data-feather="check-circle" style="width: 16px; height: 16px; color: #28a745;"></i> Complete CRUD Operations</li>
                                    <li class="mb-2"><i data-feather="check-circle" style="width: 16px; height: 16px; color: #28a745;"></i> Content Management System</li>
                                    <li class="mb-2"><i data-feather="check-circle" style="width: 16px; height: 16px; color: #28a745;"></i> Responsive Design with Bootstrap</li>
                                </ul>

                                <h6 class="mt-4">Technology Stack:</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><strong>Backend:</strong> PHP 7.4+</li>
                                    <li class="mb-2"><strong>Database:</strong> MySQL</li>
                                    <li class="mb-2"><strong>Frontend:</strong> HTML5, Bootstrap 5, JavaScript</li>
                                    <li class="mb-2"><strong>Icons:</strong> Feather Icons</li>
                                    <li class="mb-2"><strong>Security:</strong> Prepared statements, Password hashing</li>
                                </ul>

                                <p class="mt-4">This application serves as a foundation for building more complex web platforms such as blogs, social media sites, or content management systems.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Call to Action -->
            <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="text-center mt-5">
                <h4>Ready to get started?</h4>
                <p class="lead">Join our platform today and explore all the features we have to offer.</p>
                <a href="register.php" class="btn btn-primary btn-lg">Create Account</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
