<?php
/**
 * generates a random token by hashing a random number
 *
 * @return string random token
 **/
function generateToken() {
	$token = hash("sha512", mt_rand(0, mt_getrandmax()));
	return($token);
}

/**
 * generates hidden form tags for inclusion in a CSRF resistant form
 *
 * @return string hidden input tags
 * @throws RunTimeException if there's no session to store the CSRF data in
 **/
function generateInputTags() {
	// make sure there's a session to write to
	if(session_status() !== PHP_SESSION_ACTIVE) {
		throw(new RuntimeException("Unable to generate form: session inactive"));
	}

	// randomize the form name generate the token and form inputs
	$name  = "csrfName" . mt_rand(0, mt_getrandmax());
	$token = generateToken();
	$tags  = "<input name=\"csrfName\"  type=\"hidden\" value=\"$name\" />\n"
		. "<input name=\"csrfToken\" type=\"hidden\" value=\"$token\" />\n";

	// save the token to the session
	$_SESSION[$name] = $token;

	return($tags);
}

/**
 * verifies the CSRF token for the given form name
 *
 * @param string $name form name
 * @param string $sentToken token sent to verify
 * @return bool true if the token verified, false if not
 * @throws RunTimeException if there's no session to verify the CSRF data in
 **/
function verifyCsrf($name, $sentToken)
{
	// make sure there's a session to write to
	if(session_status() !== PHP_SESSION_ACTIVE) {
		throw(new RuntimeException("Unable to generate form: session inactive"));
	}

	// make sure there's a token to verify
	if(isset($_SESSION[$name]) === false)
	{
		throw(new RuntimeException("Unable to verify CSRF token: form name does not exist"));
	}

	// compare the sent token and session token
	$verified = false;
	$token	= $_SESSION[$name];
	if($token === $sentToken)
	{
		$verified = true;
	}

	// delete the session token and return the results
	unset($_SESSION[$name]);
	return($verified);
}
?>