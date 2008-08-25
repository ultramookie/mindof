<?php
        include_once("header.php");
?>

<?php

include_once("db.php");
include_once("mindoflib.php");

$secret = getSecret();

if ((stripslashes(!$_POST['checksubmit'])) && ($_SESSION['secret'] == $secret) ) {
	showPownceform();
} else if ($_SESSION['secret'] == $secret) {

	$pownceCheck = stripslashes($_POST['pownceCheck']);
	$pownceUser = stripslashes($_POST['pownceUser']);
	$powncePass1 = stripslashes($_POST['powncePass1']);
	$powncePass2 = stripslashes($_POST['powncePass2']);
	$pownceAppKey = stripslashes($_POST['pownceAppKey']);
        $user = $_SESSION['user'];
        $pass  = stripslashes($_POST['pass']);

        $logincheck = checkLogin($user,$pass);

	if ( ($logincheck == 0) && ((strcmp($powncePass1,$powncePass2)) == 0) ){
  		changePownceSettings($pownceCheck,$pownceUser,$powncePass1,$pownceAppKey);
	} else {
		echo "the username and/or password you entered was wrong.  please <a href='settings.php'>try again</a>.";
	}

} else {
	echo "please <a href='login.php'>login</a> in order to change the site settings!";
}

?>
</body>
</html>
