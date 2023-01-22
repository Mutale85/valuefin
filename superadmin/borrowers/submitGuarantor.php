<?php
	include('../includes/db.php');
	if (!empty($_POST['guarantor_id'])) {
		$guarantor_id 		= $_POST['guarantor_id'];
		$branch_id 			= preg_replace("#[^0-9]#", "", $_POST['branch_id']);
		$parent_id 			= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$firstname 			= filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
		$lastname 			= filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
		$gender  			= filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
		$identity_number 	= filter_var($_POST['identity_number'], FILTER_SANITIZE_STRING);
		$country 			= filter_var($_POST['country'], FILTER_SANITIZE_STRING);
		$city 				= filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		$address 			= filter_var($_POST['address'], FILTER_SANITIZE_STRING);
		$email 				= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		$phone 				= filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
		$dateofbirth 		= preg_replace("#[^0-9]#", "-", filter_var($_POST['dateofbirth']));
		$working_status 	= filter_var($_POST['working_status'], FILTER_SANITIZE_STRING);
		$photo 				= $_POST['photo'];
		// $files 				= $_POST['guarantor_files'];
		
		if ($branch_id == "") {
			$branch_id = $initial_branch;
		}

		if ($firstname == "" ) {
			echo 'Please borrowers names';
			exit();
		}
		
		if ($_FILES['photo_image']['name'] == "") {
			$photo = $photo;
		
		}else{

			$photo 	= $_FILES['photo_image']['name'];
			$filename 					= $_FILES['photo_image']['tmp_name'];
			$destination = '../fileuploads/'.basename($photo);
			move_uploaded_file($filename, $destination);
			
		}
		$files = "";
		// if(!empty($_POST['files'])){
		foreach ($_FILES['guarantor_files']['name'] as $key => $value) {
		 	$files .= $value. ', ';
		 	if ($files != "") {
		 		$file_name = $_FILES['guarantor_files']['tmp_name'][$key];
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
		
		$sql = $connect->prepare("UPDATE guarantors SET firstname = ?, lastname = ?, gender = ?, identity_number = ?, country = ?, city = ?, address = ?, email = ?, phone = ?, dateofbirth = ?, working_status = ?, photo = ?, files = ? WHERE id = ?  AND parent_id = ? ");
		$ex = $sql->execute(array($firstname, $lastname, $gender, $identity_number, $country, $city, $address, $email, $phone, $dateofbirth, $working_status, $photo, $files, $guarantor_id, $parent_id));
		if($ex){
			echo "Guarantor Information Updated";
		}else{
			echo "Error uploading User";
			exit();
		}
		
	}else {
		if(isset($_COOKIE['BORROWERID'])){
			$borrower_id = $_COOKIE['BORROWERID'];
		}else{
			$borrower_id    = filter_var($_POST['borrower_id'], FILTER_SANITIZE_STRING);
		}
		
		$branch_id		= preg_replace("#[^0-9]#", "", $_POST['branch_id']);
		$parent_id 		= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$firstname 		= filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
		$lastname 		= filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
		$gender  		= filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
		$identity_number= filter_var($_POST['identity_number'], FILTER_SANITIZE_STRING);
		$country 		= filter_var($_POST['country'], FILTER_SANITIZE_STRING);
		$city 			= filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		$address 		= filter_var($_POST['address'], FILTER_SANITIZE_STRING);
		$email 			= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		$phone 			= filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
		$dateofbirth 	= $_POST['dateofbirth'];
		$working_status = filter_var($_POST['working_status'], FILTER_SANITIZE_STRING);
		$photo_image 	= $_FILES['photo_image']['name'];
		$filename 		= $_FILES['photo_image']['tmp_name'];
		$destination 	= 'guarantor_uploads/'.basename($photo_image);
		move_uploaded_file($filename, $destination);
		$guarantor_files = "";
		
		if ($firstname == "" ) {
			echo 'Please add guarantors names';
			exit();
		}
		foreach ($_FILES['guarantor_files']['name'] as $key => $value) {
		 	$guarantor_files .= $value. ', ';
		 	$file_name = $_FILES['guarantor_files']['tmp_name'][$key];
		 	$destination2 = 'guarantor_uploads/'.basename($value);
		 	move_uploaded_file($file_name, $destination2);
		} 

		$files =  rtrim($guarantor_files, ", ");

		$query = $connect->prepare("SELECT * FROM guarantors WHERE identity_number = ? AND parent_id = ? AND borrower_id = ? ");
		$query->execute(array($identity_number, $parent_id, $borrower_id));
		if ($query->rowCount() > 0) {
			echo 'Guarantor ID: '. $identity_number. ' is already registered';
			exit();
		}

		$sql = $connect->prepare("INSERT INTO `guarantors`(borrower_id, branch_id, parent_id, `firstname`, `lastname`, `gender`, `identity_number`, `country`, `city`, `address`, `email`, `phone`, `dateofbirth`, `working_status`, `photo`, `files`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
		$ex = $sql->execute(array($borrower_id, $branch_id, $parent_id, $firstname, $lastname, $gender, $identity_number, $country, $city, $address, $email, $phone, $dateofbirth, $working_status, $photo_image, $files));
		$id = $connect->lastInsertId();
		// we insert loan officers, 
		$date_added = date("Y-m-d");

		foreach ($_FILES['guarantor_files']['name'] as $key => $value) {
		 	$file_name = $value;
		 	$in = $connect->prepare("INSERT INTO `guarantor_files`(`guarantor_id`, `borrower_id`, `parent_id`, `branch_id`, `file_name`) VALUES (?, ?, ?, ?, ?)");
		 	$in->execute(array($id, $borrower_id, $parent_id, $branch_id, $file_name));
		 }
		
		if($ex){
			echo "Guarantor Added Successfully";
		}else{
			echo "Error uploading User";
			exit();
		}
		
	}
?>