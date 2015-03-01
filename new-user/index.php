<?php
/**
 * new-user template
 *
 * @author Florian Goussin <florian.goussin@gmail.com>
 */
?>

<div class="container-fluid">
	<div id="main-search">
		<div class="vertical-spacer-60"></div>
		<div class="row clearfix">
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
			<p class="outputArea"></p>
		</div><!-- end row -->
		<br/>
		<div class="row">
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
		</div><!-- end row -->
		<br/>
	</div>

	<div class="row">
		<h2>Farmers in your area</h2>
		<div id="wrapper">
			<div id="scroller">
				<ul>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-01.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-02.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-03.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-04.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-05.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-01.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-02.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-03.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-04.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-05.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-01.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-02.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-03.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-04.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-05.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-01.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-02.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-03.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-04.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-05.jpg" alt="some star trek image placeholder"/>
					</li>
				</ul>
			</div>
		</div>
	</div><!-- end row -->
	<br/>
	<div class="row">
		<h2>Highest rated farmers</h2>
		<div id="wrapper">
			<div id="scroller">
				<ul>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-05.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/starwars-intrude.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-02.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-03.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-04.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-05.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-01.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-02.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-03.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-04.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-05.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-01.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-02.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-03.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-04.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-05.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-01.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-02.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-03.jpg" alt="some star trek image placeholder"/>
					</li>
					<li>
						<img src="images/star-trek-to-you/star-trek-next-generation-04.jpg" alt="some star trek image placeholder"/>
					</li>
				</ul>
			</div>
		</div>
	</div><!-- end row -->
	<br/><br/>
	<div class="row">
		<div class="col-sm-2 col-xs-4">
			<a href="">category</a>
		</div>
		<div class="col-sm-2 col-xs-4">
			<a href="">category</a>
		</div>
		<div class="col-sm-2 col-xs-4">
			<a href="">category</a>
		</div>
		<div class="col-sm-2 col-xs-4">
			<a href="">category</a>
		</div>
		<div class="col-sm-2 col-xs-4">
			<a href="">category</a>
		</div>
		<div class="col-sm-2 col-xs-4">
			<a href="">category</a>
		</div>
	</div><!-- end row -->
</div><!-- end container-fluid -->

<br/><br/><br/>