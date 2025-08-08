<?php include 'db.php'; include 'header.php';
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
$id = $_GET['id'];
$row = $conn->query("SELECT * FROM students WHERE id=$id")->fetch_assoc();
?>
<h2>Edit Student</h2>
<form method="POST" enctype="multipart/form-data">
  <input name="name" value="<?= $row['name'] ?>" class="form-control mb-2" required>
  <input name="image" type="file" class="form-control mb-2">
  <button name="update" class="btn btn-primary">Update</button>
</form>

<?php
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $img = $row['image'];
    if ($_FILES['image']['name']) {
        $img = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$img);
    }
    $conn->query("UPDATE students SET name='$name', image='$img' WHERE id=$id");
    echo "<p>Updated successfully. <a href='dashboard.php'>Go back</a></p>";
}
include 'footer.php';
?>
