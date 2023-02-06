<?php
	include '../includes/db.php';
	if (isset($_POST['delete_id'])) {
		$d = $connect->prepare("DELETE FROM loan_payments WHERE id = ? ");
		$q = $d->execute(array($_POST['delete_id']));
		if ($q) {
			echo "success";
		}
	}else{
		extract($_POST);
		if(empty($edit_id)){
			$amount  = preg_replace("#[^0-9.]#", "", $amount);
			$comment = filter_var($comment, FILTER_SANITIZE_STRING);
			// $sql = $connect->prepare("INSERT INTO `loan_payments`(`loan_number`, `borrower_id`, `amount`, `paid_date`, `payment_method`, `collected_by`, `comment`, `branch_id`, `parent_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ");
			$sql = $connect->prepare(" INSERT INTO `loan_payments`(`borrower_id`, `branch_id`, `parent_id`, `loan_number`, `currency`, `amount`, `balance`, `paid_date`, `payment_method`, `collected_by`, `comment`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$paid_date = date("Y-m-d", strtotime($paid_date));

			$ex = $sql->execute(array($borrower_id, $branch_id, $parent_id, $loan_number, $currency, $amount, $balance, $paid_date, $payment_method, $collected_by, $comment));
			$payment_id = $connect->lastInsertId();

			if ($ex) {
				$query = $connect->prepare("INSERT INTO `collected_funds`(`payment_id`, `borrower_id`, `branch_id`, `parent_id`, `loan_number`, `currency`, `amount`, `collected_by`, `month`, `date_added`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$month 		= date("F");
				$date_added = date("Y-m-d");
				$query->execute(array($payment_id, $borrower_id, $branch_id, $parent_id, $loan_number, $currency, $amount, $collected_by, $month, $date_added));
				
				echo "Payment Recorded";
			}
		}else{
			// $up = $connect->prepare("UPDATE loan_payments SET amount = ?, paid_date = ?, payment_method = ? , collected_by = ?, comment = ? WHERE id = ? AND borrower_id = ? ");
			$update = $connect->prepare("UPDATE `loan_payments` SET `amount`= ?, `balance` = ?, `paid_date`= ?,`payment_method`= ?,`collected_by`= ?,`comment` = ? WHERE id = ? AND loan_number = ? AND branch_id = ? ");
			$q = $up->execute(array($amount,$balance, $paid_date, $payment_method, $collected_by, $comment, $edit_id, $borrower_id, $branch_id));

			if($q){
				$update = $connect->prepare("UPDATE `collected_funds` SET amount = ?, collected_by = ?, month = ? WHERE payment_id = ? AND loan_number = ?");
				$updated->execute(array($edit_id, $loan_number));
				echo "updated";
			}
		}
	}
		
?>