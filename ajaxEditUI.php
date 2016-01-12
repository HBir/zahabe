
<?php
try {
    $password = "";
    include 'functions.php';
    $db = new PDO('sqlite:zahabe.db');
    $result = getAllMVs($db);
    $ip = $_SERVER['REMOTE_ADDR'];
    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
    }
    

    foreach ($result as $row) {
        if ($ip == $row["SkrivenAv"] or $password == "iklabbe") {
            echo "<div class='IPEditIcon'><a class='MVEdit' destinationurl='".$row["ID"]."' href='javascript:void(0);'><img src='assets/edit.png' alt='edit'></a></div>";
            echo "<div class='IPRemoveIcon'><a class='MVCross' destinationurl='".$row["ID"]."' href='javascript:void(0);'><img src='assets/cross.png' alt='remove'></a></div>";
        }
        if (isset($row['Story'])) {
            print "<div class='storyicon'><a href='story.php?id=" . $row['ID'] . "'><img src='assets/read.png' alt='read full'></a></div>";
            print "<a href='story.php?id=" . $row['ID'] . "'><li  value=".$row['cnt']." class='MV'><span>" . $row['Text'] . "</span></li></a>";
        } else {
            /*TBD - ta bort egna MVs utan lösen*/
            
            print "<li  value=".$row['cnt']." class='MV'>" . $row['Text'] . "</li>";
            
        }
        //print "<hr/>";
    }
    $db = NULL;
}
catch (PDOException $e) {
    print 'Exception : ' . $e->getMessage();
}

?>