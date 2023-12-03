<?php
	session_start();
	include("connect.php");

	$user = $_SESSION["user"];

	$stmt = $conn->prepare("INSERT INTO cart (uid, product_id) VALUES (:uid, :product_id)");
	$stmt->bindParam(":uid", $user["uid"]);
	$stmt->bindParam(":product_id", $_GET["product_id"]);

	$stmt->execute();
	echo "<script>alert('Add to cart successfully');</script>";
	header("refresh: 0; url=../");
?>
