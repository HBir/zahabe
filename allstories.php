<!DOCTYPE html>
<?php
	header('Content-Type: text/html; charset=utf-8');
?>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Minns vi den gången Zahabe</title>

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
				<a href="allstories.php" title="Stories"><img src="assets/read.png" alt="Stories"></a>
			</div>
			<div class="lank edit rightmenu">
				<a href="remove.php?id=<?php echo $ID;?>" title="Edit"><img src="assets/edit.png" alt="edit"></a>
			</div>
			
			<div id="rows">
				<ol>
					<?php
						try
						{
							$db = new PDO('sqlite:zahabe.db');
						
							$result = $db->query('SELECT Text, ID, Story, (select count(*) from MinnsDu b  where a.id >= b.id) as cnt
													FROM MinnsDu a INNER JOIN Stories ON a.ID = Stories.MVID ORDER BY ID desc');
							foreach($result as $row)
							{
							  if (isset($row['Story'])) {
								print "<div class='storyicon'><a href='story.php?id=".$row['ID']."'><img src='assets/read.png' alt='read full'></a></div>";
								print "<a href='story.php?id=".$row['ID']."'><li value='".$row['cnt']."'><span>".$row['Text']."</span></li></a>";
							  } else {
								print "<li>".$row['Text']."</li>";
							  }
							}
							$db = NULL;
						}
						catch(PDOException $e)
						{
							print 'Exception : '.$e->getMessage();
						}
					?>
				</ol>
			</div>

		</div>
	</body>
</html>