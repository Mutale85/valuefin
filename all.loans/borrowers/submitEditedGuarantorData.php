<?php
	include('../includes/db.php');
	if (isset($_POST['firstname'])) {
		$initial_branch = preg_replace("#[^0-9]#", "", $_POST['initial_branch']);
		$branch_id 		= preg_replace("#[^0-9]#", "", $_POST['branch_id']);
		$parent_id 		= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$firstname 		= filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
		$lastname 			= filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
		$business 			= filter_var($_POST['business'], FILTER_SANITIZE_STRING);
		$gender  			= filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
		$ID 				= filter_var($_POST['ID'], FILTER_SANITIZE_STRING);
		$country 			= filter_var($_POST['country'], FILTER_SANITIZE_STRING);
		$city 				= filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		$address 			= filter_var($_POST['address'], FILTER_SANITIZE_STRING);
		$email 			= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		$phone 			= filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
		$dateofbirth 		= preg_replace("#[^0-9]#", "-", filter_var($_POST['dateofbirth']));
		$working_status 	= filter_var($_POST['working_status'], FILTER_SANITIZE_STRING);
		$photo 			=  $_POST['photo'];
		$files 			=  $_POST['files'];
		$assigned_officers 			=  $_POST['assigned_officers'];
		$user_id 					= preg_replace("#[^0-9]#", "", $_POST['user_id']);

		if ($branch_id == "") {
			$branch_id = $initial_branch;
		}


		if ($firstname == "" ) {
			echo 'Please borrowers names';
			exit();
		}
		
		if ($_FILES['photo']['name'] == "") {
			$photo = $photo;
		
		}else{

			$photo 	= $_FILES['photo']['name'];
			$filename 					= $_FILES['photo']['tmp_name'];
			$destination = '../fileuploads/'.basename($photo);
			move_uploaded_file($filename, $destination);
			

		}
		$files = "";
		// if(!empty($_POST['files'])){
		foreach ($_FILES['files']['name'] as $key => $value) {
		 	$files .= $value. ', ';
		 	if ($files != "") {
		 		$file_name = $_FILES['files']['tmp_name'][$key];
			 	$destination2 = '../fileuploads/'.basename($value);
			 	move_uploaded_file($file_name, $destination2);
		 	}else{

		 	}
		 	
		} 
		// }

		$files =  rtrim($files, ", ");
		if ($files == "") {
			$files = $files;
		}
		
		$sql = $connect->prepare("UPDATE guarantors SET branch_id = ?, firstname = ?, lastname = ?, business = ?, gender = ?, identity_number = ?, country = ?, city = ?, address = ?, email = ?, phone = ?, dateofbirth = ?, working_status = ?, photo = ?, files = ?, loan_officers = ? WHERE id = ?  AND parent_id = ? ");
		$ex = $sql->execute(array($branch_id, $firstname, $lastname, $business, $gender, $identity_number, $country, $city, $address, $email, $phone, $dateofbirth, $working_status, $photo, $files, $officers, $user_id, $parent_id));
		if($ex){
			echo "done";
		}else{
			echo "Error uploading User";
			exit();
		}
		
	}
?>