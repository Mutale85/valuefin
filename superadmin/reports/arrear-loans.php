<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");  
?>
<!DOCTYPE html>
<html>
<head>
	<title>Arrear loans</title>
	<?php 
		include("../addon_header.php");
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
				<div class="row mt-5">
					<div class="col-md-12 ">
						<?php
							$from_period = $to_period = "";
							if (isset($_GET['from_period']) AND isset($_GET['to_period'])) {
								$from_period = $_GET['from_period'];
								$to_period = $_GET['to_period'];
							}
						?>
						<form method="get" id="searchForm" class="border p-4">
							<h4 class="mb-3">Search by Dates</h4>
							
							<div class="form-group">
								<label>From</label>
								<input type="date" name="from_period" id="from_period" class="form-control" required value="<?php echo $from_period?>">
							</div>
							<div class="form-group">
								<label>To</label>
								<input type="date" name="to_period" id="to_period" class="form-control" required value="<?php echo $to_period?>">
							</div>
							<div class="form-group">
								
								<button type="submit" name="submit" value="Submit" class="form-control btn btn-secondary">Search </button>
							</div>
							<div class="form-group">
								<a href="reports/arrear-loans" class="btn btn-outline-primary">Reset</a>
							</div>
						</form>
					</div>
				
			
					<div class="col-md-12">
						<div class="card card-danger card-outline">
							<div class="card-header">
								<h4 class="card-title text-danger">Loans in Arrears - <?php echo $branch ?></h4>
								<div class="card-tools">
									
									<div class="btn-group">
										<button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
											<b><i class="bi bi-building-check"></i> Select Branch</b>
										</button>
										<div class="dropdown-menu dropdown-menu-right" role="menu">
											<?php 
												$query = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
												$query->execute([$parent_id]);
												foreach($query->fetchAll() as $row){
													extract($row);
											?>
												<a href="reports/arrear-loans?branch_id=<?php echo base64_encode($id)?>&parent_id=<?php echo base64_encode($member_id)?>" class="dropdown-item"><?php echo getBranchName($connect, $parent_id, $id) ?></a>
											
											<?php }?>
												<a class="dropdown-divider"></a>
												<a href="reports/arrear-loans?allbranches=ALL&parent_id=<?php echo base64_encode($member_id)?>" class="dropdown-item">All branches data</a>
										</div>
									</div>
									
								</div>
							</div>
							<?php 
								if(isset($_GET['branch_id'])){	
							?>
								<div class="card-body">
									<div class="table table-reponsive">
										<table id="arrearsTable" class="table table-bordered table-sm">
											<thead>
												<tr>
													<th>Client</th>
													<th>Days</th>
													<th>Amount</th>
												</tr>
											</thead>
											<tbody class="text-dark">
												<?php
													if (isset($_GET['from_period']) AND isset($_GET['to_period'])) {
														$query = $connect->prepare("SELECT * FROM loan_payment_arrears WHERE branch_id = ? AND parent_id = ? AND date_submitted BETWEEN ? AND ? ");
														$query->execute(array($get_branch, $_SESSION['parent_id'], $_GET['from_period'], $_GET['to_period']));
													}else{
														
														$query = $connect->prepare("SELECT * FROM loan_payment_arrears WHERE branch_id = ? AND parent_id = ? ");
														$today = date("Y-m-d");
														$query->execute(array($get_branch, $_SESSION['parent_id']));
													}
													$currency = 'ZMW';
													if ($query->rowCount() > 0) {

														foreach ($query->fetchAll() as $row) {
															extract($row);
												?>

														<tr class="bg-danger">
															<td><?php echo getBorrowerFullNamesByCardId($connect, $borrower_id) ?> <small>(<?php echo $borrower_id?>)</small></td>
															<td><?php echo $days_missed?> Days</td>
															<td><?php echo $currency ?> <?php echo number_format($total_loan, 2)?></td>
														</tr>
													<?php
														}
													}
												?>
											</tbody>
											<tfoot>
												<tr>
													<th>Totals</th>
													<th></th>
													<th>
														<?php 
															if (isset($_GET['from_period']) && isset($_GET['to_period'])) {
																$query = $connect->prepare("SELECT DISTINCT borrower_id, SUM(total_loan) AS total_loan FROM loan_payment_arrears WHERE branch_id = ? AND parent_id = ? AND date_submitted BETWEEN ? AND ? ");
																$query->execute(array($get_branch, $_SESSION['parent_id'], $_GET['from_period'], $_GET['to_period']));
																$rows = $query->fetch();
																echo $currency .' '. $rows['total_loan'];
															}else{

															
																$query = $connect->prepare("SELECT DISTINCT borrower_id, SUM(total_loan) AS total_loan FROM loan_payment_arrears WHERE branch_id = ? AND parent_id = ?");
																$query->execute([$get_branch, $_SESSION['parent_id']]);
																$rows = $query->fetch();
																echo $currency .' '. $rows['total_loan'];
															}
														?>
													</th>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							<?php 
								}else if($_GET['allbranches']){
							?>
								<!-- ALl branches -->
								<div class="card-body">
									<div class="table table-reponsive">
										<table id="arrearsTable" class="table table-bordered table-sm">
											<thead>
												<tr>
													<th>Client</th>
													<th>Days</th>
													<th>Amount</th>
												</tr>
											</thead>
											<tbody class="text-dark">
												<?php
													if (isset($_GET['from_period']) AND isset($_GET['to_period'])) {
														$query = $connect->prepare("SELECT * FROM loan_payment_arrears WHERE parent_id = ? AND date_submitted BETWEEN ? AND ? ");
														$query->execute(array($parent_id, $_GET['from_period'], $_GET['to_period']));
													}else{
														
														$query = $connect->prepare("SELECT * FROM loan_payment_arrears WHERE parent_id = ? ");
														$today = date("Y-m-d");
														$query->execute(array($parent_id));
													}
													$currency = 'ZMW';
													if ($query->rowCount() > 0) {

														foreach ($query->fetchAll() as $row) {
															extract($row);
												?>

														<tr class="bg-danger">
															<td><?php echo getBorrowerFullNamesByCardId($connect, $borrower_id) ?> <small>(<?php echo $borrower_id?>)</small></td>
															<td><?php echo $days_missed?> Days</td>
															<td><?php echo $currency ?> <?php echo number_format($total_loan, 2)?></td>
														</tr>
													<?php
														}
													}
												?>
											</tbody>
											<tfoot>
												<tr>
													<th>Totals</th>
													<th></th>
													<th>
														<?php 
															if (isset($_GET['from_period']) && isset($_GET['to_period'])) {
																$query = $connect->prepare("SELECT DISTINCT borrower_id, SUM(total_loan) AS total_loan FROM loan_payment_arrears WHERE parent_id = ? AND date_submitted BETWEEN ? AND ? ");
																$query->execute(array($parent_id, $_GET['from_period'], $_GET['to_period']));
																$rows = $query->fetch();
																echo $currency .' '. $rows['total_loan'];
															}else{

															
																$query = $connect->prepare("SELECT DISTINCT borrower_id, SUM(total_loan) AS total_loan FROM loan_payment_arrears WHERE parent_id = ?");
																$query->execute([$parent_id]);
																$rows = $query->fetch();
																echo $currency .' '. $rows['total_loan'];
															}
														?>
													</th>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							<?php }?>
						</div>
					</div>
				</div>
			</div>
      	</section>
	</div>
	<?php include("../addon_footer.php")?>
	<script>
		$("#arrearsTable").DataTable();
	</script>
</body>
</html>