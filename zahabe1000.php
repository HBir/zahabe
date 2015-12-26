<!DOCTYPE html>
<?php
    require 'functions.php';
    $ip = $_SERVER['REMOTE_ADDR'];
    $db = new PDO('sqlite:zahabe.db');
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>
            var oldData = "";
            var newMvs = 0;

            function refreshPage(type) {
                $.get("ajaxMV.php", function (data) {
                    if (oldData != data) {
                        if (oldData != "" && type != "add" && document.hasFocus() == false) {
                            /*Alert goes here*/
                            //data.substring(data.indexOf("<li>") + 4, data.indexOf("</li>"));
                            
                            console.log("New MV!");
                            newMvs++;
                            document.title = "(" + newMvs + ") Minns vi den gången Zahabe";
                        }
                        $("#MVs").html(data);
                        oldData = data;
                    }
                });
            }
            $(window).focus(function () {
                document.title = "Minns vi den gången Zahabe";
                newMvs = 0;
            });
            $(document).ready(function () {
                $("#MVform").submit(function (e) {

                    var url = "add.php";

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $("#MVform").serialize(),
                        success: function (data) {
                            document.getElementById("nyruta").value = '';
                            $('#errorspace').html("");
                            refreshPage("add");
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            switch (errorThrown) {
                                case "Not Acceptable":
                                    $('#errorspace').html("...inte förstod");
                                    break;
                                case "Conflict":
                                    $('#errorspace').html("...försökte duplicera sin död");
                                    break;
                                case "Forbidden":
                                    $('#errorspace').html("...hittade det förbjudna");
                                    break;
                                case "Unauthorized":
                                    $('#errorspace').html("...gjorde bort sig totalt");
                                    break;
                                default:
                                    $('#errorspace').html("...fick " + errorThrown);
                            }
                        }
                    });
                    e.preventDefault();
                });
            });

            $(window).load(function () {
                //refreshPage("");
                setInterval("refreshPage('')", 5000);
            });

        </script>
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
        <div id="sidebarLeft">
            <?php 
            $celeb = 1000;
            $cnt = getMVAmount($db);
            if ($cnt >= $celeb) {
                echo '
                <img src="assets/dance/1000red.gif">
            <img src="assets/dance/on-drugs.gif">
            <img src="assets/dance/1000blue.gif">
            <img src="https://coubsecure-a.akamaihd.net/get/b147/p/coub/simple/cw_gif_big/e40f5177dfb/7f3448f0d83bb6d7b3d37/1446962482_1s0t6vz_u80z.gif">
            <img src="assets/dance/1000red.gif">
            <img src="assets/dance/skeleton.gif">
            <img src="assets/dance/1000blue.gif">
            <img src="assets/dance/freshdance.gif">
            <img src="assets/dance/1000red.gif">
            <img src="assets/dance/snoopydance.gif">
            <img src="assets/dance/1000blue.gif">
            <img src="assets/dance/skeleton3.gif">
            <img src="assets/dance/1000red.gif">
            <img src="assets/dance/pixeldance.gif">
            <img src="assets/dance/1000blue.gif">
            <img src="assets/dance/rus.gif">
            <img src="assets/dance/1000red.gif">
            <img src="http://ak-hdl.buzzfed.com/static/enhanced/web03/2012/9/8/17/anigif_enhanced-buzz-25963-1347140900-3.gif">
            <img src="assets/dance/1000blue.gif">
            <img src="http://ak-hdl.buzzfed.com/static/enhanced/web05/2012/9/8/17/anigif_enhanced-buzz-19987-1347140955-0.gif">
            <img src="assets/dance/1000red.gif">
            <img src="http://rs707.pbsrc.com/albums/ww74/onesuleman/miscellaneous_121.gif~c200">
            <img src="assets/dance/1000blue.gif">
            <img src="http://storage.akamai.coub.com/get/bucket:12.21/p/coub/simple/cw_gif_big/61635dd7ad7/fccc2e2533ebe0cdb69df/1409454970_1386721273_13f0ile_f8yaa8r.gif">
            <img src="assets/dance/1000red.gif">
            <img src="https://lh3.googleusercontent.com/-9jYlcV2jRnc/VblkgAEKFfI/AAAAAAAABFY/VkwKHRuLA_k/w426-h320/15%2B-%2B1">
            <img src="assets/dance/1000blue.gif">
            <img src="assets/dance/saxdance.gif">
            <img src="assets/dance/1000red.gif">
            
            
                ';
            }

