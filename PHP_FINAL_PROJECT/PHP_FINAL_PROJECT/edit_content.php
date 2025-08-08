<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';

$page = $_GET['page'] ?? 'about';
$allowed_pages = ['home', 'about'];

if (!in_array($page, $allowed_pages)) {
    header('Location: dashboard.php');
    exit;
}

$success = '';
$error = '';

// Get existing content
$stmt = $pdo->prepare("SELECT * FROM content WHERE page = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$page]);
$content = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_POST) {
    $title = trim($_POST['title'] ?? '');
    $content_text = trim($_POST['content'] ?? '');

    if (empty($title)) {
        $error = 'Title is required.';
    } elseif (empty($content_text)) {
        $error = 'Content is required.';
    } else {
        if ($content) {
            // Update existing content
            $stmt = $pdo->prepare("UPDATE content SET title = ?, content = ?, updated_at = datetime('now') WHERE id = ?");
            $result = $stmt->execute([$title, $content_text, $content['id']]);
        } else {
            // Create new content
            $stmt = $pdo->prepare("INSERT INTO content (page, title, content, created_at, updated_at) VALUES (?, ?, ?, datetime('now'), datetime('now'))");
            $result = $stmt->execute([$page, $title, $content_text]);
        }

        if ($result) {
            $success = 'Content updated successfully!';
            // Refresh content data
            $stmt = $pdo->prepare("SELECT * FROM content WHERE page = ? ORDER BY created_at DESC LIMIT 1");
            $stmt->execute([$page]);
            $content = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = 'Failed to update content. Please try again.';
        }
    }
}

$page_title = "Edit " . ucfirst($page) . " Content";
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit <?php echo ucfirst($page); ?> Content</h2>
                <a href="<?php echo $page; ?>.php" class="btn btn-secondary">
                    <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                    Back to <?php echo ucfirst($page); ?>
                </a>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i data-feather="check-circle" style="width: 16px; height: 16px;"></i>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i data-feather="alert-circle" style="width: 16px; height: 16px;"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?php echo htmlspecialchars($content['title'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content *</label>
                            <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($content['content'] ?? ''); ?></textarea>
                            <div class="form-text">You can use line breaks for formatting. HTML tags will be displayed as text for security.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo $page; ?>.php" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save" style="width: 16px; height: 16px;"></i>
                                Save Content
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if ($content): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Content Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>Created:</strong> <?php echo date('F j, Y g:i A', strtotime($content['created_at'])); ?></p>
                    <p><strong>Last Updated:</strong> <?php echo date('F j, Y g:i A', strtotime($content['updated_at'])); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
