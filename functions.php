
<?php
/**
*Innehåller funktioner
*/
//date_default_timezone_set('Europe/Stockholm');
function nl2p($string)
{
    /**Gör om radbrytning i sträng till ny paragraf*/
    $paragraphs = '';
    foreach (explode("\n", $string) as $line) {
        if (trim($line)) {
            $paragraphs .= '<p>' . $line . '</p>';
        }
    }
    return $paragraphs;
}

function getAllMVs($db){
    /**Hämtar alla inlägg i databasen sorterade efter tid inlagt**/

    $stmt = $db->prepare(
        'SELECT Text, ID, Story, SkrivenAv, (select count(*) from MinnsDu b  where a.id >= b.id) as cnt
        FROM MinnsDu a LEFT JOIN Stories ON a.ID = Stories.MVID ORDER BY MVOrder desc'
    );
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
}

function getMVByID($db, $ID)
{
    /**Hämtar det inlägg med ett specifikt ID*/
    $stmt = $db->prepare(
        "SELECT MVID, Story, Text, MVOrder, (select count(*) from MinnsDu b  where a.MVOrder >= b.MVOrder) as cnt
        FROM Stories
        INNER JOIN MinnsDu a ON a.ID=Stories.MVID 
        WHERE MVID = :ID"
    );
    $stmt->bindParam(':ID', $ID);
    $stmt->execute();
    return $stmt->fetch();
}

function getMVByNumber($db, $cnt)
{
    /**Hämtar det inlägg som är nr $cnt i ordningen */
    $stmt = $db->prepare(
        "SELECT * FROM MinnsDu 
        LEFT JOIN Stories ON MinnsDu.ID=Stories.MVID 
        WHERE ID = (SELECT ID FROM MinnsDu ORDER BY MVOrder asc LIMIT 1 OFFSET :CNT-1)"
    );
    $stmt->bindParam(':CNT', $cnt);
    $stmt->execute();
    return $stmt->fetch();
}

function getMVAmount($db){
    $stmt = $db->prepare('SELECT COUNT (*) as cnt FROM MinnsDu');
    $stmt->execute();
    $row = $stmt->fetch();
    return $row['cnt'];
}


function getDailyMV($db)
{
    /*Hämtar dagens slumpade MV. Skapar även ny dagens MV ifall det är första gången funktionen körs för dagen*/
    $stmt = $db->prepare('SELECT COUNT (Id) as cnt FROM MinnsDu');
    $stmt->execute();
    $row = $stmt->fetch();
    $rowCnt = $row['cnt'];
    

    $myfileR = fopen("daily.txt", "r") or die("Unable to open file!");
    if (fgets($myfileR) != floor((time() + 3600) / (60*60*24))) {
        $myfileW = fopen("daily.txt", "w") or die("Unable to open file!");
        fwrite($myfileW, floor((time() + 3600) / (60*60*24))."\n");
        fwrite($myfileW, rand(0, $rowCnt));
        fclose($myfileW);
    }
    $daily = fgets($myfileR);
    fclose($myfileR);

    $myfileR = fopen("daily.txt", "r") or die("Unable to open file!");
    fgets($myfileR);
    $daily = fgets($myfileR);
    fclose($myfileR);

                            
    $stmt2 = $db->prepare(
        "SELECT Text, ID, Story, MVOrder, (select count(*) from MinnsDu b  where a.MVOrder >= b.MVOrder) as cnt
        FROM MinnsDu a LEFT JOIN Stories ON a.ID = Stories.MVID ORDER BY MVOrder asc
        LIMIT 1 OFFSET :DAILY"
    );
    $daily = intval($daily)-1;
    $stmt2->bindParam(':DAILY', ($daily));
    $stmt2->execute();
    $dagens = $stmt2->fetch();
    return $dagens;
}

function getAllStories($db)
{
    /**Hämtar alla inlägg med bilagor*/
    $stmt = $db->prepare(
        'SELECT Text, ID, Story, MVOrder, (select count(*) from MinnsDu b  where a.MVOrder >= b.MVOrder) as cnt
        FROM MinnsDu a INNER JOIN Stories ON a.ID = Stories.MVID ORDER BY MVOrder desc'
    );
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
}

function prepare_MV($db, $Text, $Activation) 
{
    /**Förbereder en MV som automatiskt ska placeras på plats $Activation */
    if (getMVAmount($db) == $Activation-1) {
        addMV($Text);
    } 

}

function addMV($Text)
{
    /*Lägger till ett nytt MV*/
    $ip = $_SERVER['REMOTE_ADDR'];
    $first = substr($Text, 0, 28);
    $last = substr($Text, -1);
    $bannedWords = array("<", ">");
    $bannedIPs = array();
    
    if (in_array($ip, $bannedIPs)) {
        http_response_code(401);
        return "...gjorde bort sig totalt";
    }

    if ( $first == 'Minns vi den gången Zahabe ' && $last =='?') {
        foreach ($bannedWords as $banned) {
            if (strpos($Text, $banned) !== false) {
                http_response_code(403);
                return "...hittade det förbjudna";
            }
        }
        try
        {
            $db = new PDO('sqlite:zahabe.db');

            $result = $db->query('SELECT * FROM MinnsDu');
            foreach ($result as $row) {
                if ($row['Text'] == $Text) {
                    http_response_code(409);
                    return "...försökte duplicera sin död";
                }
            }
            
            $stmt = $db->prepare(
                "INSERT INTO MinnsDu (Text, SkrivenAv)
                VALUES (:Text, :SkrivenAv)"
            );
            addStat(1, $db);
            $stmt->bindParam(':Text', $Text);
            $stmt->bindParam(':SkrivenAv', $ip);
            $Rowtext = $Text;
            $stmt->execute();
            $stmt2 = $db->prepare(
                "UPDATE MinnsDu
                SET MVOrder = last_insert_rowid()
                WHERE ID = last_insert_rowid()"
            );
            $stmt2->execute();

            $db = null;
        }
        catch(PDOException $e)
        {
            return 'Exception : '.$e->getMessage();
        }
            return true;
            die();
    } else {
        http_response_code(406);
        return "... inte förstod?";
    }
}

