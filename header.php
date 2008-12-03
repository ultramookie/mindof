<?php 
include_once("db.php");
include_once("mindoflib.php");
?>
<html>
<head>
<title><? echo "$sitename"; ?> </title>
<link rel="stylesheet" type="text/css" media="screen" href="<? echo "$siteurl"; ?>/style.css"/>

<!-- Character Counting -->
<script type="text/javascript" src="count.js"></script> 

</head>
<body>
<div class="main">
<h2 class="title"><b><a href="<? echo "$siteurl"; ?>" class="title"><? echo "$sitename"; ?></a></b></h2>
<p class="menu">
<?php
	if(checkCookie()) {
		$username = getUserName();
		echo "<a href=\"usermod.php\" class=\"menu\">" . $username . "</a> | updates: " . $numOfEntries . " | <a href=\"settings.php\" class=\"menu\">site admin</a> | <a href=\"rss.php\" class=\"menu\">rss</a> | <a href=\"logout.php\" class=\"menu\">logout</a>";
	} else {
		echo "updates: " . $numOfEntries . " | <a href=\"login.php\" class=\"menu\">login</a> | <a href=\"rss.php\" class=\"menu\">rss</a>";
	}
?>
</p>
