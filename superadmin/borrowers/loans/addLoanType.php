<?php
	include "../../../includes/db.php";
	if (isset($_POST['type_name'])) {
		$type_name = filter_var($_POST['type_name'], FILTER_SANITIZE_SPECIAL_CHARS);
		$interest_rate = filter_var($_POST['interest_rate'], FILTER_SANITIZE_SPECIAL_CHARS);
		$period = filter_var($_POST['period'], FILTER_SANITIZE_SPECIAL_CHARS);
		$parent_id 	= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$branch_id 	= preg_replace("#[^0-9]#", "", $_POST['branch_id']);
		$id 		= preg_replace("#[^0-9]#", "", $_POST['id']);
		$user_id 	=  $_SESSION['user_id'];
		$date_added = date("Y-m-d");
		// echo $parent_id;
		if ($id !== "") {
			# update
			$up = $connect->prepare("UPDATE loan_type SET type_name = ?, interest_rate = ?, period = ? WHERE id = ? AND parent_id = ? ");
			$up->execute([$type_name, $interest_rate, $period, $id, $parent_id]);
			echo 'Loan Type Updated';
		}else{
			$query = $connect->prepare("SELECT * FROM loan_type WHERE type_name = ? AND parent_id = ?");
			$query->execute([$type_name, $parent_id]);
			if ($query->rowCount() > 0) {
				$up = $connect->prepare("UPDATE loan_type SET type_name = ?, interest_rate = ?, period = ? WHERE type_name = ? AND parent_id = ? ");
				$up->execute([$type_name, $interest_rate, $period, $type_name, $parent_id]);
				echo 'Loan Type Updated';
			}else{
				$sql = $connect->prepare("INSERT INTO `loan_type`(`branch_id`, `parent_id`, `user_id`, `type_name`, `interest_rate`, `period`) VALUES(?, ?, ?, ?, ?, ?) ");
				$ex = $sql->execute([$branch_id, $parent_id, $user_id, $type_name, $interest_rate, $period]);
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