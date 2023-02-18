<?php
	include '../../includes/db.php';
	include '../../includes/conf.php';
	$api = API;
	$sender_id = SENDER;
	
	extract($_POST);
	if(!empty($sms)){
		$parent_id = $_SESSION['parent_id'];
		// $branch_id = base64_decode($_COOKIE['selectedBranch']);
		foreach($checked_user as $to){
			echo SMSNOW($to, $sms, $api, $sender_id);
			// insert into SMS
			$response = 'message_successful';
			$sql = $connect->prepare("INSERT INTO `sms`(`receiver`, `sender_id`, `parent_id`, `branch_id`, `message`, `responseText`) VALUES (?, ?, ?, ?, ?, ?) ");
			$sql->execute(array($to, $sender_id, $parent_id, $branch_id, $sms, $response));
		}
	}
	echo "SMS Sent";



	
?>