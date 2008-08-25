<?php
	include_once("header.php");
?>

<?php
	$numEntries = getIndexNum();
	$secret = getSecret();
	$twitter_update = gettwitterCheck();
	$twitter_email = gettwitterEmail();
	$pownce_user = getpownceUser();
	$pownce_update = getpownceCheck();

        if($_SESSION['user']) {
		showUpdateForm();
		$userloggedin = 1;
        }

        if( ($_SESSION['user']) && ($_SESSION['secret'] == $secret) && ((stripslashes($_POST['checksubmit']))) ) {
		$update = strip_tags($_POST['update']);
		addEntry($update);
		if ( ($twitter_update == 1) && (strlen($twitter_email) > 0) ) {
			$twit_update = stripslashes($_POST['update']);
			updateTwitter($update);
		}
		if ( ($pownce_update == 1) && (strlen($pownce_user) > 0) ) {
			$pownce_update = stripslashes($_POST['update']);
			updatePownce($update);
		}
		echo "mindof updated.";
        }

	showEntriesIndex($numEntries,$_SESSION['secret']);

	echo "<a href=\"" . $siteUrl  . "archive.php?pagenum=2\" class=\"box\">older &#187;</a>";
?>

<?php
	include_once("footer.php");
?>

