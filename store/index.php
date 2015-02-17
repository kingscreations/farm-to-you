<?php
session_start();
$currentDir = dirname(__FILE__);

require_once('../dummy-session.php');
require_once '../root-path.php';
require_once '../php/lib/header.php';

?>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/2.7.5/idangerous.swiper.min.css"/>
<link rel="stylesheet" href="../css/main.css"/>
<div class="row-fluid">
	<div class="col-sm-12">
		<h2>Add Store</h2>
			<form class="form-inline" id="storeController" method="post" action="../php/forms/store-controller.php" onsubmit="button()">
				<div class="form-group">
					<label for="storeName">Store Name</label>
					<input type="text" id="storeName" name="storeName">
				</div>
					<br>
				<div class="form-group">
					<label for="storeDescription">Store Description</label>
					<input type="text" id="storeDescription" name="storeDescription">
				</div>
				<br>

				<div class="form-group">
					<label for="InputImage">Store Image</label>
					<input type="file" id="InputImage" name="InputImage">
				</div>
				<br>
	<br>
				<button type="submit">Submit</button>
				<br>
			</form>
			<p id="outputArea"></p>
	</div>
</div><!-- end row-fluid -->

<?php require_once "../php/lib/footer.php"; ?>