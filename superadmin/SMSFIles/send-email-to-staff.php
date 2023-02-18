<?php
	include '../includes/db.php';
	extract($_POST);
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	require '../PHPMailer/src/Exception.php';
	require '../PHPMailer/src/PHPMailer.php';
	require '../PHPMailer/src/SMTP.php';
	// require 'vendor/autoload.php';
	
	$query = $connect->prepare("SELECT * FROM emailSettingForm WHERE parent_id = ?  ");
	$query->execute(array($parent_id));
	$row = $query->fetch();
	if ($row) {
		$sender_name = $row['sender_name'];
		$smtp_server = $row['smtp_server'];
		$smtp_port = $row['smtp_port'];
		$sender_email = $row['sender_email'];
		$sender_password = $row['sender_password'];
	}
	$attachment = $_FILES['attachment']['name'];
	$filename = $_FILES['attachment']['tmp_name'];
	$destination = 'emailfiles/'.basename($attachment);

	$path = 'emailfiles/'.$_FILES['attachment']['name'];
	move_uploaded_file($filename, $destination);
	// echo $smtp_server;
	foreach ($reciever_email as $email) {	
		$msg = '
			<!doctype html>
    			<html lang="en-US">
              	<head>
					<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
					<title>Email</title>
					<meta name="description" content="Loan Email For '.getStaffMemberNamesByEmail($connect, $email, $parent_id).'">
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
						<h1 class="title" style="text-align:center;margin-bottom:20px; font-size:1.2em;">Hello '.getStaffMemberNamesByEmail($connect, $email, $parent_id).'</h1>        
						<h4 align="center">'.$subject.'</h4><hr>
            			<p>'.$message.'</p>
						<br><br>				
					</div>
					'.getOrganisationFooterDetails($connect, $_SESSION['parent_id']).'
				</body>
			</html>
		';
	
	    
		$mail = new PHPMailer(true);
		try {
		    //Server settings
		    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
		    $mail->isSMTP();                                            //Send using SMTP
		    $mail->Host       = $smtp_server;                     //Set the SMTP server to send through
		    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		    $mail->Username   = $sender_email;                     //SMTP username
		    $mail->Password   = $sender_password;                               //SMTP password
		    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
		    $mail->Port       = $smtp_port;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

		    $mail->setFrom($sender_email, $sender_name);
		    $mail->addAddress($email, getBorrowerFullNamesByEmail($connect, $email));     //Add a recipient
		    $mail->addReplyTo($sender_email, $sender_name);

		    //Attachments
		    if($_FILES['attachment']['name'] != ""){
				$mail->addAttachment($path);         //Add attachments
		   	}
		    $mail->isHTML(true);                                  //Set email format to HTML
		    $mail->Subject = $subject;
		    $mail->Body    = $msg;
		    $mail->AltBody = $msg;

		    $mail->send();
		    echo 'done';
		    $sql = $connect->prepare("INSERT INTO `sent_emails`(`receiver`, `sender_id`, `parent_id`, `branch_id`, `message`, `filename`) VALUES (?, ?, ?, ?, ?, ?) ");
    		$sql->execute(array($email, $sender_email, $parent_id, $branch_id, $message, $attachment));
			

		} catch (Exception $e) {
		    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}
	
?>