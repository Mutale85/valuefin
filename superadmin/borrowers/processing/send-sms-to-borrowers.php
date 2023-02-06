<?php
	include '../../../includes/db.php';
	include '../../../includes/conf.php';
	extract($_POST);
	
	$message = $sms;
	$receiver = "";
	foreach ($checked_user as $phone) {
	    $receiver .= $phone.',';
	}
	$receiver = rtrim($receiver, ',');
	$api_key = API;
	$sender_id = SENDER;
	
	$url = 'https://bulksms.zamtel.co.zm/api/v2.1/action/send/api_key/'.$api_key.'/contacts/'.$receiver.'/senderId/'.$sender_id.'/message/'.$message.'';
	$gateway_url = $url;
	try {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $gateway_url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HTTPGET, 1);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	    $output = curl_exec($ch);

	    if (curl_errno($ch)) {
	        $output = curl_error($ch);
	    }
	    curl_close($ch);

	   	$result = json_decode($output);
	   	$success = $result->success;
	   	$responseText =  $result->responseText;

	   	if ($responseText == 'SMS(es) have been queued for delivery') {
	   		$response = 'SMS sent to successfully';
	   	}else{
	   		$response = $responseText;
	   	}
	   	if ($success == 0) {
	   		echo $responseText;

	   	}else{
	   		foreach ($checked_user as $phone) {
			    $sql = $connect->prepare("INSERT INTO `sms`(`receiver`, `sender_id`, `parent_id`, `branch_id`, `message`, `responseText`) VALUES (?, ?, ?, ?, ?, ?) ");
			    $sql->execute(array($phone, $sender_id, $parent_id, $branch_id, $message, $response));
			}
			
	   		echo $response;
	   	}
	   

	}catch (Exception $exception){
	    echo $exception->getMessage();
	}
?>