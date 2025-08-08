<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Get all users
$stmt = $pdo->prepare("SELECT id, first_name, last_name, email, profile_image, created_at FROM users ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "User Management";
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>User Management</h2>
                <a href="dashboard.php" class="btn btn-secondary">
                    <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                    Back to Dashboard
                </a>
            </div>

            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Registered Users (<?php echo count($users); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($users)): ?>
                    <div class="text-center py-5">
                        <i data-feather="users" style="width: 64px; height: 64px; color: #6c757d;"></i>
                        <h5 class="mt-3 text-muted">No users found</h5>
                        <p class="text-muted">No users have registered yet.</p>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <?php if ($user['profile_image']): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($user['profile_image']); ?>" 
                                             alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                        <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i data-feather="user" style="width: 20px; height: 20px; color: white;"></i>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong>
                                        <?php if ($user['id'] == $_SESSION['user_id']): ?>
                                        <span class="badge bg-primary ms-2">You</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i data-feather="edit" style="width: 14px; height: 14px;"></i>
                                                Edit
                                            </a>
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <a href="delete_user.php?id=<?php echo $user['id']; ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                                Delete
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
