<?php 
extract($_POST);


$number_of_repayments = '';
if($loan_payment_options == 'Daily'){
	$number_of_repayments =  (28 * $loan_duration) / 1;
}elseif ($loan_payment_options == 'Weekly') {
	$number_of_repayments =  (28 * $loan_duration) / 7 ; //28 days / number of weeks in a month which is 4
}else if ($loan_payment_options == "Monthly") {
	$number_of_repayments = 1 * $loan_duration;
}

if ($loan_interest_method == 'flat_rate') {
	#flat interest $I = interest, $P = principal, $R = Rate charged, $T = $time of Repayment 
	$P = $principle_amount;
	$R = $loan_interest / 100;
	$T = $loan_duration/12;

	$I = $P*$R*$T;
	// A is now the total amount to pay back
	// $A = $P+$I;

	$total_interest = ($principle_amount * $loan_interest)/100;
	$Accrued = $principle_amount+$total_interest;
	$repayments = $Accrued / $number_of_repayments;

}

if ($loan_interest_method == 'reducing_rate') {

	$I = $principle_amount * ($loan_interest*$loan_duration) /100;
	$A = $principle_amount+$I; 
	$repayments = $A / $number_of_repayments;
}

$interest = $principle_amount*($loan_interest/100)*$loan_duration;
$release_date = date("Y-m-d");

// if($loan_duration == 1 && $loan_payment_options == 'Weekly'){
// 	$total_interest = $loan_interest*
// }	
?>
<hr>
<div class="table table-responsive">
<table width="100%">
	<caption><?php echo preg_replace("#[^a-z]#i", " ", ucwords($loan_interest_method))?> Interest</caption>
	<tr>
		<th class="text-center">Repayments</th>
		<th class="text-center">Total Interest</th>
		<th class="text-center">Total Payable Amount</th>
		<th class="text-center"><?php echo $loan_payment_options ?> Payments</th>
		<th class="text-center">Principal Amount</th>
	</tr>
	<tr>
		<td class="text-center"><small><?php echo floor($number_of_repayments) ?> Times </small></td>
		<td class="text-center"><?php echo $symbol_fee?> <?php echo number_format($total_interest) ?></td>
		<td class="text-center"><?php echo $symbol_fee?> <?php echo number_format($Accrued, 2) ?></td>
		<td class="text-center"><?php echo $symbol_fee?> <?php echo number_format($repayments, 2)?></td>
		<td class="text-center"><?php echo $symbol_fee?> <?php echo number_format($principle_amount, 2)?></td>
		<input type="hidden" name="repayments" id="repayments" value="<?php echo floor($number_of_repayments) ?>">
		<input type="hidden" name="annual_p_rate" id="annual_p_rate" value="<?php echo number_format(($loan_interest))?>">
		<input type="hidden" name="total_interest_amount" id="total_interest_amount" value="<?php echo $total_interest ?>">
		<input type="hidden" name="total_payable_amount" id="total_payable_amount" value="<?php echo $Accrued ?>">
		<input type="hidden" name="recurring_amount" id="recurring_amount" value="<?php echo $repayments?>">
		<input type="hidden" name="principle_amount" id="principle_amount" value="<?php echo $principle_amount?>">
		<input type="hidden" name="monthly_interest" id="monthly_interest" value="<?php echo  $total_interest/$number_of_repayments ?>">
		<input type="hidden" name="total_monthly_repayments" id="total_monthly_repayments" value="<?php echo $repayments ?>">
	</tr>
</table>
</div>
<div class="table table-responsive">
	<table class="cell-border table table-sm " id="ScheduleTable" style="width: 100%">
		<thead>
			<tr>
				<th>Repayments</th>
				<th>Dates:</th>
				<th><?php echo $symbol_fee ?> <?php echo $loan_payment_options ?> Amount</th>
				<th><?php echo $symbol_fee ?> <?php echo $loan_payment_options ?> Interest</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$i = 1;
				if ($loan_payment_options == "Daily") {
					$p_d = 'days';
				}elseif($loan_payment_options == "Weekly"){
					$p_d = 'weeks';
				}elseif ($loan_payment_options == 'Monthly') {
					$p_d = 'months';
				}elseif ($loan_payment_options == 'Lump-Sum') {
					$p_d = 'months';
				}
				$period = 1;
				$numbers = 1;
				$add = 1;
				for ($loan_duration = 1; $loan_duration <= $number_of_repayments; $loan_duration++) {?>
					<tr>
						<td><?php echo $numbers++;?></td>
						<td><?php echo date("Y-m-d", strtotime("+".$period++." ".$p_d."", strtotime($release_date))); ?></td>
						<td><small class="text-fade"><?php echo $symbol_fee?></small> <?php echo number_format($repayments, 2) ?></td>
						<td><small class="text-fade"><?php echo $symbol_fee?></small> <?php echo number_format( ($total_interest/$number_of_repayments), 2) ?></td>
					</tr>
					<input type="hidden" name="payment_period[]" id="payment_period" value="<?php echo date("Y-m-d", strtotime("+".$add++." ".$p_d."", strtotime($release_date)))?>">
			<?php
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<th>Payment Mode</th>
				<th><?php echo $loan_payment_options ?></th>
				<th>Loan Duration</th>
				<th><?php echo $loan_duration  - 1 ?> <?php echo $loan_payment_options ?></th>
			</tr>
		</tfoot>
	</table>        
</div>
<hr>