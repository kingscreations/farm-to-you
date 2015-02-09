<?php
/**
 * header template
 *
 * @author Florian Goussin <florian.goussin@gmail.com>
 */
?>

<!doctype HTML>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/2.7.5/idangerous.swiper.min.css"/>
		<link rel="stylesheet" href="css/main.css"/>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script type="text/javascript" src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
		<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script src="js/iscroll.js"></script>

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
							<ul id="menu3" class="dropdown-menu" role="menu" aria-labelledby="drop6">
								<li role="presentation"><a role="menuitem" tabindex="-1" href="">Settings</a></li>
								<li role="presentation"><a role="menuitem" tabindex="-1" href="">Order history</a></li>
								<li role="presentation" class="divider"></li>
								<li role="presentation"><a role="menuitem" tabindex="-1" href="">Sign out</a></li>
							</ul>
						</li>
					</ul> <!-- end pills -->
				</div>
			</div><!-- end row-fluid main-menu -->
		</div><!-- end container-fluid -->