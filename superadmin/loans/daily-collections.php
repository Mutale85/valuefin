<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Daily Collections</title>
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
	<style>
		.tooltips {
			position: relative;
			display: inline-block;
		}

		.tooltips .tooltipstext {
			visibility: hidden;
			width: 120px;
			background-color: black;
			color: #fff;
			text-align: center;
			border-radius: 6px;
			padding: 7px 0;
			position: absolute;
			z-index: 1;
			bottom: 125%;
			left: 50%;
			margin-left: -60px;
			opacity: 0;
			transition: opacity 0.3s;
		}

		.tooltips:hover .tooltipstext {
			visibility: visible;
			opacity: 1;
		}

	</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light p-3"> 
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-5 mb-4">
						
						<div class="card">
							<div class="card-header">
								<button class="btn btn-secondary callForm shadow" type="button" >Add New Collection</button>
							</div>
							<div class="card-body">
								<div id="chart"></div>
							</div>
						</div>
					</div>
					
					<div class="col-md-7"> 						
						<div class="card card-success card-outline">
							<div class="card-header">
								<h4  class="card-title">Daily Collections - <?php echo $branch?></h4>
								<div class="card-tools">
									<div class="btn-group">
										<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
											<b><i class="bi bi-building-check"></i> Branches</b>
										</button>
										<div class="dropdown-menu dropdown-menu-right" role="menu">
											<?php 
												$query = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
												$query->execute([$parent_id]);
												foreach($query->fetchAll() as $row){
													extract($row);
											?>
												<a href="loans/daily-collections?branch_id=<?php echo base64_encode($id)?>&parent_id=<?php echo base64_encode($member_id)?>" class="dropdown-item"><?php echo getBranchName($connect, $parent_id, $id) ?></a>
											
											<?php }?>
												<a class="dropdown-divider"></a>
												<a href="loans/daily-collections?allbranches=ALL&parent_id=<?php echo base64_encode($member_id)?>" class="dropdown-item">All branches data</a>
										</div>
									</div>
								</div>
							</div>
							<?php if(isset($_GET['branch_id'])){	?>
								<div class="card-body box-profile">
									<div class="table table-responsive">
										<table id="allTables" class="table table-bordered text-dark" style="width:100%">
											<thead>
												<tr>
													<td>Names</td>
													<td>Total Loan</td>
													<td>Expected Deposit</td>
													<td>Actual Deposit</td>
													<td>Balance </td>
												</tr>
											</thead>
											<tbody>
												<?php
													$parent_id = $_SESSION['parent_id'];
													$currency = 'ZMW';
													$today = date("Y-m-d");
													$query = $connect->prepare("SELECT * FROM loan_applications WHERE branch_id = ? AND parent_id = ? AND status = 'approved' AND repayment_status = '0' ");
													$query->execute([$get_branch, $parent_id]);
													
													foreach($query->fetchAll() as $rows){
														extract($rows);
														$total_paid = fetchTotalPaid($connect, $id, $applicant_id);
														$balance = getClientsBalanceAmount($connect, $applicant_id, $today);
														if($balance == '0.00'){
															$balance = $total_loan_amount - $total_paid;
														}else{
															$balance = getClientsBalanceAmount($connect, $applicant_id, $today) - $total_paid;
														}
														$amount_paid = getClientsPaidAmount($connect, $applicant_id, $today);
														if($repayment_amount_daily == $amount_paid) {
															$color = '<span class="text-success">'.$currency.' '.$amount_paid.'</span>';
														}else if($repayment_amount_daily > $amount_paid){
															$color = '<span class="text-warning">'.$currency.' '.$amount_paid.'</span>';	
														}else{
															$color = '<span class="text-danger">'.$currency.' '.$amount_paid.'</span>';
														}
														?>
															<tr>
																<td><a href="loans/see-loan-details?loan-id=<?php echo base64_encode($id)?>&borrower_id=<?php echo base64_encode($applicant_id) ?>"><i class="bi bi-person-badge"></i> <?php echo getBorrowerFullNamesByCardId($connect, $applicant_id) ?> <small>(<?php echo $applicant_id?>)</small></a></td>
																<td><?php echo $currency ?> <?php echo $total_loan_amount ?></td>
																<td><?php echo $currency ?> <?php echo $repayment_amount_daily ?> <small><?php echo checkPaymentsMade($connect, $applicant_id, $id)?></small></td>
																<td class="bg-secondary"><?php echo $color?> </td>
																<td class="bg-danger"><?php echo $currency ?> <?php echo fetchClientsLoanBalance($connect, $id, $applicant_id) ?></td>
															</tr>
													<?php
														
													}
												?>
											</tbody>
											<tfoot>
												<?php 
													$query2 = $connect->prepare("SELECT SUM(repayment_amount_daily) AS total_repayment, SUM(total_loan_amount) AS total_borrowed, SUM(loan_balance) AS total_loan_balances FROM loan_applications WHERE branch_id = ? AND parent_id = ? AND status = 'approved' AND repayment_status = '0' ");
													$query2->execute(array($get_branch, $parent_id));
													$row = $query2->fetch();
													$total_repayment   = $row['total_repayment'];
													$total_borrowed = $row['total_borrowed'];
													$total_collected = fetTotalCollectedByBranch($connect, $branch_id, $parent_id);
													$balance =  $row['total_loan_balances'];

												?>
												<tr class="">
													<th>Totals</th>
													<th><?php echo $currency?> <?php echo number_format($total_borrowed, 2)?></th>
													<th><?php echo $currency?> <?php echo number_format($total_repayment, 2)?></th>
													<th>
														<?php echo $currency?> <?php echo getBranchPaymentTotal($connect, $branch_id, $parent_id, $today)?>
													</th>
													<th><?php echo $currency?> <?php echo number_format($balance, 2) ?></th>
												</tr>
											</tfoot>
										</table>
										<?php 
											$query = $connect->prepare("SELECT DATE(paid_date) AS date_collected, SUM(amount) AS total_collected FROM loan_payments WHERE branch_id = ? AND parent_id = ? GROUP BY DATE(paid_date) ");
											$query->execute([$get_branch, $parent_id]);
											// Fetch the data and format it for use in ApexCharts
											$data = [];
											while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
												$data[] = [
													'x' => $row['date_collected'],
													'y' => $row['total_collected']
												];
											}
											$data_json = json_encode($data);
										?>
									</div>
								</div>
							<?php }else if($_GET['allbranches']){?>
								<!-- All branches -->
								<div class="card-body box-profile">
									<div class="table table-responsive">
										<table id="allTables" class="table table-bordered text-dark" style="width:100%">
											<thead>
												<tr>
													<td>Names</td>
													<td>Total Loan</td>
													<td>Expected Deposit</td>
													<td>Actual Deposit</td>
													<td>Balance </td>
												</tr>
											</thead>
											<tbody>
												<?php
													$parent_id = $_SESSION['parent_id'];
													$currency = 'ZMW';
													$today = date("Y-m-d");
													$query = $connect->prepare("SELECT * FROM loan_applications WHERE parent_id = ? AND status = 'approved' AND repayment_status = '0' ");
													$query->execute([$parent_id]);
													
													foreach($query->fetchAll() as $rows){
														extract($rows);
														$total_paid = fetchTotalPaid($connect, $id, $applicant_id);
														$balance = getClientsBalanceAmount($connect, $applicant_id, $today);
														if($balance == '0.00'){
															$balance = $total_loan_amount - $total_paid;
														}else{
															$balance = getClientsBalanceAmount($connect, $applicant_id, $today) - $total_paid;
														}
														$amount_paid = getClientsPaidAmount($connect, $applicant_id, $today);
														if($repayment_amount_daily == $amount_paid) {
															$color = '<span class="text-success">'.$currency.' '.$amount_paid.'</span>';
														}else if($repayment_amount_daily > $amount_paid){
															$color = '<span class="text-warning">'.$currency.' '.$amount_paid.'</span>';	
														}else{
															$color = '<span class="text-danger">'.$currency.' '.$amount_paid.'</span>';
														}
														?>
															<tr>
																<td><a href="loans/see-loan-details?loan-id=<?php echo base64_encode($id)?>&borrower_id=<?php echo base64_encode($applicant_id) ?>"><i class="bi bi-person-badge"></i> <?php echo getBorrowerFullNamesByCardId($connect, $applicant_id) ?> <small>(<?php echo $applicant_id?>)</small></a></td>
																<td><?php echo $currency ?> <?php echo $total_loan_amount ?></td>
																<td><?php echo $currency ?> <?php echo $repayment_amount_daily ?> <small><?php echo checkPaymentsMade($connect, $applicant_id, $id)?></small></td>
																<td class="bg-secondary"><?php echo $color?> </td>
																<td class="bg-danger"><?php echo $currency ?> <?php echo fetchClientsLoanBalance($connect, $id, $applicant_id) ?></td>
															</tr>
													<?php
														
													}
												?>
											</tbody>
											<tfoot>
												<?php 
													$query2 = $connect->prepare("SELECT SUM(repayment_amount_daily) AS total_repayment, SUM(total_loan_amount) AS total_borrowed,  SUM(loan_balance) AS total_loan_balances FROM loan_applications WHERE parent_id = ? AND status = 'approved' AND repayment_status = '0' ");
													$query2->execute(array($parent_id));
													$row = $query2->fetch();
													$total_repayment   = $row['total_repayment'];
													$total_borrowed = $row['total_borrowed'];
													$total_collected = fetTotalCollectedByBranch($connect, $branch_id, $parent_id);
													$balance =  $row['total_loan_balances'];

												?>
												<tr class="">
													<th>Totals</th>
													<th><?php echo $currency?> <?php echo number_format($total_borrowed, 2)?></th>
													<th><?php echo $currency?> <?php echo number_format($total_repayment, 2)?></th>
													<th>
														<?php echo $currency?> <?php echo getBranchPaymentTotal($connect, $branch_id, $parent_id, $today)?>
													</th>
													<th><?php echo $currency?> <?php echo number_format($balance, 2) ?></th>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							<?php 
								$query = $connect->prepare("SELECT DATE(paid_date) AS date_collected, SUM(amount) AS total_collected FROM loan_payments WHERE parent_id = ? GROUP BY DATE(date_collected)");
								$query->execute([$parent_id]);
								// Fetch the data and format it for use in ApexCharts
								$data = [];
								while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
									$data[] = [
										'x' => $row['date_collected'],
										'y' => $row['total_collected']
									];
								}
								$data_json = json_encode($data);
						}?>
						</div>
					</div>
      			</div>
      		</div>
      	</section>
		
		<!-- Collection form modal -->
		<div class="modal fade" id="collectionModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"><span id="titleState"> Loan Collection</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
					<form method="post" id="collectedFundsForm">
						<div class="modal-body">
						
							<div class="form-group">
								<label for="borrowerId">Borrower NRC</label>
								<input type="text" class="form-control" id="borrowerId" name="borrower_id" required onblur="getClientsLoan(this.value)">
								<span id="result"></span>
							</div>
							
							<div class="form-group">
								<label for="borrowerId">Loan Amount</label>
								<input type="text" class="form-control" id="loan_amount" name="loan_amount" readonly>
							</div>
							<input type="hidden" class="form-control" id="loan_id" name="loan_id">
							<input type="hidden" class="form-control" id="branchId" name="branch_id" value="<?php echo $BRANCHID ?>">
							<input type="hidden" class="form-control" id="parentId" name="parent_id" value="<?php echo $_SESSION['parent_id']?>">
							
							<div class="form-group">
								<label for="amount">Collected Amount</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text">ZMW</span>
									</div>
									<input type="hidden" class="form-control" id="currency" name="currency"  value="ZMW">
									<input type="number" step="any" class="form-control" id="amount" name="amount"  min="1" onkeyup="calcBalance(this.value)">
								</div>
							</div>
							<div class="form-group">
								<label for="borrowerId">Balance Amount</label>
								<input type="text" class="form-control" id="loan_balance_amount" name="loan_balance_amount" readonly>
							</div>
							<div class="form-group">
								<label for="dateAdded">Collection Date</label>
								<input type="text" class="form-control" id="dateAdded" name="date_added">
							</div>
							<div class="form-group">
								<label for="paymentMethod">Payment Method</label>
								<select name="payment_method" id="payment_method" class="form-control">
									<option value="Cash">Cash</option>
									<option value="Mobile Money">Mobile Money</option>
								</select>
							</div>
							<div class="form-group">
								<label for="collectedBy">Collected By</label>
								<input type="text" class="form-control" id="collectedBy" name="collected_by" required placeholder="Your name">
							</div>
							<div class="check-box">
								<label for="all_is_correct"><input type="checkbox" class="checkbox" id="all_is_correct" name="all_is_correct" required> Clients details and payments is correct</label>
							</div>
							<div class="modal-footer justify-content-between">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" id="btnSubmit">Submit Payment</button>
							</div>
						</div>
					</form>
                </div>
            </div>
        </div>
	</div>
	<?php include("../addon_footer.php")?>
	<script>
		$(document).ready( function () {
		    
			$(".callForm").click(function(){
				$("#collectionModal").modal("show");
			})
			$("#dateAdded").datepicker({
				dateFormat: "DD, d MM, yy",
				autoclose: true, 
        		todayHighlight: true,
				minDate: 0, maxDate: "+1M +10D"
			})
		});

		function getClientsLoan(borrower_id){
			var getClientsLoan = "getClientsLoan";
			var branchId = document.getElementById('branchId').value;
			var getLoanID = 'getLoanID';
			if(borrower_id !== ""){
				$.ajax({
					type: "POST",
					url: "loans/parsers/actionsLoans",
					data: {getClientsLoan:getClientsLoan, borrower_id:borrower_id, branchId:branchId},
					success: function(data) {
						$("#loan_amount").val(data);
					}
				});
			
				$.ajax({
					type: "POST",
					url: "loans/parsers/actionsLoans",
					data: {getLoanID:getLoanID, borrower_id:borrower_id, branchId:branchId},
					success: function(data) {
						$("#loan_id").val(data);
					}
				});
			}else{
				
			}

		}

		function calcBalance(amount){
			var check_amount_paid = "check_amount_paid";
			var branchId = document.getElementById('branchId').value;
			var loan_id = document.getElementById('loan_id').value;
			var borrower_id = document.getElementById('borrowerId').value;
			var balance =  document.getElementById('loan_amount').value -  amount;
			$("#loan_balance_amount").val(balance);
			
		}
		// submit form
		$(document).ready(function() {
			$("#collectedFundsForm").submit(function(e) {
				e.preventDefault();

				$.ajax({
					type: "POST",
					url: "loans/parsers/submitPayment",
					data: $("#collectedFundsForm").serialize(),
					beforeSend:function(){
						$("#btnSubmit").html('Processing..');
					},
					success: function(data) {
						successToast(data);
						$("#btnSubmit").html('Submit Payment');
						$("#collectedFundsForm")[0].reset();
						setTimeout(function(){
							location.reload();
						}, 2500);
					}
				});
			});
		});
		// Create and apex chart
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