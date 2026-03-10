<?php
include("config.php");

if($_SESSION['role'] != "admin"){
header("Location:home.php");
exit();
}

$name = $_POST['name'];
$asset_code = $_POST['asset_code'];
$total_lands = $_POST['total_lands'];
$land_area = $_POST['land_area'];
$location = $_POST['location'];
$city = $_POST['city'];
$status = $_POST['status'];
$valuation = $_POST['valuation'];
$description = $_POST['description'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

$stmt = $conn->prepare("
INSERT INTO assets
(name,asset_code,total_lands,land_area,location,city,status,valuation,description,latitude,longitude)
VALUES (?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
"ssiidssssss",
$name,$asset_code,$total_lands,$land_area,$location,$city,$status,$valuation,$description,$latitude,$longitude
);

$stmt->execute();

header("Location: manage_lands.php");
exit();