<?php
	include "../includes/db.php";
	if (isset($_POST['months'])) {
		$months = preg_replace("#[^0-9]#", "", $_POST['months']);
		$loan_type = preg_replace("#[^0-9]#", "", $_POST['loan_type']);
		$interest_percentage = preg_replace("#[^0-9]#", "", $_POST['interest_percentage']);
		$penalty_rate = preg_replace("#[^0-9]#", "", $_POST['penalty_rate']);
		$parent_id 	= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$id 		= preg_replace("#[^0-9]#", "", $_POST['id']);
		$date_added = date("Y-m-d");
		if ($id !== "") {
			# update
			$up = $connect->prepare("UPDATE loan_plans SET loan_type = ?,  months = ?, interest_percentage = ?, penalty_rate = ?  WHERE id = ? AND parent_id = ? ");
			$up->execute(array($loan_type, $months, $interest_percentage, $penalty_rate, $id, $parent_id));
			echo 'Updated';
		}else{
			$query = $connect->prepare("SELECT * FROM loan_plans WHERE loan_type = ? AND months = ? AND interest_percentage = ? AND parent_id = ?");
			$query->execute(array($loan_type, $months, $interest_percentage, $parent_id));
			if ($query->rowCount() > 0) {
				echo "You are trying to add the same data again";
				exit();
			}else{
				$in = $connect->prepare("INSERT INTO `loan_plans`(loan_type, `months`, `interest_percentage`, `penalty_rate`, `parent_id`) VALUES(?, ?, ?, ?, ?) ");
				$ex = $in->execute(array($loan_type, $months, $interest_percentage, $penalty_rate, $parent_id));
				if ($ex) {
					echo "done";
				}else{
					echo "Error processing the form";
					exit();
				}
			}
		}
	}

	if (isset($_POST['editor_id'])) {
		$editor_id  = preg_replace("#[^0-9]#", "", $_POST['editor_id']);
		$loggedinID = preg_replace("#[^0-9]#", "", $_POST['loggedinID']);
		$query = $connect->prepare("SELECT * FROM loan_plans WHERE id = ? AND parent_id = ? ");
		$query->execute(array($editor_id, $loggedinID));
		$row = $query->fetch();
		if ($row) {
			$data = json_encode($row);
		}
		echo $data;
	}

	if (isset($_POST['delete_id'])) {
		$delete_id  = preg_replace("#[^0-9]#", "", $_POST['delete_id']);
		$loggedParentId = preg_replace("#[^0-9]#", "", $_POST['loggedParentId']);
		$query = $connect->prepare("DELETE FROM loan_plans WHERE id = ? AND parent_id = ? ");
		$ex = $query->execute(array($delete_id, $loggedParentId));
		if($ex){
			echo "done";
		}else{
			echo 'error';
			exit();
		}
	}
	

	//======================= insert loan fees ==============================
	if (isset($_POST['fees_name'])) {
		$fees_name = filter_var($_POST['fees_name'], FILTER_SANITIZE_STRING);
		$parent_id = preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$branch_id = preg_replace("#[^0-9]#", "", $_POST['branch_id']);
		$fee_choice = filter_var(trim($_POST['fee_choice']), FILTER_SANITIZE_STRING);
		$loan_fees = filter_var($_POST['loan_fees'], FILTER_SANITIZE_STRING);
		$symbol = $_POST['symbol'];
		if ($fee_choice == 'Percentage') {
			$choice = 'percentage_based';
		}elseif ($fee_choice == 'Amount') {
			$choice = 'amount_based';
		}
		$sql = $connect->prepare("INSERT INTO `loan_fees`(`choice`, `loan_fees_name`, `loan_fees`, `symbol`, `parent_id`, `branch_id`) VALUES(?, ?, ?, ?, ?, ?)");
		$ex = $sql->execute(array($choice, $fees_name, $loan_fees, $symbol, $parent_id, $branch_id));
		if($ex){
			echo 'done';
		}
		
	}


	extract($_POST);

	$release_date = date("Y-m-d");

	$sql  = $connect->prepare("UPDATE loans SET loan_status = ?, release_date = ?, actioned_by = ? WHERE id = ? AND branch_id = ? AND parent_id = ? AND loan_number = ? ");
	$q = $sql->execute(array($loan_status, $release_date, $_SESSION['user_id'], $loan_id, $branch_id, $parent_id, $loan_number));
	if ($q) {
		echo "success";
	}


?>