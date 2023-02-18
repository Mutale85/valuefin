<?php
	include('../../../includes/db.php');
	if (isset($_POST['branch_name'])) {
		$branch_name	= filter_input(INPUT_POST, 'branch_name', FILTER_SANITIZE_SPECIAL_CHARS);
		$open_date 		= date("Y-m-d", strtotime($_POST['open_date']));
		$address 		= filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS);
		$city 			= filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS);
		$country 		= filter_input(INPUT_POST, 'country', FILTER_SANITIZE_SPECIAL_CHARS);
		
		$phone_mobile 	= filter_input(INPUT_POST, 'phone_mobile', FILTER_SANITIZE_SPECIAL_CHARS);
		$currency 		= filter_input(INPUT_POST, 'currency', FILTER_SANITIZE_SPECIAL_CHARS);
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
		$sql = $connect->prepare("INSERT INTO `branches`( `member_id`, `branch_name`, `open_date`, `address`, `city`, `country`, `phone_mobile`) VALUES ( ?, ?, ?, ?, ?, ?, ?) ");
		$ex = $sql->execute(array( $member_id, $branch_name, $open_date, $address, $city, $country, $phone_mobile));
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