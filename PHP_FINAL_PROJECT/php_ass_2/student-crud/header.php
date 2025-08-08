<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="dashboard.php">Student Manager</a>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav ms-auto">
      <?php if (isset($_SESSION['user'])): ?>
        <li class="nav-item"><a class="nav-link" href="add.php">Add Student</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
<div class="container mt-4">
