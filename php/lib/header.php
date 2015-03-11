<?php
/**
 * header template
 *
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

// require CSRF and start the session
require_once("csrf.php");
session_start();

// disallow caching for debug purpose
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Get the relative path
$currentDepth = substr_count($currentDir, "/");
$rootDepth = substr_count($rootPath, "/");
$depthDifference = $currentDepth - $rootDepth;
$prefix = str_repeat("../", $depthDifference);

// paths constants
require_once($prefix . 'paths.php');

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<!-- css files -->
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo $prefix; ?>css/main.css"/>
		<link rel="stylesheet" href="<?php echo $prefix; ?>css/custom.css"/>

		<!-- IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script type="text/javascript" src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- js libraries -->
		<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
		<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/additional-methods.min.js"></script>
		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<script src="<?php echo $prefix; ?>js/validate-exact-length.js"></script>
		<script src="<?php echo $prefix; ?>js/main.js"></script>

		<!--	stripe api	-->
		<script src="https://js.stripe.com/v2/"></script>

		<title>Farm to You</title>
	</head>
	<body>
		<!-- facebook, twitter... -->
		<?php require_once('share-api.php'); ?>

		<!-- wrapper for the sticky footer -->
		<div class="wrapper">

			<!-- start of global container -->
			<div class="container-fluid" id="main-menu">
				<?php if(@isset($showSearch) && $showSearch === false) {?>
				<div class="row clearfix">
					<div class="col-sm-6 col-xs-7">
						<div id="farm-to-you-logo" class="apply-nav-height">
							<a href="<?php echo SITE_ROOT_URL ?>"><i class="fa fa-pagelines fs30"></i></a>
							<a href="<?php echo SITE_ROOT_URL ?>">farm to you</a>
						</div>
					</div>

					<div class="col-sm-6 col-xs-5">

						<!-- desktop pills -->
						<ul class="nav nav-pills hidden-xs">
							<?php

							require('cart-icon.php');
							require('user-menu-items.php');

							?>
						</ul><!-- end desktop pills -->

						<!-- mobile pills -->
						<ul class="nav nav-pills visible-xs">
							<?php require('cart-icon.php'); ?>
							<li role="presentation" class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
									<span class="glyphicon glyphicon-menu-hamburger"></span>
								</a>
								<ul class="dropdown-menu pull-right" role="menu">
									<?php

									require('user-menu-items-mobile.php');

									?>
								</ul>
						</ul><!-- end mobile pills -->
					</div>
				</div><!-- end row main-menu -->
				<?php }else{ ?>
				<div class="row clearfix">
					<div class="col-sm-4 col-xs-3 hidden-xs">
						<div id="farm-to-you-logo">

							<a href="<?php echo SITE_ROOT_URL ?>"><i class="fa fa-pagelines fs30"></i></a>
							<a href="<?php echo SITE_ROOT_URL ?>">farm to you</a>
						</div>
					</div>

					<div class="col-sm-4 col-xs-3 visible-xs">
						<div id="farm-to-you-logo">
							<a href="<?php echo SITE_ROOT_URL ?>"><i class="fa fa-pagelines fs30"></i>
							</a>
						</div>
					</div>


					<div class="col-sm-4 col-xs-6 mrn1 mln1">
						<form  action="../php/forms/search-controller.php" id="search" method="post">
							<?php echo generateInputTags(); ?>
							<div class="input-group">
								<input class="form-control search-field" type="text" id="inputSearch" name="inputSearch" placeholder="" />
								<input type="hidden" value="yes" name="searching">
						<span class="input-group-btn">
						  <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span></button>
						</span>
							</div>
						</form>
					</div>

					<div class="col-sm-4 col-xs-3 no-padding-right pl0">

						<!-- desktop pills -->
						<ul class="nav nav-pills hidden-xs">
							<?php

							require('cart-icon.php');
							require('user-menu-items.php');

							?>
						</ul><!-- end desktop pills -->

						<!-- mobile pills -->
						<ul class="nav nav-pills visible-xs">
							<?php require('cart-icon.php'); ?>
							<li role="presentation" class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
									<span class="glyphicon glyphicon-menu-hamburger"></span>
								</a>
								<ul class="dropdown-menu pull-right" role="menu">
									<?php

									require('user-menu-items-mobile.php');

									?>
								</ul>
						</ul><!-- end mobile pills -->
					</div>
				</div><!-- end row main-menu -->
				<?php } ?>
			</div><!-- end container-fluid -->