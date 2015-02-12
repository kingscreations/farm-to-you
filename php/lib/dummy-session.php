<?php

function randomString($length = 6) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

try {
	mysqli_report(MYSQLI_REPORT_STRICT);

	// get the credentials information from the server
	$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
	$configArray = readConfig($configFile);

	// connection
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$user = new User(null, randomString(6) . '@' . randomString(8) . '.' . randomString(3),
		bin2hex(openssl_random_pseudo_bytes(64)),
		bin2hex(openssl_random_pseudo_bytes(16)),
		bin2hex(openssl_random_pseudo_bytes(8)));
	$user->insert($mysqli);

	$_SESSION['users'] = array(
		'id' => $user->getUserId()
	);

	$profile = new Profile(null, randomString(5), randomString(7), 'm', randomString(1), randomString(19), $user->getUserId());
	$profile->insert($mysqli);

	$_SESSION['profiles'] = array(
		'id' => $profile->getProfileId()
	);

	$mysqli->close();
	echo "success to connect to the database";

} catch(Exception $exception) {
	echo "fail to connect to the database";
}