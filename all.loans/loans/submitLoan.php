<?php 
include ("../includes/db.php");
extract($_POST);
// echo $branch_id;
$query = $connect->prepare("SELECT * FROM loans WHERE parent_id = ? AND borrower_id = ? AND loan_status != ? ");
$st = 'Completed';
$query->execute(array( $parent_id, $borrower_id, $st));
$count = $query->rowCount();
if ($count > 0) {
	$row = $query->fetch();
	if ($row) {
		$loan_status = $row['loan_status'];
		if ($loan_status != 'Completed') {
			$status = preg_replace("#[^a-zA-Z]#", " ", $loan_status);
			echo getBorrowerFullNamesByCardId($connect, $borrower_id) .' has a loan which is pending '. $status .' Will be eligible for another loan later';
			exit();
		}elseif ($loan_status == 'Completed') {
			// Submit Loan
			$processing_fee_type = $processing;
			$interest_type = $interest;
			$loan_status = 'For_Approval';
			$total_interest_amount = preg_replace("#[^0-9.]#", "", $total_interest_amount);
			$total_payable_amount = preg_replace("#[^0-9.]#", "", $total_payable_amount);
			$recurring_amount = preg_replace("#[^0-9.]#", "", $recurring_amount);
			$sql = $connect->prepare("INSERT INTO `loans`(`branch_id`, `parent_id`, `loan_id`, `borrower_id`, `loan_number`, `principle_amount`, `release_method`, `release_date`, `loan_interest_method`, `interest_type`, `currency`, `loan_interest`, `loan_interest_period`, `loan_duration`, `loan_payment_options`, `loan__period`, `processing_fee_type`, `loan_processing_fee`, `guarantor_id`, `loan_purpose`, `repayments`, `annual_p_rate`, `total_interest_amount`, `total_payable_amount`, `recurring_amount`, `monthly_interest`, `total_monthly_repayments`, `loan_status`, `repayment_start_date`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$ex = $sql->execute(array($branch_id, $parent_id, $loan_id, $borrower_id, $loan_number, $principle_amount, $release_method, $release_date, $loan_interest_method, $interest_type, $currency, $loan_interest, $loan_interest_period, $loan_duration, $loan_payment_options, $loan__period, $processing_fee_type, $loan_processing_fee, $guarantor_id, $loan_purpose, $repayments, $annual_p_rate, $total_interest_amount, $total_payable_amount, $recurring_amount, $monthly_interest, $total_monthly_repayments, $loan_status, $repayment_start_date));

				foreach ($payment_period as $key => $value) {
					$date_due = $value;
					$insert = $connect->prepare("INSERT INTO `loan_schedules`(`parent_id`, `branch_id`, `loan_id`, `date_due`) VALUES (?, ?, ?, ?) ");
					$insert->execute(array($parent_id, $branch_id, $loan_number, $date_due));
				}
			if ($ex) {
				# code...
				echo getBorrowerFullNamesByCardId($connect, $borrower_id) .' Loan application has been submited, pending approval';
				

			}else{
				echo "Issues";
			}
		}
	}
}else{

	$processing_fee_type = $processing;
	$interest_type = $interest;
	$loan_status = 'For_Approval';
	$total_interest_amount = preg_replace("#[^0-9.]#", "", $total_interest_amount);
	$total_payable_amount = preg_replace("#[^0-9.]#", "", $total_payable_amount);
	$recurring_amount = preg_replace("#[^0-9.]#", "", $recurring_amount);
	$sql = $connect->prepare("INSERT INTO `loans`(`branch_id`, `parent_id`, `loan_id`, `borrower_id`, `loan_number`, `principle_amount`, `release_method`, `release_date`, `loan_interest_method`, `interest_type`, `currency`, `loan_interest`, `loan_interest_period`, `loan_duration`, `loan_payment_options`, `loan__period`, `processing_fee_type`, `loan_processing_fee`, `guarantor_id`, `loan_purpose`, `repayments`, `annual_p_rate`, `total_interest_amount`, `total_payable_amount`, `recurring_amount`, `monthly_interest`, `total_monthly_repayments`, `loan_status`, `repayment_start_date`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	$ex = $sql->execute(array($branch_id, $parent_id, $loan_id, $borrower_id, $loan_number, $principle_amount, $release_method, $release_date, $loan_interest_method, $interest_type, $currency, $loan_interest, $loan_interest_period, $loan_duration, $loan_payment_options, $loan__period, $processing_fee_type, $loan_processing_fee, $guarantor_id, $loan_purpose, $repayments, $annual_p_rate, $total_interest_amount, $total_payable_amount, $recurring_amount, $monthly_interest, $total_monthly_repayments, $loan_status, $repayment_start_date));
		foreach ($payment_period as $key => $value) {
			$date_due = $value;
			$insert = $connect->prepare("INSERT INTO `loan_schedules`(`parent_id`, `branch_id`, `loan_id`, `date_due`) VALUES (?, ?, ?, ?) ");
			$insert->execute(array($parent_id, $branch_id, $loan_number, $date_due));
		}
	if ($ex) {
		# code...
		echo  'Loan application has been submited, pending approval';
	}else{
		echo "Issues";
	}
}


?>