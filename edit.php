<?php
	header('Content-Type: text/html; charset=utf-8');
	include 'functions.php';
	$ID = $_POST['ID'];
	$password = $_POST['password'];
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
		</script>
	</head>
	<body>
		<div id="wrapper">
			<a href="zahabe.php" id="rubrik"><h1>Minns vi den gången Zahabe...</h1></a>
			<?php 
            if ($ID == '1') {
                print '...försökte rubba det fundament på vilket allt vilade?';
            } else if ($password == "iklabbe") {
            try{
                $db = new PDO('sqlite:zahabe.db');

                $row2 = getMVByNumber($db, $ID);
                $MVID = $row2['ID'];

                if (empty($MVID)){
                    print '...gick vilse?';
                } else {
                    echo '<form action="editAction.php?id='.$MVID.'" method="post" accept-charset="utf-8" autocomplete="off">
                        <div id="formbox">
                            <input type="integer" name="newPos" id="editPos" placeholder="#" value="'.$ID.'">
                            <input type="text" name="Text" id="editruta" placeholder="Ny" value="'.$row2["Text"].'" required>
                                <textarea name="story" id="storyedit">'.$row2["Story"].'</textarea><br>
                            <input type="submit" class="button" value="Ändra">
                        </div>
                    </form>';
                }
            }
            catch(PDOException $e)
            {
                print 'Exception : '.$e->getMessage();
            }
            } else{
                echo "Glömde nycklarna Hemma";
            }
			?>
			
			<div class="lank"><p><a href="zahabe.php">Tillbaka</a></p></div>
		</div>
	</body>
</html>
