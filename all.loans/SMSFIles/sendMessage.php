<?php
	include '../includes/db.php';
	$url = "https://gatewayapi.com/rest/mtsms";
	$api_token = "S0nnEZZgQZCu2GDjZIrVRIfiy_D_EMA2sPsLRbHkPtiXu3KpRmZZDMNaCVhifSPb";

	//Set SMS recipients and content
	foreach ($checked_user as $phone) {
	    $$recipients .= $phone.',';
	}
	$receiver = rtrim($receiver, ',');

	$recipients = [+260974904142, ];
	$json = [
	    'sender' => 'Osabox',
	    'message' => 'This is the message to you...',
	    'recipients' => [],
	];
	foreach ($recipients as $msisdn) {
		
	    $json['recipients'][] = ['msisdn' => $msisdn];
	}

	//Make and execute the http request
	//Using the built-in 'curl' library
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
	$id =  $json->ids;



	
?>