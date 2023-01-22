<?php
	include('../includes/db.php');
	if (isset($_POST['group_name'])) {
		$initial_branch 	= preg_replace("#[^0-9]#", "", $_POST['initial_branch']);
		$branch_id 			= preg_replace("#[^0-9]#", "", $_POST['branch_id']);
		$parent_id 			= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$group_name 		= filter_var($_POST['group_name'], FILTER_SANITIZE_STRING);
		$group_unique_id 	= preg_replace("#[^0-9]#", "", $_POST['group_id']);
		$group_leader_id 	= preg_replace("#[^0-9]#", "", $_POST['group_leader_id']);
		$collectors_name 	= filter_var($_POST['collectors_name'], FILTER_SANITIZE_STRING);
		$description  		= filter_var($_POST['description'], FILTER_SANITIZE_STRING);
		$group_photo 		= $_FILES['group_photo']['name'];
		$filename 			= $_FILES['group_photo']['tmp_name'];
		$main_id  			= preg_replace("#[^0-9]#", "", $_POST['id']);
		$officers 			= $_POST['officers'];

		if ($group_photo == "") {
			$group_photo = $_POST['photo'];
		}else{
			$destination 		= '../fileuploads/'.basename($group_photo);
			move_uploaded_file($filename, $destination);
		}
		if ($group_name == "" ) {
			echo 'Please add borrowers group name';
			exit();
		}

		if ($branch_id == "") {
			$branch_id = $initial_branch;
		}else{
			$branch_id = $branch_id;
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

		if (isset($_POST['loan_officer_id'])) {
			foreach ((array) $_POST['loan_officer_id'] as $key => $value) {
				$loan_officers_id .= $value. ", ";

			}
			$assignedID = rtrim($loan_officers_id, ", ");
		}else{
			$assignedID = $officers;
			echo "Please tick loan officer";
			exit();
		}
		
		// echo $branch_id;
		// we will edit the data in just one table
		$sql = $connect->prepare("UPDATE  group_borrowers  SET branch_id = ?, group_name = ?, borrowers_id = ?, group_leader_id = ?, collectors_name = ?, description = ?, group_photo = ?, loan_officers_id = ?  WHERE id = ? AND group_id = ? AND parent_id = ?");
		$ex = $sql->execute(array($branch_id, $group_name, $borrowersID, $group_leader_id, $collectors_name, $description, $group_photo, $assignedID, $main_id, $group_unique_id, $parent_id));

		if($ex){
			echo "done";
		}else{
			echo "Error uploading User";
			exit();
		}
		
	}
?>