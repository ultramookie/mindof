<?php
        include_once("header.php");
?>

<?php

include_once("db.php");
include_once("mindoflib.php");
require_once('oauth_config.php');
if (CONSUMER_KEY === '' || CONSUMER_SECRET === '') {
  echo 'You need a consumer key and secret to setup Twitter access. Get one from <a href="https://twitter.com/apps">https://twitter.com/apps</a>. Then edit the oauth_config.php file.';
  exit;
}

if ((stripslashes(!$_POST['checksubmit'])) && (checkCookie()) ) {
	showTwitterform();
} else if (checkCookie()) {

	$username = getUserName();
	$twitterCheck = stripslashes($_POST['twitterCheck']);
        $user = $username;
        $pass  = stripslashes($_POST['pass']);

        $logincheck = checkLogin($user,$pass);

	if ($logincheck == 0) {
  		updateTwitterSettings($twitterCheck);
	} else {
		echo "the username and/or password you entered was wrong.  please <a href='settings.php'>try again</a>.";
	}

} else {
	echo "please <a href='login.php'>login</a> in order to change the site settings!";
}

?>
</body>
</html>
