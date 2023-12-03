<?php
	session_start();
	include("connect.php");

	$user = $_SESSION["user"];

	$stmt = $conn->prepare("DELETE FROM cart WHERE uid=:uid");
	$stmt->bindParam(":uid", $user["uid"]);
	$stmt->execute();
	
	header("refresh: 0; url=../?cart");
?>
