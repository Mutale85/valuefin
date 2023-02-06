<?php
	include('../includes/db.php');
	if (isset($_POST['branch_name'])) {
		$branch_name	= filter_var($_POST['branch_name'], FILTER_SANITIZE_STRING);
		$branch_unique_id = preg_replace("[^0-9]", "", $_POST['branch_unique_id']);
		$open_date 		= filter_var($_POST['open_date'], FILTER_SANITIZE_STRING);
		$address 		= filter_var($_POST['address'], FILTER_SANITIZE_STRING);
		$city 			= filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		$country 		= filter_var($_POST['country'], FILTER_SANITIZE_STRING);
		$phone_landline = filter_var($_POST['phone_landline'], FILTER_SANITIZE_STRING);
		$phone_mobile 	= filter_var($_POST['phone_mobile'], FILTER_SANITIZE_STRING);
		$currency 		= filter_var($_POST['currency'], FILTER_SANITIZE_STRING);
		$member_id 		= preg_replace("[^0-9]", "", $_POST['parent_id']);			
		if ($branch_name == "" ) {
			echo 'Please add branch name';
			exit();
		}
		
		$query = $connect->prepare("SELECT * FROM branches WHERE branch_name = ? AND member_id = ? ");
		$query->execute(array($branch_name, $member_id));
		if ($query->rowCount() > 0) {
			echo 'Branch name '. $branch_name. ' is already registered';
			exit();
		}
		$sql = $connect->prepare("INSERT INTO `branches`(`branch_unique_id`, `member_id`, `branch_name`, `open_date`, `address`, `city`, `country`, `phone_landline`, `phone_mobile`, `currency`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
		$ex = $sql->execute(array($branch_unique_id, $member_id, $branch_name, $open_date, $address, $city, $country, $phone_landline, $phone_mobile, $currency));
		$branch_id = $connect->lastInsertId();
		$staff_id = $_SESSION['user_id'];
		$parent_id = $_POST['parent_id'];
		if($ex){
			echo $branch_name . " Created";
			$sql = $connect->prepare("INSERT INTO `allowed_branches`(`staff_id`, `parent_id`, `branch_id`) VALUES (?, ?, ? )");
			$sql->execute(array($staff_id, $parent_id, $branch_id));
		}else{
			echo "Error uploading User";
			exit();
		}
		
	}
	$connect = null;
?>