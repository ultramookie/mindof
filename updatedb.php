<?php
include_once("header.php");
include_once("db.php");

$sitename = getSiteName();

$query = "show colums from user";
$result = mysql_query($query);

$cookieExists = 0;

while ($row = mysql_fetch_array($result)) {
        if ((strcmp($row['Field'],"cookie")) == 0) {
                $cookieExists = 1;
        }
}

if ($cookieExists == 0) {
        $query = "alter table user add cookie varchar(300)";
        $status = mysql_query($query);
        echo "cookie column added<br />";
} else {
        echo "cookie column already exists!<br />";
}


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

if ($twitterEmailexists == 0) {
	$query = "alter table site add twitterEmail varchar(50)";
	$status = mysql_query($query);
	echo "twitterEmail column added<br />";
} else {
	echo "twitterEmail column already exists!<br />";
}

if ($twitterPassexists == 0) {
	$query = "alter table site add twitterPass varchar(50)";
	$status = mysql_query($query);
	echo "twitterPass column added<br />";
} else {
	echo "twitterPass column already exists!<br />";
}

echo "your db has been updated!";

include_once("footer.php");
?>
