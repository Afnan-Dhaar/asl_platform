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

    <!-- LOGO (CLICK TO OPEN MENU) -->
    <div class="logo" onclick="openSidebar()">
        <h2>AWJ ASL Platform</h2>
    </div>

</header>


<!-- LEFT SIDEBAR MENU -->
<div id="sidebarMenu" class="sidebar-menu">

    <!-- CLOSE BUTTON -->
    <span class="close-sidebar" onclick="closeSidebar()">×</span>

    <!-- USER INFO -->
    <div class="sidebar-user">

        <img src="uploads/profile/<?php 
            echo !empty($_SESSION['profile_image']) 
                ? $_SESSION['profile_image'] 
                : 'default.png'; 
        ?>" class="sidebar-profile-img">

        <h3><?php echo $_SESSION['username']; ?></h3>

    </div>

    <!-- MENU LINKS -->
    <nav class="sidebar-links">

        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="testimonials.php">Testimonials</a>
        <a href="contact.php">Contact</a>
        <a href="home.php">Featured Assets</a>
        <a href="analytics.php">More Analytics</a>
        <a href="my_lands.php">My Lands</a>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>

        <a href="admin_users.php">Admin Panel</a>
        <a href="manage_lands.php">Manage Lands</a>
        <a href="reports.php">Reports</a>
        <a href="inbox.php">Inbox</a>
        <a href="manage_requests.php">Manage Requests</a>

        <?php endif; ?>

        <a href="profile.php">Profile</a>
        <a href="logout.php" class="logout-link">Logout</a>

    </nav>

</div>

<!-- MAIN CONTENT WRAPPER START -->
<div class="main-content">