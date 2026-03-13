<?php
include("config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$action = $_POST['action'] ?? '';

/* ===============================
GET SINGLE ASSET FOR MODAL
=============================== */

if($action == "get_asset"){

$id = intval($_POST['id']);

$result = $conn->query("SELECT * FROM assets WHERE id=$id");

$data = $result->fetch_assoc();

header('Content-Type: application/json');

echo json_encode($data);

exit();
}


/* ===============================
FILTER ASSETS (Analytics + Home)
=============================== */

if($action == "filter_assets"){

$city = $_POST['city'] ?? '';
$status = $_POST['status'] ?? '';

$query = "SELECT * FROM assets WHERE 1";

if($city != ""){
$query .= " AND city LIKE '%$city%'";
}

if($status != ""){
$query .= " AND status='$status'";
}

$result = $conn->query($query);

while($row = $result->fetch_assoc()){
?>

<div class="card" onclick="openAssetModal(<?php echo $row['id']; ?>)">

<?php if(!empty($row['image'])){ ?>
<img src="uploads/images/<?php echo $row['image']; ?>" style="width:100%;height:140px;object-fit:cover;border-radius:10px;">
<?php } ?>

<h3><?php echo $row['name']; ?></h3>
<p>Asset Code: <?php echo $row['asset_code']; ?></p>
<p>Total Lands: <?php echo $row['total_lands']; ?></p>
<p>Area: <?php echo $row['land_area']; ?> m²</p>
<p>City: <?php echo $row['city']; ?></p>

</div>

<?php
}

exit();

}


/* ===============================
REPORT: LANDS PER CITY
=============================== */

if($action == "lands_per_city"){

$result = $conn->query("
SELECT city, COUNT(*) as total
FROM assets
GROUP BY city
");

$data = [];

while($row = $result->fetch_assoc()){
$data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
exit();

}


/* ===============================
REPORT: LANDS BY STATUS
=============================== */

if($action == "lands_by_status"){

$result = $conn->query("
SELECT status, COUNT(*) as total
FROM assets
GROUP BY status
");

$data = [];

while($row = $result->fetch_assoc()){
$data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
exit();

}


/* ===============================
REPORT: MONTHLY LANDS ADDED
=============================== */

if($action == "lands_monthly"){

$result = $conn->query("
SELECT MONTH(created_at) as month, COUNT(*) as total
FROM assets
GROUP BY MONTH(created_at)
ORDER BY month ASC
");

$data = [];

while($row = $result->fetch_assoc()){
$data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
exit();

}

if($action == "get_message"){

$id = intval($_POST['id']);

$result = $conn->query("SELECT * FROM contact_messages WHERE id=$id");

$data = $result->fetch_assoc();

$conn->query("UPDATE contact_messages SET is_read=1 WHERE id=$id");

header('Content-Type: application/json');

echo json_encode($data);

exit();
}

if($action == "delete_message"){

$id = intval($_POST['id']);

$conn->query("DELETE FROM contact_messages WHERE id=$id");

echo "success";

exit();
}

if($action == "send_reply"){

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars($_POST['message']);

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;

$mail->Username = 'afnandhar786@gmail.com';
$mail->Password = 'zdfs mosm tash pylf';

$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('afnandhar786@gmail.com','Admin');

$mail->addAddress($email);

$mail->Subject = "Reply from ASL Platform";

$mail->Body = $message;

$mail->send();

echo "sent";

exit();
}


/* ===============================
REPORT: USER GROWTH
=============================== */

if($action == "users_growth"){

$result = $conn->query("
SELECT MONTH(created_at) as month, COUNT(*) as total
FROM users
GROUP BY MONTH(created_at)
ORDER BY month ASC
");

$data = [];

while($row = $result->fetch_assoc()){
$data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
exit();

}

?>