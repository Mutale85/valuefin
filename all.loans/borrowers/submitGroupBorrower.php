<?php
	include('../includes/db.php');
	if (isset($_POST['group_name'])) {
		$group_name 		= filter_var($_POST['group_name'], FILTER_SANITIZE_STRING);
		$branch_id 			= preg_replace("#[^0-9]#", "", $_POST['branch_id']);
		$parent_id 			= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$group_unique_id	= preg_replace("#[^0-9]#", "", $_POST['group_id']);
		$group_leader_id	= preg_replace("#[^0-9]#", "", $_POST['group_leader_id']);
		$collectors_name	= filter_var($_POST['collectors_name'], FILTER_SANITIZE_STRING);
		$description  		= filter_var($_POST['description'], FILTER_SANITIZE_STRING);
		$group_photo 		= $_FILES['group_photo']['name'];
		$filename 			= $_FILES['group_photo']['tmp_name'];
		$destination 		= '../fileuploads/'.basename($group_photo);
		if(move_uploaded_file($filename, $destination)){
			
		}else{
			echo "Files failed to upload";
			exit();
		}
		
		if ($group_name == "" ) {
			echo 'Please add borrowers group name';
			exit();
		}

		$query = $connect->prepare("SELECT * FROM group_borrowers WHERE group_id = ? AND group_name = ? AND branch_id = ? AND parent_id = ? ");
		$query->execute(array($group_unique_id, $group_name, $branch_id, $parent_id));
		if ($query->rowCount() > 0) {
			echo 'The group  '. $group_name. ' is already registered';
			exit();
		}

		$borrowers_id = $loan_officers_id = '';
		if (isset($_POST['borrowers_id'])) {
			foreach ((array) $_POST['borrowers_id'] as $key => $value) {
			 	$borrowers_id .= $value. ', ';
			}
		}else{
			echo "Please select group members";
			exit();
		}

		$borrowersID =  rtrim($borrowers_id, ", ");

		if (isset($_POST['loan_officer'])) {
			foreach ((array) $_POST['loan_officer'] as $key => $value) {
				$loan_officers_id .= $value. ", ";

			}
		}else{
			echo "Please tick loan officer";
			exit();
		}
		$assignedID = rtrim($loan_officers_id, ", ");

		$date_added = date("Y-m-d");
		// insert into tables now
		$sql = $connect->prepare("INSERT INTO `group_borrowers`(`branch_id`, `parent_id`, `group_id`, `group_name`, `borrowers_id`, `group_leader_id`, `collectors_name`, `description`, `group_photo`, `loan_officers_id`, `date_added`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
		$ex 		= $sql->execute(array($branch_id, $parent_id, $group_unique_id, $group_name, $borrowersID, $group_leader_id, $collectors_name, $description, $group_photo, $assignedID, $date_added));
		$group_id 	= $connect->lastInsertId();

		// here we will insert the borrowers into a new table and also the loan officers into a table

		foreach ((array) $_POST['borrowers_id'] as $key => $value) {
		 	$borrower_id = $value;
		 	$member_names = getBorrowerFullNames($connect, $borrower_id);
		 	$sql = $connect->prepare("INSERT INTO `group_borrower_members`(`group_id`, `group_unique_id`, `borrower_id`, `branch_id`, `parent_id`, `member_names`) VALUES (?, ?, ?, ?, ?, ?) ");
		 	$sql->execute(array($group_id, $group_unique_id, $borrower_id, $branch_id, $parent_id, $member_names));
		}

		foreach ((array) $_POST['loan_officer'] as $key => $value) {
			$loan_officer_id = $value;
			$sql = $connect->prepare("INSERT INTO `group_loan_officer`(`group_id`, `group_unique_id`, `branch_id`, `parent_id`, `loan_officer_id`, `date_added`) VALUES (?, ?, ?, ?, ?, ?) ");
			$sql->execute(array($group_id, $group_unique_id, $branch_id, $parent_id, $loan_officer_id, $date_added));
		}

		if($ex){
			echo "done";
		}else{
			echo "Error uploading User";
			exit();
		}
		
	}
?>