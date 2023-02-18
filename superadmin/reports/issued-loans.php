<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Issued Loans</title>
	<?php include("../addon_header.php");?>
	<?php
		$from_period = $to_period = "";
		if (isset($_GET['from_period']) AND isset($_GET['to_period'])) {
			$from_period = $_GET['from_period'];
			$to_period = $_GET['to_period'];
		}

		if(isset($_GET['branch_id'])){
			$get_branch = base64_decode($_GET['branch_id']);
			$parent_id = $_SESSION['parent_id'];
			$branch = getBranchName($connect, $parent_id, $get_branch);
			
		}
		
		if(isset($_GET['allbranches'])){
			$parent_id = base64_decode($_GET['parent_id']);
			$branch = 'All Branches';
		}
	?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light">     					
			<div class="container-fluid">
      			<div class="row">
					<div class="col-md-6 mb-3">
						<div class="card">
							<div class="card-header">
								<H4 class="card-title">Issued loans graph - <?php echo $branch?></H4>
							</div>
							<div class="card-body">
								<div id="chart" ></div>
							</div>
						</div>
					</div>
					<div class="col-md-6 mb-3">
						<div class="bg-light p-1">
							<div class="card card-primary card-outline">
								<div class="card-header">
									<h4  class="card-title">Search by Dates
										<?php if (isset($_GET['from_period']) AND isset($_GET['to_period'])):?> 
											<?php echo ': '. date('j F, Y', strtotime($from_period)) . ' - '. date('j F, Y', strtotime($to_period))?>
										<?php endif;?> 
									</h4>
									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse">
											<i class="fas fa-minus"></i>
										</button>
									</div>
								</div>
								<div class="card-body box-profile">
									
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
											<a href="loans/issued-loans" class="btn btn-outline-primary">Reset</a>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12"> 						
						<div class="card card-success card-outline mb-5">
							<div class="card-header">
								<h4 class="card-title">Issued Loans - <?php echo $branch ?></h4>
								<div class="card-tools">
									
									<div class="btn-group">
										<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
											<b><i class="bi bi-building-check"></i> Select Branch</b>
										</button>
										<div class="dropdown-menu dropdown-menu-right" role="menu">
											<?php 
												$query = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
												$query->execute([$parent_id]);
												foreach($query->fetchAll() as $row){
													extract($row);
											?>
												<a href="reports/issued-loans?branch_id=<?php echo base64_encode($id)?>&parent_id=<?php echo base64_encode($member_id)?>" class="dropdown-item"><?php echo getBranchName($connect, $parent_id, $id) ?></a>
											
											<?php }?>
												<a class="dropdown-divider"></a>
												<a href="reports/issued-loans?allbranches=ALL&parent_id=<?php echo base64_encode($member_id)?>" class="dropdown-item">All branches data</a>
										</div>
									</div>
									
								</div>
							</div>
							<?php 
								if(isset($_GET['branch_id'])){
									
							?>
								<div class="card-body box-profile">
									<div class="table table-responsive">
										<table id="allTables" class="table table-bordered text-dark" style="width:100%">
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
														$query->execute(array($_GET['from_period'], $_GET['to_period'], $get_branch, $parent_id));

														// get Total 
														$query2 = $connect->prepare("SELECT SUM(amount) AS total_amount FROM approvedLoans WHERE date_approved BETWEEN ? AND ? AND branch_id = ? AND parent_id = ? ");
														$query2->execute(array($_GET['from_period'], $_GET['to_period'], $get_branch, $parent_id));
														$row = $query2->fetch();
														$total_amount = $row['total_amount'];
													}else{
														$query = $connect->prepare("SELECT * FROM approvedLoans WHERE branch_id = ? AND parent_id = ? ");
														$query->execute(array($get_branch, $parent_id));

														$query2 = $connect->prepare("SELECT SUM(amount) AS total_amount FROM approvedLoans WHERE branch_id = ? AND parent_id = ? ");
														$query2->execute(array($get_branch, $parent_id));
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
																<td><a href="loans/see-loan-details?loan-id=<?php echo base64_encode($loan_id)?>&borrower_id=<?php echo base64_encode($borrower_id) ?>">Loan Details <i class="bi bi-wallet2"></i></a></td>
																<td class="bg-success"><?php echo $currency ?> <?php echo $amount?></td>
																<td><?php echo date("l, jS \of F Y ", strtotime($date_approved))?></td>
															</tr>
													<?php
														}
													}
												?>
												
											</tbody>
											<tfoot>
												<tr class="bg-success">
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
								<?php 

									$months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
									// Prepare query to count loans issued in each month
									$stmt = $connect->prepare("SELECT COUNT(*) AS num_loans, MONTH(date_approved) AS issue_month FROM approvedLoans WHERE branch_id = ? AND parent_id = ? GROUP BY issue_month");
									$stmt->execute([$get_branch, $parent_id]);
									$num_loans = array_fill(0, 12, 0);

									// Loop through the results and store the number of loans per month
									while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
										$month_index = (int)$row['issue_month'] - 1;
										$num_loans[$month_index] = (int)$row['num_loans'];
									}

								 
								}else if($_GET['allbranches']){
							?>
								<!-- All branches -->
								<div class="card-body box-profile">
									<div class="table table-responsive">
										<table id="allTables" class="table table-bordered text-dark" style="width:100%">
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
														$query = $connect->prepare("SELECT * FROM approvedLoans WHERE date_approved BETWEEN ? AND ? AND parent_id = ? ");
														$query->execute(array($_GET['from_period'], $_GET['to_period'], $get_branch, $parent_id));

														// get Total 
														$query2 = $connect->prepare("SELECT SUM(amount) AS total_amount FROM approvedLoans WHERE date_approved BETWEEN ? AND ? AND parent_id = ? ");
														$query2->execute(array($_GET['from_period'], $_GET['to_period'], $parent_id));
														$row = $query2->fetch();
														$total_amount = $row['total_amount'];
													}else{
														$query = $connect->prepare("SELECT * FROM approvedLoans WHERE parent_id = ? ");
														$query->execute(array($parent_id));

														$query2 = $connect->prepare("SELECT SUM(amount) AS total_amount FROM approvedLoans WHERE parent_id = ? ");
														$query2->execute(array($parent_id));
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
																<td><a href="loans/see-loan-details?loan-id=<?php echo base64_encode($loan_id)?>&borrower_id=<?php echo base64_encode($borrower_id) ?>">Loan Details <i class="bi bi-wallet2"></i></a></td>
																<td class="bg-success"><?php echo $currency ?> <?php echo $amount?></td>
																<td><?php echo date("l, jS \of F Y ", strtotime($date_approved))?></td>
															</tr>
													<?php
														}
													}
												?>
												
											</tbody>
											<tfoot>
												<tr class="bg-success">
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
								<?php 

									$months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
									// Prepare query to count loans issued in each month
									$stmt = $connect->prepare("SELECT COUNT(*) AS num_loans, MONTH(date_approved) AS issue_month FROM approvedLoans WHERE  parent_id = ? GROUP BY issue_month");
									$stmt->execute([$parent_id]);
									$num_loans = array_fill(0, 12, 0);

									// Loop through the results and store the number of loans per month
									while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
										$month_index = (int)$row['issue_month'] - 1;
										$num_loans[$month_index] = (int)$row['num_loans'];
									}

								
							}?>
						</div>
					</div>
				</div>
			</div>	
		</section>
		
	</div>
	<?php include("../addon_footer.php")?>
	<script>
        
		var options = {
			chart: {
			type: 'bar'
			},
			series: [{
			name: 'Loans Issued',
			data: <?php echo json_encode($num_loans); ?>
			}],
			xaxis: {
			categories: <?php echo json_encode($months); ?>
			}
  		};
  
  		// Create a new ApexCharts instance and render the chart
  		var chart = new ApexCharts(document.querySelector('#chart'), options);
  		chart.render();
      
	</script>
</body>
</html>