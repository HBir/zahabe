<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Minns vi den gången Zahabe</title>
		<link rel="stylesheet" href="style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-63495608-1', 'auto');
		  ga('send', 'pageview');
		  //ga('set', '&uid', {{USER_ID}}); // Set the user ID using signed-in user_id.

		</script>
    </head>
<body>
<div id="wrapper">
	<a href="zahabe.php"><h1>Minns vi den gången Zahabe...</h1></a>

	<?php
	header('Content-Type: text/html; charset=utf-8');
	$Text = $_POST['Text'];

	$first = substr($Text, 0, 28);
	$last = substr($Text, -1);
	$bannedWords = array("<", ">");



	if ( $first == 'Minns vi den gången Zahabe ' && $last =='?') {
		
		foreach($bannedWords as $banned) {
			if (strpos($Text, $banned) !== false) {
				print "...hittade det förbjudna";
				print '<div class="lank"><p><a href="zahabe.php">Tillbaka</a></p></div>';
				echo "</div></body></html>";
				return;
				}
			}
			
		try
		{
			$db = new PDO('sqlite:zahabe.db');
	
			$result = $db->query('SELECT * FROM MinnsDu');
				foreach($result as $row)
				{
					if ($row['Text'] == $Text) {
						print "...försökte duplicera sin död";
						print '<div class="lank"><p><a href="zahabe.php">Tillbaka</a></p></div>';
						echo "</div></body></html>";
						return;
					}
				}
	

			$ip = $_SERVER['REMOTE_ADDR'];
			$stmt = $db->prepare("INSERT INTO MinnsDu (Text, SkrivenAv) VALUES (:Text, :SkrivenAv)");

			$stmt->bindParam(':Text', $Text);
			$stmt->bindParam(':SkrivenAv', $ip);
			$Rowtext = $Text;
			$stmt->execute();

			$db = NULL;
		}
		catch(PDOException $e)
		{
			print 'Exception : '.$e->getMessage();
		}
	
		header("Location: zahabe.php");
		die();
	} else {
	print "... inte förstod?";
	print '<div class="lank"><p><a href="zahabe.php">Tillbaka</a></p></div>';
	}

?>
</div>
</body>
</html>