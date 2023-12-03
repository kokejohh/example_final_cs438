<?php
	include("connect.php");
	if (isset($_POST["submit"])) {
		if ($_FILES["image"]["error"] > 0 || $_FILES["image"]["size"] == 0 || empty($_POST["productName"]) ||
			empty($_POST["description"]) || empty($_POST["price"])) {
			echo "<script>alert('Insert Error.');</script>";
			header("refresh: 0; url=../");
			die();
		}
		$targetDir = "uploads/";
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo(basename($_FILES["image"]["name"]), PATHINFO_EXTENSION));

		$check = getimagesize($_FILES["image"]["tmp_name"]);

		if ($check !== false) {
			//echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			//echo "File is not an image.";
			$uploadOk = 0;
		}

		if ($uploadOk == 0) {
			echo "<script>alert('Sorry, your file was not uploaded.')</script>";
		} else {
			$newNameFile = $_POST["productName"].".".$imageFileType;
			$targetFile = $targetDir.$newNameFile;
			if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
				$stmt = $conn->prepare("INSERT INTO product (name, description, price, image)
										VALUES (:name, :description, :price, :image)");
				$stmt->bindParam(":name", $_POST["productName"]);
				$stmt->bindParam(":description", $_POST["description"]);
				$stmt->bindParam(":price", $_POST["price"]);
				$stmt->bindParam(":image", $newNameFile);

				$stmt->execute();
				echo "<script>alert('Inserted Successfully.');</script>";
				//echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])). " has been uploaded.";
			} else {
				echo "<script>alert('Sorry, there was an error uploading your file.')</script>";
			}
		}
	}
	header("refresh: 0; url=../");
?>
