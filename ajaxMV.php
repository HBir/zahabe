
<?php
try {
    include 'functions.php';
    $db = new PDO('sqlite:zahabe.db');
    $result = getAllMVs($db);
    
    foreach ($result as $row) {
        if (isset($row['Story'])) {
            print "<div class='storyicon'><a href='story.php?id=" . $row['ID'] . "'><img src='assets/read.png' alt='read full'></a></div>";
            print "<a href='story.php?id=" . $row['ID'] . "'><li><span>" . $row['Text'] . "</span></li></a>";
        } else {
            /*TBD - ta bort egna MVs utan lösen
            
            if ($ip == $row["SkrivenAv"]) {
            echo "<div class='IPRemoveIcon'><a href='".$row['ID']."'><img src='assets/cross.png' alt='remove'></a></div>";
            }*/
            
            print "<li>" . $row['Text'] . "</li>";
        }
    }
    $db = NULL;
}
catch (PDOException $e) {
    print 'Exception : ' . $e->getMessage();
}

?>