<?php
include_once("header.php");
include_once("db.php");

$sitename = getSiteName();

$query = "show columns from site";
$result = mysql_query($query);

$indexNumexists = 0;
$rssNumexists = 0;
$twitterCheckexists = 0;
$twitterEmailexists = 0;
$twitterPassexists = 0;

while ($row = mysql_fetch_array($result)) {
	if ((strcmp($row['Field'],"indexNum")) == 0) {
		$indexNumexists = 1;
	}
	if ((strcmp($row['Field'],"rssNum")) == 0) {
		$rssNumexists = 1;
	}
	if ((strcmp($row['Field'],"twitterCheck")) == 0) {
		$twitterCheckexists = 1;
	}
	if ((strcmp($row['Field'],"twitterEmail")) == 0) {
		$twitterEmailexists = 1;
	}
	if ((strcmp($row['Field'],"twitterPass")) == 0) {
		$twitterPassexists = 1;
	}
	if ((strcmp($row['Field'],"pownceCheck")) == 0) {
		$pownceCheckexists = 1;
	}
	if ((strcmp($row['Field'],"pownceUser")) == 0) {
		$pownceUserexists = 1;
	}
	if ((strcmp($row['Field'],"powncePass")) == 0) {
		$powncePassexists = 1;
	}
	if ((strcmp($row['Field'],"pownceAppKey")) == 0) {
		$pownceAppKeyexists = 1;
	}
}

if ($indexNumexists == 0) {
	$query = "alter table site add indexNum int NOT NULL";
	$status = mysql_query($query);
	$query = "update site set indexNum='10' where name like '$sitename'";
	$status = mysql_query($query);
	echo "indexNum column added<br />";
} else {
	echo "indexNum column already exists!<br />";
}

if ($rssNumexists == 0) {
	$query = "alter table site add rssNum int NOT NULL";
	$status = mysql_query($query);
	$query = "update site set rssNum=10 where name like '$sitename'";
	$status = mysql_query($query);
	echo "rssNum column added<br />";
} else {
	echo "rssNum column already exists!<br />";
}

if ($twitterCheckexists == 0) {
	$query = "alter table site add twitterCheck int NOT NULL";
	$status = mysql_query($query);
	$query = "update site set twitterCheck=0 where name like '$sitename'";
	$status = mysql_query($query);
	echo "twitterCheck column added<br />";
} else {
	echo "twitterCheck column already exists!<br />";
}

if ($pownceCheckexists == 0) {
	$query = "alter table site add pownceCheck int NOT NULL";
	$status = mysql_query($query);
	$query = "update site set pownceCheck=0 where name like '$sitename'";
	$status = mysql_query($query);
	echo "pownceCheck column added<br />";
} else {
	echo "pownceCheck column already exists!<br />";
}

if ($twitterEmailexists == 0) {
	$query = "alter table site add twitterEmail varchar(50)";
	$status = mysql_query($query);
	echo "twitterEmail column added<br />";
} else {
	echo "twitterEmail column already exists!<br />";
}

if ($pownceUserexists == 0) {
	$query = "alter table site add pownceUser varchar(50)";
	$status = mysql_query($query);
	echo "pownceUser column added<br />";
} else {
	echo "pownceUser column already exists!<br />";
}

if ($twitterPassexists == 0) {
	$query = "alter table site add twitterPass varchar(50)";
	$status = mysql_query($query);
	echo "twitterPass column added<br />";
} else {
	echo "twitterPass column already exists!<br />";
}

if ($powncePassexists == 0) {
	$query = "alter table site add powncePass varchar(50)";
	$status = mysql_query($query);
	echo "powncePass column added<br />";
} else {
	echo "powncePass column already exists!<br />";
}

if ($pownceAppKeyexists == 0) {
	$query = "alter table site add pownceAppKey varchar(50)";
	$status = mysql_query($query);
	echo "pownceAppKey column added<br />";
} else {
	echo "pownceAppKey column already exists!<br />";
}

echo "your db has been updated!";

include_once("footer.php");
?>
