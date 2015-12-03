<?php
	header('Content-Type: text/html; charset=utf-8');
	
	$ID = $_GET["id"];
	$Text = $_POST['Text'];
	$Story = $_POST['story'];
	$part = substr($Text, 0, 28);
	$last = substr($Text, -1);
	
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Editing</title>
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
			<a href="zahabe.php" id="rubrik"><h1>Minns vi den gången Zahabe...</h1></a>
			<?php 
				if ( $part == 'Minns vi den gången Zahabe ' && $last =='?'){
					
					$db = new PDO('sqlite:zahabe.db');
					$result = $db->query('SELECT * FROM MinnsDu');
					/*foreach($result as $row)
					{
						if ($row['Text'] == $Text) {
							print "...försökte duplicera sin död";
							print '<div class="lank"><p><a href="zahabe.php">Tillbaka</a></p></div>';

							return;
						}
					}*/


					$stmt = $db->prepare("UPDATE MinnsDu
										SET Text=:Text
										where ID = :id ");
					$stmt->bindParam(':Text', $Text);
					$stmt->bindParam(':id', $ID);
					$stmt->execute();
					echo '<div>Uppdaterat till "'.$Text.'"</div><br><br>';
					echo $Story;

					if (!empty($Story)) {
						$stmt2 = $db->prepare("INSERT OR REPLACE INTO Stories (MVID, Story) VALUES (:ID2, :story)");
						$stmt2->bindParam(':ID2', $ID);
						$stmt2->bindParam(':story', $Story);
						$stmt2->execute();
					} else {
						$stmt2 = $db->prepare("DELETE FROM Stories WHERE MVID=:ID2");
						$stmt2->bindParam(':ID2', $ID);
						$stmt2->execute();
					}
				} else {
					print "... inte förstod?";
				}
			?>
			
			<div class="lank"><p><a href="zahabe.php">Tillbaka</a></p></div>
		</div>
	</body>
</html>
