<?php include("includes/header.php"); ?>
<?php
include("config.php");
?>
<h2>Analytics Page</h2>

<!-- FILTER BOX -->
<div class="filter-box">
    <input type="text" id="filterCity" placeholder="City">

    <select id="filterStatus">
        <option value="">All Status</option>
        <option value="Developed">Developed</option>
        <option value="Lease">Lease</option>
    </select>

    <button onclick="applyFilter()">Apply Filter</button>
</div>

<!-- CARDS CONTAINER -->
<div id="cardsContainer" class="cards-container">
    <?php
    $result = $conn->query("SELECT * FROM assets");
    while ($row = $result->fetch_assoc()) {
    ?>
        <div class="card" onclick="openAssetModal(<?php echo $row['id']; ?>)">
            <h3><?php echo $row['name']; ?></h3>
            <p>Asset Code: <?php echo $row['asset_code']; ?></p>
            <p>Total Lands: <?php echo $row['total_lands']; ?></p>
            <p>Area: <?php echo $row['land_area']; ?> m²</p>
            <p>City: <?php echo $row['city']; ?></p>
        </div>
    <?php } ?>
</div>

<!-- MODAL -->
<div id="assetModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">×</span>
        <div id="modalContent"></div>
    </div>
</div>

<a href="home.php">← Back to Home</a>

<?php include("includes/footer.php"); ?>