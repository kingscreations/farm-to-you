<?php

$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once('../php/lib/header.php');

?>

<div class="home-top">
	<div class="home-top-search-area">
		<h1>Delicious products and fair trades directly with the farmers</h1>
		<form action="php/forms/search-controller.php" id="search" method="post">
			<div class="col-xs-10 col-sm-11 no-padding-right">
				<input type="text" id="inputSearch" name="inputSearch" placeholder="What are looking for today?" />
			</div>
			<div class="col-xs-2 col-sm-1">
				<input type="hidden" value="yes" name="searching">
				<button type="submit" class="btn btn-default" name="inputSubmit" id="inputSubmit">
					<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
				</button>
			</div>
		</form>
		<p class="outputArea"></p>
	</div>
</div>
<div class="home-main">

</div>

<?php require_once('../php/lib/footer.php'); ?>