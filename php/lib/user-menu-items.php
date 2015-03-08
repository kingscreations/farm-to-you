<?php if(@isset($_SESSION['userId'])) { ?>
	<li role="presentation" class="dropdown">
		<a id="drop6" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
			My Account
			<span class="caret"></span>
		</a>
		<ul id="my-account-dropdown-menu" class="dropdown-menu" role="menu" aria-labelledby="drop6">
			<li role="presentation">
				<a role="menuitem" tabindex="-1" href="<?php echo SITE_ROOT_URL . 'edit-profile'; ?>">Settings</a>
			</li>
			<li role="presentation">
				<a role="menuitem" tabindex="-1" href="<?php echo SITE_ROOT_URL . 'client-order-list'; ?>">Order history</a>
			</li>
			<li role="presentation" class="divider"></li>
			<li role="presentation">
				<a id="sign-out" role="menuitem" tabindex="-1" href="<?php echo SITE_ROOT_URL . 'sign-out'; ?>">Sign out</a>
			</li>
		</ul>
	</li>
<?php } else { ?>
	<li><a href="<?php echo SITE_ROOT_URL . 'sign-in'; ?>">Login</span></a></li>
	<li><a href="<?php echo SITE_ROOT_URL . 'sign-up'; ?>">Register</span></a></li>
<?php } ?>