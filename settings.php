<?php
        include_once("header.php");
?>

<?php

include_once("db.php");
include_once("mindoflib.php");

$secret = getSecret();

if ((stripslashes(!$_POST['checksubmit'])) && ($_SESSION['secret'] == $secret) ) {
	showSettingsform();
} else if ($_SESSION['secret'] == $secret) {

	$site = strip_tags($_POST['site']);
	$url = stripslashes($_POST['url']);
	$numberIndex = stripslashes($_POST['index']);
	$numberRSS = stripslashes($_POST['rss']);
        $user = $_SESSION['user'];
        $pass  = stripslashes($_POST['pass']);

        $logincheck = checkLogin($user,$pass);

	if ($logincheck == 0) {
  		changeSettings($site,$url,$numberIndex,$numberRSS);
	} else {
		echo "the username and/or password you entered was wrong.  please <a href='settings.php'>try again</a>.";
	}

} else {
	echo "please <a href='login.php'>login</a> in order to change the site settings!";
}

?>
</body>
</html>
