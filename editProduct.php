<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>5th2 hand phone shop</title>
	<link rel="stylesheet" href="/style.css">
</head>
<body>
<?php
	session_start();
	$user = $_SESSION["user"];
	$role = $_SESSION["superRole"];
	if (empty($user) || !in_array($user["role"], $role)) {
		header("refresh: 0; url=../");
		die();
	} else {
		include("connect.php");

		$productId = basename($_SERVER["REQUEST_URI"]);
		$stmt = $conn->prepare("SELECT * FROM product WHERE id=:id");
		$stmt->bindParam(":id", $productId);
		$stmt->execute();

		$product = $stmt->fetch();

		if ($_POST["submit"]) {
			if (empty($_POST["productName"]) || empty($_POST["description"]) || empty($_POST["price"])) {
				echo "<script>alert('Update Error.')</script>";
				header("refresh: 0; url=../");
				die();
			} else {
				$targetDir = "uploads/";
				$imageFileType = strtolower(pathinfo(basename($_FILES["image"]["name"]), PATHINFO_EXTENSION));

				$check = $_FILES["image"]["tmp_name"] ? getimagesize($_FILES["image"]["tmp_name"]) : true;
				$uploadOk = $check !== false ? 1 : 0;

				if ($uploadOk == 0) {
					echo "<script>alert('Sorry, your file was not uploaded.')</script>";
				} else {
					if ($_FILES["image"]["tmp_name"]) {
						$newNameFile = $_POST["productName"].".".$imageFileType;
						$targetFile = $targetDir.$newNameFile;
						if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
						} else {
							echo "<script>alert('Sorry, there was an error uploading your file.')</script>";
						}
					} else {
						$newNameFile = $product["image"];
					}
					$stmt = $conn->prepare("UPDATE product SET name=:name, description=:description,
											price=:price, image=:image, update_date=:update_date
											WHERE id=:id");
					$stmt->bindParam(":name", $_POST["productName"]);
					$stmt->bindParam(":description", $_POST["description"]);
					$stmt->bindParam(":price", $_POST["price"]);
					$stmt->bindParam(":image", $newNameFile);
					$stmt->bindParam(":id", $productId);
					$stmt->bindParam(":update_date", date("Y-m-d H:i:s"));

					$stmt->execute();
					echo "<script>alert('Updated Successfully.')</script>";
					header("refresh: 0; url=../");
					die();
				}
			}
		}
	}
?>

<form style="text-align: center" method="post" enctype="multipart/form-data" action="/editProduct.php/<?php echo $product["id"] ?>">
	<h1>Edit Product</h1>
	<img style="height: 250px" alt="" src="/uploads/<?php echo $product["image"]."?".time(); ?>">
	<br><br>
	<input type="file" name="image" accept="image/png, image/jpg, image/jpeg">
	<p>Product Name</p>
	<input type="text" name="productName" placeholder="Product Name" value="<?php echo $product["name"]; ?>">
	<p>Description</p>
	<input type="text" name="description" placeholder="Description" value="<?php echo $product["description"]; ?>">
	<p>Price</p>
	<input type="number" step="0.01" name="price" placeholder="Price" value="<?php echo $product["price"]; ?>">
	<br><br>
	<input type="button" name="back" onclick="location.href='../'" value="Back">
	<input type="submit" name="submit" value="Save">
</form>
</body>
</html>
