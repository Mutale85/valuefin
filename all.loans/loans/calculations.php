<?php 
extract($_POST);


// $interest = $principle_amount*($loan_interest/100)*$loan_duration;
$release_date = date("Y-m-d");

if ($loan_payment_options == 1) {
	$days = 'Daily';
}elseif($loan_payment_options == 7){
	$days = 'Weekly';
}elseif ($loan_payment_options == 28) {
	$days = 'Monthly';
}elseif ($loan_payment_options == 'Lump-Sum') {
	$days = 'Month(s)';
}

if ($loan_payment_options == 1) {
	$duration = 'Day(s)';
}elseif($loan_payment_options == 7){
	$duration = 'Week(s)';
}elseif ($loan_payment_options == 28) {
	$duration = 'Month(s)';
}elseif ($loan_payment_options == 'Lump-Sum') {
	$duration = 'Month(s)';
}

if ($interest_type == 'Percentage') {

	$total_interest = ($principle_amount * $loan_interest) / 100; 
	$t = ($loan_duration * $loan__period) / $interest_per_period;
	$Accrued = $t * $loan_interest;
	echo "Accrued Interest =". $Accrued .'%';
	echo "<br>";
	$interest = $total_interest*$t;
	echo "Total Interest =". $interest;
	echo "<br>";
	$total = $interest+$principle_amount;
	echo "<br>";
	echo "Principal Amount =". $principle_amount;
	echo "<br>";
	echo "Amount To pay =". $total;
	echo "<br>";
	$number_of_repayments = ($loan_duration * $loan__period)/$loan_payment_options;
	echo "Number of Payments =". $number_of_repayments;
	echo "<br>";
	if($loan_payment_options == 1) {

		$payment = $total/$number_of_repayments;
		echo 'Daily payment = '. $payment;
	}elseif ($loan_payment_options == 7) {
		$payment = $total/$number_of_repayments;
		echo 'Weekly payment = '. $payment;
	}elseif ($loan_payment_options == 28) {
		$payment = $total/$number_of_repayments;
		echo 'Monthly payment = '. $payment;
	}elseif ($loan_payment_options == 'Lump-Sum') {
		$payment = $total;
		echo 'Once off payment = '. $payment;
	}
}elseif ($interest_type == 'Amount') {
	$total_interest = ($loan_interest * $loan_duration); 
	$t = ($loan_duration * $loan__period) / $interest_per_period;
	$Accrued = $t * $loan_interest;
	echo "Accrued Interest = ".$symbol.' '. $Accrued ;
	echo "<br>";
	$interest = $total_interest;
	echo "Total Interest = ".$symbol.' '. $interest;
	echo "<br>";
	$total = $interest+$principle_amount;
	echo "<br>";
	echo "Principal Amount = ".$symbol.' '. $principle_amount;
	echo "<br>";
	echo "Amount To pay = " .$symbol. ' '. $total;
	echo "<br>";
	$number_of_repayments = ($loan_duration * $loan__period)/$loan_payment_options;
	echo "Number of Payments =". $number_of_repayments;
	echo "<br>";
	if($loan_payment_options == 1) {

		$payment = $total/$number_of_repayments;
		echo 'Daily payment = '.$symbol.' '.number_format($payment, 2);
	}elseif ($loan_payment_options == 7) {
		$payment = $total/$number_of_repayments;
		echo 'Weekly payment = '.$symbol.' '.number_format($payment, 2);
	}elseif ($loan_payment_options == 28) {
		$payment = $total/$number_of_repayments;
		echo 'Monthly payment = '.$symbol.' '.number_format($payment, 2);
	}elseif ($loan_payment_options == 'Lump-Sum') {
		$payment = $total;
		echo 'Once off payment = '.$symbol.' '.number_format($payment, 2);
	}
}
?>
<hr>
<div class="table table-responsive">
<table width="100%">
	<caption><?php echo preg_replace("#[^a-z]#i", " ", ucwords($loan_interest_method))?> Interest</caption>
	<tr>
		<th class="text-center">Repayments</th>
		<th class="text-center">Principal Amount</th>
		<th class="text-center">Total Interest</th>
		<th class="text-center">Total Payable Amount</th>
		<th class="text-center"><?php echo $days ?> Payments</th>
		
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
				<th>Dates:</th>
				<th><?php echo $days ?> Amount</th>
				
			</tr>
		</thead>
		<tbody>
			<?php
				$i = 1;
				$p_d = '';
				if ($loan_payment_options == 1) {
					$p_d = 'days';
				}elseif($loan_payment_options == 7){
					$p_d = 'weeks';
				}elseif ($loan_payment_options == 28) {
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
				<th><?php echo $days ?></th>
				<th>Loan Duration <?php echo $loan_duration  - 1 ?> <?php echo $duration ?></th>
			</tr>
		</tfoot>
	</table>        
</div>
<hr>