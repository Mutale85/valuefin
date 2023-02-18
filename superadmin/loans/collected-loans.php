<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Collected Loans Amounts</title>
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
					<div class="col-md-12 mb-4">
						<button class="btn btn-secondary callForm shadow" type="button" >Add New Collection</button>
					</div>
					<div class="col-md-6">
						<div class="bg-light p-1">
							<div class="card card-primary card-outline">
								<div class="card-header">
									<h4 class="card-title">All Collected by Days</h4>
								</div>
								<div class="card-body">
									<div id="chart"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
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
									<form method="get" id="searchForm" class="">
										
										<div class="form-group mb-3">
											<div class="input-group">
												<span class="input-group-text"><i class="bi bi-calendar-check"></i> </span>
												<input type="date" name="from_period" id="from_period" class="form-control" required value="<?php echo $from_period?>">
												
											</div>
										</div>
										<div class="form-group mb-3">
											<div class="input-group">
												
												<span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
												<input type="date" name="to_period" id="to_period" class="form-control" required value="<?php echo $to_period?>">
												
											</div>
										</div>
										<div class="form-group mb-3">
											<button type="submit" class="btn btn-primary">Search </button>
										</div>
										<a href="loans/collected-loans" class="btn btn-outline-primary">Reset</a>
									</form>	
								</div>
							</div>
						</div>
					</div>	
					<div class="col-md-12"> 						
						<div class="card card-success mb-5">
							<div class="card-header">
								<h4  class="card-title">Collected Loans - <?php echo $branch?></h4>
								<div class="card-tools">
									<div class="btn-group">
										<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">
											<b><i class="bi bi-building-check"></i> Branches</b>
										</button>
										<div class="dropdown-menu dropdown-menu-right" role="menu">
											<?php 
												$query = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
												$query->execute([$parent_id]);
												foreach($query->fetchAll() as $row){
													extract($row);
											?>
												<a href="loans/collected-loans?branch_id=<?php echo base64_encode($id)?>&parent_id=<?php echo base64_encode($member_id)?>" class="dropdown-item text-dark"><?php echo getBranchName($connect, $parent_id, $id);?></a>
											<?php }?>
												<a class="dropdown-divider"></a>
												<a href="loans/collected-loans?allbranches=ALL&parent_id=<?php echo base64_encode($member_id)?>" class="dropdown-item text-dark">All branches data</a>
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
													<th>Client</th>
													<th>Paid</th>
													<th>Balance</th>
													<th>Date </th>
													<th>Details</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$currency = 'ZMW';
													$parent_id = $_SESSION['parent_id'];
													if (isset($_GET['from_period']) AND isset($_GET['to_period'])) {
														$query = $connect->prepare("SELECT * FROM loan_payments WHERE paid_date BETWEEN ? AND ? AND branch_id = ? AND parent_id = ? ");
														$query->execute(array($_GET['from_period'], $_GET['to_period'], $get_branch, $parent_id));
														$query2 = $connect->prepare("SELECT SUM(amount) AS total_amount FROM loan_payments WHERE paid_date BETWEEN ? AND ? AND branch_id = ? AND parent_id = ? ");
														$query2->execute(array($_GET['from_period'], $_GET['to_period'], $get_branch, $parent_id));
														$rows = $query2->fetch();
														$total_amount = $rows['total_amount'];

													}else{
														$query = $connect->prepare("SELECT * FROM loan_payments WHERE branch_id = ? AND parent_id = ? ");
														$query->execute(array($get_branch, $parent_id));

														$query2 = $connect->prepare("SELECT *, SUM(amount) AS total_amount FROM loan_payments WHERE branch_id = ? AND parent_id = ? ");
														$query2->execute(array($get_branch, $parent_id));
														$rows = $query2->fetch();
														$total_amount = $rows['total_amount'];
													}
													
													$numRows = $query->rowCount();
													$i = 1;
													if ($numRows > 0 ) {
														
														$i = 1;
														foreach ($query->fetchAll() as $row) {
															extract($row);
															$month = date('F', strtotime($paid_date))
														?>
															<tr>
																<td><a href="borrowers/loan-request?client-id=<?php echo base64_encode($borrower_id)?>&application_id=<?php echo base64_encode($loan_number)?>"><i class="bi bi-person-badge"></i> <?php echo getBorrowerFullNamesByCardId($connect, $borrower_id) ?></a></td>
																<td><?php echo $currency ?> <?php echo $amount?></td>
																<td><?php echo $currency ?> <?php echo $balance ?></td>
																<td><?php echo date("l, jS \of F Y ", strtotime($paid_date))?></td>
																<td><a href="loans/see-loan-details?loan-id=<?php echo base64_encode($loan_number)?>&borrower_id=<?php echo base64_encode($borrower_id) ?>"><i class="bi bi-wallet2"></i> View Loan details</a></td>
															</tr>
													<?php
														}
													}
												?>
											</tbody>
											<tfoot>
												<tr>
													<th>Total</th>
													<td><?php echo $currency?> <?php echo $total_amount?></td>
													<td></td>
													<td>
														<?php if(isset($_GET['from_period']) AND isset($_GET['to_period'])):?>
															<?php echo date("j F, Y", strtotime($from_period)) .' - '. date("j F, Y", strtotime($to_period))?>
														<?php else: ?>
															All Period
														<?php endif;?>	
													</td>
													<td></td>
												</tr>
											</tfoot>
										</table>
										<?php 
											$query = $connect->prepare("SELECT DATE(paid_date) AS date_collected, SUM(amount) AS total_collected FROM loan_payments WHERE branch_id = ? AND parent_id = ? GROUP BY DATE(paid_date) ");
											$query->execute([$get_branch, $parent_id]);
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
							<?php }elseif(isset($_GET['allbranches'])){	?>
								<div class="card-body box-profile">
									<div class="table table-responsive">
										<table id="allTables" class="table table-bordered text-dark" style="width:100%">
											<thead>
												<tr>
													<th>Client</th>
													<th>Paid</th>
													<th>Balance</th>
													<th>Date </th>
													<th>Details</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$currency = 'ZMW';
													$parent_id = $_SESSION['parent_id'];
													if (isset($_GET['from_period']) AND isset($_GET['to_period'])) {
														$query = $connect->prepare("SELECT * FROM loan_payments WHERE paid_date BETWEEN ? AND ?  AND parent_id = ? ");
														$query->execute(array($_GET['from_period'], $_GET['to_period'], $parent_id));
														$query2 = $connect->prepare("SELECT SUM(amount) AS total_amount FROM loan_payments WHERE paid_date BETWEEN ? AND ?  AND parent_id = ? ");
														$query2->execute(array($_GET['from_period'], $_GET['to_period'], $parent_id));
														$rows = $query2->fetch();
														$total_amount = $rows['total_amount'];

													}else{
														$query = $connect->prepare("SELECT * FROM loan_payments WHERE parent_id = ? ");
														$query->execute([$parent_id]);

														$query2 = $connect->prepare("SELECT *, SUM(amount) AS total_amount FROM loan_payments WHERE parent_id = ? ");
														$query2->execute([$parent_id]);
														$rows = $query2->fetch();
														$total_amount = $rows['total_amount'];
													}
													
													$numRows = $query->rowCount();
													$i = 1;
													if ($numRows > 0 ) {
														
														$i = 1;
														foreach ($query->fetchAll() as $row) {
															extract($row);
															$month = date('F', strtotime($paid_date))
														?>
															<tr>
																<td><a href="borrowers/loan-request?client-id=<?php echo base64_encode($borrower_id)?>&application_id=<?php echo base64_encode($loan_number)?>"><i class="bi bi-person-badge"></i> <?php echo getBorrowerFullNamesByCardId($connect, $borrower_id) ?></a></td>
																<td><?php echo $currency ?> <?php echo $amount?></td>
																<td><?php echo $currency ?> <?php echo $balance ?></td>
																<td><?php echo date("l, jS \of F Y ", strtotime($paid_date))?></td>
																<td><a href="loans/see-loan-details?loan-id=<?php echo base64_encode($loan_number)?>&borrower_id=<?php echo base64_encode($borrower_id) ?>"><i class="bi bi-wallet2"></i> View Loan details</a></td>
															</tr>
													<?php
														}
													}
												?>
											</tbody>
											<tfoot>
												<tr>
													<th>Total</th>
													<td><?php echo $currency?> <?php echo $total_amount?></td>
													<td></td>
													<td>
														<?php if(isset($_GET['from_period']) AND isset($_GET['to_period'])):?>
															<?php echo date("j F, Y", strtotime($from_period)) .' - '. date("j F, Y", strtotime($to_period))?>
														<?php else: ?>
															All Period
														<?php endif;?>	
													</td>
													<td></td>
												</tr>
											</tfoot>
										</table>
										<?php 
											$query = $connect->prepare("SELECT DATE(paid_date) AS date_collected, SUM(amount) AS total_collected FROM loan_payments WHERE parent_id = ? GROUP BY DATE(paid_date) ");
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
										?>
									</div>
								</div>
							<?php }?>
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
								<!-- <label for="borrowerId">Balance Amount</label> -->
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