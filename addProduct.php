<form action="/upload.php" method="post" enctype="multipart/form-data" style="margin: 50px auto">
	Image: <input type="file" name="image" accept="image/png, image/jpg, image/jpeg">
	<input type="text" name="productName" placeholder="Product Name">
	<input type="text" name="description" placeholder="Description">
	<input type="number" name="price" placeholder="Price" step="0.01">
	<input type="submit" name="submit" value="Add">
</form>
