<?php

include_once("header.php");
include_once("db.php");
include_once("mindoflib.php");

if (!(stripslashes($_POST['checksubmit']))) {
	showLoginform();
} else {
        $user = stripslashes($_POST['user']);
        $pass  = stripslashes($_POST['pass']);

	$logincheck = checkLogin($user,$pass);

	if ($logincheck == 0) {
		setLoginCookie($user);
		echo "thanks for logging in $user!<br /><b>return to <a href='$siteurl'>$sitename</a></b>.";
	} else {
		echo "login failed.  try again.";
	}
}

?>

<?php
	include_once("footer.php");
?>

