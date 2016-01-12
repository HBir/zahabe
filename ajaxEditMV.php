<?php
    require 'functions.php';

    $ID = $_POST["id"];
    $newPos = $_POST["newPos"];
    $text = $_POST['Text'];
    //print $story = $_POST['story'];
    $story = "";

    
    
    if (isset($_POST['newPos'])) {
                    if (!replaceMVOrder($ID, $_POST['newPos'])) {
                        print "...skjöt genom taket";
                        return;
                    }
                }
                
                $message = editMV($ID, $text, $story);
				print $message;
?>
