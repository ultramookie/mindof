<?php

include_once("header.php");
include_once("db.php");
include_once("mindoflib.php");

$tempid = $_GET['number'];

if (preg_match('/^[0-9]+$/',$tempid)) {
	$id = $tempid;
} else {
	$id = 1;
}

printEntry($id);

?>

<?php
	include_once("footer.php");
?>

