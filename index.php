<?php
	include_once("header.php");
?>

<?php
	$numEntries = getIndexNum();
	$cookie = $_COOKIE['mindof'];
	$storedcookie = getCookie();
	$twitter_update = gettwitterCheck();
	$twitter_email = gettwitterEmail();

        if(checkCookie()) {
		showUpdateForm();
        }

        if( (checkCookie()) && ((stripslashes($_POST['checksubmit']))) ) {
		$update = strip_tags($_POST['update']);
		addEntry($update);
		if ( ($twitter_update == 1) && (strlen($twitter_email) > 0) ) {
			$twit_update = stripslashes($_POST['update']);
			updateTwitter($update);
		}
		echo " <img src=\"icon_accept.gif\" border=\"0\" /> mindof updated. ";
        }

	showEntriesIndex($numEntries);

	echo "<a href=\"" . $siteUrl  . "archive.php?pagenum=2\" class=\"box\">older &#187;</a>";
?>

<?php
	include_once("footer.php");
?>

