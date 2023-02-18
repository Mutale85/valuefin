<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");

?>
<!DOCTYPE html>
<html>
<head>
	<title>Fully Paid Loans</title>
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
      			<div class="row">
				  	<div class="col-md-6"> 						
						<div class="card card-success card-outline mb-5">
							<div class="card-header">

							</div>
							<div class="card-body">
								<div id="chart"></div>
							</div>
						</div>
				  	</div>
					<div class="col-md-6"> 						
						<div class="card card-success card-outline mb-5">
							<div class="card-header">
								<h4 class="card-title">Fully Paid Loans - <?php echo $branch ?></h4>
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
												<a href="reports/fully-paid-loans?branch_id=<?php echo base64_encode($id)?>&parent_id=<?php echo base64_encode($member_id)?>" class="dropdown-item"><?php echo getBranchName($connect, $parent_id, $id) ?></a>
											
											<?php }?>
												<a class="dropdown-divider"></a>
												<a href="reports/fully-paid-loans?allbranches=ALL&parent_id=<?php echo base64_encode($member_id)?>" class="dropdown-item">All branches data</a>
										</div>
									</div>
									
								</div>
							</div>
							<?php 
								if(isset($_GET['branch_id'])){	
							?>
								<div class="card-body box-profile">
									<div class="table table-responsive">
										<table id="allTables" class="cell-border text-dark" style="width:100%">
											<thead>
												<tr>
													<th>Client</th>
													<th>Date</th>
													<th>Amount</th>
												</tr>
											</thead>
											<tbody>
												<?php
													
													
													$query = $connect->prepare("SELECT * FROM loan_applications WHERE branch_id = ? AND parent_id = ? AND status = 'approved' AND repayment_status = '1' ");
													$query->execute(array($get_branch, $parent_id));
													$count = $query->rowCount();
													$currency = "ZMW";
													if ($count > 0 ) {
														foreach ($query->fetchAll() as $row) {
															extract($row);
														?>
															<tr>
																<td><a href="loans/see-loan-details?loan-id=<?php echo base64_encode($id)?>&borrower_id=<?php echo base64_encode($applicant_id) ?>"><i class="bi bi-person-badge"></i> <?php echo getBorrowerFullNamesByCardId($connect, $applicant_id) ?> <small>(<?php echo $applicant_id?>)</small></a></td>
																<td><small><?php echo getDateLoanFullyPaid($connect, $applicant_id, $id)?></small></td>
																<td><?php echo $currency ?> <?php echo $total_loan_amount?></td>
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
													
													<th>
														<?php 
															$sql = $connect->prepare("SELECT currency, SUM(total_loan_amount) AS total_loan_amount FROM loan_applications WHERE branch_id = ? AND parent_id = ? AND status = 'approved' AND repayment_status = '1' ");
															$sql->execute(array($get_branch, $parent_id));
															$row = $sql->fetch();
															echo $row['currency'] .' '. $row['total_loan_amount'];
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
								<!-- ALL BRANCHES DATA -->
								<div class="card-body box-profile">
									<div class="table table-responsive">
										<table id="allTables" class="cell-border text-dark" style="width:100%">
											<thead>
												<tr>
													<th>Client</th>
													<th>Date</th>
													<th>Amount</th>
												</tr>
											</thead>
											<tbody>
												<?php
													
													
													$query = $connect->prepare("SELECT * FROM loan_applications WHERE  parent_id = ? AND status = 'approved' AND repayment_status = '1' ");
													$query->execute(array($parent_id));
													$count = $query->rowCount();
													$currency = "ZMW";
													if ($count > 0 ) {
														foreach ($query->fetchAll() as $row) {
															extract($row);
														?>
															<tr>
																<td><a href="loans/see-loan-details?loan-id=<?php echo base64_encode($id)?>&borrower_id=<?php echo base64_encode($applicant_id) ?>"><i class="bi bi-person-badge"></i> <?php echo getBorrowerFullNamesByCardId($connect, $applicant_id) ?> <small>(<?php echo $applicant_id?>)</small></a></td>
																<td><small><?php echo getDateLoanFullyPaid($connect, $applicant_id, $id)?></small></td>
																<td><?php echo $currency ?> <?php echo $total_loan_amount?></td>
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
													
													<th>
														<?php 
															$sql = $connect->prepare("SELECT currency, SUM(total_loan_amount) AS total_loan_amount FROM loan_applications WHERE  parent_id = ? AND status = 'approved' AND repayment_status = '1' ");
															$sql->execute(array($parent_id));
															$row = $sql->fetch();
															echo $row['currency'] .' '. $row['total_loan_amount'];
														?>
													</th>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							<?php
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<?php 
				$query = $connect->prepare("SELECT DATE(paid_date) AS date_collected, SUM(amount) AS total_collected, loan_number, borrower_id FROM loan_payments WHERE branch_id = ? AND parent_id = ? AND balance = '0.00' GROUP BY DATE(paid_date) ");
				$query->execute([$get_branch, $parent_id]);
				// Fetch the data and format it for use in ApexCharts
				$data = [];
				while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
					$data[] = [
						'x' => $row['date_collected'],
						//'y' => $row['total_collected'],
						'y' => getTotalLoanByLoanId($connect, $row['loan_number'])
					];
				}
				$data_json = json_encode($data);
			?>	
		</section>
	</div>
	<?php include("../addon_footer.php")?>
	<script>
		var options = {
            chart: {
                type: 'line'
            },
            series: [{
                name: 'Collections',
                data: <?php echo $data_json; ?>
            }],
            xaxis: {
                type: 'datetime',
                categories: <?php echo $data_json; ?>
            },
			yaxis: {
                labels: {
                    formatter: function (value) {
                        return "<?php echo 'ZMW '; ?>" + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return "<?php echo 'ZMW '; ?>" + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
		
	</script>
</body>
</html>