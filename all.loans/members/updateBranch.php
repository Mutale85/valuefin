<?php
	include('../includes/db.php');
	if (isset($_POST['branch_name'])) {
		$branch_id 		= preg_replace("#[^0-9]#", "", $_POST['branch_id']);
		$branch_name	= filter_var($_POST['branch_name'], FILTER_SANITIZE_STRING);
		$open_date 		= filter_var($_POST['open_date'], FILTER_SANITIZE_STRING);
		$address 		= filter_var($_POST['address'], FILTER_SANITIZE_STRING);
		$city 			= filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		$country 		= filter_var($_POST['country'], FILTER_SANITIZE_STRING);
		$phone_landline = filter_var($_POST['phone_landline'], FILTER_SANITIZE_STRING);
		$phone_mobile 	= filter_var($_POST['phone_mobile'], FILTER_SANITIZE_STRING);
		$currency 		= filter_var($_POST['currency'], FILTER_SANITIZE_STRING);
		$member_id 		= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		// $address  		= getCountryName($connect, $country);
		
		if ($branch_name == "" ) {
			echo 'Please add branch name';
			exit();
		}
		
		$sql = $connect->prepare("UPDATE branches SET member_id = ?, branch_name = ?, open_date = ?, address = ?, city = ?, country = ?, phone_landline = ?, phone_mobile = ?, currency = ? WHERE id = ? ");
		$ex = $sql->execute(array($member_id, $branch_name, $open_date, $address, $city, $country, $phone_landline, $phone_mobile, $currency, $branch_id));
		if($ex){
			echo "done";
		}else{
			echo "Error uploading User";
			exit();
		}
		
	}
?>