<?php include 'db.php'; include 'header.php';
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
?>
<h2>Add Student</h2>
<form method="POST" enctype="multipart/form-data">
  <input name="name" class="form-control mb-2" placeholder="Student Name" required>
  <input name="image" type="file" class="form-control mb-2" required>
  <button name="save" class="btn btn-success">Add</button>
</form>

<?php
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $img = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$img);
    $conn->query("INSERT INTO students(name, image) VALUES('$name', '$img')");
    echo "<p>Added successfully. <a href='dashboard.php'>Go back</a></p>";
}
include 'footer.php';
?>
