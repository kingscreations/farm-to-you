<?php
if(@isset($_SESSION['userId'])) {

	require_once("../php/classes/profile.php");

	require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$profile = Profile::getProfileByUserId($mysqli, $_SESSION['userId']);
	if($profile !== null) {
		$profileType = $profile->getProfileType();
	} else {
		$profileType = "u";
	}

	?>

	<li role="presentation" class="dropdown">
		<a id="drop6" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
			My Account
			<span class="caret"></span>
		</a>
		<ul id="my-account-dropdown-menu" class="dropdown-menu" role="menu" aria-labelledby="drop6">
			<li role="presentation">
				<a role="menuitem" tabindex="-1" href="<?php echo SITE_ROOT_URL . 'edit-profile/'; ?>">Settings</a>
			</li>
		<?php if($profileType === "c") { ?>
			<li role="presentation">
				<a role="menuitem" tabindex="-1" href="<?php echo SITE_ROOT_URL . 'client-order-list/'; ?>">Order history</a>
			</li>
			<li role="presentation" class="divider"></li>
		<?php } elseif($profileType === "m") { ?>
		<li role="presentation">
			<a role="menuitem" tabindex="-1" href="<?php echo SITE_ROOT_URL . 'merchant-order-list/'; ?>">Order history</a>
		</li>
		<li role="presentation" class="divider"></li>
		<?php } else {?>
			<li role="presentation" class="divider"></li>
		<?php } ?>
		<li role="presentation">
				<a id="sign-out" role="menuitem" tabindex="-1" href="<?php echo SITE_ROOT_URL . 'sign-out/'; ?>">Sign out</a>
			</li>
		</ul>
	</li>
<?php } else { ?>
	<li><a href="<?php echo SITE_ROOT_URL . 'sign-in/'; ?>">Login</span></a></li>
	<li><a href="<?php echo SITE_ROOT_URL . 'sign-up/'; ?>">Register</span></a></li>
<?php } ?>