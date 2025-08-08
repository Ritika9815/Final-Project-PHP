<?php
include 'db.php';
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
$id = $_GET['id'];
$conn->query("DELETE FROM students WHERE id=$id");
header("Location: dashboard.php");
?>
