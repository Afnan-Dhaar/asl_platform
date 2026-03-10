<?php

include("config.php");

if($_SESSION['role'] != "admin"){
header("Location:home.php");
exit();
}

$id = $_GET['id'];

$conn->query("DELETE FROM assets WHERE id=$id");

header("Location: manage_lands.php");

?>