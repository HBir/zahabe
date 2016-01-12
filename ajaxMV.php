
<?php
try {
    include 'functions.php';
    $db = new PDO('sqlite:zahabe.db');
    $result = getAllMVs($db);
    
    

    foreach ($result as $row) {
        if (isset($row['Story'])) {
            print "<div class='storyicon'><a href='story.php?id=" . $row['ID'] . "'><img src='assets/read.png' alt='read full'></a></div>";
            print "<a href='story.php?id=" . $row['ID'] . "'><li value=".$row['cnt']." class='MV'><span>" . $row['Text'] . "</span></li></a>";
        } else {
            
            print "<li value=".$row['cnt']." class='MV'>" . $row['Text'] . "</li>";
            
        }
        //print "<hr/>";
    }
    $db = NULL;
}
catch (PDOException $e) {
    print 'Exception : ' . $e->getMessage();
}

?>