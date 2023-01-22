<?php
	include('../includes/db.php');
	if (isset($_POST['borrower_firstname'])) {
		$initial_branch 			= preg_replace("#[^0-9]#", "", $_POST['initial_branch']);
		$branch_id 					= preg_replace("#[^0-9]#", "", $_POST['branch_id']);
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
		$borrower_phone 			= preg_replace("#[^0-9]#", "", $_POST['borrower_phone']);
		$borrower_dateofbirth 		= preg_replace("#[^0-9]#", "-", filter_var($_POST['borrower_dateofbirth']));
		$borrower_working_status 	= filter_var($_POST['borrower_working_status'], FILTER_SANITIZE_STRING);
		$borrower_photo 			=  $_POST['borrower_photo'];
		$borrower_files 			=  $_POST['borrower_files'];
		$assigned_officers 			=  $_POST['assigned_officers'];
		$user_id 					= preg_replace("#[^0-9]#", "", $_POST['user_id']);

		if ($branch_id == "") {
			$branch_id = $initial_branch;
		}


		if ($borrower_firstname == "" ) {
			echo 'Please borrowers names';
			exit();
		}
		
		if ($_FILES['borrower_borrower_photo']['name'] == "") {
			$borrower_borrower_photo = $borrower_photo;
		
		}else{

			$borrower_borrower_photo 	= $_FILES['borrower_borrower_photo']['name'];
			$filename 					= $_FILES['borrower_borrower_photo']['tmp_name'];
			$destination = '../fileuploads/'.basename($borrower_borrower_photo);
			move_uploaded_file($filename, $destination);
			
		}
		$borrower_borrower_files = "";
		// if(!empty($_POST['borrower_borrower_files'])){
		foreach ($_FILES['borrower_borrower_files']['name'] as $key => $value) {
		 	$borrower_borrower_files .= $value. ', ';
		 	if ($borrower_borrower_files != "") {
		 		$file_name = $_FILES['borrower_borrower_files']['tmp_name'][$key];
			 	$destination2 = '../fileuploads/'.basename($value);
			 	move_uploaded_file($file_name, $destination2);
		 	}else{

		 	}
		 	
		} 
		// }

		$files =  rtrim($borrower_borrower_files, ", ");
		if ($files == "") {
			$files = $borrower_files;
		}
		
		$loan_officers = $officers = "";
		if (isset($_POST['loan_officer'])) {
			foreach ((array) $_POST['loan_officer'] as $key => $value) {
		 		$loan_officers .= $value. ', ';
			}
			$officers = rtrim($loan_officers, ", ");

			
		}
		if ($officers == "") {
			$officers = $assigned_officers;
		}
		// echo $branch_id;
		
		$sql = $connect->prepare("UPDATE borrowers SET branch_id = ?, borrower_firstname = ?, borrower_lastname = ?, borrower_business = ?, borrower_gender = ?, borrower_ID = ?, borrower_country = ?, borrower_city = ?, borrower_address = ?, borrower_email = ?, borrower_phone = ?, borrower_dateofbirth = ?, borrower_working_status = ?, borrower_borrower_photo = ?, borrower_borrower_files = ?, loan_officers = ? WHERE id = ?  AND parent_id = ? ");
		$ex = $sql->execute(array($branch_id, $borrower_firstname, $borrower_lastname, $borrower_business, $borrower_gender, $borrower_ID, $borrower_country, $borrower_city, $borrower_address, $borrower_email, $borrower_phone, $borrower_dateofbirth, $borrower_working_status, $borrower_borrower_photo, $files, $officers, $user_id, $parent_id));
		if($ex){
			echo "done";
		}else{
			echo "Error uploading User";
			exit();
		}
		
	}
?>