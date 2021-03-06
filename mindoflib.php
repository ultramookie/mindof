<?php

// mindof main library
// steve "mookie" kong
// http://mindof-project.org
//
// licensed under gplv3
// http://www.gnu.org/licenses/gpl-3.0.html

error_reporting(E_ERROR | E_PARSE);

require_once('twitteroauth/twitteroauth.php');
require_once('oauth_config.php');

$sitename = getSiteName();
$siteurl = getSiteUrl();
$indexNum = getIndexNum();
$rssNum = getRssNum();
$numOfEntries = getNumEntries();

function showUpdateForm() {
	echo "<div id=\"txtmsg\" class=\"count\">&nbsp;</div>";
        echo "<form action=\"";
        echo $_SERVER['PHP_SELF'];
        echo "\"";
        echo " method=\"post\">";
	echo "<textarea cols=\"40\" rows=\"4\"  onkeyup=\"keyup(this)\" name=\"update\"></textarea>";
        echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<br />";
        echo "<input type=\"submit\" name=\"submit\" value=\"update\" id=\"submitbutton1\">";
        echo "</form>";
}

function addEntry($update) {
	$update = mysql_real_escape_string($update);

	$query = "insert into main (entry,entrytime) values ('$update',NOW())";
	$status = mysql_query($query);
}

function updateTwitter($status) {

	$oauth_token = gettwitterEmail();
	$oauth_token_secret = gettwitterPass();

	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
	$connection->format = 'xml';
	$connection->decode_json = FALSE;
	$connection->post('statuses/update', array('status' => "$status"));
	// check that everything went OK.
	// twitter returns a long string that contains the update when things
	// are ok.
	$result = $connection->response;
	if (eregi("Could not authenticate you",$result)) {
		echo " <img src=\"action_stop.gif\" border=\"0\" /> twitter error: " . $result . "<br />";
	} else {
		echo " <img src=\"icon_accept.gif\" border=\"0\" /> twitter updated. ";
	}
}

function showEntriesIndex($num) {

        $query = "select id from main order by entrytime desc limit $num";
        $result = mysql_query($query);

        while ($row = mysql_fetch_array($result)) {
		printEntry($row['id']);
        }
}

function showEntriesArchive($num,$pnum) {

        if($pnum == 1) {
                $offset = 1;
        } else {
                $offset = ($pnum-1) * $num;
        }

        $query = "select id from main order by entrytime desc limit $offset,$num";
        $result = mysql_query($query);

        while ($row = mysql_fetch_array($result)) {
		printEntry($row['id']);
        }
}


function printEntry($id) {
       
	$query = "select entry,date_format(entrytime, '%b %e, %Y | %h:%i %p') as date from main where id = '$id'";
        $result = mysql_query($query);
        $row = mysql_fetch_array($result);

	if (ereg(".*http.*",$row['entry'])) {
		$text = makeLinks($row['entry']);
	} else {
		$text = $row['entry'];
	}

        echo "<p class=\"entry\">" . $text . " </p>";
	echo "<p class=\"timedate\"><a href=\"entry.php?number=" . $id . "\">" . $row['date'] . "</a>";
	if(checkCookie()) {
		echo " | <a href=\"delete.php?number=" . $id ."\">delete</a> ";
	}
	echo "</p><hr />";
}

function makeYouTube($in_url) {

	list($blah,$args) = split("\?",$in_url,2);

	if ($args) {
		$argsList = split("\&",$args);
		$num = count($argsList);
		for($i=0;$i<=$num;$i++) {
			list($key,$value) = split("=",$argsList[$i]);
			$$key = $value;
		}
		if ($v) {
			$youtube = "<object width=\"425\" height=\"344\"><param name=\"movie\" value=\"http://www.youtube.com/v/$v&hl=en&fs=1\"></param><param name=\"allowFullScreen\" value=\"true\"></param><embed src=\"http://www.youtube.com/v/$v&hl=en&fs=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" width=\"425\" height=\"344\"></embed></object>";
		} else {
		$youtube = "<a href=\"$youtube_url\">$youtube_url</a>";
		}
	}

	return ($youtube);
}

function makeFlickr($in_url) {

	$appkey = "260422cecc98a0ef5233856d6b7ffc05";

	list($http,$blah,$base,$photos,$user,$photoid) = split("/",$in_url,7);

	if ($photoid) {
		$url = "http://api.flickr.com/services/rest/";

		$session = curl_init();
		curl_setopt ( $session, CURLOPT_URL, $url );
		curl_setopt ( $session, CURLOPT_HEADER, false );
		curl_setopt ( $session, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $session, CURLOPT_POST, 1);
		curl_setopt ( $session, CURLOPT_POSTFIELDS,"method=flickr.photos.getSizes" . "&photo_id=" . $photoid . "&api_key=" . $appkey);
		$result = curl_exec ( $session );
		curl_close( $session );

		$xml = simplexml_load_string($result);

		if ($xml->attributes() == "ok") {
			foreach ($xml->sizes->size[3]->attributes() as $key => $value) {
				$$key = $value;
			}

			$flickr = "<a href=\"$in_url\"><img src=\"$source\" width=\"$width\" height=\"$height\" border=\"0\" /></a>";
		} else {
			$flickr = "<a href=\"$in_url\">$in_url</a>";
		}

	} else {
		$flickr = "<a href=\"$in_url\">$in_url</a>";
	}

	return($flickr);
}

