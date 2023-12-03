<?php
	session_start();
	include("connect.php");

	$user = $_SESSION["user"];
	$productId = basename($_SERVER["REQUEST_URI"]);

	$stmt = $conn->prepare("DELETE FROM cart WHERE uid=:uid AND product_id=:product_id");
	$stmt->bindParam(":uid", $user["uid"]);
	$stmt->bindParam(":product_id", $productId);
	$stmt->execute();
	
	header("refresh: 0; url=../?cart");
?>
