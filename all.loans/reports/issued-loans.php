<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Issued Loans Reports</title>
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
		            <h4 class="m-0"><?php echo ucwords(getOrganisationName($connect, $_SESSION['parent_id']))?></h4>
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
      					<div class="col-md-12 mt-5">
  							<!-- <h4><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $BRANCHID))?> Loans</h4> -->
  							<?php
  								$from_period = $to_period = "";
  								if (isset($_GET['from_period']) AND isset($_GET['to_period'])) {
  									$from_period = $_GET['from_period'];
  									$to_period = $_GET['to_period'];
  								}
  							?>
  							<form method="get" id="searchForm" class="border p-4">
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
									<a href="reports/issued-loans" class="btn btn-outline-primary">Reset</a>
								</div>
  							</form>
  						</div>
      				</div>
      			</div>
      			<div class="container-fluid mt-5 mb-5" id="loans_container">
      				<div class="row">
      					<div class="col-md-12">
      						<div class="card card-primary">
      							<div class="card-header">
      								<h4 class="card-title"> ISSUED LOANS </h4>
      							</div>
      							<div class="card-body">
      								<div class="table table-reponsinve">
      									<table id="loansTable" class="cell-table  table-sm" style="width:100%">
      										<thead>
												<tr>
													<th>Photo</th>
													<th>Details</th>
													<th>Amount Details</th>
													<th>Loan Status</th>
													<th>Remarks</th>
												</tr>
      										</thead>
      										<tbody class="text-dark">
			      								<?php
			      									if (isset($_GET['from_period']) AND isset($_GET['to_period'])) {

			      										$query = $connect->prepare("SELECT * FROM loans_table WHERE `release_date` BETWEEN ? AND ? AND branch_id = ? AND parent_id = ? AND loan_status = 'Released' ");
			      										$query->execute(array($_GET['from_period'], $_GET['to_period'], $BRANCHID,  $_SESSION['parent_id']));
			      									}else{
			      										$query = $connect->prepare("SELECT * FROM loans_table WHERE branch_id = ? AND parent_id = ? AND loan_status = 'Released' ");
			      										$query->execute(array($BRANCHID,  $_SESSION['parent_id']));
			      									}
			      								
													if ($query->rowCount() > 0) {

														foreach ($query->fetchAll() as $row) {
															extract($row);
														?>

														<tr>
															<td>
																<a href="borrowers/see-borrower-details?applicant-id=<?php echo base64_encode($borrower_id) ?>" class="text-primary">
																	<img src="fileuploads/<?php echo $photo ?>" id="output_image" class="shadow-lg img-fluid img-responsive" alt="pic" style="width: 80px; height: 80px; border-radius: 50%;">
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
																				<?php 
																					if ($loan_payment_options == "Monthly") {
																						echo $repayments . ' Months';
																					}elseif ($loan_payment_options == "Weekly") {
																						echo $repayments . ' Weeks';
																					} 
																				?>
																			</td>
																		</tr>
																	</table>
																</div>
															</td>
															
															<td id="loan_status">
																<?php echo $loan_status;?> <br><br><p> <?php echo date("l, jS \of F Y", strtotime($release_date))?></p>
															</td>
															
															<td>
																<?php
																	$query = $connect->prepare("SELECT * FROM reports_issued_loans WHERE loan_number = ? AND display = '1' ");
																	$query->execute(array($loan_number));
																	if ($query->rowCount() > 0) {?>
																		<table class="table table-primary table-borderless" style="background-color: red;">
																	<?php
																		foreach ($query->fetchAll() as $row) {
																			extract($row);
																	?>
																			<tr>
																				<td><?php echo $remarks ?></td>
																			</tr>
																			<tr>
																				<td>By: <?php echo getStaffMemberNames($connect, $user_id, $parent_id) ?> - <small> <?php echo date("l, jS \of F Y", strtotime($date_added))?></small>
																				</td>
																			</tr>
																			<?php if($user_id == $_SESSION['user_id']):?>
																				<tr>
																					<td>
																						<a href="<?php echo $id?>" id="<?php echo $borrower_id?>" class="editRemarks"><i class="bi bi-pencil-square"></i> Edit</a> &nbsp;&nbsp;&nbsp; <a href="<?php echo $id?>" id="<?php echo $borrower_id?>" class="deleteRemarks text-danger"><i class="bi bi-trash"></i> Delete</a>
																					</td>
																				</tr>
																			<?php endif;?>
																	<?php
																		}
																		echo "</table>";
																	}
																?>
																		
																
																<a href="<?php echo $borrower_id?>" class="addComment btn btn-outline-primary"> <i class="bi bi-chat-right"></i> Add Remarks</a>
															</td>
														</tr>

														<div class="modal fade" id="remarksModal_<?php echo $borrower_id?>">
															<div class="modal-dialog modal-lg">
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title">Add remarks for <em><?php echo $firstname ?> <?php echo $lastname ?></em></h4>
																		<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	<form method="post" id="loanRemarksForm">
																		<div class="modal-body">
																			<div class="form-group mt-2">
																				<label>Add remarks</label>
																				<textarea class="form-control" rows="5" cols="5" name="remarks" id="remarks"></textarea>
																			</div>
																			<input type="hidden" name="remarks_id" id="remarks_id" value="">
																			<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID ?>">
																			<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id'] ?>">
																			<input type="hidden" name="borrower_id" id="borrower_id" value="<?php echo $borrower_id?>">
																			<input type="hidden" name="loan_number" id="loan_number" value="<?php echo $loan_number?>">
																		</div>
																		<div class="modal-footer d-flex justify-content-between ">
																			<button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close" onclick="_reset()">Close</button>
																			<button class="btn btn-outline-primary" type="submit" id="save_changes">Save Changes</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
														<script>
															$("#loanRemarksForm_<?php echo $borrower_id?>").submit(function(e){
																e.preventDefault();
																var loanRemarksForm = document.getElementById('loanRemarksForm_<?php echo $borrower_id?>');
																var data = new FormData(loanRemarksForm);

																$.ajax({
																	url:'reports/actionsReports?<?php echo time()?>',
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
																		$("#loan_status").load(location.href + " #loan_status");

																	}
																})
															})
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
	<script src="plugins/toastr/toastr.min.js"></script>
	<script>
		$(document).ready( function () {
		    $('#loansTable').DataTable();
		    // select

		    $(document).on("click", ".addComment", function(e){
		    	e.preventDefault();
		    	var borrower_id = $(this).attr("href");
		    	$("#remarksModal_"+borrower_id).modal("show");
		    })

		    $(document).on("click", ".editRemarks", function(e){
		    	e.preventDefault();
		    	var remarksEditId = $(this).attr("href");
		    	var borrower_id = $(this).attr("id");
		    	$("#remarksModal_"+borrower_id).modal("show");
		    	$.ajax({
					url:'reports/actionsReports',
					method:"post",
					data:{remarksEditId:remarksEditId},
					dataType:"JSON",
					success:function(data){
						$("#remarks_id").val(data.id);
						$("#remarks").val(data.remarks);
					}
				})
		    })

		    $(document).on("click", ".deleteRemarks", function(e){
		    	e.preventDefault();
		    	var deleteEditId = $(this).attr("href");
		    	var borrower_id = $(this).attr("id");
		    	if(confirm("Deleting the remarks will be archived for 30 days"))
		    	$.ajax({
					url:'reports/actionsReports',
					method:"post",
					data:{deleteEditId:deleteEditId, borrower_id:borrower_id},
					success:function(data){
						successNow(data);
						// location.reload();
					}
				})
		    })
		    // 
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
	    
	    function _reset() {
			$('#loanRemarksForm')[0].reset();
		}

	</script>
</body>
</html>