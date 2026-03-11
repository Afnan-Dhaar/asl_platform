<?php
include 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: home.php"); 
    exit();
}

/* ======================
   ADD USER
====================== */
if(isset($_POST['add_user'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO users (name,email,password,role,status) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss",$name,$email,$password,$role,$status);
    $stmt->execute();

    $_SESSION['toast']="User Added Successfully";
    $_SESSION['toast_type']="success";
    header("Location: admin_users.php");
    exit();
}

/* ======================
   UPDATE USER
====================== */
if(isset($_POST['update_user'])){
    $id = $_POST['edit_id'];

    if($id == $_SESSION['user_id']){
        $_SESSION['toast']="You cannot change your own role!";
        $_SESSION['toast_type']="error";
        header("Location: admin_users.php");
        exit();
    }

    $name = $_POST['edit_name'];
    $email = $_POST['edit_email'];
    $role = $_POST['edit_role'];
    $status = $_POST['edit_status'];

    $stmt = $conn->prepare("UPDATE users SET name=?,email=?,role=?,status=? WHERE id=?");
    $stmt->bind_param("ssssi",$name,$email,$role,$status,$id);
    $stmt->execute();

    $_SESSION['toast']="User Updated Successfully";
    $_SESSION['toast_type']="success";
    header("Location: admin_users.php");
    exit();
}

/* ======================
   BULK ACTIONS
====================== */
if(isset($_POST['bulk_action'])){
    if(!empty($_POST['selected_users'])){
        $action = $_POST['bulk_action'];
        foreach($_POST['selected_users'] as $user_id){

            if($user_id == $_SESSION['user_id']) continue;

            if($action == "delete"){
                $conn->query("DELETE FROM users WHERE id=$user_id");
            }

            if($action == "activate"){
                $conn->query("UPDATE users SET status='active' WHERE id=$user_id");
            }

            if($action == "deactivate"){
                $conn->query("UPDATE users SET status='inactive' WHERE id=$user_id");
            }
        }
        $_SESSION['toast']="Bulk Action Completed";
        $_SESSION['toast_type']="success";
    }
    header("Location: admin_users.php");
    exit();
}

/* ======================
   SEARCH + PAGINATION
====================== */
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$search = "";
$where = "";

if(isset($_GET['search']) && $_GET['search'] != ""){
    $search = $_GET['search'];
    $where = "WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
}

$total = $conn->query("SELECT COUNT(*) as count FROM users $where")->fetch_assoc()['count'];
$pages = ceil($total / $limit);

$result = $conn->query("SELECT * FROM users $where ORDER BY id DESC LIMIT $start,$limit");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Users</title>
<link rel="stylesheet" href="assets/style.css">

</head>

<body>
<div class="asl_admin_wrapper">
    <div class="asl_admin_wrapper-header">
<h2 class="asl_admin_title">User Management</h2>

<div>
    <a href="home.php" class="asl_admin_btn" 
       style="background: rgb(131, 117, 80);color: white;text-decoration:none;">
        Back to Home
    </a>
</div>
</div>

<form class="user-management-search-wrapper" method="GET">
<input class="asl_admin_input" type="text" name="search" placeholder="Search..." value="<?= $search ?>">
<button class="asl_admin_btn" type="submit">Search</button>
</form>

<br>

<button class="asl_admin_btn asl_admin_add_btn" onclick="aslOpenModal('aslAddModal')">+ Add User</button>

<form method="POST">
<div class="asl_admin_bulk_bar">
<select class="asl_admin_select" name="bulk_action">
<option value="">Bulk Actions</option>
<option value="delete">Delete</option>
<option value="activate">Activate</option>
<option value="deactivate">Deactivate</option>
</select>
<button class="asl_admin_btn" type="submit">Apply</button>
</div>

<table class="asl_admin_table">
<tr>
<th>
    <input type="checkbox" id="asl_select_all" onclick="aslToggleAll(this)">
</th>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td>
<?php if($row['id'] != $_SESSION['user_id']): ?>
    <input type="checkbox" 
           class="asl_user_checkbox"
           name="selected_users[]" 
           value="<?= $row['id'] ?>">
<?php else: ?>
    —
<?php endif; ?>
</td>
<td><?= $row['id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['email'] ?></td>
<td><?= $row['role'] ?></td>
<td class="asl_admin_status_<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></td>
<td>
<a href="#"
   class="asl_admin_btn_link asl_admin_edit_btn"
   onclick='aslEditUser(
       <?= json_encode($row["id"]) ?>,
       <?= json_encode($row["name"]) ?>,
       <?= json_encode($row["email"]) ?>,
       <?= json_encode($row["role"]) ?>,
       <?= json_encode($row["status"]) ?>
   )'>
Edit
</a>
</td>
</tr>
<?php endwhile; ?>
</table>
</form>

<!-- Pagination -->
<div class="asl_admin_pagination">
<?php for($i=1;$i<=$pages;$i++): ?>
<a href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
<?php endfor; ?>
</div>

</div>

<!-- ADD MODAL -->
<div id="aslAddModal" class="asl_admin_modal">
<div class="asl_admin_modal_content">
<span class="asl_admin_modal_close" onclick="aslCloseModal('aslAddModal')">&times;</span>
<h3 class="add-user-modal-heading">Add User</h3>
<form method="POST">
<input class="asl_admin_input user-management-add-modal-input" type="text" name="name" placeholder="Name" required>
<input class="asl_admin_input user-management-add-modal-input" type="email" name="email" placeholder="Email" required>
<input class="asl_admin_input user-management-add-modal-input" type="password" name="password" placeholder="Password" required>

<select class="asl_admin_select user-management-add-modal-select" name="role">
<option value="user">User</option>
<option value="admin">Admin</option>
</select>

<select class="asl_admin_select user-management-add-modal-select" name="status">
<option value="active">Active</option>
<option value="inactive">Inactive</option>
</select>

<button class="asl_admin_btn asl_admin_add_btn" type="submit" name="add_user">Create</button>
</form>
</div>
</div>

<!-- EDIT MODAL -->
<div id="aslEditModal" class="asl_admin_modal">
<div class="asl_admin_modal_content">
<span class="asl_admin_modal_close" onclick="aslCloseModal('aslEditModal')">&times;</span>
<h3 class="edit-user-modal-heading">Edit User</h3>
<form method="POST">

<input type="hidden" name="edit_id" id="asl_edit_id">

<input class="asl_admin_input user-management-add-modal-input" type="text" name="edit_name" id="asl_edit_name" required>
<input class="asl_admin_input user-management-add-modal-input" type="email" name="edit_email" id="asl_edit_email" required>

<select class="asl_admin_select user-management-add-modal-select" name="edit_role" id="asl_edit_role">
<option value="user">User</option>
<option value="admin">Admin</option>
</select>

<select class="asl_admin_select user-management-add-modal-select" name="edit_status" id="asl_edit_status">
<option value="active">Active</option>
<option value="inactive">Inactive</option>
</select>

<button class="asl_admin_btn asl_admin_edit_btn" type="submit" name="update_user">Update</button>
</form>
</div>
</div>

<!-- Toast -->
<?php if(isset($_SESSION['toast'])): ?>
<div class="asl_admin_toast asl_admin_toast_<?= $_SESSION['toast_type'] ?>">
<?= $_SESSION['toast'] ?>
</div>
<?php unset($_SESSION['toast']); endif; ?>

<script src="assets/admin-users.js"></script>


</body>
</html>