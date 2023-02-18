<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
	if(isset($_GET['loan-id'])){
		$loan_id = base64_decode($_GET['loan-id']);
		$borrower_id = base64_decode($_GET['borrower_id']);

	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Loan Details</title>
	<?php include("../addon_header.php");?>
	<style>
		.letterhead {
		font-family: Arial, sans-serif;
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 20px;
		border: 2px solid #000080;
		border-radius: 10px;
		padding: 20px;
		}
		.logo {
		width: 150px;
		height: auto;
		}
		.company-name {
		font-size: 20px;
		font-weight: bold;
		color: #000080;
		margin-bottom: 10px;
		}
		.company-info {
		display: flex;
		flex-direction: column;
		align-items: flex-end;
		font-size: 16px;
		margin-left: 20px;
		}
		.location {
		margin-bottom: 5px;
		}
		.contacts {
		margin-bottom: 5px;
		}
		@media (max-width: 600px) {
		.letterhead {
			flex-direction: column;
			align-items: center;
		}
		.company-info {
			align-items: center;
			margin-left: 0;
		}
		}
	</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light"> 
			<?php 
				$sql = $connect->prepare("SELECT * FROM loan_applications WHERE id = ? AND applicant_id = ?");
				$sql->execute([$loan_id, $borrower_id]);
				$row = $sql->fetch();
				$balance = $row['total_loan_amount'];
				$repayment_start_date = $row['repayment_start_date'];
				$currency = $row['currency'];
			?>
			<div class="container mt-5">
				<div class="row">
					<div class="col-md-12">

						<div class="letterhead">
							<img src="<?php echo getClientsImage($connect, $borrower_id) ?>" alt="<?php echo getClientsImage($connect, $borrower_id) ?>" class="logo">
							<div class="company-info">
								<div class="company-name"><?php echo getBorrowerFullNamesByCardId($connect, $borrower_id) ?></div>
								<div class="location">Loan Number: <?php echo $loan_id?></div>
								<div class="contacts">Borrowed Amount: <?php echo $currency?> <?php echo $balance?></div>
								<div class="contacts">Due Date: <?php echo date("l, jS \of F Y ", strtotime($repayment_start_date))?></div>
								<div class="contacts">Days Running: <?php echo getLoanApprovedDays($connect, $loan_id, $borrower_id)?></div>
							</div>
						</div>
						
					</div>
					<div class="col-md-12">
						<div class="bg-light p-1">
							<div class="card card-primary card-outline">
								<div class="card-header">
									<h4  class="card-title">Loan Payments</h4>
									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse">
											<i class="fas fa-minus"></i>
										</button>
									</div>
								</div>
								<div class="card-body box-profile">
									<table class="table">
										<thead class="thead-dark">
											<tr>
												
												<th>Payments Made</th>
												<th>Dates Paid</th>
												<th>Loan Balance</th>
												<!-- <th>Loan Due Date</th> -->
											</tr>
										</thead>
										
										<tbody>
											<?php
												$currency = 'ZMW';
												$parent_id = $_SESSION['parent_id'];
												// if (isset($_GET['from_period']) AND isset($_GET['to_period'])) {
												// 	$query = $connect->prepare("SELECT * FROM loan_payments WHERE paid_date BETWEEN ? AND ? AND branch_id = ? AND parent_id = ? ");
												// 	$query->execute(array($_GET['from_period'], $_GET['to_period'], $BRANCHID, $parent_id));
												// 	$query2 = $connect->prepare("SELECT SUM(amount) AS total_amount FROM loan_payments WHERE paid_date BETWEEN ? AND ? AND branch_id = ? AND parent_id = ? ");
												// 	$query2->execute(array($_GET['from_period'], $_GET['to_period'], $BRANCHID, $parent_id));
												// 	$rows = $query2->fetch();
												// 	$total_amount = $rows['total_amount'];

												// }else{
												$query = $connect->prepare("SELECT * FROM loan_payments WHERE loan_number = ? AND borrower_id = ? AND branch_id = ? AND parent_id = ? ");
												$query->execute([$loan_id, $borrower_id, $BRANCHID, $parent_id]);

												$query2 = $connect->prepare("SELECT *, SUM(amount) AS total_amount FROM loan_payments WHERE loan_number = ? AND borrower_id = ? AND branch_id = ? AND parent_id = ? ");
												$query2->execute([$loan_id, $borrower_id, $BRANCHID, $parent_id]);
												$rows = $query2->fetch();
												$total_amount = $rows['total_amount'];
												// }
												
												$numRows = $query->rowCount();
												$i = 1;
												if ($numRows > 0 ) {
													foreach ($query->fetchAll() as $row) {
														extract($row);
														$month = date('F', strtotime($paid_date));
														//$i++;
													?>
														<tr>
															<td> <?php echo $currency ?> <?php echo $amount?></td>
															<td><?php echo date("l, jS \of F Y ", strtotime($paid_date))?></td>
															<td><?php echo $currency ?> <?php echo $balance ?></td>								
															<!-- <td><?php echo getClientsLoanDueDate($connect, $loan_id, $borrower_id)?></td> -->
														</tr>

												<?php
													}
												}else{

												}
											?>
										</tbody>
									</table>
								</div>
								<div class="card-footer">
									<a href="" class="btn btn-primary"><i class="bi bi-printer"></i> Print Statement</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
      	</section>

	</div>
	<?php include("../addon_footer.php")?>
	<script></script>
</body>
</html>