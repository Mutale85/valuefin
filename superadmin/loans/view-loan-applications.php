<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 
?>
<!DOCTYPE html>
<html>
<head>
	<title>View Loans Applications</title>
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
			<div class="content-header">
		      <div class="container-fluid mt-4">
		        <div class="row mb-2 mt-5">
		          <div class="col-sm-6">
		            <h4 class="m-0">Loan Applications</h4>
		          </div>
		          <div class="col-sm-6">
		            <ol class="breadcrumb float-sm-right">
		              <li class="breadcrumb-item"><a href="./" id="timeRemaining">Home</a></li>
		              <li class="breadcrumb-item active"><?php echo ucwords(getOrganisationName($connect, $_SESSION['parent_id']))?> </li>
		            </ol>
		          </div>
		        </div>
		      </div>
		    </div>
			<section class="content mt-5">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
						<?php
							$from_period = $to_period = "";
							if (isset($_GET['from_period']) AND isset($_GET['to_period'])) {
								$from_period = $_GET['from_period'];
								$to_period = $_GET['to_period'];
							}
						?>
						<form method="get" id="searchForm" class="border p-4 shadow-sm">
							<h4 class="mb-3">Search By Dates</h4>
							<div class="d-flex justify-content">
								<div class="form-group">
									<label>From</label>
									<input type="date" name="from_period" id="from_period" class="form-control" required value="<?php echo $from_period?>">
								</div>
								<div class="form-group">
									<label>To</label>
									<input type="date" name="to_period" id="to_period" class="form-control" required value="<?php echo $to_period?>">
								</div>
								<div class="form-group">
									<label>Search</label>
									<input type="submit" name="submit" value="Submit" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<a href="loans/view-loan-applications" class="btn btn-outline-primary">Reset</a>
							</div>
						</form>
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
      											<th>Client Details</th>
      											<th>Amount Details</th>
      											<th>Loan Details</th>
      											<th>Actions</th>
      										</thead>
      										<tbody class="text-dark">
			      								<?php
			      									
			      									if (isset($_GET['from_period']) && isset($_GET['to_period'])) {
			      										$query = $connect->prepare("SELECT * FROM loans_table WHERE `date_added` BETWEEN ? AND ? AND branch_id = ? AND parent_id = ? ");
														$query->execute(array($_GET['from_period'], $_GET['to_period'], $BRANCHID,  $_SESSION['parent_id']));
			      									}else{

			      										$query = $connect->prepare("SELECT * FROM loans_table WHERE branch_id = ? AND parent_id = ? ");
														$query->execute(array($BRANCHID,  $_SESSION['parent_id']));
													}
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
															
															<td id="loan_status_<?php echo $borrower_id?>">
																
																<table class="table table-borderless">
																	<tr>
																		<td>Status</td>
																		<td>
																			<?php 
																				if ($loan_status == 'Pending Approval') {?>
																					<div class="frames">
																						<p>Pending Approval</p>
																						<a href="<?php echo $borrower_id?>" class="btn btn-outline-primary actionModal"> Click to Action <i class="bi bi-arrow-right-square"></i></a>
																					</div>
																			<?php
																				}else{?>
																					<button class="btn btn-success" type="button"><?php echo $loan_status?></button><br><br>
																					<p><?php echo date("l, jS \of F Y", strtotime($release_date))?></p>
																			<?php }?>
																		</td>
																	</tr>
																	<tr>
																		<td>Remarks</td>
																		<td>
																			<?php
																				$query = $connect->prepare("SELECT * FROM `reports_issued_loans` WHERE loan_number = ? AND display = '1' ");
																				$query->execute(array($loan_number));
																				$count = $query->rowCount();
																			?>
																			<span class="badge badge-primary"><?php echo $count?></span>
																		</td>
																	</tr>
																</table>
															</td>
															
															<td>
																<div class="list-groups">
									        						<a class="text-dark list-group-item list-group-item-action mb-2 list-group-item-primary" href="loans/view-loan-schedule?loan_number=<?php echo $loan_number?>&applicant_id=<?php echo $borrower_id?>"><i class="bi bi-clock"></i> Loan Schedule  </a>
									        						<?php if ($loan_status == 'Released'): ?>
									        						<a class="text-dark list-group-item list-group-item-action mb-2 list-group-item-success" href="loans/add-payments?loan_number=<?php echo $loan_number?>&borrower_id=<?php echo base64_encode($borrower_id) ?>"><i class="bi bi-circle"></i> View & Add Payment</a>
									        						<a class="text-dark list-group-item list-group-item-action mb-2 list-group-item-warning" href="loans/view-loan-statement?loan_number=<?php echo $loan_number?>&applicant_id=<?php echo $borrower_id?>"><i class="bi bi-file-pdf"></i> Loan Statement</a>
									        						<?php endif;?>
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
																	<form method="post" id="loanActionForm_<?php echo $borrower_id?>">
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
																					<option value="Rejected"><span class="text-danger"> Reject & Close</span></option>
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
														<script>
															$("#loanActionForm_<?php echo $borrower_id?>").submit(function(e){
																e.preventDefault();
																var loanActionForm = document.getElementById('loanActionForm_<?php echo $borrower_id?>');
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
																		$("#loan_status_<?php echo $borrower_id?>").load(location.href + " #loan_status_<?php echo $borrower_id?>");
																	}
																})
															});

														</script>

														

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
	<script src="plugins/select2/js/select2.full.min.js"></script>
	<script src="plugins/toastr/toastr.min.js"></script>
	<script>
		$(document).ready( function () {
		    $('#loansTable').DataTable();
		    // select

		    $(document).on("click", ".actionModal", function(e){
		    	e.preventDefault();
		    	var borrower_id = $(this).attr("href");
		    	$("#actionModal_"+borrower_id).modal("show");
		    })

		    $(document).on("click", ".addRemarks", function(e){
		    	e.preventDefault();
		    	var borrower_id = $(this).attr("href");
		    	$("#remarksModal_"+borrower_id).modal("show");
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