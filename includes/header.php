<?php
// ==============================
// HEADER FILE
// This file is included on pages
// that require login (home, analytics)
// ==============================

// If session is not started, start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Protect page — redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AWJ ASL Platform</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<!-- ================= HEADER SECTION ================= -->
<header class="main-header">

    <!-- LEFT: LOGO -->
    <div class="logo">
        <h2>AWJ ASL Platform</h2>
    </div>

    <!-- CENTER: MENU -->
    <nav class="menu">
        <a href="home.php">ASL Platform</a>
        <a href="analytics.php">More Analytics</a>
        <a href="#">Reports</a>
    </nav>

    <!-- RIGHT: USER INFO -->
    <div class="user-section">
        <span class="username">
            👤 <?php echo $_SESSION['username']; ?>
        </span>

        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

</header>

<!-- MAIN CONTENT WRAPPER START -->
<div class="main-content">