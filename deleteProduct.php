<?php
	include("connect.php");

	$productId = basename($_SERVER["REQUEST_URI"]);

	$stmt = $conn->prepare("DELETE FROM product WHERE id=:id");
	$stmt->bindParam(":id", $productId);

	$stmt->execute();

	header("refresh: 0; url=../");
?>
