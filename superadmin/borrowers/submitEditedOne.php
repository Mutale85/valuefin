<?php
	include('../includes/db.php');

	if (!empty($_POST['applicant_id'])) {

		$applicant_id = $_POST['applicant_id']; 
		extract($_POST);
		
		$select = $connect->prepare("SELECT * FROM borrowers_details WHERE id = ? ");
		$select->execute(array($applicant_id));
		$row = $select->fetch();
		if($row){
			$BORROWERID = $row['borrower_ID'];

			$photo = "";
			$borrower_photo 			= $_FILES['borrower_photo']['name'];
			$filename 					= $_FILES['borrower_photo']['tmp_name'];
			if ($borrower_photo == "") {

				$photo = $row['borrower_photo'];

			}else{
				$photo = $borrower_photo;
				$destination = '../fileuploads/'.basename($borrower_photo);
				move_uploaded_file($filename, $destination);
			}
			$update = $connect->prepare("UPDATE borrowers_details SET `borrower_photo`= ?,`borrower_title`= ?,`borrower_firstname`= ?,`borrower_lastname`= ?,`borrower_gender`= ?, `borrower_country`= ?,`borrower_city`= ?,`borrower_address`= ?,`borrower_email`= ?,`borrower_phone`= ?,`borrower_dateofbirth`= ?,`borrower_working_status`= ?,`borrower_employer_name`= ?,`borrower_employer_phone`= ?,`borrower_employer_address`= ?,`borrower_business`= ?,`borrower_business_type`= ?,`borrower_business_address` = ? WHERE id = ? AND borrower_ID = ?  ");
			
			$ex = $update->execute(array($photo, $borrower_title, $borrower_firstname, $borrower_lastname, $borrower_gender, $borrower_country, $borrower_city, $borrower_address, $borrower_email, $borrower_phone, $borrower_dateofbirth, $borrower_working_status, $borrower_employer_name, $borrower_employer_phone, $borrower_employer_address, $borrower_business, $borrower_business_type, $borrower_business_address, $applicant_id, $BORROWERID));
			if($ex){
				echo $borrower_firstname . " Details Updated";
			}else{
				echo "Error uploading User";
				exit();
			}
		}

		
	}else {
		$branch_id					= filter_var($_POST['branch_id'], FILTER_SANITIZE_STRING);
		$parent_id 					= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$borrower_title 			= filter_var($_POST['borrower_title'], FILTER_SANITIZE_STRING);
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
		$borrower_employer_name 	= filter_var($_POST['borrower_employer_name'], FILTER_SANITIZE_STRING);
		$borrower_employer_phone 	= filter_var($_POST['borrower_employer_phone'], FILTER_SANITIZE_STRING);
		$borrower_employer_address 	= filter_var($_POST['borrower_employer_address'], FILTER_SANITIZE_STRING);
		$borrower_business 			= filter_var($_POST['borrower_business'], FILTER_SANITIZE_STRING);
		$borrower_business_type 	= filter_var($_POST['borrower_business_type'], FILTER_SANITIZE_STRING);
		$borrower_business_address 	= filter_var($_POST['borrower_business_address'], FILTER_SANITIZE_STRING);
		$borrower_photo 			= $_FILES['borrower_photo']['name'];
		$filename 					= $_FILES['borrower_photo']['tmp_name'];
		$destination = '../fileuploads/'.basename($borrower_photo);
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
		
		$sql = $connect->prepare("INSERT INTO borrowers_details(`branch_id`, `parent_id`, `borrower_photo`, `borrower_title`, `borrower_firstname`, `borrower_lastname`, `borrower_gender`, `borrower_ID`, `borrower_country`, `borrower_city`, `borrower_address`, `borrower_email`, `borrower_phone`, `borrower_dateofbirth`, `borrower_working_status`, `borrower_employer_name`, `borrower_employer_phone`, `borrower_employer_address`, `borrower_business`, `borrower_business_type`, `borrower_business_address`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
		$ex = $sql->execute(array($branch_id, $parent_id, $borrower_photo, $borrower_title, $borrower_firstname, $borrower_lastname, $borrower_gender, $borrower_ID, $borrower_country, $borrower_city, $borrower_address, $borrower_email, $borrower_phone, $borrower_dateofbirth, $borrower_working_status, $borrower_employer_name, $borrower_employer_phone, $borrower_employer_address, $borrower_business, $borrower_business_type, $borrower_business_address));
		$borrower_id = $connect->lastInsertId();
		setcookie("BORROWERID", $borrower_id, time()+60*60*24*7, '/');
		if($ex){
			echo $borrower_firstname . " Details Added";
		}else{
			echo "Error uploading User";
			exit();
		}
	}
?>