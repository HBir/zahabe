<?php
    /**
    *API-Dokumentation
    *URL parametrar:
    *id             = Return MV with specified ID
    *cnt            = Return MV with ordered numbered specified
    *add            = Adds a new MV
    *type >
    *=daily
    *=stories       = Returns all 
    *no parameters  = Returns all MVs
    *
    *returns json format
    */
    
    include 'functions.php';
    
    $db = new PDO('sqlite:zahabe.db');

    if (isset($_GET['id'])) {
        $rows = getMVByID($db, $_GET['id']);

    } else if (isset($_GET['cnt'])) {
        $rows = getMVByNumber($db, $_GET['cnt']);

    } else if (isset($_GET['add'])) {
        /*Not implemented*/
        print "To be implemented";
    } else if(isset($_GET['type'])) {
        if ($_GET['type'] == "daily") {
            $rows = getDailyMV($db);
        } else if ($_GET['type'] == "stories"){
            $rows = getAllStories($db);
        }
    } else {
    $stmt = $db->prepare("SELECT Text, ID, Story, (select count(*) from MinnsDu b  where a.id >= b.id) as cnt
                            FROM MinnsDu a LEFT JOIN Stories ON a.ID = Stories.MVID ORDER BY ID asc");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    }
    
    if (empty($rows)){
        
        echo json_encode(["error" => "404 not found"]);
        http_response_code(404);
    } else
    print_r(json_encode($rows));

    $db = NULL;

?>