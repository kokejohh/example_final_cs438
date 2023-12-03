<?php
	session_start();
	include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>5th hand phone shop</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="flex flex-between">
		<h2>5th hand phone shop</h2>
	<?php
		$user = $_SESSION['user'];
		if (isset($user)) {
	?>
			<form action="./logout.php" method="post">
				<h3>
					<label><?php echo "{$user["firstname"]} {$user["lastname"]}" ?></label>
					<input type="submit" value="Log out">
				</h3>
			</form>
	<?php
		} else {
	?>
			<form action="./login.php" method="post">
				<h3>
					<input type="text" name="username" placeholder="username">
					<input type="password" name="password" placeholder="password">
					<input type="submit" value="Log in">
				</h3>
			</form>
	<?php
		}
	?>
	</div>
	<?php
		if (isset($user)) {
	?>
	<h1 style="text-align: center;"><?php echo $user["role"]; ?></h1>
	<?php
			if (in_array($user["role"], $_SESSION["superRole"])) {
				if ($user["role"] == "admin") {
				include("addProduct.php");
				}
			} else {
	?>
			<input type="button" onclick="location.href='.?home'" value="home">
			<input type="button" onclick="location.href='.?cart'" value="My Cart">
	<?php
			}
		}
		$action = basename($_SERVER["REQUEST_URI"]);
		if (str_starts_with($action, "?cart") && isset($user) && !in_array($user["role"], $_SESSION["superRole"])) {
			include("cart.php");
		} else {
			include("products.php");
		}
	?>
</body>
</html>
<?php
