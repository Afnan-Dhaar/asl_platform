<?php
include("config.php");   // Database connection
include("includes/header.php"); // Header layout + session protection
?>

<!-- ================= HOME CARDS SECTION ================= -->

<h2 class="home-page-heading">Featured Assets</h2>

<!-- Cards Container -->
<div id="homeCardsContainer" class="cards-container">

<?php
// Fetch only 4 cards initially
$result = $conn->query("SELECT * FROM assets LIMIT 8");

while($row = $result->fetch_assoc()){
?>

    <!-- CARD -->
    <!-- Clicking card opens modal with detailed DB data -->
    <div class="card" onclick="openAssetModal(<?php echo $row['id']; ?>)">
        <h3><?php echo $row['name']; ?></h3>
        <p><strong>Asset Code:</strong> <?php echo $row['asset_code']; ?></p>
        <p><strong>Total Lands:</strong> <?php echo $row['total_lands']; ?></p>
        <p><strong>Area:</strong> <?php echo $row['land_area']; ?> m²</p>
        <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
    </div>

<?php } ?>

</div>

<!-- ================= VIEW ALL BUTTON ================= -->
<!-- This now loads all cards using AJAX instead of redirecting -->
<!-- <div style="margin-top:20px;">
    <button onclick="loadAllCards()" class="view-btn">
        View All Cards
    </button>
</div> -->


<!-- ================= MODAL FOR CARD DETAILS ================= -->
<div id="assetModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">×</span>

        <!-- AJAX will inject full DB details here -->
        <div id="modalContent"></div>
    </div>
</div>

<?php include("includes/footer.php"); ?>