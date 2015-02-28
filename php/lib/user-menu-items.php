
<?php if(@isset($_SESSION['user'])) { ?>
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
<?php } else { ?>
	<li><a href="#">Login</span></a></li>
	<li><a href="#">Register</span></a></li>
	<li><a href="#">Become a merchant</span></a></li>
<?php } ?>