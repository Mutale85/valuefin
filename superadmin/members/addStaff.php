<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	include('../includes/db.php');
	require '../PHPMailer/src/Exception.php';
	require '../PHPMailer/src/PHPMailer.php';
	require '../PHPMailer/src/SMTP.php';
	if (isset($_POST['firstname'])) {
		$photo 			= $_FILES['photo']['name'];
		$filename 		= $_FILES['photo']['tmp_name'];
		$destination    = 'adminphotos/'.basename($photo);
		$firstname		= filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
		$lastname 		= filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
		$email 			= filter_var($_POST['email'], FILTER_SANITIZE_STRING);
		$phonenumber 	= filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
		$position 		= filter_var($_POST['staff_role'], FILTER_SANITIZE_STRING);
		$parent_id 		= preg_replace("[^0-9]", "", $_POST['parent_id']);
		$permission 	= $_POST['staff_permission'];

		// get email settings --
		$query = $connect->prepare("SELECT * FROM emailSettingForm WHERE parent_id = ?  ");
		$query->execute(array($parent_id));
		$row = $query->fetch();
		if ($row) {
			$smtp_server = $row['smtp_server'];
			$smtp_port = $row['smtp_port'];
			$sender_email = $row['sender_email'];
			$sender_password = $row['sender_password'];
		}
		
		if ($permission == "yes") {
			$password  	= passwordGenerate();
			$pass_w = base64_encode($password);
			$hashed_pasword = password_hash($password, PASSWORD_DEFAULT);

		}elseif($permission == "no"){
			$password  	= "nil";
			$pass_word = "nil";
		}
		$branches = "";
		
		if ($photo == "") {
			echo "Please add photo";
			exit();
		}
		if ($firstname == "" ) {
			echo 'Please staff names';
			exit();
		}
		$branches = $allowed_branches = "";
		if ($permission == 'yes') {
			# code...
			if (!isset($_POST['branches'])) {
				echo "Please Select Atleast one branch where the staff will log in";
				exit();
			}

			// send email and sms to the admin.
			$msg = '
				<!doctype html>
	    			<html lang="en-US">
	              	<head>
						<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
						<title>Chuma Solutions</title>
						<meta name="description" content="Admin Email '.$firstname.'">
						<style type="text/css">
						    a:hover {text-decoration: none !important;}
						      th, td {
						        text-align: left;
						        padding: 16px;
						        border-bottom:1px solid #ddd;
						    }
						    
						    
	    				</style>
	  				</head>
					<body style="font-family:sans-serif; background-color: #f2f3f8;" marginheight="0" topmargin="0" marginwidth="0" leftmargin="0">
						<div class="logo" style="margin:20px auto;width:80px;height:80px;">
							'.getOrganisationLogo($connect, $_SESSION['parent_id']).'
						</div>
						<div class="messageBody" style="background-color: #ffffff; max-width:670px; margin:0 auto;padding:25px;">
							<h1 class="title" style="text-align:center;margin-bottom:20px; font-size:1.2em;">Hello '.$firstname.'</h1>        
							<h4 align="center">Welcome</h4><hr>
	            			<p>You have been added as '.ucwords($position).' for '. ucwords(getOrganisationName($connect, $_SESSION['parent_id'])).'</p>
	            			<p>You login credentials are as follows:</p>
	            			<p>Email: '.$email.'</p>
	            			<p>Password: '.$password.'</p>
							<br><br>				
						</div>
						'.getOrganisationFooterDetails($connect, $_SESSION['parent_id']).'
					</body>
				</html>';
				
			$subject = 'You are Admin';
			$mail = new PHPMailer(true);
			$sender_name = getStaffMemberNames($connect, $_SESSION['user_id'], $_SESSION['parent_id']);
			try {
			    //Server settings
			    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
			                          //Enable verbose debug output
			    $mail->isSMTP();                                            //Send using SMTP
			    $mail->Host       = "smtp.zoho.com";                     //Set the SMTP server to send through
			    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			    $mail->Username   = "info@chumasolutions.com";                     //SMTP username
			    $mail->Password   = "Chumasolutions@2022";                               //SMTP password
			    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
			    $mail->Port       = $smtp_port;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

			    $mail->setFrom($sender_email, $sender_name);
			    $mail->addAddress($email, $firstname);     //Add a recipient
			    $mail->addReplyTo($sender_email, $sender_name);

			    //Attachments
				// $mail->addAttachment($path);         //Add attachments
			   
			    $mail->isHTML(true);                                  //Set email format to HTML
			    $mail->Subject = $subject;
			    $mail->Body    = $msg;
			    $mail->AltBody = $msg;

			    $mail->send();
			   	
				
			} catch (Exception $e) {
			    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		}
		if (isset($_POST['branches'])) {
			foreach ((array) $_POST['branches'] as $key => $value) {
				$branches .= $value. ', ';
			}
			$branch = rtrim($branches, ", ");
		}else{
			$branch = '';
		}
		
		$query = $connect->prepare("SELECT * FROM admins WHERE phonenumber = ? AND parent_id = ? ");
		$query->execute(array($phonenumber, $parent_id));
		if ($query->rowCount() > 0) {
			echo 'Member of Staff with phone number '. $phonenumber. ' is already registered';
			exit();
		}
		
		move_uploaded_file($filename, $destination);
		if (isset($_POST['branches'])) {
			foreach ((array) $_POST['branches'] as $key => $branch_id) {
				$allowed_branches .= $branch_id.', '; 
				
			}
			$allowed_branches = rtrim($allowed_branches, ", ");
		}else{
			$allowed_branches = "";
		}
		
		$query = $connect->prepare("SELECT * FROM admins WHERE parent_id = ? ");
		$query->execute(array($parent_id));
		$row = $query->fetch();
		if ($row) {
			$new_plan = $row['plan'];
			$new_price= $row['price'];
			$new_currency = $row['currency'];
			$new_transaction_id = $row['transaction_id'];
			$new_start_date = $row['start_date'];
			$new_end_date = $row['end_date'];
		}

		$activate = 1;
		$admin_role = 'admin';
		$sql = $connect->prepare("INSERT INTO `admins`(`firstname`, `lastname`, `email`, `password`, `pass_w`, `phonenumber`, `activate`, `parent_id`, `admin_role`, `plan`, `price`, `currency`, `transaction_id`, `photo`, `start_date`, `end_date`, `position`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
		$ex = $sql->execute(array($firstname, $lastname, $email, $hashed_pasword, $pass_w, $phonenumber, $activate, $parent_id, $admin_role, $new_plan, $new_price, $new_currency, $new_transaction_id, $photo, $new_start_date, $new_end_date, $position));
		$staff_id = $connect->lastInsertId();
		//insert in the allowed branches table
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