function addStat($amount, $db) {
    $date = date("Ymd");
    
    $stmt = $db->prepare("SELECT * FROM DailyStat WHERE TheDate = :date");
    $stmt->bindParam(':date', ($date));
    $stmt->execute();
    $dailyStat = 1;
    $dailyStatFetch = $stmt->fetch();
    if (!empty($dailyStatFetch)) {
        $dailyStat = $dailyStatFetch['Amount'] + intval($amount);
    }

    $stmt2 = $db->prepare(
        "INSERT OR REPLACE INTO DailyStat (TheDate, Amount) VALUES
        (:date, :amount)"
    );
    $stmt2->bindParam(':date', ($date));
    $stmt2->bindParam(':amount', ($dailyStat));
    $stmt2->execute();

}

function removeMV2($ID) {
    if ($ID == '1') {
        return '...försökte rubba det fundament på vilket allt vilade?';
    }
    if (empty($ID)) {
        return '...inte förstod?';
    }
    try{
        $db = new PDO('sqlite:zahabe.db');
        
        $stmt = $db->prepare('delete from MinnsDu where id = :id');
        $stmt->bindParam(':id', $ID);
        $stmt->execute();
        addStat(-1, $db);
        $db = NULL;
    } catch(PDOException $e) {
        return 'Exception : '.$e->getMessage();
    }
}

function removeMV($password, $ID)
{
    /*Tar bort ett MV*/
    if ($password === "iklabbe") {
        if ($ID == '1') {
            return '...försökte rubba det fundament på vilket allt vilade?';
        }
        if (empty($ID)) {
            return '...inte förstod?';
        }
        try{
            $db = new PDO('sqlite:zahabe.db');
            $result = $db->query('SELECT * FROM MinnsDu ORDER BY MVOrder asc LIMIT 1 OFFSET ' . $ID . '-1');
                
            $db->query('delete from MinnsDu 
                        where id = (
                        select id from 
                        (select id from MinnsDu order by MVOrder limit 1 OFFSET ' . $ID . '-1) 
                        as t
                        )');
            $i = 0;    
            addStat(-1, $db);
            foreach ($result as $row) {
                return "..tog bort raden: ".$row['Text']."<br>";
                $i = 1;
            }
            
            if ($i == 0) {
                return "Rad nummer ".$ID." existerar ej";
            }
            $db = NULL;
        } catch(PDOException $e) {
            return 'Exception : '.$e->getMessage();
        }
    } else {
        return "...glömde nycklarna hemma?";
    }
}

function editMV($ID, $text, $story)
{
    /*Ändrar en MV*/
    $part = substr($text, 0, 28);
    $last = substr($text, -1);
    if ( $part == 'Minns vi den gången Zahabe ' && $last =='?') {
        $db = new PDO('sqlite:zahabe.db');
        /*$result = $db->query('SELECT * FROM MinnsDu');
        foreach($result as $row){
            if ($row['Text'] == $text) {
                print "...försökte duplicera sin död";
                print '<div class="lank"><p><a href="zahabe.php">Tillbaka</a></p></div>';
                return;
            }
        }*/
        $stmt = $db->prepare(
            "UPDATE MinnsDu
            SET Text=:Text
            where ID = :id "
        );
        $stmt->bindParam(':Text', $text);
        $stmt->bindParam(':id', $ID);
        $stmt->execute();
        
        if (!empty($story)) {
            $stmt2 = $db->prepare("INSERT OR REPLACE INTO Stories (MVID, Story) VALUES (:ID2, :story)");
            $stmt2->bindParam(':ID2', $ID);
            $stmt2->bindParam(':story', $story);
            $stmt2->execute();
        } else {
            /*Tar bort bilagan ifall textfältet är tomt*/
            $stmt2 = $db->prepare("DELETE FROM Stories WHERE MVID=:ID2");
            $stmt2->bindParam(':ID2', $ID);
            $stmt2->execute();
            return '<div>Uppdaterat till "'.$text.'"</div><br><br>';
        }
        return '<div>Uppdaterat till "'.$text.'"</div><br><br>'.$story;
    } else {
        return "... inte förstod?";
    }
}

function replaceMVOrder($ID, $NewPos){
    $db = new PDO('sqlite:zahabe.db');
    $pos1 = getMVByNumber($db, $NewPos-1);
    $pos2 = getMVByNumber($db, ($NewPos));
    $calcPos = $pos1["MVOrder"]+(($pos2["MVOrder"]-$pos1["MVOrder"])/2);
    if ($calcPos <= 1) {
        return FALSE;
    }
    try {
        $stmt = $db->prepare(
            "UPDATE MinnsDu
            SET MVOrder=:MVOrder
            where ID = :id "
        );
        $stmt->bindParam(':MVOrder', $calcPos);
        $stmt->bindParam(':id', $ID);
        $stmt->execute();
    } catch(PDOException $e) {
            return 'Exception : '.$e->getMessage();
    }
    $db = null;
    return true;

}

?>