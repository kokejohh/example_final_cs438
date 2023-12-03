<?php
	$servername = "localhost";
	$username_db = "root";
	$password_db = "root";

	try {
		$conn = new PDO("mysql:host=$servername;dbname=phone_shop", $username_db, $password_db);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$_SESSION["superRole"] = array("admin", "manager");
	} catch (PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}
?>
