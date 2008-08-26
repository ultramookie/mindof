<?php
        include_once("header.php");
?>

<?php

include_once("db.php");
include_once("mindoflib.php");

if ((stripslashes(!$_POST['checksubmit'])) && (checkCookie()) ) {
	showTwitterform();
} else if (checkCookie()) {

	$username = getUserName();
	$twitterCheck = stripslashes($_POST['twitterCheck']);
	$twitterEmail = stripslashes($_POST['twitterEmail']);
	$twitterPass1 = stripslashes($_POST['twitterPass1']);
	$twitterPass2 = stripslashes($_POST['twitterPass2']);
        $user = $username;
        $pass  = stripslashes($_POST['pass']);

        $logincheck = checkLogin($user,$pass);

	if ( ($logincheck == 0) && ((strcmp($twitterPass1,$twitterPass2)) == 0) ){
  		changeTwitterSettings($twitterCheck,$twitterEmail,$twitterPass1);
	} else {
		echo "the username and/or password you entered was wrong.  please <a href='settings.php'>try again</a>.";
	}

} else {
	echo "please <a href='login.php'>login</a> in order to change the site settings!";
}

?>
</body>
</html>
