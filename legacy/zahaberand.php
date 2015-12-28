<!DOCTYPE html>
<?php
	header('Content-Type: text/html; charset=utf-8');
?>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Minns vi den gången Zahabe</title>
		<link rel="stylesheet" href="style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script>/*
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-63495608-1', 'auto');
		  ga('send', 'pageview');
		  */
		</script>
	</head>
	<body>
		<div id="wrapper">
			<h1><a href="zahabe.php">Minns vi den gången Zahabe...</a></h1>

			<?php
			$verb = array(
			"accepterade", 
			"frikände",
			"förtalade",
			"misslyckades");
			$preposition = array(
			"hans", 
			"sin",
			"på");
			$substantiv = array(
			"död", 
			"rektor",
			"gubbslem"
			);
			$verbint = rand(0,3);
			$prepint = rand(0,2);
			$subint = rand(0,2);

			?>
			<p>Minns vi den gången Zahabe <?php echo $verb[intval($verbint)]." ".$preposition[intval($prepint)]." ".$substantiv[intval($subint)]; ?>?</p>
			
		</div>
	</body>
</html>