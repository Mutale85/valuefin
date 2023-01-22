<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 

  	if (isset($_GET['loan_number']) && isset($_GET['applicant_id'])) {
  		$loan_number = $_GET['loan_number'];
  		$borrower_id = $_GET['applicant_id'];
  	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Loans Schedule </title>
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
      			<div class="container-fluid mt-5 mb-5">
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
      											<th>Applicant Photo</th>
      											<th>Applicant Details</th>
      											<th>Amount Details</th>
      											<th>Loan Schedule</th>
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
																<a href="loans/view_loan_details?loan_number=<?php echo $loan_number?>&borrower_id=<?php echo $borrower_id?>" class="text-primary">
																	<img src="fileuploads/<?php echo $photo ?>" id="output_image" class="shadow-sm img-fluid img-responsive" alt="pic" style="width: 80px; height: 80px; border-radius: 2%;">
																</a>
															</td>
															
															<td>
																<a href="loans/view_loan_details?loan_number=<?php echo $loan_number?>&borrower_id=<?php echo $borrower_id?>" class="text-primary">
																	<table class="table table-borderless">
																		<tr>
																			<td>Names</td>
																			<td><?php echo $firstname ?> <?php echo $lastname ?>, <?php echo $gender ?></td>
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
																			<td>Submitted By</td>
																			<td><?php echo $submitted_by?> - <?php echo date("Y-m-d", strtotime($date_added)) ?></td>
																		</tr>
																		
																	</table>
																</a>
															</td>
															
															<td>
																
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
																	<tr>
																		<td>Loan Status</td>
																		<td>
																			<?php 
																				if ($loan_status == 'Pending Approval') {?>
																					
																					<a href="<?php echo $borrower_id?>" class="actionModal"><span class="text-secondary"> Pending Approval </span><br><br><span class="text-primary"> Click to Action <i class="bi bi-arrow-right-square"></i></span></a>
																					
																			<?php
																				}else{
																					echo $loan_status;
																				}
																			?>
																		</td>
																	</tr>
																</table>
															</td>
															
															<td>
																
																	<table class="table table-bordered" id="ScheduleTables">
																		<thead>
																			<tr>
																				<th>Due Date</th>
																				<th>Amount</th>
																			</tr>
																		</thead>
																		<tbody>
																<?php 
																	
																	$query = $connect->prepare("SELECT * FROM loan_schedules WHERE borrower_id = ? AND loan_id = ? ");
																	$query->execute(array($borrower_id, $loan_number));
																	foreach ($query->fetchAll() as $rows) {
																		extract($rows);
																	?>
																		<tr>
																			<td><?php echo date("l, jS \of F Y", strtotime($date_due))?></td>
																			<td><?php echo $currency?> <?php echo $amount?></td>
																		</tr>
																	<?php
																		}
																	?>
																		
																		</tbody>
																	</table>
																 	<div class="frame">
																		<a href="loans/view-loan-applications" class="custom-btn btn-3">View all loans</a>
																	</div>
															</td>
															
														</tr>

														<div class="modal fade" id="actionModal_<?php echo $borrower_id?>">
															<div class="modal-dialog modal-lg">
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title">Loan Action For <em><?php echo $firstname ?> <?php echo $lastname ?></em></h4>
																		<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	<form method="post" id="loanActionForm">
																		<div class="modal-body">
																			<div class="form-group mt-2">
																				<label>Amount Applied</label>
																				<div class="input-group">
																					<span class="input-group-text"><?php echo $currency ?></span>
																					<input type="hidden" name="currency" id="currency" value="<?php echo $currency?>">
																					<input type="text" name="amount" id="amount" class="form-control" value="<?php echo $principle_amount?>">
																				</div>
																			</div>
																			<div class="form-group mb-3">
																				<label>Change Status</label>
																				<select class="form-control" name="loan_status" id="loan_status" required>
																					<option value="" disabled selected>Select</option>
																					<option value="Released">Approve & Pay</option>
																					<option value="Rejected">Reject & Close</option>
																					<!-- <option value="Completed">Close Completed Loan</option> -->
																				</select>
																				<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID ?>">
																				<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id'] ?>">
																				<input type="hidden" name="borrower_id" id="borrower_id" value="<?php echo $borrower_id?>">
																				<input type="hidden" name="loan_number" id="loan_number" value="<?php echo $loan_number?>">
																			</div>
																		</div>
																		<div class="modal-footer d-flex justify-content-between ">
																			<button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Close</button>
																			<button class="btn btn-outline-primary" type="submit" id="save_changes">Save Changes</button>
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
      		</section>
		</div>
		<aside class="control-sidebar control-sidebar-dark"></aside>
	</div>
	<?php include("../footer_links.php")?>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	<script src="plugins/toastr/toastr.min.js"></script>
	<script>
		$(document).ready( function () {
		    $('#loansTable').DataTable();
		    $(document).on("click", ".actionModal", function(e){
		    	e.preventDefault();
		    	var borrower_id = $(this).attr("href");
		    	$("#actionModal_"+borrower_id).modal("show");
		    })

		    $("#loanActionForm").submit(function(e){
				e.preventDefault();
				var loanActionForm = document.getElementById('loanActionForm');
				var data = new FormData(loanActionForm);

				$.ajax({
					url:'loans/actionsLoan?<?php echo time()?>',
					method:"post",
					data:data,
					cache : false,
    				processData: false,
    				contentType: false,
    				beforeSend:function(){
    					$("#save_changes").html('<i class="fa fa-spinner fa-spin"></i>');
    				},
					success:function(data){
						successNow(data);
						$("#save_changes").html("Save Changes");
						$("#loans_container").load(location.href + " #loans_container");

					}
				})
			}) 
		});

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
	    
	</script>
</body>
</html>