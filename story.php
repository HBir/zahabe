<!DOCTYPE html>
<?php
	header('Content-Type: text/html; charset=utf-8');
	$ID = $_GET["id"];
	$db = new PDO('sqlite:zahabe.db');

	
	$stmt = $db->prepare("SELECT MVID, Story, Text, (select count(*) from MinnsDu b  where a.id >= b.id) as cnt
						FROM Stories
						INNER JOIN MinnsDu a ON a.ID=Stories.MVID 
						WHERE MVID = :ID");
	$stmt->bindParam(':ID', $ID);
	$stmt->execute();
	$row = $stmt->fetch();

?>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title><?php echo $row['Text'];?></title>

		<meta name="description" content="Stories of Zahabe">
		<meta name="keywords" content="Zahabe">
		<meta name="author" content="Hannes Birgersson">
		<meta name="contact" content="hannesbirgersson@gmail.com">

		<link rel="stylesheet" href="style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!--Google analytics kod-->
		<script>
  		(function (i, s, o, g, r, a, m) {
  			i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () {
  				(i[r].q = i[r].q || []).push(arguments)
  			}, i[r].l = 1 * new Date(); a = s.createElement(o),
  		m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
  		})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
		<?php
		$userId = $_SERVER['REMOTE_ADDR'];
		if (isset($userId)) {
		  $gacode = "ga('create', 'UA-63495608-1', { 'userId': '%s' });";
		  echo sprintf($gacode, $userId);
		} else {
		  $gacode = "ga('create', 'UA-63495608-1');";
		  echo sprintf($gacode);
		}?>
  		ga('send', 'pageview');
		</script>
	</head>
	<body>
		<div id="wrapper">
			<h1><a href="zahabe.php">Minns vi den gången Zahabe...</a></h1>
			<div class="lank edit">
				<a href="allstories.php" title="Stories"><img src="assets/read.jpg" alt="Stories"></a>
			</div>
			<div class="lank edit rightmenu">
				<a href="remove.php?id=<?php echo $row['cnt'];?>" title="Edit"><img src="assets/edit.png" alt="edit"></a>
			</div>
			<?php 
					function nl2p($string)
					/*Gör om radbrytning i sträng till ny paragraf*/
					{
						$paragraphs = '';
						foreach (explode("\n", $string) as $line) {
							if (trim($line)) {
								$paragraphs .= '<p>' . $line . '</p>';
							}
						}
						return $paragraphs;
					}
					$titel = str_replace("Minns vi den gången Zahabe ","...", $row['Text']);

					echo "<h2>".$titel."</h2>";

					echo "<div id='storytext'>".nl2p($row['Story'])."</div>";
			?>
			</div>
		</div>
	</body>
</html>