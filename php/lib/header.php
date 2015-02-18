<?php
/**
 * header template
 *
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

// Get the relative path
$currentDepth = substr_count($currentDir, "/");
$rootDepth = substr_count($rootPath, "/");
$depthDifference = $currentDepth - $rootDepth;
$prefix = str_repeat("../", $depthDifference);

?>
<!doctype HTML>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/2.7.5/idangerous.swiper.min.css"/>
		<link rel="stylesheet" href="<?php echo $prefix; ?>css/main.css"/>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script type="text/javascript" src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->
		<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
		<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/additional-methods.min.js"></script>
		<script>

			/**
			 * add exactlength to check the exact length of a field
			 */
			$.validator.addMethod("exactlength",
				function(value, element, param) {
					return this.optional(element) || value.length == param;
				},
				$.validator.format("Please enter exactly {0} characters.")
			);

		</script>
		<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script src="<?php echo $prefix; ?>js/iscroll.js"></script>
		<script src="<?php echo $prefix; ?>js/add-profile.js"></script>
		<script src="<?php echo $prefix; ?>js/cart.js"></script>
		<script src="<?php echo $prefix; ?>js/sign-up.js"></script>
		<script src="<?php echo $prefix; ?>js/store.js"></script>
		<script src="<?php echo $prefix; ?>js/checkout-shipping.js"></script>
		<script src="https://js.stripe.com/v2/"></script><!--	stripe api	-->
		<script src="<?php echo $prefix; ?>js/checkout.js"></script>
		<script src="<?php echo $prefix; ?>js/main.js"></script>

		<!-- Latest compiled and minified Bootstrap JavaScript, all compiled plugins included -->
		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

		<title>Farm To You</title>
	</head>
	<body>
		<!-- start of global container -->
		<div class="container-fluid">
			<div class="row-fluid clearfix" id="main-menu">
				<div class="col-xs-6">
					<h1 class="no-margin">farm-to-you</h1>
				</div>
				<div class="col-xs-6">
					<ul class="nav nav-pills" role="tablist">
						<li role="presentation" class="dropdown">
							<a id="drop6" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
								My Account
								<span class="caret"></span>
							</a>
							<ul id="my-account-dropdown-menu" class="dropdown-menu" role="menu" aria-labelledby="drop6">
								<li role="presentation"><a role="menuitem" tabindex="-1" href="">Settings</a></li>
								<li role="presentation"><a role="menuitem" tabindex="-1" href="">Order history</a></li>
								<li role="presentation" class="divider"></li>
								<li role="presentation"><a role="menuitem" tabindex="-1" href="">Sign out</a></li>
							</ul>
						</li>
					</ul> <!-- end pills -->
				</div>
			</div><!-- end row-fluid main-menu -->