<?php include 'db.php'; include 'header.php'; ?>
<h2>Login</h2>
<form method="POST">
  <input name="username" class="form-control mb-2" placeholder="Username" required>
  <input name="password" type="password" class="form-control mb-2" placeholder="Password" required>
  <button name="login" class="btn btn-primary">Login</button>
</form>

<?php
if (isset($_POST['login'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];
    $res = $conn->query("SELECT * FROM users WHERE username='$u'");
    if ($res->num_rows == 1) {
        $row = $res->fetch_assoc();
        if (password_verify($p, $row['password'])) {
            $_SESSION['user'] = $u;
            header("Location: dashboard.php");
        } else echo "<p>Wrong password.</p>";
    } else echo "<p>User not found.</p>";
}
include 'footer.php';
?>
