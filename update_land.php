<?php
include("config.php");

$id=$_POST['id'];

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

$query="UPDATE assets SET
name=?,
asset_code=?,
total_lands=?,
land_area=?,
location=?,
city=?,
status=?,
valuation=?,
description=?,
latitude=?,
longitude=?";

$params=[
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
$_POST['longitude']
];

if($image!=""){
$query.=", image=?";
$params[]=$image;
}

if($document!=""){
$query.=", document=?";
$params[]=$document;
}

$query.=" WHERE id=?";

$params[]=$id;

$stmt=$conn->prepare($query);
$stmt->execute($params);

header("Location: manage_lands.php");
exit();