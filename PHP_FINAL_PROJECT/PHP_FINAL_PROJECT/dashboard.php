<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Get user information
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get statistics
$stmt = $pdo->prepare("SELECT COUNT(*) as total_users FROM users");
$stmt->execute();
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total_content FROM content");
$stmt->execute();
$total_content = $stmt->fetch(PDO::FETCH_ASSOC)['total_content'];

$page_title = "Dashboard";
include 'includes/header.php';
?>

<div class="container my-5">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="card-title mb-0">Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h2>
                            <p class="card-text mt-2">Manage your account and site content from this dashboard.</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <?php if ($user['profile_image']): ?>
                            <img src="uploads/<?php echo htmlspecialchars($user['profile_image']); ?>" 
                                 alt="Profile Image" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                            <?php else: ?>
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                <i data-feather="user" style="width: 40px; height: 40px; color: #6c757d;"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i data-feather="users" class="mb-2" style="width: 48px; height: 48px; color: #0d6efd;"></i>
                    <h3 class="card-title"><?php echo number_format($total_users); ?></h3>
                    <p class="card-text">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i data-feather="file-text" class="mb-2" style="width: 48px; height: 48px; color: #0d6efd;"></i>
                    <h3 class="card-title"><?php echo number_format($total_content); ?></h3>
                    <p class="card-text">Content Pages</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <a href="users.php" class="btn btn-outline-primary btn-lg">
                                    <i data-feather="users" style="width: 20px; height: 20px;"></i>
                                    Manage Users
                                </a>
                            </div>
                            <small class="text-muted d-block mt-1">View, edit, and delete user accounts</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <a href="about.php" class="btn btn-outline-success btn-lg">
                                    <i data-feather="edit" style="width: 20px; height: 20px;"></i>
                                    Edit Content
                                </a>
                            </div>
                            <small class="text-muted d-block mt-1">Update website content and pages</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Your Profile</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                            <p><strong>Member since:</strong> <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                        </div>
                        <div class="col-md-4">
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-primary">
                                <i data-feather="edit" style="width: 16px; height: 16px;"></i>
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
