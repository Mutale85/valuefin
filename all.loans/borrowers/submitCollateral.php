<?php
	include '../includes/db.php';
	extract($_POST);
	if (!empty($collateral_id)) {
		# update

		if ($_FILES['photo']['name'] == "") {
			$photo = $col_photo;
		}else{
			$photo 	  = $_FILES['photo']['name'];
			$filename = $_FILES['photo']['tmp_name'];
			$destination = 'collateral_uploads/'.basename($photo);
			move_uploaded_file($filename, $destination);
		}


		if($_FILES['files']['size'] != 0){
			$file = "";
			$get = $connect->prepare("DELETE FROM `collaterals_files` WHERE collateral_id = ? AND borrower_id = ? ");
			$get->execute(array($collateral_id, $borrower_id));
			foreach ((array) $_FILES['files']['name'] as $key => $value) {
				
				if ($value == "") {
					
				}else{
					$file .= $value.', ';
					$filename = $_FILES['files']['tmp_name'][$key];
					$destination = 'collateral_uploads/'.basename($value);
					move_uploaded_file($filename, $destination);
					$insert = $connect->prepare("INSERT INTO `collaterals_files`(`collateral_id`, `borrower_id`, `filename`) VALUES (?, ?, ?) ");
					$insert->execute(array($collateral_id, $borrower_id, $value));
				}
				
			}
			$files = rtrim($file, ", ");
		}else{
			
		}
		

		$update = $connect->prepare("UPDATE  collaterals  SET  collateral_type = ?, product_name = ?, register_date = ?, product_value = ?, currency = ?, product_location = ?, action_date = ?, address = ?, serial_number = ?, model_name = ?, model_number = ?, color = ?, manufature_date = ?, product_condition = ?, description = ?, photo = ?, files = ?, vehicle_reg_number = ?, millage = ?, vehicle_engine_num = ? WHERE id = ? ");
		if($update->execute(array($collateral_type, $product_name, $register_date, $product_value, $currency, $product_location, $action_date, $address,  $serial_number, $model_name, $model_number, $color, $manufature_date, $product_condition, $description, $photo, $files, $vehicle_reg_number, $millage, $vehicle_engine_num, $collateral_id))){
			
			echo 'Collaterals details updated';
		
		}

	}else{
		if (isset($_COOKIE['BORROWERID'])) {
			$borrower_id = $_COOKIE['BORROWERID'];
		}else{
			$borrower_id = $borrower_id;
		}
		// $borrower_id = $_COOKIE['BORROWERID'];
		$file = "";
		
		foreach ($_FILES['files']['name'] as $key => $value) {
			$file .= $value.', ';
			$filename = $_FILES['files']['tmp_name'][$key];
			$destination = 'collateral_uploads/'.basename($value);
			move_uploaded_file($filename, $destination);
		}
		$files = rtrim($file, ', ');

		$photo = $_FILES['photo']['name'];
		$file_name = $_FILES['photo']['tmp_name'];
		$destination = 'collateral_uploads/'.basename($photo);
		move_uploaded_file($file_name, $destination);

		$sql = $connect->prepare("INSERT INTO `collaterals`(`collateral_type`, `branch_id`, `parent_id`, `borrower_id`, `product_name`, `register_date`, `product_value`, `currency`, `product_location`, `action_date`, `address`, `serial_number`, `model_name`, `model_number`, `color`, `manufature_date`, `product_condition`, `description`, `photo`, `files`, `vehicle_reg_number`, `millage`, `vehicle_engine_num`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$ex = $sql->execute(array($collateral_type, $branch_id, $parent_id, $borrower_id, $product_name, $register_date, $product_value, $currency, $product_location, $action_date, $address,  $serial_number, $model_name, $model_number, $color, $manufature_date, $product_condition, $description, $photo, $files, $vehicle_reg_number, $millage, $vehicle_engine_num));

		$collateral_id = $connect->lastInsertId();

		if ($ex) {
			foreach ($_FILES['files']['name'] as $key => $value) {
				$filename = $value;
				$insert = $connect->prepare("INSERT INTO `collaterals_files`(`collateral_id`, `borrower_id`, `filename`) VALUES (?, ?, ?) ");
				$insert->execute(array($collateral_id, $borrower_id, $filename));
				
			}
			echo "Collaterals details Saved";
		}
	}

?>