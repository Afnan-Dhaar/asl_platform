<?php
include("config.php");
include("includes/header.php");
?>
<h2 class="analytics-page-heading">Analytics Page</h2>

<!-- FILTER BOX -->
<div class="filter-box">

    <input type="text" id="filterName" placeholder="Search by Name">
    <input type="text" id="filterCity" placeholder="Search by City">
    <input type="text" id="filterCode" placeholder="Search by Asset Code">

    <select id="filterStatus">
        <option value="">All Status</option>
        <option value="Developed">Developed</option>
        <option value="Lease">Lease</option>
    </select>

    <input type="number" id="filterMinArea" placeholder="Min Area">
    <input type="number" id="filterMaxArea" placeholder="Max Area">

    <input type="number" id="filterMinPrice" placeholder="Min Price">
    <input type="number" id="filterMaxPrice" placeholder="Max Price">

    <button class="reset-btn" onclick="resetFilters()">Reset</button>

</div>

<div id="filterChips" class="filter-chips"></div>

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

<a class="back-home-btn" href="home.php">Back to Home</a>

<?php include("includes/footer.php"); ?>