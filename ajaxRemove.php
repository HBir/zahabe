<?php
    require 'functions.php';

    $ID = $_POST['id'];

    $message = removeMV2($ID);
    echo $message;
    

?>
