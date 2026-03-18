<?php
include("config.php");
include("includes/header.php");

/* REQUEST STATISTICS */

$total = $conn->query("SELECT COUNT(*) as c FROM land_requests")->fetch_assoc()['c'];

$pending = $conn->query("SELECT COUNT(*) as c FROM land_requests WHERE status='pending'")->fetch_assoc()['c'];

$approved = $conn->query("SELECT COUNT(*) as c FROM land_requests WHERE status='approved'")->fetch_assoc()['c'];

$declined = $conn->query("SELECT COUNT(*) as c FROM land_requests WHERE status='declined'")->fetch_assoc()['c'];
?>

<h2 class="manage-request-heading">Land Requests Dashboard</h2>

<!-- STATS CARDS -->

<div class="requests-stats">

<div class="stat-card total">
<p>Total Requests</p>
<h2><?php echo $total; ?></h2>
</div>

<div class="stat-card pending">
<p>Pending</p>
<h2><?php echo $pending; ?></h2>
</div>

<div class="stat-card approved">
<p>Approved</p>
<h2><?php echo $approved; ?></h2>
</div>

<div class="stat-card declined">
<p>Declined</p>
<h2><?php echo $declined; ?></h2>
</div>

</div>

<!-- SEARCH -->

<div class="requests-toolbar">

<input type="text"
id="searchRequests"
placeholder="Search by user, email or asset..."
onkeyup="filterRequests()">

<select id="statusFilter" onchange="filterRequests()">
<option value="">All Status</option>
<option value="pending">Pending</option>
<option value="approved">Approved</option>
<option value="declined">Declined</option>
</select>

<select id="typeFilter" onchange="filterRequests()">
<option value="">All Types</option>
<option value="buy">Buy</option>
<option value="rent">Rent</option>
</select>

</div>

<table id="requestsTable" class="requests-table">

<tr>
<th>User</th>
<th>Email</th>
<th>Asset</th>
<th>Type</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php

$result = $conn->query("
SELECT land_requests.*, assets.name as asset_name
FROM land_requests
JOIN assets ON assets.id = land_requests.asset_id
ORDER BY created_at DESC
");

while($row = $result->fetch_assoc()){
?>

<tr>
<td><?php echo $row['user_name']; ?></td>

<td><?php echo $row['user_email']; ?></td>

<td><?php echo $row['asset_name']; ?></td>

<td><?php echo ucfirst($row['request_type']); ?></td>

<td>

<?php

$status = $row['status'];

if($status == "pending"){
echo "<span class='badge pending'>Pending</span>";
}
elseif($status == "approved"){
echo "<span class='badge approved'>Approved</span>";
}
else{
echo "<span class='badge declined'>Declined</span>";
}

?>

</td>

<td>

<button class="action-btn view-btn"
onclick="viewRequest(<?php echo $row['id']; ?>)">
View
</button>

<a class="action-btn approve-btn"
href="approve_request.php?id=<?php echo $row['id']; ?>">
Approve
</a>

<a class="action-btn decline-btn"
href="decline_request.php?id=<?php echo $row['id']; ?>">
Decline
</a>

</td>

</tr>

<?php } ?>

</table>

<!-- REQUEST DETAIL MODAL -->

<div id="requestModal" class="request-modal">

<div class="request-modal-content">

<span class="request-close-btn" onclick="closeRequestModal()">×</span>

<div id="requestDetails"></div>

</div>

</div>

<?php include("includes/footer.php"); ?>