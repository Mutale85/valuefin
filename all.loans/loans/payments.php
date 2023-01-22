<?php
	include '../includes/db.php';
	if (isset($_POST['loan_number'])) {
		$loan_number = $_POST['loan_number'];
		$borrower_id = $_POST['borrower_id'];
	
?>
<div class="table table-responsive">
	<table class="cell-table table table-sm" id="paymentsTable" width="100%">
		<thead>
			<th>Collection Date</th>
			<th>Collected By</th>
			<th>Payment Method</th>
			<th>Amount Paid</th>
			<th>Balance</th>
			<th>Actions</th>
			<th>View Receipt</th>
		</thead>
		<tbody class="text-dark" id="tbody">
		<?php
			
		$currency = "";
			$sql = $connect->prepare("SELECT * FROM `loan_payments` WHERE loan_number = ? AND borrower_id = ? ");
			$sql->execute(array($loan_number, $borrower_id));
			if ($sql->rowCount() > 0) {

				foreach ($sql->fetchAll() as $rows) {
					extract($rows);
					?>
					<tr>
						<td><?php echo $paid_date ?></td>
						<td><?php echo getStaffMemberNames($connect, $collected_by, $parent_id)?></td>
						<td><?php echo $payment_method ?></td>
						<td><?php echo $currency ?> <?php echo number_format($amount, 2)?></td>
						<td>
							<?php
								$owed = getTotalAmountOwed($connect, $borrower_id, $loan_number);
								$paid = getTotalAmountPaid($connect, $borrower_id, $loan_number);
								$balance = $owed - $paid;

								echo $currency . ' ' . $balance;
							?>
						</td>
						<td>
							<a href="<?php echo $id?>" class="editPayment" data-id="<?php echo $id?>"><i class="bi bi-pencil-square"></i></a>
							<a href="<?php echo $id?>" class="deletePayment" data-id="<?php echo $id?>"><i class="bi bi-trash"></i></a>
						</td>
						<td>
							<a href="loans/payment_receitp?loan_number=<?php echo $loan_number?>&borrower_id=<?php echo base64_encode($borrower_id)?>&payment_id=<?php echo $id?>" class="pdf" target="_blank"> <i class="bi bi-printer"></i> View</a>
						</td>
					</tr>
				<?php
				}	
			}else{?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>
						<p class="text-center">
							<strong>
							Balance: -
							<?php
								$owed = getTotalAmountOwed($connect, $borrower_id, $loan_number);
								$paid = getTotalAmountPaid($connect, $borrower_id, $loan_number);
								$balance = $owed - $paid;

								echo getCurrency($connect, $_SESSION['parent_id']) . ' ' . number_format($balance, 2);
							?>
							</strong>
						</p>
					</td>
					<td></td>
					<td></td>
				</tr>
		<?php
			}
		?>
		</tbody>
	</table>
	<script>
		$('#paymentsTable').DataTable();
	</script>
</div>

<?php }?>