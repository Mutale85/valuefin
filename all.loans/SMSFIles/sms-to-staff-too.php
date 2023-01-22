<?php
	include '../../includes/db.php';
	$url = "https://gatewayapi.com/rest/mtsms";
	$api_token = "S0nnEZZgQZCu2GDjZIrVRIfiy_D_EMA2sPsLRbHkPtiXu3KpRmZZDMNaCVhifSPb";

	//Set SMS recipients and content
	$receiver = '';
	extract($_POST);
	
	$sender = getSenderID($connect, $parent_id);
	$message = $sms;
	// print_r($recipients);
	$json = [
	    'sender' => $sender,
	    'message' => $message,
	    'recipients' => [],
	];
	foreach ($checked_user as $msisdn) {
	    $json['recipients'][] = ['msisdn' => $msisdn];
	}


	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	curl_setopt($ch,CURLOPT_USERPWD, $api_token.":");
	curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($json));
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
	print($result);
	$json = json_decode($result);
	print_r($json->ids);

	$response = $json->ids;

	foreach ($checked_user as $phone) {
	    $sql = $connect->prepare("INSERT INTO `sms`(`receiver`, `sender_id`, `parent_id`, `branch_id`, `message`, `responseText`) VALUES (?, ?, ?, ?, ?, ?) ");
	    $sql->execute(array($phone, $sender, $parent_id, $branch_id, $message, $response));
	}
	
?>