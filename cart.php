<?php
	$stmt = $conn->prepare("SELECT product.id, product.name, product.description, product.image, product.price,
							COUNT(cart.product_id) as amount, SUM(product.price) as total FROM cart
							LEFT JOIN product ON cart.product_id = product.id
							WHERE cart.uid = :uid
							GROUP BY cart.uid, cart.product_id
							ORDER BY cart.product_id");
	$stmt->bindParam(":uid", $user["uid"]);
	$stmt->execute();
	$carts = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$stmt_bill= $conn->prepare("SELECT SUM(bill.amount) as all_amount, SUM(bill.total) as all_total
							FROM (SELECT product.name, product.description, product.image, product.price,
							COUNT(cart.product_id) as amount, SUM(product.price) as total FROM cart
							LEFT JOIN product ON cart.product_id = product.id
							WHERE cart.uid = :uid
							GROUP BY cart.uid, cart.product_id) as bill");
	$stmt_bill->bindParam(":uid", $user["uid"]);
	$stmt_bill->execute();
	$bill = $stmt_bill->fetchAll(PDO::FETCH_ASSOC);
	$carts = array_merge($bill, $carts);
	$carts = array_merge($carts, $bill);

	if (count($carts) <= 2) {
		echo "<h1>Cart is empty</h1>";
		die();
	}
?>
<h3>My Cart</h3>
<table>
  <tr>
  	<th>Image</th>
    <th>Name</th>
    <th>Description</th>
    <th>Price</th>
	<th>Amount</th>
	<th>Total</th>
	<th></th>
  </tr>
  <?php
  	foreach ($carts as $cart) {
	?>
  	<tr id="<?php echo $cart["id"] ?>">
  	<td style="width: 15%"><img style="width: 150px" alt="" src="uploads/<?php echo $cart["image"]."?".time() ?>"></td>
   	<td><?php echo isset($cart["name"]) ? $cart["name"] : "-" ?></td>
    <td><?php echo isset($cart["description"]) ? $cart["description"] : "-" ?></td>
    <td><?php echo isset($cart["price"]) ? $cart["price"] : "-" ?></td>
    <td><?php echo isset($cart["amount"]) ? $cart["amount"] : $cart["all_amount"];  ?></td>
    <td><?php echo isset($cart["total"]) ? $cart["total"] : $cart["all_total"]; ?></td>
	<?php
		if (isset($user)) {
			$role = $_SESSION["superRole"];
			if (in_array($user["role"], $role)) {
	?>
			<td>
				<a href="editProduct.php/<?php echo $cart["id"]; ?>" style="text-decoration-line: none;">Edit</a>
			<?php
				if ($user["role"] == "admin") {
			?>
				<a href="deleteProduct.php/<?php echo $cart["id"]; ?>" style="text-decoration-line: none;">Delete</a>
			<?php
				}
			?>
			</td>

	<?php
			} else {
				if (empty($cart["all_total"])) {
	?>
				<td>
				<input id="<?php echo $cart["id"] ?>" type="button" onclick="onConfirmDelete(this.id)" style="text-decoration-line: none;" value="Delete">
				<input id="<?php echo $cart["id"] ?>" type="button" onclick="onConfirmDeleteAll(this.id)" style="text-decoration-line: none;" value="Delete All">
				</td>
	<?php
				} else {
	?>
				<td><input id="<?php echo $cart["all_total"] ?>" type="button" onclick="onConfirmPay(this.id)" style="text-decoration-line: none;" value="Pay"></td>
	<?php
				}
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
			location.href = "deleteCart.php/" + id;
		}
	}
	function onConfirmDeleteAll(id) {
		if (confirm("Confirm to delete all ?")) {
			location.href = "deleteCartsAll.php/" + id;
		}
	}
	function onConfirmPay(id) {
		if (confirm("Do you want to pay? total price is " + id + " Baht.")) {
			location.href = "pay.php";
		}
	}
</script>

