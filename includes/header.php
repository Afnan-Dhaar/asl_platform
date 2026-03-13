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
<a href="index.php">Home</a>
<a href="about.php">About</a>
<a href="testimonials.php">Testimonials</a>
<a href="contact.php">Contact</a>
<a href="home.php">ASL Platform Assets</a>
<a href="analytics.php">More Analytics</a>

<?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>

<a href="admin_users.php">Admin Panel</a>

<a href="manage_lands.php">Manage Lands</a>

<a href="reports.php">Reports</a>

<a href="inbox.php">Inbox</a>
<?php endif; ?>

<!-- <a href="#">Reports</a> -->

</nav>

    <!-- RIGHT: USER INFO -->
    <!-- RIGHT: USER INFO -->
<div class="user-section">

    <a href="profile.php" class="user-info">

        <img src="uploads/profile/<?php 
            echo !empty($_SESSION['profile_image']) 
                ? $_SESSION['profile_image'] 
                : 'default.png'; 
        ?>" class="header-profile-img">

        <span class="username">
            <?php echo $_SESSION['username']; ?>
        </span>

    </a>

    <a href="logout.php" class="logout-btn">Logout</a>

</div>

</header>

<!-- MAIN CONTENT WRAPPER START -->
<div class="main-content">