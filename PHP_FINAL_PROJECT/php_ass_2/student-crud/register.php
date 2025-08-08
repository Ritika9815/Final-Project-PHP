<?php include 'db.php'; include 'header.php'; ?>
<h2>Register</h2>
<form method="POST">
  <input name="username" class="form-control mb-2" placeholder="Username" required>
  <input name="password" type="password" class="form-control mb-2" placeholder="Password" required>
  <button name="register" class="btn btn-success">Register</button>
</form>

<?php
if (isset($_POST['register'])) {
    $u = $_POST['username'];
    $p = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users(username, password) VALUES('$u', '$p')");
    echo "<p>Registered successfully. <a href='login.php'>Login here</a></p>";
}
include 'footer.php';
?>
