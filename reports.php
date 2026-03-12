<?php
include("config.php");

if($_SESSION['role']!="admin"){
header("Location:home.php");
exit();
}

include("includes/header.php");
?>

<h2 class="reports-page-heading">Reports Dashboard</h2>
<?php
$totalLands = $conn->query("SELECT COUNT(*) as total FROM assets")->fetch_assoc()['total'];

$totalCities = $conn->query("SELECT COUNT(DISTINCT city) as total FROM assets")->fetch_assoc()['total'];

$totalArea = $conn->query("SELECT SUM(land_area) as total FROM assets")->fetch_assoc()['total'];

$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
?>

<div class="kpi-grid">

<div class="kpi-card">
<h3>Total Lands</h3>
<p><?php echo $totalLands; ?></p>
</div>

<div class="kpi-card">
<h3>Total Cities</h3>
<p><?php echo $totalCities; ?></p>
</div>

<div class="kpi-card">
<h3>Total Land Area</h3>
<p><?php echo $totalArea; ?> m²</p>
</div>

<div class="kpi-card">
<h3>Total Users</h3>
<p><?php echo $totalUsers; ?></p>
</div>

</div>
<div class="reports-grid">

<div class="chart-card">
<h3>Lands per City</h3>
<canvas id="cityChart"></canvas>
</div>

<div class="chart-card">
<h3>Lands by Status</h3>
<canvas id="statusChart"></canvas>
</div>

<div class="chart-card">
<h3>Monthly Lands Added</h3>
<canvas id="monthlyChart"></canvas>
</div>

<div class="chart-card">
<h3>User Growth</h3>
<canvas id="userChart"></canvas>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="reports.js"></script>

<?php include("includes/footer.php"); ?>