<?php include 'db.php'; include 'header.php';
$res = $conn->query("SELECT * FROM students");
?>
<h2>Student Records</h2>
<table class="table">
  <tr><th>Name</th><th>Image</th><th>Actions</th></tr>
  <?php while($row = $res->fetch_assoc()): ?>
    <tr>
      <td><?= $row['name'] ?></td>
      <td><img src="uploads/<?= $row['image'] ?>" height="60"></td>
      <td>
        <?php if (isset($_SESSION['user'])): ?>
          <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>
<?php include 'footer.php'; ?>
