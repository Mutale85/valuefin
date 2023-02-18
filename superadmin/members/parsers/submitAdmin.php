<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	include('../../../includes/db.php');
	include('../../../includes/conf.php');
	$api = API;
	$sender = SENDER;
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
		$phonenumber 	= filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
		$user_role 		= filter_input(INPUT_POST, 'user_role', FILTER_SANITIZE_SPECIAL_CHARS);
		$parent_id 		= $_SESSION['parent_id'];
		$permission 	= $_POST['staff_permission'];

		if ($permission == "yes") {
			$password  	= passwordGenerate();
			$pass_w 	= base64_encode($password);
			$hash 		= password_hash($password, PASSWORD_DEFAULT);

		}elseif($permission == "no"){
			$password  	= "nil";
			$pass_word = "nil";
		}
		$branches = "";
		
		if ($profile_pic == "") {
			echo "Please add staff photo";
			exit();
		}

		if ($firstname == "" ) {
			echo 'Please staff names';
			exit();
		}
		$branches = $allowed_branches = "";
		
		if (isset($_POST['branches'])) {
			foreach ((array) $_POST['branches'] as $key => $value) {
				$branches .= $value. ', ';
			}
			$branch = rtrim($branches, ", ");
		}else{
			$branch = '';
		}
		
		if($user_role == 'Loan Officer'){
			$query = $connect->prepare("SELECT * FROM loan_officers WHERE nrc_number = ? AND parent_id = ? ");
			$query->execute(array($nrc_number, $parent_id));
			if ($query->rowCount() > 0) {
				echo ' Staff with nrc number '. $nrc_copy. ' is already added';
				exit();
			}

		}else if($user_role == 'Admin'){
			$query = $connect->prepare("SELECT * FROM officers_loan WHERE nrc_number = ? AND parent_id = ? ");
			$query->execute(array($nrc_number, $parent_id));
			if ($query->rowCount() > 0) {
				echo ' Staff with nrc number '. $nrc_copy. ' is already added';
				exit();
			}
		}
		if ($permission == 'yes') {
			# code...
			if (!isset($_POST['branches'])) {
				echo "Please Select Atleast one branch where the staff will log in";
				exit();
			}

			// send sms to staff member
			$to = $phonenumber;
			//$to = '260976330092';
			$message = 'Hello '. $firstname. ' you have been added as '.$user_role.' to valuefin.co, your login details are: Username: '.$firstname.', Password: '. $password;
			echo SMSNOW($to, $message, $api, $sender);
			
		}
		
		move_uploaded_file($filename, $destination);
		move_uploaded_file($nrc_filename, $destination2);
		if (isset($_POST['branches'])) {
			foreach ((array) $_POST['branches'] as $key => $branch_id) {
				$allowed_branches .= $branch_id.', '; 
				
			}
			$allowed_branches = rtrim($allowed_branches, ", ");
		}else{
			$allowed_branches = "";
		}
		
		$activate = 1;
		$email = "";

		$sql_main = $connect->prepare("INSERT INTO `admins`(`username`, `firstname`, `lastname`, `email`, `password`, `pass_w`, `phonenumber`, `profile_pic`, `nrc_number`, `nrc_copy`, `home_address`, `activate`, `user_role`, `parent_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$ex = $sql_main->execute([$firstname, $firstname, $lastname, $email, $hash, $pass_w, $phonenumber, $profile_pic, $nrc_number, $nrc_copy, $home_address, $activate, $user_role, $parent_id]);
		$staff_id = $connect->lastInsertId();
		if($user_role == 'Loan Officer'){
			$sql = $connect->prepare("INSERT INTO `loan_officers`(`parent_id`, `staff_id`, `firstname`, `lastname`, `nrc_number`, `nrc_copy`, `phonenumber`, `home_address`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);
			");
			$sql->execute([$parent_id, $staff_id, $firstname, $lastname, $nrc_number, $nrc_copy, $phonenumber, $home_address]);
		}else if($user_role == 'Admin'){
			$sql = $connect->prepare("INSERT INTO `officers_admin`(`parent_id`, `staff_id`, `firstname`, `lastname`, `nrc_number`, `nrc_copy`, `phonenumber`, `home_address`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);
			");
			$sql->execute([$parent_id, $staff_id, $firstname, $lastname, $nrc_number, $nrc_copy, $phonenumber, $home_address]);
		}
		//insert staff in the allowed branches table
		if (isset($_POST['branches'])) {
			foreach ((array) $_POST['branches'] as $key => $branch_id) {
				$sql = $connect->prepare("INSERT INTO `allowed_branches`(`staff_id`, `parent_id`, `branch_id`) VALUES (?, ?, ? )");
				$sql->execute(array($staff_id, $parent_id, $branch_id));
			}
		}
		if($ex){
			echo "Staff Added Successfully";
		}else{
			echo "Error uploading User";
			exit();
		}
	}
	$connect = null;
?>