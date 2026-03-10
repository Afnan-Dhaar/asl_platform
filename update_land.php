<?php

include("config.php");

$id = $_POST['id'];
$name = $_POST['name'];
$asset_code = $_POST['asset_code'];
$city = $_POST['city'];

$stmt = $conn->prepare("
UPDATE assets
SET name=?,asset_code=?,city=?
WHERE id=?
");

$stmt->bind_param("sssi",$name,$asset_code,$city,$id);

$stmt->execute();

header("Location: manage_lands.php");
exit();