<?php
	if (empty($conn)) {
		header("refresh: 0; url=../");
		die();
	}
	$stmt = $conn->prepare("SELECT id, name, description, price, image FROM product");
	$stmt->execute();
	$result =$stmt->fetchAll();
?>
<h3>Products</h3>
<table>
  <tr>
  	<th>Image</th>
    <th>Name</th>
    <th>Description</th>
    <th>Price</th>
	<th></th>
  </tr>
  <?php
  	foreach ($result as $product) {
	?>
  	<tr id="<?php echo $product["id"] ?>">
  	<td style="width: 15%"><img style="width: 250px" alt="" src="uploads/<?php echo $product["image"]."?".time() ?>"></td>
   	<td><?php echo $product["name"] ?></td>
    <td><?php echo $product["description"] ?></td>
    <td><?php echo $product["price"] ?></td>
	<?php
		if (isset($user)) {
			$role = $_SESSION["superRole"];
			if (in_array($user["role"], $role)) {
	?>
			<td>
				<input id="<?php echo $product["id"] ?>" type="button" onclick="onEdit(this.id)" style="text-decoration-line: none;" value="Edit">
			<?php
				if ($user["role"] == "admin") {
			?>
				<input id="<?php echo $product["id"] ?>" type="button" onclick="onConfirmDelete(this.id)" style="text-decoration-line: none;" value="Delete">
			<?php
				}
			?>
			</td>

	<?php
			} else {
	?>
			<td>
				<input id="<?php echo $product["id"] ?>" type="button" onclick="onAddToCart(this.id)" style="text-decoration-line: none;" value="Add to cart">
			</td>
	<?php
			}
		} else {
	?>
		<td><a>Log in to buy</a></td>
	<?php
		}
	?>
  </tr>
  <?php
	}
  ?>
</table>

<script>
	function onConfirmDelete(id) {
		if (confirm("Confirm to delete ?")) {
			location.href = "deleteProduct.php/" + id;
		}
	}
	function onEdit(id) {
		location.href = "editProduct.php/" + id;
	}
	function onAddToCart(id) {
		location.href = "addToCart.php?product_id=" + id;
	}
</script>
