
<?php if(@isset($_SESSION['products'])) { ?>
	<li id="cart-main-menu-item">
		<a href="<?php echo SITE_ROOT_URL . 'cart/' ?>">
			<span class="glyphicon glyphicon-shopping-cart"></span>
			<small>Cart</small>
			<span class="count"><?php echo count($_SESSION['products']); ?></span>
		</a>
	</li>
<?php } else { ?>
	<li id="cart-main-menu-item">
		<a href="<?php echo SITE_ROOT_URL . 'cart/' ?>">
			<span class="glyphicon glyphicon-shopping-cart"></span>
			<small>Cart</small>
		</a>
	</li>
<?php } ?>