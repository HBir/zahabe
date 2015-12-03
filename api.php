<?php
	$db = new PDO('sqlite:zahabe.db');

	if( isset( $_GET['id'])) {
		$ID = $_GET['id']; 
	} 

	if( isset( $_GET['cnt'])) {
		$CNT = $_GET['cnt']; 
	}


	if (isset($ID)) {
		$stmt = $db->prepare("SELECT Text, ID, Story, (select count(*) from MinnsDu b  where a.id >= b.id) as cnt
							FROM MinnsDu a LEFT JOIN Stories ON a.ID = Stories.MVID WHERE ID = :ID ORDER BY ID asc");
		$stmt->bindParam(':ID', $ID);
							

	} else if (isset($CNT)){
		$stmt = $db->prepare("SELECT Text, ID, Story, (select count(*) from MinnsDu b  where a.id >= b.id) as cnt
							FROM MinnsDu a LEFT JOIN Stories ON a.ID = Stories.MVID WHERE cnt = :CNT ORDER BY ID asc");
		$stmt->bindParam(':CNT', $CNT);

	} else{
	$stmt = $db->prepare("SELECT Text, ID, Story, (select count(*) from MinnsDu b  where a.id >= b.id) as cnt
							FROM MinnsDu a LEFT JOIN Stories ON a.ID = Stories.MVID ORDER BY ID asc");
	}
	$stmt->execute();
	$rows = $stmt->fetchAll();
	
	if (empty($rows)){
		echo json_encode($rows); 
		http_response_code(404);
	} else
	print_r(json_encode($rows));

	
	
	$db = NULL;

		?>