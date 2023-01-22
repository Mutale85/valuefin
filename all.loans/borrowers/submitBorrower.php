<?php
	include('../includes/db.php');
	if (isset($_POST['borrower_firstname'])) {
		$branch_id					= filter_var($_POST['branch_id'], FILTER_SANITIZE_STRING);
		$parent_id 					= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$borrower_firstname 		= filter_var($_POST['borrower_firstname'], FILTER_SANITIZE_STRING);
		$borrower_lastname 			= filter_var($_POST['borrower_lastname'], FILTER_SANITIZE_STRING);
		$borrower_business 			= filter_var($_POST['borrower_business'], FILTER_SANITIZE_STRING);
		$borrower_gender  			= filter_var($_POST['borrower_gender'], FILTER_SANITIZE_STRING);
		$borrower_ID 				= filter_var($_POST['borrower_ID'], FILTER_SANITIZE_STRING);
		$borrower_country 			= filter_var($_POST['borrower_country'], FILTER_SANITIZE_STRING);
		$borrower_city 				= filter_var($_POST['borrower_city'], FILTER_SANITIZE_STRING);
		$borrower_address 			= filter_var($_POST['borrower_address'], FILTER_SANITIZE_STRING);
		$borrower_email 			= filter_var($_POST['borrower_email'], FILTER_SANITIZE_EMAIL);
		$borrower_phone 			= preg_replace("#[^0-9]#", "", $_POST['phone']);
		$borrower_dateofbirth 		= $_POST['borrower_dateofbirth'];
		$borrower_working_status 	= filter_var($_POST['borrower_working_status'], FILTER_SANITIZE_STRING);
		$borrower_borrower_photo 	= $_FILES['borrower_borrower_photo']['name'];
		$filename 					= $_FILES['borrower_borrower_photo']['tmp_name'];
		$destination = '../fileuploads/'.basename($borrower_borrower_photo);
		move_uploaded_file($filename, $destination);
		$borrower_borrower_files = "";
		if ($branch_id == "") {
			echo "Please add select branch";
			exit();
		}
		if ($borrower_firstname == "" ) {
			echo 'Please add borrowers names';
			exit();
		}
		foreach ($_FILES['borrower_borrower_files']['name'] as $key => $value) {
		 	$borrower_borrower_files .= $value. ', ';
		 	$file_name = $_FILES['borrower_borrower_files']['tmp_name'][$key];
		 	$destination2 = '../fileuploads/'.basename($value);
		 	//move to upload folder
		 	move_uploaded_file($file_name, $destination2);
		} 

		$files =  rtrim($borrower_borrower_files, ", ");

		$loan_officers = $officers = "";
		if (isset($_POST['loan_officer'])) {
			foreach ((array) $_POST['loan_officer'] as $key => $value) {
		 		$loan_officers .= $value. ', ';
			}
			$officers = rtrim($loan_officers, ", ");
		}
		
		$query = $connect->prepare("SELECT * FROM borrowers WHERE borrower_ID = ? AND parent_id = ? ");
		$query->execute(array($borrower_ID, $parent_id));
		if ($query->rowCount() > 0) {
			echo 'user with ID: '. $borrower_ID. ' is already registered';
			exit();
		}

		$sql = $connect->prepare("INSERT INTO `borrowers`(branch_id, parent_id, `borrower_firstname`, `borrower_lastname`, `borrower_business`, `borrower_gender`, `borrower_ID`, `borrower_country`, `borrower_city`, `borrower_address`, `borrower_email`, `borrower_phone`, `borrower_dateofbirth`, `borrower_working_status`, `borrower_borrower_photo`, `borrower_borrower_files`, `loan_officers`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
		$ex = $sql->execute(array($branch_id, $parent_id, $borrower_firstname, $borrower_lastname, $borrower_business, $borrower_gender, $borrower_ID, $borrower_country, $borrower_city, $borrower_address, $borrower_email, $borrower_phone, $borrower_dateofbirth, $borrower_working_status, $borrower_borrower_photo, $files, $officers));
		$borrower_id = $connect->lastInsertId();
		// we insert loan officers, 
		$date_added = date("Y-m-d");
		foreach ($_POST['loan_officer'] as $key => $value) {
			$loan_officer = $value;
			$insert = $connect->prepare("INSERT INTO `loan_offciers`(`borrower_id`, `parent_id`, `branch_id`, `borrower_id_number`, `loan_officer_id`, `date_added`) VALUES(?, ?, ?, ?, ?, ?)");
			$insert->execute(array($borrower_id, $parent_id, $branch_id, $borrower_ID, $loan_officer, $date_added));
		}
		foreach ($_FILES['borrower_borrower_files']['name'] as $key => $value) {
		 	$file_name = $value;

		 	$in = $connect->prepare("INSERT INTO `borrower_files`( `borrower_id`, `parent_id`, `branch_id`, `borrower_id_number`, `file_name`) VALUES (?, ?, ?, ?, ?)");
		 	$in->execute(array($borrower_id, $parent_id, $branch_id, $borrower_ID, $file_name));
		 }
		
		if($ex){
			echo "done";
		}else{
			echo "Error uploading User";
			exit();
		}
		
	}
?>