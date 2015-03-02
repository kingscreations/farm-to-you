<?php

$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once('../php/lib/header.php');

?>

<div class="home-top">
	<div class="home-top-search-area">
		<h1 class="heading">Delicious products and fair trades directly from the farmers</h1>
		<form class="mt30" action="../php/forms/search-controller.php" id="search" method="post">
			<div class="input-group">
				<input class="form-control search-field" type="text" id="inputSearch" name="inputSearch" placeholder="What are looking for today?" />
				<span class="input-group-btn">
				  <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span></button>
				</span>
			</div><!-- end input-group -->
		</form>
		<p class="outputArea"></p>
	</div>
</div>
<div class="home-main">

</div>

<?php require_once('../php/lib/footer.php'); ?>