<?php
	include "../../../includes/db.php";
	

	if (isset($_POST['getClientsLoan'])) {
		
		$borrower_id  	= $_POST['borrower_id'];
		$branch_id 		= $_POST['branchId'];
		$parent_id 		= $_SESSION['parent_id'];
		$loan_id 		= getLoanID($connect, $borrower_id);
		$sql = $connect->prepare("SELECT * FROM  `loan_payments` WHERE borrower_id = ? AND `loan_number` = ? ");
		$sql->execute([$borrower_id, $loan_id]);
		
		if($sql->rowCount() > 0){
			$query = $connect->prepare("SELECT *, SUM(amount) AS total_paid FROM `loan_payments` WHERE borrower_id = ? AND `loan_number` = ? ");
			$query->execute([$borrower_id, $loan_id]);
			$row = $query->fetch();
			if($row){
				extract($row);
				echo getClientsTotalLoan($connect, $loan_id, $borrower_id) - $total_paid;
			}
		}else{
			echo getClientsTotalLoan($connect, $loan_id, $borrower_id);
		}
	}
	if(isset($_POST['getLoanID'])) {
		$borrower_id = $_POST['borrower_id'];
		echo trim(getLoanID($connect, $borrower_id));
	}

	if(isset($_POST['check_amount_paid'])){
		$borrower_id  	= $_POST['borrower_id'];
		$branch_id 		= $_POST['branchId'];
		$parent_id 		= $_SESSION['parent_id'];
		$loan_id 		= trim($_POST['loan_id']);
		$sql = $connect->prepare("SELECT * FROM `loan_payments` WHERE borrower_id = ? AND `loan_number` = ? ");
		$sql->execute([$borrower_id, $loan_id]);
		
		if($sql->rowCount() > 0){
			$query = $connect->prepare("SELECT SUM(amount) AS total_paid FROM `loan_payments` WHERE borrower_id = ? AND `loan_number` = ? ");
			$query->execute([$borrower_id, $loan_id]);
			$row = $query->fetch();
			if($row){
				extract($row);
				
				echo $total_paid;
			}	
				
		}else{
			echo getClientsTotalLoan($connect, $loan_id, $borrower_id);
		}

	}
	

	