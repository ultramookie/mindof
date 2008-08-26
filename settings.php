<?php
        include_once("header.php");
?>

<?php

include_once("db.php");
include_once("mindoflib.php");

if ((stripslashes(!$_POST['checksubmit'])) && (checkCookie()) ) {
	showSettingsform();
} else if (checkCookie()) {

	$username = getUserName();
	$site = strip_tags($_POST['site']);
	$url = stripslashes($_POST['url']);
	$numberIndex = stripslashes($_POST['index']);
	$numberRSS = stripslashes($_POST['rss']);
        $user = $username;
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
