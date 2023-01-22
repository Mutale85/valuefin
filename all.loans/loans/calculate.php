<?php
include '../includes/db.php'; 
extract($_POST);


// $interest = $principle_amount*($loan_interest/100)*$loan_duration;
$release_date = date("Y-m-d"); 
$symbol = getCurrency($connect, $_SESSION['parent_id']);

if ($processing_symbol == 'Percentage') {
	$processing_fee = ($loan_processing_fee / 100) * $principle_amount;
}else{
	$processing_fee = $loan_processing_fee;
}

if ($loan_interest_method == 'Flat Rate') {
	$R = $loan_interest/100;
	
	if($loan_payment_options == 'Weekly' AND $interest_per_period == "Per Week"){
		$duration = $weeks;
		$interest = $principle_amount * $R * $duration;
		$total = $principle_amount + $interest;
		$number_of_repayments = $duration;
		$payment = ($total/$number_of_repayments)+$processing_fee;
	}elseif ($loan_payment_options == 'Weekly' AND $interest_per_period == "Per Month") {
		#formula 
		$duration = $weeks;
		$interest = $principle_amount * $R * $duration;
		$total = $principle_amount + $interest;
		$number_of_repayments = $duration;
		$payment = ($total/$number_of_repayments)+$processing_fee;
	}elseif ($loan_payment_options == 'Weekly' AND $interest_per_period == "Per Month") {
		$duration = $weeks;
		$interest = $principle_amount * $R * $duration;
		$total = $principle_amount + $interest;
		$number_of_repayments = $duration;
		$payment = ($total/$number_of_repayments)+$processing_fee;
	}

	if($loan_payment_options == 'Monthly' AND $interest_per_period == "Per Week"){
		$duration = $months;
		$interest = $principle_amount * $R * $duration;
		$total = $principle_amount + $interest;
		$number_of_repayments = $duration;
		$payment = ($total/$number_of_repayments)+$processing_fee;
	}elseif ($loan_payment_options == 'Monthly' AND $interest_per_period == "Per Month") {
		#formula 
		$duration = $months;
		$interest = $principle_amount * $R * $duration;
		$total = $principle_amount + $interest;
		$number_of_repayments = $duration;
		$payment = ($total/$number_of_repayments)+$processing_fee;
	}elseif ($loan_payment_options == 'Monthly' AND $interest_per_period == "Per Month") {
		$duration = $months;
		$interest = $principle_amount * $R * $duration;
		$total = $principle_amount + $interest;
		$number_of_repayments = $duration;
		$payment = ($total/$number_of_repayments)+$processing_fee;
	}

	if($loan_payment_options == 'Lump-Sum' AND $interest_per_period == "Per Week"){
		$duration = 1;
		$interest = $principle_amount * $R * $duration;
		$total = $principle_amount + $interest;
		$number_of_repayments = $duration;
		$payment = ($total/$number_of_repayments)+$processing_fee;
	}elseif ($loan_payment_options == 'Lump-Sum' AND $interest_per_period == "Per Month") {
		#formula 
		$duration = 1;
		$interest = $principle_amount * $R * $duration;
		$total = $principle_amount + $interest;
		$number_of_repayments = $duration;
		$payment = ($total/$number_of_repayments)+$processing_fee;
	}elseif ($loan_payment_options == 'Lump-Sum' AND $interest_per_period == "Per Month") {
		$duration = 1;
		$interest = $principle_amount * $R * $duration;
		$total = $principle_amount + $interest;
		$number_of_repayments = $duration;
		$payment = ($total/$number_of_repayments)+$processing_fee;
	}
	
// }elseif ($loan_interest_method == "Reducing Rate") {
// 	# code...
// 	//Interest Payable per Installment = Interest Rate per Installment * Remaining Loan Amount

// }elseif ($loan_interest_method == "Compound Rate") {
// 	# code...
	//A = P (1 + r/n) (nt)
}else{
	echo "Select You Interest";
	exit();
}

?>
<hr>
<div class="table table-responsive">
<table width="100%">
	<caption><?php echo ucwords($loan_interest_method)?> Interest</caption>
	<tr>
		<th class="text-center">Repayments</th>
		<th class="text-center">Principal Amount</th>
		<th class="text-center">Total Interest</th>
		<th class="text-center">Total Payable Amount</th>
		<th class="text-center"><?php echo $duration ?> Payments</th>
		
	</tr>
	<tr>
		<td class="text-center"><small><?php echo floor($number_of_repayments) ?> Times </small></td>
		<td class="text-center"><?php echo $symbol?> <?php echo number_format($principle_amount, 2)?></td>
		<td class="text-center"><?php echo $symbol?> <?php echo number_format($interest) ?></td>
		<td class="text-center"><?php echo $symbol?> <?php echo number_format($total, 2) ?></td>
		<td class="text-center"><?php echo $symbol?> <?php echo number_format($payment, 2)?></td>
		
		<input type="hidden" name="repayments" id="repayments" value="<?php echo floor($number_of_repayments) ?>">
		<input type="hidden" name="annual_p_rate" id="annual_p_rate" value="<?php echo number_format(($loan_interest))?>">
		<input type="hidden" name="total_interest_amount" id="total_interest_amount" value="<?php echo $interest ?>">
		<input type="hidden" name="total_payable_amount" id="total_payable_amount" value="<?php echo $total ?>">
		<input type="hidden" name="recurring_amount" id="recurring_amount" value="<?php echo $payment?>">
		<input type="hidden" name="principle_amount" id="principle_amount" value="<?php echo $principle_amount?>">
		<input type="hidden" name="monthly_interest" id="monthly_interest" value="<?php echo  $interest/$number_of_repayments ?>">
		<input type="hidden" name="total_monthly_repayments" id="total_monthly_repayments" value="<?php echo $payment ?>">
	</tr>
</table>
</div>
<div class="table table-responsive">
	<table class="table table-bordered table-sm " id="ScheduleTable" style="width: 100%">
		<thead>
			<tr>
				<th>Repayments</th>
				<th>Dates</th>
				<th>Amounts</th>
				
			</tr>
		</thead>
		<tbody>
			<?php
				$i = 1;
				$p_d = '';
				if ($loan_payment_options == "Daily") {
					$p_d = 'days';
				}elseif($loan_payment_options == "Weekly"){
					$p_d = 'weeks';
				}elseif ($loan_payment_options == "Monthly") {
					$p_d = 'months';
				}elseif ($loan_payment_options == 'Lump-Sum') {
					$p_d = 'months';
				}
				$periods = 1;
				$numbers = 1;
				$add = 1;
				for ($loan_duration = 1; $loan_duration <= $number_of_repayments; $loan_duration++) {?>
					<tr>
						<td><?php echo $numbers++;?></td>
						<td><?php echo date("Y-m-d", strtotime("+".$periods++." ".$p_d."", strtotime($release_date))); ?></td>
						<td><small class="text-fade"><?php echo $symbol?></small> <?php echo number_format($payment, 2) ?></td>
					</tr>
					<input type="hidden" name="payment_period[]" id="payment_period" value="<?php echo date("Y-m-d", strtotime("+".$add++." ".$p_d."", strtotime($release_date)))?>">
			<?php
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<th>Payment Mode</th>
				<th><?php echo $duration ?> Times</th>
				<th><?php echo $loan_duration  - 1 ?> <?php echo $p_d ?></th>
			</tr>
		</tfoot>
	</table>        
</div>
<hr>