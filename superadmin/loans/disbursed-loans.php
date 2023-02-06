<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Disbursed Amounts</title>
	<?php include("../addon_header.php");?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light">     					
			<div class="container-fluid">
      			<div class="row">
					<div class="col-md-12">
						<div class="bg-light p-1">
							<div class="card card-primary card-outline">
								<div class="card-header">
									<h4  class="card-title">Search by Dates</h4>
									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse">
											<i class="fas fa-minus"></i>
										</button>
									</div>
								</div>
								<div class="card-body box-profile">
									<?php
										$from_period = $to_period = "";
										if (isset($_GET['from_period']) AND isset($_GET['to_period'])) {
											$from_period = $_GET['from_period'];
											$to_period = $_GET['to_period'];
										}
									?>
									<form method="get" id="searchForm">
										<div class="form-group">
											<label>From</label>
											<input type="date" name="from_period" id="from_period" class="form-control" required value="<?php echo $from_period?>">
										</div>
										<div class="form-group">
											<label>To</label>
											<input type="date" name="to_period" id="to_period" class="form-control" required value="<?php echo $to_period?>">
										</div>
										<div class="form-group">
											
											<button type="submit" class="btn btn-primary">Search</button>
										</div>
										<div class="form-group">
											<a href="loans/disbursed-loans" class="btn btn-outline-primary">Reset</a>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12"> 						
						<div class="card card-success card-outline mb-5">
							<div class="card-header">
								<h4 class="card-title">Disbursed Loans</h4>
							</div>
							<div class="card-body box-profile">
								<div class="table table-responsive">
									<table id="disbursedLoans" class="cell-border text-dark" style="width:100%">
										<thead>
											<tr>
												<th>Client</th>
												<th>Details</th>
												<th>Amount</th>
												<th>Period</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$parent_id = $_SESSION['parent_id'];
												if (isset($_GET['from_period']) AND isset($_GET['to_period'])) {
													$query = $connect->prepare("SELECT * FROM approvedLoans WHERE date_approved BETWEEN ? AND ? AND branch_id = ? AND parent_id = ? ");
													$query->execute(array($_GET['from_period'], $_GET['to_period'], $BRANCHID, $parent_id));

													// get Total 
													$query2 = $connect->prepare("SELECT SUM(amount) AS total_amount FROM approvedLoans WHERE date_approved BETWEEN ? AND ? AND branch_id = ? AND parent_id = ? ");
													$query2->execute(array($_GET['from_period'], $_GET['to_period'], $BRANCHID, $parent_id));
													$row = $query2->fetch();
													$total_amount = $row['total_amount'];
												}else{
													$query = $connect->prepare("SELECT * FROM approvedLoans WHERE branch_id = ? AND parent_id = ? ");
													$query->execute(array($BRANCHID, $parent_id));

													$query2 = $connect->prepare("SELECT SUM(amount) AS total_amount FROM approvedLoans WHERE branch_id = ? AND parent_id = ? ");
													$query2->execute(array($BRANCHID, $parent_id));
													$row = $query2->fetch();
													$total_amount = $row['total_amount'];
												}
												$numRows = $query->rowCount();
												$i = 1;
												$currency = "ZMW";
												if ($numRows > 0 ) {
													
													$i = 1;
													foreach ($query->fetchAll() as $row) {
														extract($row);
														$month = date("F", strtotime($date_approved));
													?>
														<tr>
															<td><a href="borrowers/loan-request?client-id=<?php echo base64_encode($borrower_id)?>&application_id=<?php echo base64_encode($loan_id)?>"><?php echo getBorrowerFullNamesByCardId($connect, $borrower_id) ?></a></td>
															<td><a href="loans/see-loan-details?loan-id=<?php echo base64_encode($loan_id)?>&borrower_id=<?php echo base64_encode($borrower_id) ?>">View details</a></td>
															<td><?php echo $currency ?> <?php echo $amount?></td>
															<td><?php echo date("l, jS \of F Y ", strtotime($date_approved))?></td>
														</tr>
												<?php
													}
												}
											?>
											
										</tbody>
										<tfoot>
											<tr>
												<th>Total Disbursed</th>
												<th></th>
												<th><?php echo $currency?> <?php echo $total_amount?></th>
												<th>
													<?php if(isset($_GET['from_period']) AND isset($_GET['to_period'])):?>
														<?php echo date("j F, Y", strtotime($from_period)) .' - '. date("j F, Y", strtotime($to_period))?>
													<?php else: ?>
														All Period
													<?php endif;?>
														
												</th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>	
		</section>
	</div>
	<?php include("../addon_footer.php")?>
	<script>
		$(document).ready( function () {
		    $('#disbursedLoans').DataTable();
		});

		
	</script>
</body>
</html>