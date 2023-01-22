<?php
	include('../includes/db.php');
	if (isset($_POST['firstname'])) {
		$photo 			= $_FILES['photo']['name'];
		$filename 		= $_FILES['photo']['tmp_name'];
		$destination    = 'adminphotos/'.basename($photo);
		$firstname		= filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
		$lastname 		= filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
		// $gender 		= filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
		// $country 		= filter_var($_POST['staff_country'], FILTER_SANITIZE_STRING);
		$email 			= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		// $address 		= filter_var($_POST['address'], FILTER_SANITIZE_STRING);
		$phone 			= filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
		$user_role 		= filter_var($_POST['staff_role'], FILTER_SANITIZE_STRING);
		$parent_id 		= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$staff_id 		= preg_replace("#[^0-9]#", "", $_POST['staff_id']);
		$staff_permission = filter_var($_POST['staff_permission'], FILTER_SANITIZE_STRING);

		$branches = "";
		if ($staff_permission == "yes") {
			$password  = passwordGenerate();
			$pass_word = password_hash($password, PASSWORD_DEFAULT);

		}elseif($staff_permission == "no" OR $staff_permission == ""){
			$password  	= "nil";
			$pass_word = "nil";
		}

		if ($firstname == "" ) {
			echo 'Please staff names';
			exit();
		}

		if ($staff_permission == 'yes') {
			# code...
			if (!isset($_POST['branches'])) {
				echo "Please select atleast one branch where the staff will log in";
				exit();
			}
		}
		if (!isset($_POST['branchID'])) {
			# code...
			$branch = "";
			$allowed_branches = "";
		}else{
			foreach ((array) $_POST['branchID'] as $key => $value) {
				$branches .= $value. ', ';
			}
		
			$branch = rtrim($branches, ", ");
			$allowed_branches = rtrim($branches, ", ");
		}
		
		$photo_hidden = $_POST['photo_hidden'];

		if ($photo != "") {
			move_uploaded_file($filename, $destination);
		}else{
			$photo = $photo_hidden;
		}
		$query = $connect->prepare("SELECT * FROM admins WHERE id = ? AND parent_id = ? ");
		$query->execute(array($staff_id, $parent_id));
		$rows = $query->fetch();
		if ($rows) {
			if ($rows['password'] == "" OR $rows['password'] == 'nil') {
				# Update user and add Password
				$sql = $connect->prepare("UPDATE `admins` SET firstname = ?, lastname = ?,  email = ?, password = ?, pass_w = ?, phonenumber = ?, photo = ?, position = ? WHERE id = ? AND parent_id = ? ");
				$ex = $sql->execute(array($firstname, $lastname, $email, $pass_word, $password, $phone, $photo, $user_role, $staff_id, $parent_id));

				if($ex){
					echo "done";
					
					$del = $connect->prepare("DELETE FROM allowed_branches WHERE staff_id = ? AND parent_id = ? ");
					$del->execute(array($staff_id, $parent_id));
					if (!isset($_POST['branchID'])) {
						# do nothing
					}else{
						foreach ((array) $_POST['branchID'] as $key => $value) {
							$branch_id = $value;
							$query = $connect->prepare("INSERT INTO `allowed_branches`(`staff_id`, `parent_id`, `branch_id`) VALUES (?, ?, ? )");
							$query->execute(array($staff_id, $parent_id, $branch_id));
							
						}
					}
				}else{
					echo "Error uploading User";
					exit();
				} 
			}elseif ($rows['password'] != "") {
				// echo "Password is not blank";
				$sql = $connect->prepare("UPDATE `admins` SET firstname = ?, lastname = ?, email = ?, phonenumber = ?, photo = ?, position = ? WHERE id = ? AND parent_id = ? ");
				$ex = $sql->execute(array($firstname, $lastname, $email, $phone, $photo, $user_role, $staff_id, $parent_id));

				if($ex){
					echo "done";
					
					$del = $connect->prepare("DELETE FROM allowed_branches WHERE staff_id = ? AND parent_id = ? ");
					$del->execute(array($staff_id, $parent_id));
					if (!isset($_POST['branchID'])) {
						# do nothing
					}else{
						foreach ((array) $_POST['branchID'] as $key => $value) {
							$branch_id = $value;
							$query = $connect->prepare("INSERT INTO `allowed_branches`(`staff_id`, `parent_id`, `branch_id`) VALUES (?, ?, ? )");
							$query->execute(array($staff_id, $parent_id, $branch_id));
							
						}
					}
				}else{
					echo "Error uploading User";
					exit();
				}
			}
		}

		
		
	}
	$connect = null;
?>