?>
            
        </div>
        <div id="sidebarRight">
            
            <?php
                if ($cnt >= $celeb) {
                    echo '<img src="assets/dance/1000blue.gif">
            <img src="assets/dance/verka.gif">
            <img src="assets/dance/1000red.gif">
            <img src="assets/dance/weirdkiddance.gif">
            <img src="assets/dance/1000blue.gif">
            <img src="assets/dance/citydance.gif">
            <img src="assets/dance/1000red.gif">
            <img src="assets/dance/kiddance.gif">
            <img src="assets/dance/1000blue.gif">
            <img src="assets/dance/suitdance.gif">
            <img src="assets/dance/1000red.gif">
            <img src="assets/dance/skeleton2.gif">
            <img src="assets/dance/1000blue.gif">
            <img src="assets/dance/giphy.gif">
            <img src="assets/dance/1000red.gif">
            <img src="assets/dance/scooby.gif">
            <img src="assets/dance/1000blue.gif">
            <img src="assets/dance/white-kid-dance-club.gif">
            <img src="assets/dance/1000red.gif">
            <img src="http://ak-hdl.buzzfed.com/static/enhanced/web05/2012/9/8/18/anigif_enhanced-buzz-20001-1347143302-11.gif">
            <img src="assets/dance/1000blue.gif">
            <img src="http://ak-hdl.buzzfed.com/static/enhanced/terminal05/2012/9/8/17/anigif_enhanced-buzz-5303-1347139716-5.gif">
            <img src="assets/dance/1000red.gif">
            <img src="http://stream1.gifsoup.com/webroot/animatedgifs5/3143438_o.gif">
            <img src="assets/dance/1000blue.gif">
            <img src="http://31.media.tumblr.com/7b65e77d2e4f61f1be38c4f959fcedcf/tumblr_mxyvn7VtGE1sb1yhlo1_400.gif">
            <img src="assets/dance/1000red.gif">
            <img src="https://45.media.tumblr.com/972c978046da0b7b56c15c569abc514a/tumblr_mus1vsXII41s2wio8o1_400.gif">
            <img src="assets/dance/1000blue.gif">
            <img src="http://i.imgur.com/LUs4sYF.gif">
            <img src="assets/dance/1000red.gif">
            
            
            
            ';

                }

            ?>
            
            
        </div>
        
        <div id="wrapper">
            <h1><a href="zahabe.php">Minns vi den gången Zahabe...</a></h1>
            

<?php
                if ($cnt >= $celeb) {
                    echo '<audio autoplay="autoplay" loop>
                      <source src="assets/dance/verka.mp3" type="audio/mp3">
                    </audio>';
                
                }

            ?>
            
            <div id="errorspace"><!--Error messages appear here--></div>
			<div class="lank edit">
				<a href="allstories.php" title="Attatchments"><img src="assets/read.png" alt="Attatchments"></a>
				
			</div>
			<div class="lank edit rightmenu">
				<a href="remove.php" title="Edit"><img src="assets/edit.png" alt="edit"></a>
			</div>
			<form id="MVform" action="add.php" method="post" accept-charset="utf-8" autocomplete="off">
				<div id="formbox">
					<input type="text" name="Text" id="nyruta" placeholder="Ny" required>
					<button type="submit" id="nybutton">Lägg till</button>
				</div>
			</form>
            
			<div id="dagens">
				<b>Dagens Zahabe:</b>
				<ol>
				<?php
				try{

                    
                    
                    $row = getDailyMV($db);


                    if (isset($row['Story'])) {
                        print "<div class='storyicon'><a href='story.php?id=".$row['ID']."'><img src='assets/read.png' alt='read full'></a></div>";
                        print "<a href='story.php?id=".$row['ID']."'><li value='".$row['cnt']."'><span>".$row['Text']."</span></li></a>";
                    } else {
                        print "<li value='".$row['cnt']."'>".$row['Text']."</li>";
                    }
                    
                    }catch(PDOException $e){
                        print 'Exception : '.$e->getMessage();
                    }
                    
				?></ol>
			</div>
			<div id="rows">
				<ol reversed id="MVs">
                    
					<?php
                        /*This place is populated by AJAX from ajaxMV.php*/
                        
                        /*Old non-ajax below*/
                        try{
                            $result = getAllMVs($db);

                            foreach($result as $row)
                            {
                              if (isset($row['Story'])) {
                                print "<div class='storyicon'><a href='story.php?id=".$row['ID']."'><img src='assets/read.png' alt='read full'></a></div>";
                                print "<a href='story.php?id=".$row['ID']."'><li><span>".$row['Text']."</span></li></a>";
                              } else {
                                /*TBD - ta bort egna MVs utan lösen
                                
                                if ($ip == $row["SkrivenAv"]) {
                                    echo "<div class='IPRemoveIcon'><a href='".$row['ID']."'><img src='assets/cross.png' alt='remove'></a></div>";
                                }*/
                                print "<li>".$row['Text']."</li>";
                                
                                
                              }
                            }
                            $db = NULL;
                        }
                        catch(PDOException $e)
                        {
                            print 'Exception : '.$e->getMessage();
                            $db = NULL;
                        }
					?>
				</ol>

			</div>
		</div>
	</body>
</html>