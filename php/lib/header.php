<?php
/**
 * header template
 *
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

// require CSRF and start the session
require_once("csrf.php");
session_start();

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

		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<!--		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/2.7.5/idangerous.swiper.min.css"/>-->
		<link rel="stylesheet" href="<?php echo $prefix; ?>css/main.css"/>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script type="text/javascript" src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- google maps -->
		<script src="https://maps.googleapis.com/maps/api/js"></script>
		<script src="<?php echo $prefix; ?>js/google-maps.js"></script>

		<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
		<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/additional-methods.min.js"></script>
		<script src="<?php echo $prefix; ?>js/validate-exact-length.js"></script>
		<script src="<?php echo $prefix; ?>js/iscroll.js"></script>
		<script src="<?php echo $prefix; ?>js/add-profile.js"></script>
		<script src="<?php echo $prefix; ?>js/product.js"></script>
		<script src="<?php echo $prefix; ?>js/cart.js"></script>
		<script src="<?php echo $prefix; ?>js/sign-up.js"></script>
		<script src="<?php echo $prefix; ?>js/checkout-pickup.js"></script>
		<script src="https://js.stripe.com/v2/"></script><!--	stripe api	-->
		<script src="<?php echo $prefix; ?>js/checkout.js"></script>
		<script src="<?php echo $prefix; ?>js/store.js"></script>
		<script src="<?php echo $prefix; ?>js/search.js>"</script>
		<script src="<?php echo $prefix; ?>js/main.js"></script>

		<!-- Latest compiled and minified Bootstrap JavaScript, all compiled plugins included -->
		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

		<title>Farm To You</title>
	</head>
	<body>
		<?php require_once('share-api.php'); ?>

		<!-- wrapper for the sticky footer -->
		<div class="wrapper">


			<!-- start of global container -->
			<div class="container-fluid" id="main-menu">
				<?php if(@isset($showSearch) && $showSearch === false) {?>
				<div class="row clearfix">
					<div class="col-sm-6 col-xs-8">
						<div id="farm-to-you-logo" class="apply-nav-height">
							<a href="<?php echo SITE_ROOT_URL ?>">Farm to You</a>
						</div>
					</div>

					<div class="col-sm-6 col-xs-4">

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
								<ul class="dropdown-menu" role="menu">
									<?php

									require('user-menu-items-mobile.php');

									?>
								</ul>
						</ul><!-- end mobile pills -->
					</div>
				</div><!-- end row main-menu -->
				<?php }else{ ?>
				<div class="row clearfix">
					<div class="col-sm-4 col-xs-4">
						<div id="farm-to-you-logo" class="apply-nav-height">
							<a href="<?php echo SITE_ROOT_URL ?>">Farm to You</a>
						</div>
					</div>

					<div class="col-sm-4 col-xs-5">
						<form  action="../php/forms/search-controller.php" id="search" method="post">
							<div class="input-group">
								<input class="form-control search-field" type="text" id="inputSearch" name="inputSearch" placeholder="What are you looking for today?" />
								<input type="hidden" value="yes" name="searching">
						<span class="input-group-btn">
						  <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span></button>
						</span>
							</div>
						</form>
					</div>

					<div class="col-sm-4 col-xs-3">

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
								<ul class="dropdown-menu" role="menu">
									<?php

									require('user-menu-items.php');

									?>
								</ul>
						</ul><!-- end mobile pills -->
					</div>
				</div><!-- end row main-menu -->
				<?php } ?>
			</div><!-- end container-fluid -->