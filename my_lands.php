<?php
include("config.php");
include("includes/header.php");

$user_id = $_SESSION['user_id'];

$user = $conn->query("SELECT email FROM users WHERE id=$user_id")->fetch_assoc();
$email = $user['email'];
?>

<h2 id="mylandsPageTitle">My Land Requests</h2>

<div id="mylandsCardsContainer">

<?php

$result = $conn->query("
SELECT land_requests.*, assets.name, assets.location, assets.image
FROM land_requests
JOIN assets ON assets.id = land_requests.asset_id
WHERE land_requests.user_email='$email'
ORDER BY created_at DESC
");

while($row = $result->fetch_assoc()){

$status = $row['status'];

if($status == "pending"){
$statusBadge = "<span class='mylandsStatusBadge mylandsPending'>Pending</span>";
}
elseif($status == "approved"){
$statusBadge = "<span class='mylandsStatusBadge mylandsApproved'>Approved</span>";
}
else{
$statusBadge = "<span class='mylandsStatusBadge mylandsDeclined'>Declined</span>";
}

?>

<div class="mylandsAssetCard">

<div class="mylandsCardImageBox">

<img 
src="uploads/images/<?php echo $row['image']; ?>" 
class="mylandsAssetImage"
>

</div>

<div class="mylandsCardContent">

<h3 class="mylandsAssetTitle">
<?php echo $row['name']; ?>
</h3>

<p class="mylandsAssetLocation">
<strong>Location:</strong> <?php echo $row['location']; ?>
</p>

<p class="mylandsAssetType">
<strong>Request Type:</strong> <?php echo ucfirst($row['request_type']); ?>
</p>

<p class="mylandsAssetDate">
<strong>Date:</strong> <?php echo $row['created_at']; ?>
</p>

<div class="mylandsStatusBox">

<button 
class="mylandsViewBtn"
onclick="openAssetModal(<?php echo $row['asset_id']; ?>,'mylands')">
View Details
</button>

<?php echo $statusBadge; ?>

</div>

</div>

</div>

<?php } ?>

</div>


<div id="assetModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">×</span>

        <!-- AJAX will inject full DB details here -->
        <div id="modalContent"></div>
    </div>
</div>
<?php include("includes/footer.php"); ?>