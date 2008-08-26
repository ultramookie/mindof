<?php
	include_once("header.php");
?>

<?php

	$numEntries = getIndexNum();

        if (!$_GET['pagenum']) {
                $pagenum = 1;
        } else {
                $pagenum = $_GET['pagenum'];
        }

	showEntriesArchive($numEntries,$pagenum);

	$pagenum++;

	echo "<a href=\"" . $_SERVER['PHP_SELF'] . "?pagenum=" . $pagenum . "\" class=\"box\">older &#187;</a>";
?>

<?php
	include_once("footer.php");
?>

