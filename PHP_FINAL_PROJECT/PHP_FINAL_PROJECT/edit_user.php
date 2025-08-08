<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';

$user_id = $_GET['id'] ?? 0;

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: users.php');
    exit;
}

$errors = [];
$success = '';

if ($_POST) {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation
    if (empty($first_name)) {
        $errors[] = 'First name is required.';
    }
    
    if (empty($last_name)) {
        $errors[] = 'Last name is required.';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    // Check if email already exists (excluding current user)
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            $errors[] = 'Email address is already registered.';
        }
    }

    // Handle image upload
    $profile_image = $user['profile_image']; // Keep existing image by default
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($_FILES['profile_image']['type'], $allowed_types)) {
            $errors[] = 'Invalid image type. Only JPEG, PNG, and GIF are allowed.';
        } elseif ($_FILES['profile_image']['size'] > $max_size) {
            $errors[] = 'Image size must be less than 2MB.';
        } else {
            $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $new_image = uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_image;
            
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                // Delete old image if exists
                if ($profile_image && file_exists($upload_dir . $profile_image)) {
                    unlink($upload_dir . $profile_image);
                }
                $profile_image = $new_image;
            } else {
                $errors[] = 'Failed to upload image.';
            }
        }
    }

    // Update user if no errors
    if (empty($errors)) {
        if ($password) {
            // Update with new password
            if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters long.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ?, profile_image = ? WHERE id = ?");
                $result = $stmt->execute([$first_name, $last_name, $email, $hashed_password, $profile_image, $user_id]);
            }
        } else {
            // Update without changing password
            $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, profile_image = ? WHERE id = ?");
            $result = $stmt->execute([$first_name, $last_name, $email, $profile_image, $user_id]);
        }

        if (empty($errors) && $result) {
            $success = 'User updated successfully!';
            
            // Update session if editing own profile
            if ($user_id == $_SESSION['user_id']) {
                $_SESSION['user_name'] = $first_name . ' ' . $last_name;
                $_SESSION['user_email'] = $email;
            }

            // Refresh user data
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $errors[] = 'Failed to update user. Please try again.';
        }
    }
}

$page_title = "Edit User";
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit User: <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                <a href="users.php" class="btn btn-secondary">
                    <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                    Back to Users
                </a>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i data-feather="check-circle" style="width: 16px; height: 16px;"></i>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Current Profile Image -->
                    <div class="text-center mb-4">
                        <?php if ($user['profile_image']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($user['profile_image']); ?>" 
                             alt="Current Profile" class="rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                        <p class="small text-muted">Current Profile Image</p>
                        <?php else: ?>
                        <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                             style="width: 100px; height: 100px;">
                            <i data-feather="user" style="width: 50px; height: 50px; color: white;"></i>
                        </div>
                        <p class="small text-muted">No Profile Image</p>
                        <?php endif; ?>
                    </div>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text">Leave blank to keep current password. Minimum 6 characters if changing.</div>
                        </div>

                        <div class="mb-3">
                            <label for="profile_image" class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                            <div class="form-text">Max size: 2MB. Formats: JPEG, PNG, GIF. Leave blank to keep current image.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="users.php" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save" style="width: 16px; height: 16px;"></i>
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- User Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">User Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>User ID:</strong> <?php echo $user['id']; ?></p>
                    <p><strong>Registered:</strong> <?php echo date('F j, Y g:i A', strtotime($user['created_at'])); ?></p>
                    <?php if ($user_id == $_SESSION['user_id']): ?>
                    <span class="badge bg-primary">This is your account</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
