<?php
/**
 * main-search template
 *
 * @author Florian Goussin <florian.goussin@gmail.com>
 */
?>

<div class="row-fluid clearfix">
	<form action="php/forms/search-controller.php" id="search" method="post">
		<div class="col-xs-10 col-sm-11 no-padding-right">
			<input type="text" id="inputSearch" name="inputSearch" placeholder="startrek?" />
		</div>
		<div class="col-xs-2 col-sm-1">
			<input type="hidden" value="yes" name="searching">
			<button type="submit" class="btn btn-default" name="inputSubmit" id="inputSubmit">
				<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
			</button>
		</div>
	</form>
</div><!-- end row-fluid -->
<br/>
<div class="row-fluid">
	<div class="col-sm-2 col-xs-4">
		<input type="checkbox" name="filter1" id="filter1"/>
		<label for="filter1">filter1</label>
	</div>
	<div class="col-sm-2 col-xs-4">
		<input type="checkbox" name="filter2" id="filter2"/>
		<label for="filter2">filter2</label>
	</div>
	<div class="col-sm-2 col-xs-4">
		<input type="checkbox" name="filter3" id="filter3"/>
		<label for="filter3">filter3</label>
	</div>
	<div class="col-sm-2 col-xs-4">
		<input type="checkbox" name="filter4" id="filter4"/>
		<label for="filter4">filter4</label>
	</div>
	<div class="col-sm-2 col-xs-4">
		<input type="checkbox" name="filter5" id="filter5"/>
		<label for="filter5">filter5</label>
	</div>
	<div class="col-sm-2 col-xs-4">
		<input type="checkbox" name="filter6" id="filter6"/>
		<label for="filter6">filter6</label>
	</div>
</div><!-- end row-fluid -->