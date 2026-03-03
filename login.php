<?php 
include("config.php");

// If already logged in → go to home
if(isset($_SESSION['user_id'])){
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<!-- HELP BUTTON -->
<button class="help-btn" onclick="openHelp()">Help.?</button>

<!-- HELP MODAL -->
<div id="helpModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeHelp()">×</span>

        <h3>Instructions</h3>
        <p>• Enter your registered email and password.</p>
        <p>• If you don’t have an account, click Sign Up.</p>
        <p>• After login, you will be redirected to dashboard.</p>
    </div>
</div>

<!-- LOGIN CONTAINER -->
<div class="login-box">
    <h2>User Login</h2>
    <p class="login-p-tag">
        Hey, Enter your details to get login to your account
    </p>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter Email" required>

        <div class="password-wrapper">
            <input type="password" id="password" name="password" placeholder="Enter Password" required>

            <span class="toggle-password" onclick="togglePassword('password', this)">
                <!-- Eye Icon -->
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

        <button type="submit" name="login">Sign In</button>
    </form>

    <p>No account?
        <a href="register.php">Sign Up</a>
    </p>
</div>

<!-- TOAST MESSAGE -->
<div id="toast"></div>

<script src="assets/script.js"></script>
</body>
</html>

<?php
// ================= LOGIN LOGIC =================
if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Prepared statement (SECURE)
    $stmt = $conn->prepare("SELECT id, username, password, profile_image FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){

            // Store session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['profile_image'] = $user['profile_image'] ?? 'default.png';

            echo "<script>
                showToast('Login Successful','success'); 
                setTimeout(()=>{window.location='home.php'},1500);
            </script>";

        }else{
            echo "<script>showToast('Wrong Password','error');</script>";
        }

    }else{
        echo "<script>showToast('User Not Found','error');</script>";
    }

    $stmt->close();
}
?>