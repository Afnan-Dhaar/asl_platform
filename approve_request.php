<?php
include("config.php");

$id = intval($_GET['id']);

$conn->query("UPDATE land_requests SET status='approved' WHERE id=$id");

header("Location: manage_requests.php");