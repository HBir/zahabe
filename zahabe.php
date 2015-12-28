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
        <script src="Script.js"</script>
        <!--Google analytics kod-->
        <script>
            (function (i, s, o, g, r, a, m) {
              i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () {
                  (i[r].q = i[r].q || []).push(arguments)
              }, i[r].l = 1 * new Date(); a = s.createElement(o),
          m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
          })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
        <?php
        if (isset($userId)) {
          $gacode = "ga('create', 'UA-63495608-1', { 'user': '%s' });";
          echo sprintf($gacode, 'user');
        } else {
          $gacode = "ga('create', 'UA-63495608-1');";
          echo sprintf($gacode);
        }?>
          ga('send', 'pageview');
        </script>
    </head>
    <body>
        <div id="sidebarLeft">
            
        </div>
        <div id="sidebarRight">
       
        </div>
        
        <div id="wrapper">
            <h1><a href="zahabe.php">Minns vi den gången Zahabe...</a></h1>
            
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
                ?>
                </ol>
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
                                print "<a href='story.php?id=".$row['ID']."'><li class='MV'><span>".$row['Text']."</span></li></a>";
                              } else {
                                /*TBD - ta bort egna MVs utan lösen
                                
                                if ($ip == $row["SkrivenAv"]) {
                                    echo "<div class='IPRemoveIcon'><a href='".$row['ID']."'><img src='assets/cross.png' alt='remove'></a></div>";
                                }*/
                                print "<li class='MV'>".$row['Text']."</li>";
                                
                                
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