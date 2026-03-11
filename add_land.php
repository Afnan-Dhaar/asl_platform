<?php
include("config.php");

if($_SESSION['role'] != "admin"){
header("Location:home.php");
exit();
}

$image="";
$document="";

if(!empty($_FILES['image']['name'])){
$image=time()."_".$_FILES['image']['name'];
move_uploaded_file($_FILES['image']['tmp_name'],"uploads/images/".$image);
}

if(!empty($_FILES['document']['name'])){
$document=time()."_".$_FILES['document']['name'];
move_uploaded_file($_FILES['document']['tmp_name'],"uploads/documents/".$document);
}

$stmt = $conn->prepare("
INSERT INTO assets
(name,asset_code,total_lands,land_area,location,city,status,valuation,description,latitude,longitude,image,document)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
"ssiidssssssss",
$_POST['name'],
$_POST['asset_code'],
$_POST['total_lands'],
$_POST['land_area'],
$_POST['location'],
$_POST['city'],
$_POST['status'],
$_POST['valuation'],
$_POST['description'],
$_POST['latitude'],
$_POST['longitude'],
$image,
$document
);

$stmt->execute();

header("Location: manage_lands.php");
exit();