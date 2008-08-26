<?php

include_once("header.php");
include_once("db.php");
include_once("mindoflib.php");

$id = $_GET['number'];

if (!(stripslashes($_POST['checksubmit'])) &&  (checkCookie())) {
        showDelform($id);
} else if ( (stripslashes($_POST['checksubmit'])) && (checkCookie()) ) {
	deleteEntry( stripslashes($_POST['id']));
} else {
        echo "please <a href='login.php'>login</a> in order to delete entries!";
}	
?>

<?php
	include_once("footer.php");
?>

