<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Minns vi den gången Zahabe</title>
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
			<a href="zahabe.php"><h1>Minns vi den gången Zahabe...</h1></a>
			<div>
				<form method="post" accept-charset="utf-8" autocomplete="off">
				Inlägg nr: <input type="number" name="ID" value="<?php echo $_GET["id"];?>" id="removeruta" min="1" required><br>
				Lösenord: <input type="password" name="password" id="password" required><br>
					<input type="submit" formaction="removeAction.php" class="button" value="Ta bort">
					<input type="submit" formaction="edit.php" class="button" value="Ändra">

				</form>

				<div class="lank"><p><a href="zahabe.php">Tillbaka</a></p></div>
			</div>

		</div>
	</body>
</html>