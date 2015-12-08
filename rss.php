<?xml version="1.0" encoding="utf-8" ?>
<rss version="2.0">

<channel>
<title>Zahabe.ga</title>
<description>Zahabe life</description>
<link>http://dvwebb.mah.se/ae3897/Zahabe/zahabe.php</link>
<?php
require 'functions.php';
//header("Content-Type: application/xml; charset=ISO-8859-1");
//include("classes/RSS.class.php");
$db = new PDO('sqlite:zahabe.db');
$result = getAllMVs($db);

foreach($result as $row){
    print "<item>";
    print "<title>" . $row['Text'] . "</title>";
    print "<description>" . $row['Text'] . "</description>";
    print "<link>http://dvwebb.mah.se/ae3897/Zahabe/zahabe.php</link>";
    print "</item>";
}

$db = NULL;
?>
</channel>
</rss>