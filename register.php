<?php
include("config.php");

// If already logged in → go to home
if(isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

// ================= REGISTER LOGIC =================
if(isset($_POST['register'])){

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if(empty($username) || empty($email) || empty($password)){
        echo "<script>window.onload=function(){showToast('All fields are required','error')}</script>";
    } else {

        // Check if email already exists
        $check = $conn->query("SELECT * FROM users WHERE email='$email'");

        if($check->num_rows > 0){

            echo "<script>window.onload=function(){showToast('Email already exists','error')}</script>";

        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $conn->query("INSERT INTO users(username,email,password)
                          VALUES('$username','$email','$hashedPassword')");

            echo "<script>
                    window.onload=function(){
                        showToast('Registration Successful','success');
                        setTimeout(()=>{window.location='login.php'},1500);
                    }
                  </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<!-- HELP BUTTON -->
<button class="help-btn" onclick="openHelp()">Help.?</button>

<!-- HELP MODAL -->
<div id="helpModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeHelp()">×</span>
        <h3>Registration Instructions</h3>
        <p>• Enter your username.</p>
        <p>• Enter a valid email address.</p>
        <p>• Choose a secure password.</p>
    </div>
</div>

<!-- REGISTER BOX -->
<div class="login-box">
    <h2>Create Account</h2>

    <form method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="email" name="email" placeholder="Enter Email" required>
       <div class="password-wrapper">
    <input type="password" id="password" name="password" placeholder="Enter Password" required>

    <span class="toggle-password" onclick="togglePassword('password', this)">
        <!-- Default icon = Eye -->
        <svg xmlns="http://www.w3.org/2000/svg"
             width="20" height="20" viewBox="0 0 24 24"
             fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round"
             stroke-linejoin="round">
            <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/>
            <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/>
            <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/>
            <path d="m2 2 20 20"/>
        </svg>
    </span>
</div>
        <button type="submit" name="register">Sign Up</button>
    </form>

    <p>Already have an account?
        <a href="login.php">Sign In</a>
    </p>
</div>

<!-- TOAST -->
<div id="toast"></div>

<script src="assets/script.js"></script>
</body>
</html>