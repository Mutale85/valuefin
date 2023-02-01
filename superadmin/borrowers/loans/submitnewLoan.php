<?php 
include ("../includes/db.php");
extract($_POST);

$query = $connect->prepare("SELECT * FROM loans_table WHERE parent_id = ? AND borrower_id = ? AND loan_status != ? ");
$st = 'Completed';
$query->execute(array($parent_id, $applicant_id, $st));
$count = $query->rowCount();
if ($count > 0) {
	$row = $query->fetch();
	if ($row) {
		$loan_status = $row['loan_status'];
		if ($loan_status != 'Completed') {
			$status = preg_replace("#[^a-zA-Z]#", " ", $loan_status);
			echo getBorrowerFullNamesByCardId($connect, $applicant_id) .' has a loan which is pending '. $status .' Will be eligible for another loan later';
			exit();
		}elseif ($loan_status == 'Completed') {
			$loan_status = 'Pending Approval';
			$total_interest_amount = preg_replace("#[^0-9.]#", "", $total_interest_amount);
			$total_payable_amount = preg_replace("#[^0-9.]#", "", $total_payable_amount);
			$recurring_amount = preg_replace("#[^0-9.]#", "", $recurring_amount);

			if ($loan_payment_options == "Monthly") {
				$loan_duration = $repayments . ' Months';
			}elseif ($loan_payment_options == "Weekly") {
				$loan_duration = $repayments . ' Weeks';
			}
			if ($symbol_fee == "%") {
				
				$processing_fee_type = 'Percentage';

			}else{
				$processing_fee_type = 'Fixed Amount';
			}
			$user_id = $_SESSION['user_id'];
			$submitted_by =  getStaffMemberNames($connect, $user_id, $parent_id);
			$sql = $connect->prepare("INSERT INTO `loans_table`(`branch_id`, `parent_id`, `borrower_id`, `photo`, `title`, `firstname`, `lastname`, `identity_number`, `gender`, `phone_number`, `loan_number`, `principle_amount`, `release_method`, `loan_interest_method`,  `currency`, `loan_interest`, `loan_interest_period`, `loan_duration`, `loan_payment_options`, `processing_fee_type`, `loan_processing_fee`, `loan_purpose`, `repayments`, `total_interest_amount`, `total_payable_amount`, `recurring_amount`, `monthly_interest`, `total_monthly_repayments`, `loan_status`, `submitted_by`, `repayment_start_date`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
			$ex = $sql->execute(array($branch_id, $parent_id, $applicant_id, $borrower_photo, $borrower_title, $borrower_firstname, $borrower_lastname, $borrower_ID, $borrower_gender, $borrower_phone, $loan_number, $principle_amount, $release_method, $loan_interest_method, $currency, $loan_interest, $loan_interest_period, $loan_duration, $loan_payment_options, $processing_fee_type, $loan_processing_fee, $loan_purpose, $repayments, $total_interest_amount, $total_payable_amount, $recurring_amount, $monthly_interest, $total_monthly_repayments, $loan_status, $submitted_by, $repayment_start_date));
				foreach ($payment_period as $key => $value) {
					$date_due = $value;
					$insert = $connect->prepare("INSERT INTO `loan_schedules`(`borrower_id`, `parent_id`, `branch_id`, `loan_id`, `currency`, `amount`, `date_due`) VALUES (?, ?, ?, ?) ");
					$insert->execute(array($applicant_id, $parent_id, $branch_id, $loan_number, $currency, $recurring_amount, $date_due)); 
				}
			if ($ex) {
				# code... 0974988498
				echo  'Loan application has been submited, pending approval';
			}else{
				echo "Issues";
			}
		}
	}
}else{
	

	$loan_status = 'Pending Approval';
	$total_interest_amount = preg_replace("#[^0-9.]#", "", $total_interest_amount);
	$total_payable_amount = preg_replace("#[^0-9.]#", "", $total_payable_amount);
	$recurring_amount = preg_replace("#[^0-9.]#", "", $recurring_amount);

	if ($loan_payment_options == "Monthly") {
		$loan_duration = $repayments . ' Months';
	}elseif ($loan_payment_options == "Weekly") {
		$loan_duration = $repayments . ' Weeks';
	}
	if ($symbol_fee == "%") {

		$processing_fee_type = 'Percentage';

	}else{
		$processing_fee_type = 'Fixed Amount';
	}

	$user_id = $_SESSION['user_id'];
	$submitted_by =  getStaffMemberNames($connect, $user_id, $parent_id);

	$sql = $connect->prepare("INSERT INTO `loans_table`(`branch_id`, `parent_id`, `borrower_id`, `photo`, `title`, `firstname`, `lastname`, `identity_number`, `gender`, `phone_number`, `loan_number`, `principle_amount`, `release_method`, `loan_interest_method`, `currency`, `loan_interest`, `loan_interest_period`, `loan_duration`, `loan_payment_options`, `processing_fee_type`, `loan_processing_fee`, `loan_purpose`, `repayments`, `total_interest_amount`, `total_payable_amount`, `recurring_amount`, `monthly_interest`, `total_monthly_repayments`, `loan_status`, `submitted_by`, `repayment_start_date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
	$ex = $sql->execute(array($branch_id, $parent_id, $applicant_id, $borrower_photo, $borrower_title, $borrower_firstname, $borrower_lastname, $borrower_ID, $borrower_gender, $borrower_phone, $loan_number, $principle_amount, $release_method, $loan_interest_method, $currency, $loan_interest, $loan_interest_period, $loan_duration, $loan_payment_options, $processing_fee_type, $loan_processing_fee, $loan_purpose, $repayments, $total_interest_amount, $total_payable_amount, $recurring_amount, $monthly_interest, $total_monthly_repayments, $loan_status, $submitted_by, $repayment_start_date));
		foreach ($payment_period as $key => $value) {
			$date_due = $value;
			$insert = $connect->prepare("INSERT INTO `loan_schedules`(`borrower_id`, `parent_id`, `branch_id`, `loan_id`, `currency`, `amount`, `date_due`) VALUES (?, ?, ?, ?, ?, ?, ?) ");
			$insert->execute(array($applicant_id, $parent_id, $branch_id, $loan_number, $currency, $recurring_amount, $date_due)); 
		}
	if ($ex) {
		# code... 0974988498
		echo  'Loan application has been submited, pending approval';
	}else{
		echo "Issues";
	}
}


?>