<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Create New Loan</title>
	<?php include("../addon_header.php");?>
</head>
<body class=" layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-10">
						<div class="card card-primary mb-5">
							<div class="card-header">
								<h2 class="card-title"><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $BRANCHID))?> New Loan Application</h2>
							</div>
								<form method="post" id="loanForm">
									
									<div class="card-body">
										<?php
											if (isset($_GET['applicant-id'])) {
												$branch_id 		= base64_decode($_GET['branch-id']);
												$applicant_id 	= base64_decode($_GET['applicant-id']);
												$parent_id 		= base64_decode($_GET['parent-id']);
												$query = $connect->prepare("SELECT * FROM borrowers_details WHERE borrower_id = ? AND branch_id = ? AND parent_id = ?");
												$query->execute(array($applicant_id, $branch_id,  $parent_id));
												$row = $query->fetch();
												if ($row) {
													extract($row);
										?>
										
										<div class="row">
											<div class="form-group col-md-12 mb-3">
												<div class="p-3">
													<input type="hidden" name="loan_edit" id="loan_edit">
													<input type="hidden" name="borrower_photo" id="borrower_photo" class="form-control" value="<?php echo $borrower_photo ?>">
													<img src="borrowers/uploads/<?php echo $borrower_photo ?>" id="output_image" class="shadow-sm img-fluid img-responsive" alt="pic" style="width: 120px; height: 120px; border-radius: 50%;">
												</div>
											</div>
											<div class="form-groups col-md-6">
												<label for="form">First-name</label>
												<input type="hidden" id="borrower_title" name="borrower_title" class="form-control" readonly value="<?php echo $borrower_title?>">
												<div class="input-group mb-3">
													<span class="input-group-text"><?php echo $borrower_title?></span>
													<input type="text" name="borrower_firstname" id="borrower_firstname" class="form-control" readonly value="<?php echo $borrower_firstname?>">
												</div>
												<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID ?>">
												<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
												<input type="hidden" name="applicant_id" id="applicant_id" value="<?php echo $applicant_id?>">
											</div>
											<div class="form-groups col-md-6">
												<label for="form">Last-name</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-person"></i></span>
													<input type="text" name="borrower_lastname" id="borrower_lastname" class="form-control" readonly value="<?php echo  $borrower_lastname?>">
												</div>
											</div>
											<div class="border-bottom pb-2 mb-4"></div>
											<div class="form-groups col-md-6">
												<label>Gender</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
													<input id="borrower_gender" name="borrower_gender" class="form-control" readonly value="<?php echo $borrower_gender ?>">
												</div>
											</div>
											<div class="form-groups col-md-6 mb-3">
												<label for="form">NRC</label>
												<div class="input-group mb-1">
													<span class="input-group-text"><i class="bi bi-file-person"></i></span>
													<input type="text" name="borrower_id" id="borrower_id" class="form-control" readonly value="<?php echo $borrower_id?>">
												</div>
											</div>
											<div class="form-groups col-md-6 mb-3">											
												<label for="form">Phone</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-phone"></i></span>
													<input type="text" name="borrower_phone" id="borrower_phone" class="form-control" readonly value="<?php echo  $borrower_phone?>">
												</div>
											</div>
											
											<div class="col-md-6 mb-3">
												<label>Loan No:</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-plus"></i></span>
													<input type="text" name="loan_number" id="loan_number" class="form-control" placeholder="Create Loan ID">
												</div>
												<em>Please Add Loan ID, else you wont proceed.</em>
											</div>

											<div class="col-md-12">
												<div class="border-bottom mt-4 mb-4"></div>
												<h5 class="mb-3 text-primary">Alternative contact person (same house or neighborhood)</h5>
											</div>
											<div class="col-md-4">
												<label>Fullnames</label>
												<input type="text" name="alt_contact_names" id="alt_contact_names" class="form-control">
											</div>
											<div class="col-md-4">
												<label>Relationship</label>
												<input type="text" name="alt_contact_relationship" id="alt_contact_relationship" class="form-control">
											</div>
											<div class="col-md-4">
												<label>Phone</label>
												<input type="text" name="alt_contact_phone" id="alt_contact_phone" class="form-control">
											</div>

											<div class="col-md-12">
												<div class="border-bottom mt-4 mb-4"></div>
												<h4 class="mb-3 text-danger">Loan Details</h4>
											</div>
											
											
											<div class="col-md-4 mb-3">
												<label>Principle Amount</label>
												<div class="input-group">
													<div class="input-group-append">
														<span class="input-group-text"><?php echo getCurrency($connect, $parent_id) ?></span>
													</div>
													<input type="hidden" name="currency" id="currency" value="<?php echo getCurrency($connect, $parent_id) ?>">
													<input type="number" step="any" class="form-control" name="principle_amount" id="principle_amount" min="0" required>
												</div>
											</div>
											<div class="col-md-4 mb-3">
												<label>Loan type / Interest Rate (Per Month)</label>
												<select class="form-control" id="loan_id" name="loan_id" required>
													<option value="">Select loan type</option>
												</select>
												<em>To add loan types <a href="borrowers/loan-settings" class="call_modals"> Click Here</a></em>
											</div>
											
											<input type="hidden" name="interest" id="interest" class="form-control" readonly>
											<div class="col-md-4 mb-3">
												<label for="loan_payment_options">Total Loan Amount</label>
												<div class="input-group">
													<div class="input-group-append">
														<span class="input-group-text" id="feeSymbol">ZMW</span>
													</div>
													<input type="number" step="any" class="form-control" name="total_loan_amount" id="total_loan_amount" min="0" readonly>
												</div>
											</div>
											<div class="col-md-4 mb-3" id="formEnter">
												<label> Repayment Amount <small>(Daily)</small></label>
												<div class="input-group">
													<div class="input-group-append">
														<span class="input-group-text" id="selectedSymbol">ZMW</span>
													</div>
													<input class="form-control" name="repayment_amount_daily" id="repayment_amount_daily" readonly>
												</div>
											</div>
											<div class="col-md-4 mb-3" id="formEnter">
												<label> Repayment Amount <small>(Weekly)</small></label>
												<div class="input-group">
													<div class="input-group-append">
														<span class="input-group-text" id="selectedSymbol">ZMW</span>
													</div>
													<input class="form-control" name="repayment_amount_weekly" id="repayment_amount_weekly" readonly>
												</div>
											</div>
											<div class="col-md-4 mb-3" id="formEnter">
												<label> Repayment Amount <small>(Month)</small></label>
												<div class="input-group">
													<div class="input-group-append">
														<span class="input-group-text" id="selectedSymbol">ZMW</span>
													</div>
													<input class="form-control" name="repayment_amount_month" id="repayment_amount_month" readonly>
												</div>
											</div>
											<div class="col-md-4 mb-3" id="formEnter">
												<label>Tenor - Working Days(No Weekends)</label>
												<div class="input-group">
													<input type="text" step="any" class="form-control" name="days" id="days" placeholder="Days" onkeyup="getDays(this.value)">
													<div class="input-group-append">
														<span class="input-group-text">Day(s)</span>
													</div>
												</div>
											</div>
											<div class="col-md-4 mb-3" id="formEnter">
												<label>Tenor - Week</label>
												<div class="input-group">
													<input type="text" step="any" class="form-control" name="weeks" id="weeks" placeholder="Weeks" readonly>
													<div class="input-group-append">
														<span class="input-group-text">Week(s)</span>
													</div>
												</div>
											</div>
											
											<!-- Processing Fees -->
											
											<div class="col-md-4 mb-3" id="formEnter">
												<label >Processing Fee (Upfront Fee)</label>
												<div class="input-group">
													<div class="input-group-append">
														<span class="input-group-text" id="feeSymbol">ZMW</span>
													</div>
													<input type="number" step="any" class="form-control" name="loan_processing_fee" id="loan_processing_fee" min="20">
												</div>
												<em>This is the <span id="resultspanP"></span> which will be deducted from the total borrowed amount.</em>
											</div>

											<div class="col-md-4 mb-3">
												<label for="loan_payment_options">Net Loan</label>
												<div class="input-group">
													<div class="input-group-append">
														<span class="input-group-text" id="feeSymbol">ZMW</span>
													</div>
													<input type="number" step="any" class="form-control" name="net_loan" id="net_loan" min="0" readonly>
												</div>
											</div>
											
											<div class="col-md-4 mb-3">
												<label>Repayment Date</label>
												<input type="text" name="repayment_start_date" id="repayment_start_date" class="form-control" autocomplete="off" required placeholder="Set Repayment Date">
											</div>
											<div class="col-md-4 mb-3">
												<label>Release Method:</label>
												<select class="form-control" name="release_method" required>
													<option value="Mobile Money">Mobile Money</option>
													<option value="Cash">Cash</option>
												</select>
											</div>
											
											<div class="col-md-12">
												<div id="calculation_result"></div>
											</div>
										</div>
									</div>
									<div class="card-footer justify-content-between">
										<!-- <button class="btn btn-secondary" type="button" id="calculate">Calculate Loan</button> -->
										<button type="submit" class="btn btn-primary" id="saveLoan" disabled>Save changes</button>
									</div>
									<?php
										}
									}
									?>
								</form>
						</div>
					</div>
					<div class="col-md-1"></div>
				</div>
				<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                  Launch Default Modal
                </button>
				<!-- /.modal-content -->
				<div class="modal fade" id="modal-default">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
							<h4 class="modal-title">Default Modal</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							</div>
							<div class="modal-body">
							<p>One fine body&hellip;</p>
							</div>
							<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary">Save changes</button>
							</div>
						</div>
					</div>
				</div>
				<!-- end of modal -->
			</div>
		</section>
	</div>

	<?php include("../addon_footer.php")?>
	<script>
		function getDays(day){
			if(day !== ""){
				let days = day;
				let weeks = Math.round(days / 7);
				document.getElementById('weeks').value = weeks;
				
			}
		}
		
		$(document).ready( function () {
		    $(document).on("click", ".call_modal", function(e){
				e.preventDefault();
				$("#modal-default").modal("show");
			})

			$("#repayment_start_date").datepicker({
				dateFormat: "DD, d MM, yy",
				autoclose: true, 
        		todayHighlight: true,
				minDate: 0, maxDate: "+1M +10D"
			});

		});
		

		
		 function fetchLoanTypes(loan_parent_id){
				// we make an ajax call to find collectors to assign the work to.
			$.ajax({
				url:'borrowers/loans/fetchApplicants?<?php echo time()?>',
				method:"post",
				data:{loan_parent_id:loan_parent_id},
				success:function(data){
					$("#loan_id").html(data);
				}
			})
		}
		fetchLoanTypes("<?php echo base64_encode($_SESSION['parent_id'])?>");

		$(function(){
			$(document).on("change", "#loan_id", function(){
				var rates = $(this).val();
				var principleAmount = document.getElementById('principle_amount').value;
				if(principleAmount === ""){
					alert("Add principle amount");
					document.getElementById('principle_amount').focus();
					return false;
				}
				var interest =  $(this).find(':selected').data('rate');
				var period   =  $(this).find(':selected').data('period');
				
				var interestRatePerMonth = interest;
				var tenorInDays = 20;
				var tenorInWeeks = 4;
				var tenorInMonths = 1;
				var total_loan_amount = (principleAmount * interestRatePerMonth /100);
				var grossLoan = parseFloat(principleAmount)+ parseFloat(total_loan_amount);
				document.getElementById('repayment_amount_daily').value = parseFloat(grossLoan)/tenorInDays;
				document.getElementById('repayment_amount_weekly').value = parseFloat(grossLoan)/tenorInWeeks;
				document.getElementById('repayment_amount_month').value = parseFloat(grossLoan)/tenorInMonths;
				
				if(rates !== ""){
					document.getElementById('interest').value = interest;
					document.getElementById('total_loan_amount').value = grossLoan;
				}else{
					document.getElementById('interest').value = "0.00";
					document.getElementById('total_loan_amount').value = '0.00';
				}
			})

			$(document).on("keyup", "#loan_processing_fee", function(){
				var loan_processing_fee = $(this).val();
				var total_loan_amount = document.getElementById('total_loan_amount').value;
				var net_loan =  document.getElementById('net_loan').value = parseFloat(repayment_amount) - parseFloat(loan_processing_fee);
			})
		})
		
	
	</script>
	<script>
		$(function(){
			$("#loanForm").submit(function(e){
				e.preventDefault();
				var saveLoan = document.getElementById('saveLoan');
				var loanForm = document.getElementById('loanForm');
				var data = new FormData(loanForm);
				var url = 'borrowers/loans/submitLoan';
				$.ajax({
					url:'borrowers/loans/submitnewLoan?<?php echo time()?>',
					method:"post",
					data:data,
					cache : false,
    				processData: false,
    				contentType: false,
					success:function(data){
						successNow(data);
					}
				})
			})
		})
	</script>
</body>
</html>