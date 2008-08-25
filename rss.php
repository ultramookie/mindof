<?php

include_once("db.php");
include_once("mindoflib.php");

$numRss = getRssNum();

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";

?>

<rss version="2.0">
  <channel>
	<title><?php echo $sitename; ?></title>
	<link><?php echo $siteurl; ?></link>
	<description><?php echo $sitename; ?></description>
	<generator>mindof <?php echo $version; ?></generator>
	<ttl>5</ttl>
<?php
	printRSS($numRss);
?>

  </channel>
</rss>

