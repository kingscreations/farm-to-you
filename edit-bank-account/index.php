<?php
$currentDir = dirname(__FILE__);
require_once ("../root-path.php");

session_start();

if($_SESSION['profileId'] === null) {
	header('Location: ../sign-in/index.php');
}

session_abort();

require_once("../php/lib/header.php");



require_once("../php/classes/profile.php");
require_once '/etc/apache2/capstone-mysql/encrypted-config.php';

$profileId = $_SESSION['profileId'];

mysqli_report(MYSQLI_REPORT_STRICT);
$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

//$profileFromUser = Profile::getProfileByUserId($mysqli,$_SESSION['user']['id']);
//$profileId = $profileFromUser->getProfileId();

$profile = Profile::getProfileByProfileId($mysqli, $profileId);

$profileFirstname = $profile->getFirstName();
$profileLastname = $profile->getLastName();
$profilePhone = $profile->getPhone();
$profileImage = $profile->getImagePath();
$profileType = $profile->getProfileType();
$profileToken = $profile->getCustomerToken();


if($profileType !== "m") {
	echo 'YOU HAVE NO POWER HERE!';
	exit();
}

?>

	<div class="container-fluid container-margin-sm transparent-form user-account">
		<div class="row">
			<div id="multi-menu" class="col-md-3 hidden-xs">
				<ul class="nav nav-pills nav-stacked">
					<li><a href="../edit-profile/index.php">Edit Profile</a></li>
					<li><a href="../add-store/index.php">Manage Stores</a></li>
					<li><a href="../merchant-order-list/index.php">List of Orders</a></li>
					<li class="active"><a href="../bank-account/index.php">Bank Account</a></li>
				</ul>
			</div>

			<div class="dropdown visible-xs" style="position:relative">
				<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Menu<span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="../edit-profile/index.php">Edit Profile</a></li>
					<li><a href="../add-store/index.php">Manage Stores</a></li>
					<li><a href="../merchant-order-list/index.php">List of Orders</a></li>
					<li class="active"><a href="../bank-account/index.php">Bank Account</a></li>
				</ul>
			</div>


			<div class="col-md-9">
				<form id="bankInfo" class="form-inline" method="post" action="../php/forms/bank-account-controller.php" enctype="multipart/form-data">
					<?php echo generateInputTags(); ?>
					<div class="center">
						<h2>Edit Bank Account</h2>
					</div>

					<div class="form-group">
						<label for="inputName">Name:</label>
						<input type="text" class="form-control" value="<?php echo $profileFirstname . ' ' . $profileLastname;?>">
					</div>

					<br>

					<div class="form-group">
						<label for="routing-number">Routing Number:</label>
						<input id="routing-number" name="routing-nmb" type="text" class="form-control" data-stripe="routing_number">
					</div>

					<br>

					<div class="form-group">
						<label for="bank-account">Bank Account Number:</label>
						<input id="bank-account" name="bank-acct" type="text" class="form-control" data-stripe="account_number">
					</div>

					<br>

					<div class="form-group">
						<select name="country" class="form-control option-menu">
							<option value="">Select Country...</option>
							<option value="US" data-stripe="country">United States</option>
						</select>
					</div>

					<br>

					<div class="form-group">
						<input type="submit" class="form-control" value="Submit">
					</div>

				</form>
				<p id="outputArea"></p>
			</div>
		</div>
	</div>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript" src="../js/edit-bank-account.js"></script>

<?php require_once('../php/lib/footer.php'); ?>