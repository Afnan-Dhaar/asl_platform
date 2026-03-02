<?php include("config.php"); ?>

<?php
// If already logged in → go to home
if(isset($_SESSION['user_id'])){
    header("Location: home.php");
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
<button class="help-btn" onclick="openHelp()">Help?</button>

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

    <form method="POST">
        <input type="text" name="email" placeholder="Enter Email" required>
        <div class="password-wrapper">
    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
    <span class="toggle-password" onclick="togglePassword('confirm_password', this)">👁</span>
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
// LOGIN LOGIC
if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){

        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            echo "<script>showToast('Login Successful','success'); 
                  setTimeout(()=>{window.location='home.php'},1500);</script>";

        }else{
            echo "<script>showToast('Wrong Password','error');</script>";
        }

    }else{
        echo "<script>showToast('User Not Found','error');</script>";
    }
}
?>