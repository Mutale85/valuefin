<?php
	include('../includes/db.php');
	if (isset($_POST['group_borrower_group_name'])) {
		$group_borrower_group_name 			= filter_var($_POST['group_borrower_group_name']);
		$group_id 							= preg_replace("#[^0-9]#", "", $_POST['group_id']);
		$group_borrower_group_leader		= filter_var($_POST['group_borrower_group_leader']);
		$group_borrower_collector_name 		= filter_var($_POST['group_borrower_collector_name']);
		$group_borrower_description  		= filter_var($_POST['group_borrower_description']);
		
		if ($group_borrower_group_name == "" ) {
			echo 'Please add borrowers group name';
			exit();
		}

		$query = $connect->prepare("SELECT * FROM group_borrowers WHERE group_id = ? AND group_borrower_group_name = ? ");
		$query->execute(array($group_id, $group_borrower_group_name));
		if ($query->rowCount() > 0) {
			echo 'The group  '. $group_borrower_group_name. ' is already registered';
			exit();
		}

		$group_borrower_names = '';
		if (isset($_POST['group_borrower_names'])) {
			foreach ((array) $_POST['group_borrower_names'] as $key => $value) {
			 	$group_borrower_names .= $value. ', ';
			}
		}else{
			echo "Please select group members";
			exit();
		}

		
		$date_added = date("Y-m-d");
		$sql = $connect->prepare("INSERT INTO `group_borrowers`(`group_id`, `group_borrower_group_name`, `group_borrower_names`, `group_borrower_group_leader`, `group_borrower_collector_name`, `group_borrower_description`, date_added) VALUES (?, ?, ?, ?, ?, ?, ?) ");
		$ex = $sql->execute(array($group_id, $group_borrower_group_name, $group_borrowernames, $group_borrower_group_leader, $group_borrower_collector_name, $group_borrower_description, $date_added));
		if($ex){
			echo "done";
		}else{
			echo "Error uploading User";
			exit();
		}
		
	}
?>