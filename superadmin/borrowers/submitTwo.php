<?php
	include('../includes/db.php');

	if (!empty($_COOKIE['BORROWERID'])) {
		// $applicant_id = $_POST['applicant_id'];
		$applicant_id = $_COOKIE['BORROWERID'];
		$update = $connect->prepare("UPDATE borrowers_details SET `borrower_bank_name` = ?, `borrower_account_number` = ?, `borrower_sort_code` = ?, `borrower_branch_name` = ? WHERE id = ? ");
		extract($_POST);
		
		$ex = $update->execute(array($borrower_bank_name, $borrower_account_number, $borrower_sort_code, $borrower_branch_name, $applicant_id));
		if($ex){
			echo "Bank Details Updated";
		}else{
			echo "Error uploading User";
			exit();
		}
		
	}elseif (!empty($_POST['applicant_id'])) {
		$applicant_id = $_POST['applicant_id'];
		// $applicant_id = $_COOKIE['BORROWERID'];
		$update = $connect->prepare("UPDATE borrowers_details SET `borrower_bank_name` = ?, `borrower_account_number` = ?, `borrower_sort_code` = ?, `borrower_branch_name` = ? WHERE id = ? ");
		extract($_POST);
		
		$ex = $update->execute(array($borrower_bank_name, $borrower_account_number, $borrower_sort_code, $borrower_branch_name, $applicant_id));
		if($ex){
			echo "Bank Details Updated";
		}else{
			echo "Error uploading User";
			exit();
		}
	}
?>