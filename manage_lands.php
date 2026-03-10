<?php
include("config.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: home.php");
    exit();
}

/* ===========================
PAGINATION
=========================== */

$limit = 8;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$start = ($page - 1) * $limit;

$result = $conn->query("SELECT * FROM assets ORDER BY id DESC LIMIT $start,$limit");

$totalResult = $conn->query("SELECT COUNT(*) as total FROM assets");
$totalRow = $totalResult->fetch_assoc();

$totalPages = ceil($totalRow['total'] / $limit);

include("includes/header.php");
?>

<h2>Manage Lands</h2>

<button type="button" onclick="openAddLandModal()" class="view-btn">
Add Land
</button>

<br><br>

<input 
type="text"
id="searchLand"
placeholder="Search Land..."
onkeyup="searchLand()"
style="padding:8px;width:250px;"
>

<br><br>

<!-- ===========================
LANDS CONTAINER
=========================== -->

<div id="landsContainer" class="cards-container">

<?php while($row = $result->fetch_assoc()){ ?>

<div class="card" onclick="openAssetModal(<?= $row['id'] ?>)">

<h3><?= $row['name'] ?></h3>

<p>Asset Code: <?= $row['asset_code'] ?></p>
<p>Total Lands: <?= $row['total_lands'] ?></p>
<p>Area: <?= $row['land_area'] ?> m²</p>
<p>City: <?= $row['city'] ?></p>

<div class="card-actions">

<button class="edit-land-button" onclick="event.stopPropagation(); editLand(
'<?= $row['id'] ?>',
'<?= $row['name'] ?>',
'<?= $row['asset_code'] ?>',
'<?= $row['total_lands'] ?>',
'<?= $row['land_area'] ?>',
'<?= $row['location'] ?>',
'<?= $row['city'] ?>',
'<?= $row['status'] ?>',
'<?= $row['valuation'] ?>',
'<?= $row['description'] ?>',
'<?= $row['latitude'] ?>',
'<?= $row['longitude'] ?>'
)">
Edit
</button>

<a class="delete-land-button" onclick="event.stopPropagation();" href="delete_land.php?id=<?= $row['id'] ?>">
Delete
</a>

</div>

</div>

<?php } ?>

</div>


<!-- ===========================
PAGINATION
=========================== -->

<div class="pagination">

<?php for($i=1;$i<=$totalPages;$i++){ ?>

<a href="?page=<?= $i ?>" 
class="<?= ($page==$i)?'active':'' ?>">
<?= $i ?>
</a>

<?php } ?>

</div>


<!-- ===========================
ADD LAND MODAL
=========================== -->

<div id="addLandModal" class="modal">
<div class="modal-content">

<span class="close-btn" onclick="closeAddLandModal()">×</span>

<h3>Add Land</h3>

<form method="POST" action="add_land.php" class="modal-form">

<input type="text" name="name" placeholder="Land Name" required>
<input type="text" name="asset_code" placeholder="Asset Code">

<input type="number" name="total_lands" placeholder="Total Lands">
<input type="number" name="land_area" placeholder="Land Area (m²)">

<input type="text" name="location" placeholder="Location">
<input type="text" name="city" placeholder="City">

<select name="status">
<option value="">Status</option>
<option>Developed</option>
<option>Lease</option>
</select>

<input type="text" name="valuation" placeholder="Valuation">

<textarea name="description" placeholder="Description"></textarea>

<input type="text" name="latitude" placeholder="Latitude">
<input type="text" name="longitude" placeholder="Longitude">

<div class="modal-footer">
<button type="submit">Add Land</button>
</div>

</form>

</div>
</div>


<!-- ===========================
EDIT LAND MODAL
=========================== -->

<div id="editLandModal" class="modal">
<div class="modal-content">

<span class="close-btn" onclick="closeEditLandModal()">×</span>

<h3>Edit Land</h3>

<form method="POST" action="update_land.php" class="modal-form">

<input type="hidden" name="id" id="edit_id">

<input type="text" name="name" id="edit_name">
<input type="text" name="asset_code" id="edit_asset_code">

<input type="number" name="total_lands" id="edit_total_lands">
<input type="number" name="land_area" id="edit_land_area">

<input type="text" name="location" id="edit_location">
<input type="text" name="city" id="edit_city">

<select name="status" id="edit_status">
<option>Developed</option>
<option>Lease</option>
</select>

<input type="text" name="valuation" id="edit_valuation">

<textarea name="description" id="edit_description"></textarea>

<input type="text" name="latitude" id="edit_lat">
<input type="text" name="longitude" id="edit_long">

<div class="modal-footer">
<button type="submit">Update Land</button>
</div>

</form>

</div>
</div>


<!-- ===========================
ASSET DETAILS MODAL
=========================== -->

<div id="assetModal" class="modal">

<div class="modal-content">

<span class="close-btn" onclick="closeModal()">×</span>

<div id="modalContent"></div>

</div>

</div>


<?php include("includes/footer.php"); ?>