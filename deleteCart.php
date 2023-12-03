<?php
	session_start();
	include("connect.php");

	$user = $_SESSION["user"];
	$productId = basename($_SERVER["REQUEST_URI"]);

	$stmt = $conn->prepare("SELECT id FROM cart WHERE uid=:uid AND product_id=:product_id");
	$stmt->bindParam(":uid", $user["uid"]);
	$stmt->bindParam(":product_id", $productId);
	$stmt->execute();
	
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	$stmt_del = $conn->prepare("DELETE FROM cart WHERE id=:id");
	$stmt_del->bindParam(":id", $result["id"]);
	$stmt_del->execute();

	header("refresh: 0; url=../?cart");

?>