function makeLinks($text) {
	$chunk = preg_split("/[\s]+/", $text);
	$size = count($chunk);

	for($i=0;$i<$size;$i++) {
		if(ereg("^http.*youtube\.com.*watch",$chunk[$i])) {
			$embed = makeYouTube($chunk[$i]);
			$total = $total . "<br /><br />" . $embed . "<br /><br />";
		} else if(ereg("^http.*flickr\.com.*photos",$chunk[$i])) {
			$embed = makeFlickr($chunk[$i]);
			$total = $total . "<br /><br />" . $embed . "<br /><br />";
		} else if(ereg("^http",$chunk[$i])) {
			$url = $chunk[$i];
			$new = "<a href=\"$url\" rel=\"nofollow\" target=\"blank\">$url</a>";
			$total = $total . " " . $new;
		} else {
			$total = $total . " " . $chunk[$i];
		}
	}

	return $total;
}

function printRSS($num) {
        $query = "select id,entry,date_format(entrytime, '%a, %d %b %Y %H:%i:%s') as date from main order by entrytime desc limit $num";
        $result = mysql_query($query);

	$siteurl = getSiteUrl();

        while ($row = mysql_fetch_array($result)) {
		echo "\t<item>\n";
		echo "\t\t<title>" . htmlspecialchars($row['entry'],ENT_COMPAT,UTF-8) . "</title>\n";
		echo "\t\t<pubDate>" . $row['date'] . " PST</pubDate>\n";
		echo "\t\t<guid>" . $siteurl . "/entry.php?number=" . $row['id'] . "</guid>\n";
		echo "\t\t<link>" . $siteurl . "/entry.php?number=" . $row['id'] . "</link>\n";
		echo "\t</item>\n";
        }
}

function showLoginForm() {
	echo "<form action=\"";
	echo $_SERVER['PHP_SELF'];
	echo "\"";
	echo " method=\"post\">";
	echo "user: <input type=\"text\" name=\"user\"><br />";
	echo "pass: <input type=\"password\" name=\"pass\"><br />";
	echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<input type=\"submit\" name=\"submit\" value=\"login\">";
	echo "</form>";
	echo "<hr />";
	echo "<a href='forgot.php'>forgot password</a>";
}

function getSecret() {
        $query = "select secret from user limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['secret']);
}

function getCookie() {
        $query = "select cookie from user limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['cookie']);
}

function checkCookie() {
	$secret = getSecret();
	$cookie = $_COOKIE['mindof'];
	$user = $_COOKIE['user'];
	$storedcookie = getCookie();

	$loggedin = 0;

	$test = sha1($user . $secret);

	if ( (strlen($cookie) > 0) && ($cookie == $storedcookie) && ($cookie == $test) ) {
		$loggedin = 1;
	}

	return $loggedin;
}

function getUserName() {
	if(checkCookie()) {
		$name = $_COOKIE['user'];
	} else {
		$name = "not logged in";
	}
	return $name;
}

function getNumEntries() {
	$query = "select count(id) from main";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

        return($row['count(id)']);
}

function getEmail() {
        $query = "select email from user limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['email']);
}

function gettwitterEmail() {
        $query = "select twitterEmail from site limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['twitterEmail']);
}

function gettwitterPass() {
        $query = "select twitterPass from site limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['twitterPass']);
}

function gettwitterCheck() {
        $query = "select twitterCheck from site limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['twitterCheck']);
}

function getUser() {
        $query = "select name from user limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['name']);
}

function getSiteName() {
	$query = "select name from site limit 1";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

	return($row['name']);
}

function getSiteUrl() {
	$query = "select url from site limit 1";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

	return("http://" . $row['url']);
}

function getRawSiteURl() {
	$query = "select url from site limit 1";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

	return($row['url']);
}

function getIndexNum() {
        $query = "select indexNum from site limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['indexNum']);
}

function getRssNum() {
        $query = "select rssNum from site limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['rssNum']);
}

function setLoginCookie($user) {
		$secret = getSecret();
                $login = sha1($user . $secret);
                $expiry = time()+60*60*24*30;
		setcookie('user',$user,"$expiry");
                setcookie('mindof',$login,"$expiry");

	        $query = "update user set cookie='$login' where name = '$user'";
        	$result = mysql_query($query);
}

