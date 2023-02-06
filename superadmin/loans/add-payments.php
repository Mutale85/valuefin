<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 
  	if (isset($_GET['loan_number']) AND isset($_GET['borrower_id'])) {
  		$loan_number = $_GET['loan_number'];
  		$borrower_id = base64_decode($_GET['borrower_id']);
  	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Payment</title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
	<link rel="stylesheet" type="text/css" href="../css/buttons.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content mt-5">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-5 border-bottom">
  							<h4><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $BRANCHID))?> Loans</h4>
  						</div>
      				</div>
      			</div>
      			<div class="container-fluid mt-5 mb-5" id="loans_container">
      				<div class="row">
      					<div class="col-md-12">
      						<div class="card card-primary">
      							<div class="card-header">
      								<h4 class="card-title"> Loans Table</h4>
      							</div>
      							<div class="card-body">
      								<div class="table table-reponsinve">
      									<table id="loansTable" class="cell-table  table-sm" style="width:100%">
      										<thead>
      											<th>Photo</th>
      											<th>Details</th>
      											<th>Amount Details</th>
      											<th>Loan Status</th>
      											<th>Actions</th>
      										</thead>
      										<tbody class="text-dark">
			      								<?php
			      									// 

			      									$query = $connect->prepare("SELECT * FROM loans_table WHERE borrower_id = ? AND loan_number = ? AND branch_id = ? AND parent_id = ? ");
													$query->execute(array($borrower_id, $loan_number, $BRANCHID,  $_SESSION['parent_id']));

													if ($query->rowCount() > 0) {

														foreach ($query->fetchAll() as $row) {
															extract($row);

														?>

														<tr>
															<td>
																<a href="borrowers/see-borrower-details?applicant-id=<?php echo base64_encode($borrower_id) ?>" class="text-primary">
																	<img src="fileuploads/<?php echo $photo ?>" id="output_image" class="shadow-sm img-fluid img-responsive" alt="pic" style="width: 80px; height: 80px; border-radius: 2%;">
																</a>
															</td>
															
															<td>
																<a href="borrowers/see-borrower-details?applicant-id=<?php echo base64_encode($borrower_id) ?>" class="text-primary">
																	<table class="table table-borderless">
																		<tr>
																			<td>Names</td>
																			<td><?php echo $firstname ?> <?php echo $lastname ?></td>
																		</tr>
																		<tr>
																			<td>Gender</td>
																			<td><?php echo $gender ?></td>
																		</tr>
																		<tr>
																			<td>Phone: </td>
																			<td><?php echo $phone_number ?></td>
																		</tr>
																		<tr>
																			<td>ID No.</td>
																			<td><?php echo $identity_number ?></td>
																		</tr>
																		<tr>
																			<td>Loan No.</td>
																			<td><?php echo $loan_number ?></td>
																		</tr>
																		<tr>
																			<td>Handled By</td>
																			<td><?php echo $submitted_by?> - <?php echo date("Y-m-d", strtotime($date_added)) ?></td>
																		</tr>
																	</table>
																</a>
															</td>
															
															<td>
																<div class="table table-responsive">
																	<table class="table table-borderless">
																		<tr>
																			<td>Amount Requested</td>
																			<td><?php echo $currency ?> <?php echo number_format($principle_amount, 2)?></td>
																		</tr>
																		<tr>
																			<td>Interest Rate</td>
																			<td><?php echo $loan_interest ?>% <?php echo $loan_interest_period ?></td>
																		</tr>
																		<tr>
																			<td>Amount to Repay</td>
																			<td><?php echo $currency ?></small> <?php echo number_format($total_payable_amount, 2) ?></td>
																		</tr>
																		<tr>
																			<td>Repayment Period</td>
																			<td>
																				<?php if ($loan_payment_options == "Monthly") {
																					echo $repayments . ' Months';
																				}elseif ($loan_payment_options == "Weekly") {
																					echo $repayments . ' Weeks';
																				} ?>
																			</td>
																		</tr>
																	</table>
																</div>
															</td>
															
															<td id="loan_status">
																<?php 
																	if ($loan_status == 'Pending Approval') {?>
																		<div class="frames">
																			<a href="<?php echo $borrower_id?>" class="text-primary actionModal">Pending Approval <br><br> Click to Action <i class="bi bi-arrow-right-square"></i></a>
																		</div>
																<?php
																	}else{?>
																		<p><?php echo $loan_status?> | <?php echo date("j, F Y", strtotime($release_date))?></p>
																		

																<?php	}?>
																
															</td>
															
															<td>
								        						<div class="list-group">
																	<?php if ($loan_status == 'Released'): ?>
																		<a href="loans/add-payments?loan_number=<?php echo $loan_number?>&borrower_id=<?php echo base64_encode($borrower_id) ?>" class="btn btn-outline-secondary mt-2 addBtn">Add Payment</a>
																	<?php endif;?>
																	<a href="loans/view-loan-schedule?loan_number=<?php echo $loan_number?>&applicant_id=<?php echo $borrower_id?>" class="btn btn-outline-primary mt-2"><i class="bi bi-clock"></i> Loan Schedule </a>
																</div>
															</td>
														</tr>

														<div class="modal fade" id="paymentModal">
															<div class="modal-dialog modal-lg">
																<div class="modal-content">
																	<form method="post" id="loanPaymentForm">
																		<div class="modal-header">
																			<h4 class="modal-title">Loan Payment Form</h4>
																			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																			</button>
																		</div>
																		<div class="modal-body">
																			<?php
																				$owed = getTotalAmountOwed($connect, $borrower_id, $loan_number);
					                     										$paid = getTotalAmountPaid($connect, $borrower_id, $loan_number);
					                     										$balance = $owed - $paid;
																			?>
																			<div class="form-group mb-3">
																				<label>Amount Paid</label>
																				<div class="input-group">
																					<div class="input-group-prepend">
																						<span class="input-group-text"><?php echo getCurrency($connect, $_SESSION['parent_id'])?></span>
																					</div>

																					<input type="number" name="amount" id="amount" step="any" min="0" class="form-control" required onkeyup="reduceBalance(this.value)">
																					<input type="hidden" name="currency" id="currency" value="<?php echo getCurrency($connect, $_SESSION['parent_id'])?>">

																					<input type="hidden" name="edit_id" id="edit_id">
																				</div>
																			</div>
																			<div class="form-group mb-3">
																				<label> Balance Owed</label>
																				<div class="input-group">
																					<div class="input-group-prepend">
																						<span class="input-group-text"><?php echo getCurrency($connect, $_SESSION['parent_id'])?></span>
																					</div>
																					<input type="text" name="balance" id="balance" class="form-control" value="<?php echo $balance?>" readonly>
																				</div>
																				<script>
																					function reduceBalance(entered_amount){
																						var balance = document.getElementById('balance').value;
																						if (entered_amount !== "") {
																							amount = parseInt(entered_amount);
																							balanceOwed = parseInt(balance) - amount;
																							document.getElementById('balance').value = balanceOwed;
																						}else{
																							document.getElementById('balance').value = '<?php echo $balance?>';
																						}

																					}
																					
																				</script>
																			</div>
																			<div class="form-group mb-3">
																				<label>Payment Method</label>
																				<div class="input-group">
																					<div class="input-group-prepend">
																						<span class="input-group-text"><i class="bi bi-wallet2"></i></span>
																					</div>
																					<select class="form-control" name="payment_method" id="payment_method">
																						<option value="Cash">Cash</option>
																						<option value="Bank Transfer">Bank Transfer</option>
																						<option value="Mobile Money">Mobile Money</option>
																						<option value="Cheque">Cheque</option>
																					</select>
																				</div>
																			</div>
																			<div class="form-group">
																				<label>Collection Date</label>
																				<div class="input-group">
																					<div class="input-group-prepend">
																						<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
																					</div>
																					<input type="date" name="paid_date" class="form-control" id="datemask2" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask>
																				</div>
																			</div>
																			<?php
																			$options = '';
																			$sql = $connect->prepare("SELECT * FROM allowed_branches WHERE branch_id = ? AND parent_id = ? ");
																	        $sql->execute(array($BRANCHID, $_SESSION['parent_id']));
																	        foreach ($sql->fetchAll() as $rows) {
																	            $staff_id = $rows['staff_id'];
																			    $options .= '<option value="'.$staff_id.'">'.getStaffMemberNames($connect, $staff_id, $_SESSION['parent_id']).'</option>';
																			}

																			
																			?>
																			<div class="form-group mb-3">
																				<label>Collected by:</label>
																				<div class="input-group">
																					<div class="input-group-prepend">
																						<span class="input-group-text"><i class="bi bi-file-person"></i></span>
																					</div>
																					<select class="form-control" name="collected_by" id="collected_by">
																						<?php echo $options?>
																					</select>
																				</div>
																			</div>
																			
																			<div class="form-group">
																				
																				<label>Description or Comments</label>
																				<div class="input-group">
																					<div class="input-group-prepend">
																						<span class="input-group-text"><i class="bi bi-x-diamond"></i></span>
																					</div>
																					<textarea class="form-control" rows="3" name="comment" id="comment" placeholder="Being payment to service the loan" required> </textarea>
																				</div>
																			</div>
																			<div class="form-group mb-3">
																				<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID ?>">
																				<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id'] ?>">
																				<input type="hidden" name="loan_number" id="loan_number" value="<?php echo $loan_number ?>">
																				<input type="hidden" name="borrower_id" id="borrower_id" value="<?php echo $borrower_id?>">
																			</div>
																		</div>
																		<div class="modal-footer d-flex justify-content-between">
																			<button type="button" class="btn btn-outline-danger" data-dismiss="modal" aria-label="Close">Close</button>
																			<button class="btn btn-outline-primary" type="submit" id="addPay">Submit Payment</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
													<?php
														}
													}
			      								?>
			      							</tbody>
      									</table>
      								</div>
      							</div>
      						</div>
      					</div>
      				</div>
      			</div>
      			<div class="container-fluid">
      				<div class="row">
      					<div class="col-md-12">
      						<div class="card card-warning">
      							<div class="card-header">
      								<h4 class="card-title">Payments</h4>
      							</div>
      							<div class="card-body" id="card-body">
      								<div id="paymentDiv"></div>
      							</div>
      						</div>
      					</div>
      				</div>
      			</div>
      		</section>
      		<?php
      			if (isset($_GET['loan_number']) AND isset($_GET['borrower_id'])) {?>
      				<script>
      					$("#paymentModal").modal("show");
      				</script>
      		<?php	
      			}
      		?>
		</div>
		<aside class="control-sidebar control-sidebar-dark"></aside>
	</div>
	<?php include("../footer_links.php")?>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script src="plugins/toastr/toastr.min.js"></script>
	<script>
		$(document).ready( function () {
		    $('#loansTable, #paymentsTable').DataTable();
		   
		    // select

		    $(document).on("click", ".addBtn", function(e){
		    	e.preventDefault();
		    	$("#paymentModal").modal("show");
		    	
		    })
		    //
		   $("#loanPaymentForm").submit(function(e){
				e.preventDefault();
				var loanPaymentForm = document.getElementById('loanPaymentForm');
				var data = new FormData(loanPaymentForm);

				$.ajax({
					url:'loans/loanPayments?<?php echo time()?>',
					method:"post",
					data:data,
					cache : false,
    				processData: false,
    				contentType: false,
    				beforeSend:function(){
    					$("#addPay").html('<i class="fa fa-spinner fa-spin"></i>');
    				},
					success:function(data){
						
						successNow(data);
						paymentDone();
						$("#addPay").html('Submit Payment')
					}
				})
			})
		})

		// ======= EDIT LOAN --------

		$(document).on("click", ".editPayment", function(e){
			e.preventDefault();
			var payment_id = $(this).data("id");
			$("#paymentModal").modal("show");
			$.ajax({
				url:"loans/editPayments",
				method:"post",
				data:{payment_id:payment_id},
				dataType:"JSON",
				success:function(data){
					$("#edit_id").val(data.id);
					$("#payment_method").val(data.payment_method);
					$("#datemask2").val(data.paid_date);
					$("#comment").val(data.comment);
					$("#amount").val(data.amount);
				}
			})
		})

		$(document).on("click", ".deletePayment", function(e){
			e.preventDefault();
			var payment_id = $(this).data("id");
			$("#modal-danger").modal("show");
			$("#delete_id").val(payment_id);
		})

		$("#submitTodelete").click(function(e){
			var delete_id = $("#delete_id").val();
			$.ajax({
				url:'loans/loanPayments?<?php echo time()?>',
				method:"post",
				data:{delete_id:delete_id},
				beforeSend:function(){
					$("#submitTodelete").html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success:function(data){
					successNow(data);
					paymentDone();
				}
			})	

		})
		    
		function successNow(msg){
			toastr.success(msg);
	      	toastr.options.progressBar = true;
	      	toastr.options.positionClass = "toast-top-center";
	      	toastr.options.showDuration = 1000;
	    }

		function errorNow(msg){
			toastr.error(msg);
	      	toastr.options.progressBar = true;
	      	toastr.options.positionClass = "toast-top-center";
	      	toastr.options.showDuration = 1000;
	    }
	    
	    function paymentDone() {
	    	var borrower_id = "<?php echo $borrower_id?>";
	    	var loan_number = "<?php echo $loan_number?>";
	    	$.ajax({
				url:'loans/payments',
				method:"post",
				data:{loan_number:loan_number, borrower_id:borrower_id},
				success:function(data){
					$("#paymentDiv").html(data);
				}
			})	
	    }
	    paymentDone();

	</script>
</body>
</html>