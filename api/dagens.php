

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />

    </head>
    <body>
        <?php

		$db = new PDO('sqlite:../zahabe.db');


		$stmt = $db->prepare("SELECT COUNT (Id) as cnt FROM MinnsDu");
		$stmt->execute();
		$rows = $stmt->fetch();
		
		$rowCnt = $rows['cnt'];
		
		$myfileR = fopen("../daily.txt", "r") or die("Unable to open file!");
		if (fgets($myfileR) != floor(time() / (60*60*24))) {
			$myfileW = fopen("../daily.txt", "w") or die("Unable to open file!");
			fwrite($myfileW, floor(time() / (60*60*24))."\n");
			fwrite($myfileW, rand(0, $rowCnt));
			fclose($myfileW);
		}
		$daily = fgets($myfileR);
		fclose($myfileR);

		$myfileR = fopen("../daily.txt", "r") or die("Unable to open file!");
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
		print_r(json_encode($dagens));

		$db = NULL;
		
?>
    </body>
</html>
