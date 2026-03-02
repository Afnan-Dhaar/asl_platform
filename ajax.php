<?php
// ============================================
// AJAX HANDLER FILE
// This file handles:
// 1) Fetch single asset (for modal)
// 2) Filter assets (for analytics page)
// ============================================

include("config.php");

// ================================
// 1️⃣ FETCH SINGLE ASSET FOR MODAL
// ================================
if (isset($_POST['action']) && $_POST['action'] == "get_asset") {

    $id = intval($_POST['id']);

    $query = $conn->query("SELECT * FROM assets WHERE id = $id");
    $row = $query->fetch_assoc();

    // Return JSON response
    echo json_encode($row);
    exit();
}

// ================================
// 2️⃣ FILTER ASSETS
// ================================
if (isset($_POST['action']) && $_POST['action'] == "filter_assets") {

    // Safely get values (avoid undefined errors)
    $city = isset($_POST['city']) ? $_POST['city'] : "";
    $status = isset($_POST['status']) ? $_POST['status'] : "";

    $where = "WHERE 1=1";

if (!empty($city)) {
    $city = $conn->real_escape_string($city);
    $where .= " AND city = '$city'";
}

    if (!empty($status)) {
        $status = $conn->real_escape_string($status);
        $where .= " AND status = '$status'";
    }

    $result = $conn->query("SELECT * FROM assets $where");

    $cards = "";

    while ($row = $result->fetch_assoc()) {

        $cards .= '
        <div class="card" onclick="openAssetModal('.$row['id'].')">
            <h3>'.$row['name'].'</h3>
            <p><strong>Asset Code:</strong> '.$row['asset_code'].'</p>
            <p><strong>Total Lands:</strong> '.$row['total_lands'].'</p>
            <p><strong>Area:</strong> '.$row['land_area'].' m²</p>
            <p><strong>City:</strong> '.$row['city'].'</p>
        </div>';
    }

    echo $cards;
    exit();
}
?>