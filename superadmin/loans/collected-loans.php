<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Collected Loans Amounts</title>
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
					<div class="col-md-12 mb-4">
						<button class="btn btn-secondary callForm shadow" type="button" >Add New Collection</button>
					</div>
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
								<h4 class="card-title">Collected Loans</h4>
								<div class="card-tools">
									<button type="button" class="btn btn-tool" data-card-widget="collapse">
										<i class="fas fa-minus"></i>
									</button>
								</div>
							</div>
							<div class="card-body box-profile">
								<div class="table table-responsive">
									<table id="collectedLoans" class="cell-border text-dark" style="width:100%">
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
													$query->execute(array($_GET['from_period'], $_GET['to_period'], $BRANCHID, $parent_id));
													$query2 = $connect->prepare("SELECT SUM(amount) AS total_amount FROM loan_payments WHERE paid_date BETWEEN ? AND ? AND branch_id = ? AND parent_id = ? ");
													$query2->execute(array($_GET['from_period'], $_GET['to_period'], $BRANCHID, $parent_id));
													$rows = $query2->fetch();
													$total_amount = $rows['total_amount'];

												}else{
													$query = $connect->prepare("SELECT * FROM loan_payments WHERE branch_id = ? AND parent_id = ? ");
													$query->execute(array($BRANCHID, $parent_id));

													$query2 = $connect->prepare("SELECT *, SUM(amount) AS total_amount FROM loan_payments WHERE branch_id = ? AND parent_id = ? ");
													$query2->execute(array($BRANCHID, $parent_id));
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
															<td><a href="borrowers/loan-request?client-id=<?php echo base64_encode($borrower_id)?>&application_id=<?php echo base64_encode($loan_number)?>"><?php echo getBorrowerFullNamesByCardId($connect, $borrower_id) ?></a></td>
															<td><?php echo $currency ?> <?php echo $amount?></td>
															<td><?php echo $currency ?> <?php echo $balance ?></td>
															<td><?php echo date("l, jS \of F Y ", strtotime($paid_date))?></td>
															<td><a href="loans/see-loan-details?loan-id=<?php echo base64_encode($loan_number)?>&borrower_id=<?php echo base64_encode($borrower_id) ?>">View details</a></td>
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
								</div>
							</div>
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
		    $('#collectedLoans').DataTable();
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

			
			// 565489/45/1
			// 342327/87/1
			// 009988/09/1
		}

		function calcBalance(amount){
			var check_amount_paid = "check_amount_paid";
			var branchId = document.getElementById('branchId').value;
			var loan_id = document.getElementById('loan_id').value;
			var borrower_id = document.getElementById('borrowerId').value;
			$.ajax({
				type: "POST",
				url: "loans/parsers/actionsLoans",
				data: {check_amount_paid:check_amount_paid, borrower_id:borrower_id, branchId:branchId, loan_id:loan_id},
				success: function(data) {
					
					var balance =  data -  amount;
					$("#loan_balance_amount").val(balance);
				}
			});
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
					}
				});
			});
		});

	</script>
</body>
</html>