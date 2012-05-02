<?php 
include_once("db.php");
include_once("mindoflib.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title><? echo "$sitename"; ?> </title>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.5.0/build/cssreset/cssreset-min.css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.5.0/build/cssfonts/cssfonts-min.css">
<link rel="stylesheet" type="text/css" media="screen" href="<? echo "$siteurl"; ?>/style.css" />
<link rel="alternate" type="application/rss+xml" title="<?php echo "$sitename"; ?> (RSS 2.0)" href="<?php echo "$siteurl"; ?>/rss.php"  />
<meta name="generator" content="mindof <?php echo "$version"; ?>" />
<meta name="keywords" content="microblog, steve kong, steven kong, steve mookie kong, mookie, ultramookie, unix, unix geek, linux, geek, programmer, sarcastic, funny, engineer, yahoo, sony, mookiekong" /> 
<meta name="description" content="mookie's place microblog: bits of mookie's mind." /> 
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=1">

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
		echo "<a href=\"usermod.php\">" . $username . "</a> | updates: " . $numOfEntries . " | <a href=\"settings.php\">site admin</a> | <a href=\"rss.php\">rss</a> | <a href=\"logout.php\">logout</a>";
	} else {
		echo "updates: " . $numOfEntries . " | <a href=\"login.php\">login</a> | <a href=\"rss.php\">rss</a>";
	}
?>
</p>