function killCookie() {
	if(checkCookie()) {
		$expiry = time() - 4800;
		setcookie('user','',"$expiry");
		setcookie('mindof','',"$expiry");
	}
}

function checkLogin($user,$pass) {
        $salt = substr("$user",0,2);
        $epass = crypt($pass,$salt);

	$query = "select * from user where name = '$user' and pass = '$epass'";
	$result = mysql_query($query);

	if (mysql_num_rows($result)==1) {
		return 0;
	} else {
		return 1;
	}
}

function showAddform() {
	echo "<form action=\"";
	echo $_SERVER['PHP_SELF'];
	echo "\"";
	echo " method=\"post\">";
	echo "user: <input type=\"text\" name=\"user\"><br />";
	echo "email: <input type=\"text\" name=\"email\"><br />";
	echo "password: <input type=\"password\" name=\"pass1\"><br />";
	echo "password (again): <input type=\"password\" name=\"pass2\"><br />";
	echo "name of site: <input type=\"text\" name=\"site\"><br />";
	echo "base url (without http://): <input type=\"text\" name=\"url\"><br />";
	echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<input type=\"submit\" name=\"submit\" value=\"install\">";
	echo "</form>";
}

function showSettingsform() {
	$sitename = getSiteName();
	$rawsiteurl = getRawSiteUrl();
	$indexNum = getIndexNum();
	$rssNum = getRssNum();
	
	echo "<p>click here for: <a href=\"twittersettings.php\">twitter settings</a></p>";
	echo "<p><b>general site settings:</b></p>";
	echo "<form action=\"";
	echo $_SERVER['PHP_SELF'];
	echo "\"";
	echo " method=\"post\">";
        echo "user: <input type=\"text\" name=\"user\"><br />";
        echo "pass: <input type=\"password\" name=\"pass\"><br />";
	echo "name of site: <input type=\"text\" name=\"site\" value=\"" . $sitename . "\"><br />";
	echo "base url (without http://): <input type=\"text\" name=\"url\" value=\"" . $rawsiteurl . "\"><br />";
	echo "number of entries to display per page: <input type=\"text\" name=\"index\" value=\"" . $indexNum . "\"><br />";
	echo "number of entries to display in rss feed: <input type=\"text\" name=\"rss\" value=\"" . $rssNum . "\"><br />";
	echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<input type=\"submit\" name=\"submit\" value=\"update\">";
	echo "</form>";


}

function showTwitterform() {

	$twitterCheck =  gettwitterCheck();

	if($twitterCheck == 1) {
		$checked = "checked";
		$content = '';
	} else  {
		$checked = "";
		$content = '<a href="./redirect.php"><img src="./images/lighter.png" alt="Sign in with Twitter"/></a><br />';
	}

	echo "<form action=\"";
	echo $_SERVER['PHP_SELF'];
	echo "\"";
	echo " method=\"post\">";
        echo "user: <input type=\"text\" name=\"user\"><br />";
        echo "pass: <input type=\"password\" name=\"pass\"><br />";
	echo "update twitter also: <input type=\"checkbox\" name=\"twitterCheck\" value=\"1\" " .  $checked . "><br />";
	print_r($content);
	echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<input type=\"submit\" name=\"submit\" value=\"update\">";
	echo "</form>";

}

function showDelform($id,$secret) {
	echo "hey! are you SURE you want to delete this entry?";
	$siteurl = getSiteUrl();
        echo "<form action=\"";
        echo $_SERVER['PHP_SELF'];
        echo "\"";
        echo " method=\"post\">";
        echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
        echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
        echo "<input type=\"submit\" name=\"submit\" value=\"YES\">";
	echo " <a href=\"$siteurl\">no</a>";
        echo "</form>";
}

function showForgotform() {
        echo "Please enter the following information to reset your password: <br />";
        echo "<form action=\"";
        echo $_SERVER['PHP_SELF'];
        echo "\"";
        echo " method=\"post\">";
        echo "user: <input type=\"text\" name=\"user\"><br />";
        echo "email: <input type=\"text\" name=\"email\"><br />";
        echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
        echo "<input type=\"submit\" name=\"submit\" value=\"Reset Password\">";
        echo "</form>";
}


function deleteEntry($id) {
	$query = "delete from main where id='$id'";
	$result = mysql_query($query);

	echo "entry " . $id . " deleted!";
}

function generateCode($length=16) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
		$code .= $chars[mt_rand(0,$clen)];
        }
        return $code;
}

