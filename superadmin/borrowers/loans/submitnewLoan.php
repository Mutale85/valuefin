<?php 
include ("../../../includes/db.php");
extract($_POST);

$query = $connect->prepare("SELECT * FROM loan_applications WHERE branch_id = ? AND parent_id = ? AND applicant_id = ? AND repayment_status = '0' ");
$query->execute([$branch_id, $parent_id, $applicant_id]);
$count = $query->rowCount();
if ($count > 0) {
	$row = $query->fetch();
	extract($row);
	if($status == 'pending'){
		echo getBorrowerFullNamesByCardId($connect, $applicant_id) .' has a pending loan application';
		exit();
	}
}else{
	// insert 
	$repayment_start_date = date("Y-m-d", strtotime($repayment_start_date));
	$sql = $connect->prepare("INSERT INTO `loan_applications`(`branch_id`, `parent_id`, `applicant_id`, `loan_number`, `alt_contact_names`, `alt_contact_relationship`, `alt_contact_phone`, `currency`, `principle_amount`, `interest`, `total_loan_amount`, `loan_processing_fee`, `net_loan`, `repayment_amount_daily`, `repayment_amount_weekly`, `repayment_amount_month`, `days`, `weeks`, `repayment_start_date`, `release_method`) VALUES (?, ?, ?, ?, ?, ? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? )");
	$ex = $sql->execute([$branch_id, $parent_id, $applicant_id, $loan_number, $alt_contact_names, $alt_contact_relationship, $alt_contact_phone, $currency, $principle_amount, $interest, $total_loan_amount, $loan_processing_fee, $net_loan, $repayment_amount_daily, $repayment_amount_weekly, $repayment_amount_month, $days, $weeks, $repayment_start_date, $release_method]);
	if($ex){
		echo "Loan application for ". getBorrowerFullNamesByCardId($connect, $applicant_id). ", sumitted, pending admins review";
	}
}
	
?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          