<!DOCTYPE html>

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
				<a href="allstories.php" title="Stories"><img src="assets/read.jpg" alt="Stories"></a>
				
			</div>
			<div class="lank edit rightmenu">
				<a href="remove.php" title="Edit"><img src="assets/edit.png" alt="edit"></a>
			</div>
			<form action="add.php" method="post" accept-charset="utf-8" autocomplete="off">
				<div id="formbox">
					<input type="text" name="Text" id="nyruta" placeholder="Ny" required>
					<button type="submit" id="nybutton">Lägg till</button>
				</div>
			</form>
			<div id="dagens">
				<b>Dagens Zahabe:</b>
				<ol>
				<?php

				try
						{
							$db = new PDO('sqlite:zahabe.db');
							
							$res = $db->query('SELECT COUNT (Id) as cnt FROM MinnsDu');
							
							foreach($res as $row) {
								$rowCnt = $row['cnt'];
							}
							/*****Försök på att göra en dagens icke minnes-beroende******
							//srand(floor(time() / (60*60*24)));
							$dailyX = rand(1, 20);
							print "dailyX: ".$dailyX."<br>";
							$MaxDailyY = floor($rowCnt/20);
							print "MaxDailyY: ".$MaxDailyY."<br>";
							$dailyY = rand(1, $MaxDailyY);
							print "dailyY: ".$dailyY."<br>";

							print "daily: ".($dailyX + (($dailyY-1)*20));
							*/



							$myfileR = fopen("daily.txt", "r") or die("Unable to open file!");
							if (fgets($myfileR) != floor(time() / (60*60*24))) {
								$myfileW = fopen("daily.txt", "w") or die("Unable to open file!");
								fwrite($myfileW, floor(time() / (60*60*24))."\n");
								fwrite($myfileW, rand(0, $rowCnt));
								fclose($myfileW);
							}
							$daily = fgets($myfileR);
							fclose($myfileR);

							$myfileR = fopen("daily.txt", "r") or die("Unable to open file!");
							fgets($myfileR);
							$daily = fgets($myfileR);
							fclose($myfileR);

							//srand(floor(time() / (60*60*24)));
							//$daily = rand(0, 520) % $rowCnt;
							
							$query = 'SELECT Text, ID, Story, (select count(*) from MinnsDu b  where a.id >= b.id) as cnt
												FROM MinnsDu a LEFT JOIN Stories ON a.ID = Stories.MVID ORDER BY ID asc
												LIMIT 1 OFFSET '.($daily-1);
							$result = $db->query($query);
							foreach($result as $row) {
							  if (isset($row['Story'])) {
								print "<div class='storyicon'><a href='story.php?id=".$row['ID']."'><img src='assets/read.jpg' alt='read full'></a></div>";
								print "<a href='story.php?id=".$row['ID']."'><li value='".$row['cnt']."'><span>".$row['Text']."</span></li></a>";
							  } else {
								print "<li value='".$row['cnt']."'>".$row['Text']."</li>";
							  }
							}
							$db = NULL;
						}
						catch(PDOException $e)
						{
							print 'Exception : '.$e->getMessage();
						}

				?></ol>
			</div>
			<div id="rows">
				<ol reversed>
					<?php
						try
						{
							$db = new PDO('sqlite:zahabe.db');
						
							$result = $db->query('SELECT * FROM MinnsDu LEFT JOIN Stories ON Stories.MVID = MinnsDu.ID ORDER BY ID desc');
							foreach($result as $row)
							{
							  if (isset($row['Story'])) {
								print "<div class='storyicon'><a href='story.php?id=".$row['MVID']."'><img src='assets/read.jpg' alt='read full'></a></div>";
								print "<a href='story.php?id=".$row['MVID']."'><li><span>".$row['Text']."</span></li></a>";
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