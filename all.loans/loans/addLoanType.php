<?php
	include "../includes/db.php";
	if (isset($_POST['type_name'])) {
		$type_name = filter_var($_POST['type_name'], FILTER_SANITIZE_STRING);
		$interest_rate = filter_var($_POST['interest_rate'], FILTER_SANITIZE_STRING);
		$period = filter_var($_POST['period'], FILTER_SANITIZE_STRING);
		$parent_id 	= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$id 		= preg_replace("#[^0-9]#", "", $_POST['id']);
		$date_added = date("Y-m-d");
		if ($id !== "") {
			# update
			$up = $connect->prepare("UPDATE loan_type SET type_name = ?, interest_rate = ?, period = ? WHERE id = ? AND parent_id = ? ");
			$up->execute(array($type_name, $interest_rate, $period, $id, $parent_id));
			echo 'Loan Type Updated';
		}else{
			$query = $connect->prepare("SELECT * FROM loan_type WHERE type_name = ? AND parent_id = ?");
			$query->execute(array($type_name, $parent_id));
			if ($query->rowCount() > 0) {
				$up = $connect->prepare("UPDATE loan_type SET type_name = ?, interest_rate = ?, period = ? WHERE type_name = ? AND parent_id = ? ");
				$up->execute(array($type_name, $interest_rate, $period, $type_name, $parent_id));
				echo 'Loan Type Updated';
			}else{
				$in = $connect->prepare("INSERT INTO loan_type (type_name, interest_rate, period, parent_id, date_added) VALUES(?, ?, ?, ?, ?) ");
				$ex = $in->execute(array($type_name, $interest_rate, $period, $parent_id, $date_added));
				if ($ex) {
					echo "Loan Type Saved";
				}else{
					echo "Error processing the form";
					exit();
				}
			}
		}
	}


?>