<?php
include("config.php");

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
?>