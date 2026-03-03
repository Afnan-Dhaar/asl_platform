<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ================= FETCH USER DATA ================= */
$stmt = $conn->prepare("SELECT name, email, profile_image, password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


/* ================= UPDATE NAME ================= */
if (isset($_POST['update_name'])) {

    $name = trim($_POST['name']);

    if (!empty($name)) {

        $stmt = $conn->prepare("UPDATE users SET name=? WHERE id=?");
        $stmt->bind_param("si", $name, $user_id);
        $stmt->execute();

        $_SESSION['username'] = $name;

        $_SESSION['toast'] = "Name updated successfully!";
        $_SESSION['toast_type'] = "success";

        header("Location: profile.php");
        exit();
    }
}


/* ================= CHANGE PASSWORD ================= */
if (isset($_POST['change_password'])) {

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($new !== $confirm) {

        $_SESSION['toast'] = "New passwords do not match!";
        $_SESSION['toast_type'] = "error";

    } elseif (!password_verify($current, $user['password'])) {

        $_SESSION['toast'] = "Current password is incorrect!";
        $_SESSION['toast_type'] = "error";

    } else {

        $hashed = password_hash($new, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $hashed, $user_id);
        $stmt->execute();

        $_SESSION['toast'] = "Password updated successfully!";
        $_SESSION['toast_type'] = "success";

        header("Location: profile.php");
        exit();
    }
}


/* ================= UPLOAD PROFILE IMAGE ================= */
if (isset($_POST['upload_image'])) {

    if (!empty($_FILES['profile_image']['name'])) {

        $allowed = ['jpg', 'jpeg', 'png'];
        $file_name = $_FILES['profile_image']['name'];
        $file_tmp  = $_FILES['profile_image']['tmp_name'];
        $file_size = $_FILES['profile_image']['size'];

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {

            $_SESSION['toast'] = "Only JPG, JPEG & PNG allowed!";
            $_SESSION['toast_type'] = "error";

        } elseif ($file_size > 2 * 1024 * 1024) {

            $_SESSION['toast'] = "File must be under 2MB!";
            $_SESSION['toast_type'] = "error";

        } else {

            $new_name = time() . "_" . $file_name;
            $target = "uploads/profile/" . $new_name;

            move_uploaded_file($file_tmp, $target);

            if ($user['profile_image'] && $user['profile_image'] != 'default.png' 
                && file_exists("uploads/profile/" . $user['profile_image'])) {
                unlink("uploads/profile/" . $user['profile_image']);
            }

            $stmt = $conn->prepare("UPDATE users SET profile_image=? WHERE id=?");
            $stmt->bind_param("si", $new_name, $user_id);
            $stmt->execute();

            $_SESSION['toast'] = "Profile picture updated!";
            $_SESSION['toast_type'] = "success";

            header("Location: profile.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="profile-wrapper">

    <div class="profile-card">
        <div class="profile-card-header">
        <h2 class="profile-box-header-heading">My Profile</h2>
        <a href="home.php" class="back-btn">Back to Dashboard</a>
        </div>
        <!-- TOAST -->
        <?php if (isset($_SESSION['toast'])): ?>
            <div class="toast <?php echo $_SESSION['toast_type']; ?>">
                <?php 
                    echo $_SESSION['toast']; 
                    unset($_SESSION['toast']);
                    unset($_SESSION['toast_type']);
                ?>
            </div>
        <?php endif; ?>

        <!-- PROFILE IMAGE -->
        <div class="profile-image-section">
            <img src="uploads/profile/<?php echo $user['profile_image'] ?: 'default.png'; ?>" 
                 class="profile-img">

            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="profile_image" required>
                <button type="submit" name="upload_image">Upload Photo</button>
            </form>
        </div>

        <hr>

        <!-- EDIT NAME -->
        <h3 class="profile-box-sub-heading">Edit Name</h3>
        <form method="POST" class="form-group">
            <input type="text" name="name" 
                   value="<?php echo htmlspecialchars($user['name']); ?>" required>
            <button type="submit" name="update_name">Update Name</button>
        </form>

        <hr>

        <!-- CHANGE PASSWORD -->
        <h3 class="profile-box-sub-heading">Change Password</h3>
        <form method="POST" class="form-group">
            <input type="password" name="current_password" placeholder="Current Password" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            <button type="submit" name="change_password">Change Password</button>
        </form>

    </div>
</div>

</body>
</html>