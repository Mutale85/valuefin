<?php
	include('../../../includes/db.php');
	if (isset($_POST['firstname'])) {
		$profile_pic 	= $_FILES['photo']['name'];
		$filename 		= $_FILES['photo']['tmp_name'];
		$nrc_copy 		= $_FILES['nrc_copy']['name'];
		$nrc_filename 	= $_FILES['nrc_copy']['tmp_name'];
		$destination    = '../uploads/'.basename($profile_pic);
		$destination2   = '../uploads/'.basename($nrc_copy);

		$firstname		= filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS);
		$lastname 		= filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);
		$nrc_number 	= filter_input(INPUT_POST, 'nrc_number', FILTER_SANITIZE_SPECIAL_CHARS);
		$home_address 	= filter_input(INPUT_POST, 'home_address', FILTER_SANITIZE_SPECIAL_CHARS);
		$phonenumber 	= filter_input(INPUT_POST, 'phonenumber', FILTER_SANITIZE_SPECIAL_CHARS);
		$user_role 		= filter_input(INPUT_POST, 'user_role', FILTER_SANITIZE_SPECIAL_CHARS);
		$parent_id 		= $_SESSION['parent_id'];
		$staff_id 		= $_POST['staff_id'];

		$branches = "";
		
		if ($firstname == "" ) {
			echo 'Please staff names';
			exit();
		}

		
		if (!isset($_POST['branchID'])) {
			$branch = "";
			$allowed_branches = "";
			echo "Please select atleast one branch where the staff will log in";
			exit();
		}else{
			foreach ((array) $_POST['branchID'] as $key => $value) {
				$branches .= $value. ', ';
			}
		
			$branch = rtrim($branches, ", ");
			$allowed_branches = rtrim($branches, ", ");
		}
		
		$photo_hidden = $_POST['photo_hidden'];
		$nrc_copy_hidden = $_POST['nrc_copy_hidden'];

		if ($profile_pic != "") {
			move_uploaded_file($filename, $destination);
		}else{
			$profile_pic = $photo_hidden;
		}
		if ($nrc_copy != "") {
			move_uploaded_file($nrc_filename, $destination2);
		}else{
			$nrc_copy = $nrc_copy_hidden;
		}

		

		
		if($user_role == 'Loan Officer'){
			$update = $connect->prepare("UPDATE `loan_officers` SET `firstname`= ?,`lastname`= ?,`nrc_number`= ?,`nrc_copy`= ?,`phonenumber`= ?,`home_address`= ? WHERE  staff_id = ?");
			$update->execute([$firstname, $lastname,$nrc_number, $nrc_copy, $phonenumber, $home_address, $staff_id]);
		}else if($user_role == 'Admin'){
			$update = $connect->prepare("UPDATE `officers_admin` SET `firstname`= ?,`lastname`= ?,`nrc_number`= ?,`nrc_copy`= ?,`phonenumber`= ?,`home_address`= ? WHERE  staff_id = ?");
			$update->execute([$firstname, $lastname,$nrc_number, $nrc_copy, $phonenumber, $home_address, $staff_id]);
		
		}
		$sql = $connect->prepare("UPDATE `admins` SET `firstname`= ?,`lastname`= ?, `phonenumber`= ?, `profile_pic`= ?, `nrc_number`= ?, `nrc_copy`= ?, `home_address`= ?, `user_role`= ? WHERE Id = ? AND parent_id = ?");
		$ex = $sql->execute([$firstname, $lastname, $phonenumber, $profile_pic, $nrc_number, $nrc_copy, $home_address, $user_role, $staff_id, $parent_id]);

		// insert the selected branches
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
		echo "Personnel details updated";
		
	}
	$connect = null;
?>