function showPasswordChangeform() {
	$username = getUserName();
	echo "changing password for ";
	echo $username;
	echo "<form action=\"";
	echo $_SERVER['PHP_SELF'];
	echo "\"";
	echo " method=\"post\">";
	echo "old pass: <input type=\"password\" name=\"oldpass\"><br />";
	echo "new pass: <input type=\"password\" name=\"newpass1\"><br />";
	echo "new pass (again): <input type=\"password\" name=\"newpass2\"><br />";
	echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<input type=\"submit\" name=\"submit\">";
	echo "</form>";
}

function changePass($user,$pass) {
	$email = getEmail();
        $salt = substr("$user",0,2);
        $epass = crypt($pass,$salt);

	$query = "update user set pass='$epass' where name = '$user'";
	$result = mysql_query($query);

	echo " <img src=\"icon_accept.gif\" border=\"0\" /> password has been updated!";
}

function changeSettings($site,$url,$numberIndex,$numberRSS) {

        $site = mysql_real_escape_string($site);
        $url = mysql_real_escape_string($url);
        $numberIndex = mysql_real_escape_string($numberIndex);
        $numberRSS = mysql_real_escape_string($numberRSS);

	$query = "update site set name='$site', url='$url', indexNum='$numberIndex', rssNum='$numberRSS' limit 1";
	$result = mysql_query($query);

	echo "your settings have been updated!";

}

function changeTwitterSettings($twitterCheck,$oauth_token,$oauth_token_secret) {

        $twitterCheck = mysql_real_escape_string($twitterCheck);
        $oauth_token = mysql_real_escape_string($oauth_token);
        $oauth_token_secret = mysql_real_escape_string($oauth_token_secret);
        
	$query = "update site set twitterCheck='$twitterCheck', twitterEmail='$oauth_token', twitterPass='$oauth_token_secret' limit 1";

	$result = mysql_query($query);

	echo "your twitter settings have been updated!";
}

function updateTwitterSettings($twitterCheck) {

        $twitterCheck = mysql_real_escape_string($twitterCheck);
        
	$query = "update site set twitterCheck='$twitterCheck' limit 1";

	$result = mysql_query($query);

	echo "your twitter settings have been updated!";
}

function addUser($user,$email,$pass,$site,$url) {
        $salt = substr("$user",0,2);
        $epass = crypt($pass,$salt);

	$query = "select * from user";
	$status = mysql_query($query);

	if (mysql_num_rows($status) >= 1) {
		echo "already installed!";
	} else {
		$user = mysql_real_escape_string($user);
		$email = mysql_real_escape_string($email);
		$pass = mysql_real_escape_string($pass);
		$site = mysql_real_escape_string($site);
		$url = mysql_real_escape_string($url);
		
		$query = "create table user ( name varchar(30) NOT NULL, email varchar(30) NOT NULL, pass varchar(30) NOT NULL, secret varchar(6), cookie varchar(300) )";
		$status = mysql_query($query);

		$query = "create table main ( id int NOT NULL AUTO_INCREMENT, entrytime DATETIME NOT NULL, entry varchar(160) NOT NULL, PRIMARY KEY (id)); ";
		$status = mysql_query($query);
		
		$query = "create table site ( name varchar(160) NOT NULL, url varchar(160) NOT NULL, indexNum int NOT NULL, rssNum int NOT NULL, twitterCheck int NOT NULL, twitterEmail varchar(250), twitterPass varchar(250) ); ";
		$status = mysql_query($query);
	
		$secret = generateCode();
	
		$query = "insert into user (name,email,pass,secret) values ('$user','$email','$epass','$secret')";
		$status = mysql_query($query);
	
		$query = "insert into site (name,url,indexNum,rssNum,twitterCheck,twitterEmail,twitterPass) values ('$site','$url','10','10','0','','')";
		$status = mysql_query($query);

		echo "mindof installed!  thanks!";
	}
}

function sendRandomPass($email,$func) {
        $query = "select name from user where email='$email'";
        $status = mysql_query($query);
        $row = mysql_fetch_array($status);

        $user = $row['name'];

        $pass = generateCode();
	$salt = substr("$user",0,2);
	$epass = crypt($pass,$salt);

	$email = mysql_real_escape_string($email);
	
	$to = "$email";
	$from = "From: webmaster@ultramookie.com";
	$subject = "password";
	$body = "hi, your password is $pass. please login using your email address and the password.  feel free to change your password at anytime.";
	if (mail($to, $subject, $body, $from)) {
		if ((strcmp($func,"new")) == 0) {
			$query = "insert into user (email,pass) values ('$email','$epass')";
			$status = mysql_query($query);
		} else if ((strcmp($func,"lost")) == 0) {
			$query = "update user set pass='$epass' where email = '$email'";
			$status = mysql_query($query);
		} else {
			echo "nothing to do!";
		}

		echo "<p>Your new password has been sent!  <a href='login.php'>login</a> after you receive your password.</p>";
	} else {
		echo("<p>Message delivery failed...</p>");
	}
}

