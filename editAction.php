<?php
    /**
    *Executes the edit functions
    */
    header('Content-Type: text/html; charset=utf-8');
    require 'functions.php';

    $ID = $_GET["id"];
    $text = $_POST['Text'];
    $story = $_POST['story'];
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
                $message = editMV($ID, $text, $story);
				print $message;
				
			?>
			
			<div class="lank"><p><a href="zahabe.php">Tillbaka</a></p></div>
		</div>
	</body>
</html>
