<?php
header('Content-type: application/rss+xml');

include_once("db.php");
include_once("mindoflib.php");

$numRss = getRssNum();

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";

?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:atom="http://www.w3.org/2005/Atom"
>
  <channel>
	<title><?php echo $sitename; ?></title>
	<link><?php echo $siteurl; ?></link>
	<description><?php echo $sitename; ?></description>
	<generator>mindof <?php echo $version; ?></generator>
	<atom:link href="<?php echo $siteurl; ?>/rss.php" rel="self" type="application/rss+xml" />
	<ttl>5</ttl>
<?php
	printRSS($numRss);
?>

  </channel>
</rss>

