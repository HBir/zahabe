
<?php
/**
*Innehåller funktione
*/
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

    $stmt = $db->prepare('SELECT Text, ID, Story, (select count(*) from MinnsDu b  where a.id >= b.id) as cnt
                            FROM MinnsDu a LEFT JOIN Stories ON a.ID = Stories.MVID ORDER BY ID desc'
    );
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
}

function getMVByID($db, $ID)
{
    /**Hämtar det inlägg med ett specifikt ID*/
    $stmt = $db->prepare("SELECT Text, ID, Story, (select count(*) from MinnsDu b  where a.id >= b.id) as cnt
                            FROM MinnsDu a LEFT JOIN Stories ON a.ID = Stories.MVID WHERE ID = :ID ORDER BY ID asc");
    $stmt->bindParam(':ID', $ID);
    $stmt->execute();
    return $stmt->fetch();
}

function getMVByNumber($db, $cnt)
{
    /**Hämtar det inlägg som är nr $cnt i ordningen */
    $stmt = $db->prepare("SELECT * FROM MinnsDu 
                            LEFT JOIN Stories ON MinnsDu.ID=Stories.MVID 
                            WHERE ID = (SELECT ID FROM MinnsDu ORDER BY ID asc LIMIT 1 OFFSET :CNT-1)"
    );
    $stmt->bindParam(':CNT', $cnt);
    $stmt->execute();
    return $stmt->fetch();
}

function getDailyMV($db)
{
    /*Hämtar dagens slumpade MV. Skapar även ny dagens MV ifall det är första gången funktionen körs för dagen*/
    $stmt = $db->prepare('SELECT COUNT (Id) as cnt FROM MinnsDu');
    $stmt->execute();
    $row = $stmt->fetch();
    $rowCnt = $row['cnt'];
                            
    $myfileR = fopen("daily.txt", "r") or die("Unable to open file!");
    if (fgets($myfileR) != floor(time() / (60*60*24))) {
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

                            
    $stmt2 = $db->prepare("SELECT Text, ID, Story, (select count(*) from MinnsDu b  where a.id >= b.id) as cnt
                                                FROM MinnsDu a LEFT JOIN Stories ON a.ID = Stories.MVID ORDER BY ID asc
                                                LIMIT 1 OFFSET :DAILY");
    $daily = intval($daily)-1;
    $stmt2->bindParam(':DAILY', ($daily));
    $stmt2->execute();
    $dagens = $stmt2->fetch();
    return $dagens;
}

function getAllStories($db)
{
    /**Hämtar alla inlägg med bilagor*/
    $stmt = $db->prepare('SELECT Text, ID, Story, (select count(*) from MinnsDu b  where a.id >= b.id) as cnt
                            FROM MinnsDu a INNER JOIN Stories ON a.ID = Stories.MVID ORDER BY ID desc'
    );
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
}

function addMV($Text)
{
    /*Lägger till ett nytt MV*/
    $first = substr($Text, 0, 28);
    $last = substr($Text, -1);
    $bannedWords = array("<", ">");

    if ( $first == 'Minns vi den gången Zahabe ' && $last =='?') {
        foreach($bannedWords as $banned) {
            if (strpos($Text, $banned) !== false) {
                return "...hittade det förbjudna";
                }
            }    
        try
        {
            $db = new PDO('sqlite:zahabe.db');
    
            $result = $db->query('SELECT * FROM MinnsDu');
                foreach($result as $row)
                {
                    if ($row['Text'] == $Text) {
                        return "...försökte duplicera sin död";
                    }
                }

            $ip = $_SERVER['REMOTE_ADDR'];
            $stmt = $db->prepare("INSERT INTO MinnsDu (Text, SkrivenAv) VALUES (:Text, :SkrivenAv)");

            $stmt->bindParam(':Text', $Text);
            $stmt->bindParam(':SkrivenAv', $ip);
            $Rowtext = $Text;
            $stmt->execute();

            $db = NULL;
        }
        catch(PDOException $e)
        {
            return 'Exception : '.$e->getMessage();
        }
        return TRUE;
        die();
    } else {
    return "... inte förstod?";
    }
}

function removeMV($password, $ID)
{
    /*Tar bort ett MV*/
    if ($ID == '1') {
        return '...försökte rubba det fundament på vilket allt vilade?';
    }
    if ($password === "iklabbe") {
        if (empty($ID)) {
            return '...inte förstod?';
        }
        try{
            $db = new PDO('sqlite:zahabe.db');
            $result = $db->query('SELECT * FROM MinnsDu ORDER BY ID asc LIMIT 1 OFFSET ' . $ID . '-1');
                
            $db->query('delete from MinnsDu 
                        where id = (
                        select id from 
                        (select id from MinnsDu order by id limit 1 OFFSET ' . $ID . '-1) 
                        as t
                        )');
            $i = 0;    
            foreach($result as $row){
                return "..tog bort raden: ".$row['Text']."<br>";
                $i = 1;
            }
                
            if ($i == 0){
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
    if ( $part == 'Minns vi den gången Zahabe ' && $last =='?'){
        $db = new PDO('sqlite:zahabe.db');
        /*$result = $db->query('SELECT * FROM MinnsDu');
        foreach($result as $row){
            if ($row['Text'] == $text) {
                print "...försökte duplicera sin död";
                print '<div class="lank"><p><a href="zahabe.php">Tillbaka</a></p></div>';
                return;
            }
        }*/
        $stmt = $db->prepare("UPDATE MinnsDu
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

?>