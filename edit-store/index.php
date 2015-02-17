<?php
session_start();
$currentDir = dirname(__FILE__);

require_once("../dummy-session.php");
require_once("../root-path.php");
require_once("../php/lib/header.php");



?>
<div class="container">
	<h2>Edit Store</h2>

	<form class="form-inline" method="post" action="../php/forms/edit-store-controller.php">

		<div class="form-group">
			<label for="storeName">Store Name</label>
			<input type="text" class="form-control" name="editStoreName" id="editStoreName" placeholder=<?php echo $_SESSION['store']['name'];?>>
		</div>

		<br>

		<div class="form-group">
			<label for="inputProductPrice">Store Description</label>
			<input type="text" class="form-control" name="editStoreDescription" id="editStoreDescription" placeholder=<?php echo $_SESSION['store']['description'];?>>
		</div>

		<br>

		<div class="form-group">
			<label for="inputProductType">Image Path</label>
			<input type="file" class="form-control" name="editInputImage" id="editInputImage" placeholder=<?php echo $_SESSION['store']['image'];?>>
		</div>

		<br>
		<br>
		<br>
		<button type="submit">Submit</button>
		<br>
		<br>
	</form>
	<p id="outputArea"></p>
	<br>
</div>
	</form>

<?php require_once "../php/lib/footer.php"; ?>