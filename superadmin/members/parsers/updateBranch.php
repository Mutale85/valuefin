<?php
	include('../../../includes/db.php');
	if (isset($_POST['branch_name'])) {
		$branch_id 		= preg_replace("#[^0-9]#", "", $_POST['branch_id']);
		$branch_name	= filter_input(INPUT_POST, 'branch_name', FILTER_SANITIZE_SPECIAL_CHARS);
		$open_date 		= filter_input(INPUT_POST, 'open_date', FILTER_SANITIZE_SPECIAL_CHARS);
		$address 		= filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS);
		$city 			= filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS);
		$country 		= filter_input(INPUT_POST, 'country', FILTER_SANITIZE_SPECIAL_CHARS);
		$phone_mobile 	= filter_input(INPUT_POST, 'phone_mobile', FILTER_SANITIZE_SPECIAL_CHARS);
		$member_id 		= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		
		if ($branch_name == "" ) {
			echo 'Please add branch name';
			exit();
		}
		
		$sql = $connect->prepare("UPDATE branches SET branch_name = ?, open_date = ?, address = ?, city = ?, country = ?, phone_mobile = ? WHERE id = ? ");
		$ex = $sql->execute(array( $branch_name, $open_date, $address, $city, $country, $phone_mobile, $branch_id));
		if($ex){
			echo "Branch details updated";
		}else{
			echo "Error uploading branch";
			exit();
		}
		
	}
?>