<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Create New Loan</title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
	<style>
		
		.cursor-pointer {
			cursor: pointer;
			font-size: 1em;
		}
		.select2-container--default.select2-container--focus .select2-selection--multiple, .select2-container--default.select2-container--focus .select2-selection--single {
		    border-color: #ff80ac;
		    height: 40px !important;
		}

		.select2-container--default .select2-selection--multiple .select2-selection__rendered li:first-child.select2-search.select2-search--inline {
		    width: 100%;
		    margin-left: .375rem;
		    height: 40px;
		}
		.select2-container--default .select2-selection--single {
		    background-color: #f8f9fa;
		    border: 1px solid #aaa;
		    border-radius: 4px;
		    height: 40px;
		}
		.select2-container--default .select2-selection--multiple .select2-selection__rendered {
		    box-sizing: border-box;
		    list-style: none;
		    margin: 0;
		    padding: .4em;
		    width: 100%;
		}
	</style>
</head>
<?php
	
?>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content mt-5">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 pb-2 d-flex justify-content-between border-bottom">
  							<h4><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $BRANCHID))?> Loan Applications</h4>
  							<button class="btn btn-outline-primary" type="button"  data-toggle="modal" data-target="#modalCalculator"><i class="bi bi-calculator"></i> Calculator </button>
  						</div>
      				</div>
      			</div>
      			<!-- Add Loan -->
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
											<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID ?>">
											<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
											<div class="row">
												<?php if(!isset($_GET['borrower_id'])):?>
												<div class="col-md-6 mb-3">
													<label>Individual Borrower</label>
													<select class="form-control" id="borrower_id" name="borrower_id" onchange="fetchGuarantor(this.value)" style="width: 100%" oninput="fetchLoanID(this.value)" required>
														<option value="">Select Borrower</option>
													</select>
                                                <em>If you have not added borrowers <a href="borrowers/add_borrowers"> Click Here</a></em>
												</div>
												<?php else:?>
													<div class="col-md-6">
														<label>Borrower:</label> 
														<?php echo getBorrowerFullNamesByCardId($connect, $_GET['borrower_id'])?>
														<input type="text" name="borrower_id" id="borrower_id" class="form-control" value="<?php echo $_GET['borrower_id']?>" readonly>
														<script>
															document.addEventListener('DOMContentLoaded', function (){
																fetchGuarantor('<?php echo $_GET['borrower_id']?>');
															})
															
														</script>
													</div>
												<?php endif;?>
												<div class="col-md-6 mb-3">
													<label>Loan Type</label>
													<select class="form-control" id="loan_id" name="loan_id" style="width: 100%" required>
														<option value="">Select Type</option>
													</select>
                                                	<em>To add loan types <a href="loans/loan_settings"> Click Here</a></em>
												</div>
												<div class="col-md-6 mb-3">
													<label>Loan No:</label>
													<input type="text" name="loan_number" id="loan_number" class="form-control" placeholder="Will auto fill">
                                                	<em>If it doesn't autofill - Please Add Loan ID, else you wont proceed.</em>
												</div>
												<div class="col-md-6 mb-3">
													<label>Principle Amount</label>
													<div class="input-group">
								                      	<input type="number" step="any" class="form-control" name="principle_amount" id="principle_amount" min="1" required>
								                      	<div class="input-group-append">
													    	<span class="input-group-text" id="SelectedValue"></span>
													  	</div>
								                    </div>
												</div>
												<div class="col-md-6 mb-3">
													<label>Release Method:</label>
													<select class="form-control" name="release_method" required>
														<option value="Cash">Cash</option>
														<option value="Cheque">Cheque</option>
														<option value="Wire Transfer">Wire Transfer</option>
													</select>
												</div>
												<div class="col-md-6 mb-3">
													<label>Application Date</label>
													<div class="input-group">
														<div class="input-group-append">
															<span class="input-group-text">
																<i class="bi bi-calendar"></i>
															</span>
														</div>
														<input type="text" name="release_date" id="release_date" class="form-control" required autocomplete="off">
													</div>
												</div>
												<div class="col-md-12">
													<div class="border-bottom mt-4 mb-4"></div>
													<h3 class="mb-3 text-danger">Charges</h3>
												</div>
												<div class="col-md-6 mb-3">
													<label>Interest Method</label>
													<select class="form-control" name="loan_interest_method" id="loan_interest_method" required>
														<option value=""></option>
							                            <option value="flat_rate" > Flat Rate</option>
							                            <!-- <option value="reducing_rate" >Reducing Balance</option> -->
							                            <!-- <option value="compound_interest" >Compound Interest</option> -->
							                        </select>
												</div>
												<div class="col-md-6 mb-3">
													<label>Interest Type</label>
													<select class="form-control" name="interest" id="interest" onchange="InterestCharge(this.value)" required>
														<option value=""></option>
														<option value="Percentage">Percentage Based</option>
														<option value="Amount">Fixed Amount Based</option>
													</select>
												</div>
												<script>
													function InterestCharge(fees) {
														// document.getElementById('label').innerHTML =  fees ;
														if (fees === 'Percentage') {
															document.getElementById('selectedSymbol').innerHTML = '%';
															document.getElementById('loan_interest').setAttribute('max', '100');
															document.getElementById('symbol').value = '%';
														}else if(fees === 'Amount'){
															document.getElementById('selectedSymbol').innerHTML = localStorage.getItem('currency_main');
															document.getElementById('loan_interest').setAttribute('max', '1000000000');
															document.getElementById('symbol').value = localStorage.getItem('currency_main');
														}
													}
												</script>
												<input type="hidden" name="currency" id="currency">
												<div class="col-md-6 mb-3" id="formEnter">
													<label> Interest Rate</label>
													<div class="input-group">
														<input type="hidden" name="symbol" id="symbol">
								                      	<input type="number" step="any" class="form-control" name="loan_interest" id="loan_interest" min="0" required value="1">
								                      	<div class="input-group-append">
													    	<span class="input-group-text" id="selectedSymbol"></span>
													  	</div>
													  	<select class="form-control" name="loan_interest_period" id="loan_interest_period" required onchange="testInt()" required>
													  		<option selected disabled>Select One</option>
													  		<option value="1">Daily</option>
													  		<option value="7">Weekly</option>
								                            <option value="28">Monthly</option>
								                        </select>
								                    </div>
												</div>
												
												<div class="col-md-6 mb-3" id="formEnter">
													<label>Loan Duration</label>
													<div class="input-group">
								                      	<input type="number" step="any" class="form-control" name="loan_duration" id="loan_duration" min="1" required value="1" required>

								                      	<div class="input-group-append">
													    	<span class="input-group-text">Period</span>
													  	</div>
													  	<select class="form-control" name="loan__period" id="loan__period" required>
													  		<option value=""></option>
													  		<option value="7">Week(s)</option>
								                            <option value="28">Month(s)</option>
								                        </select>
								                    </div>
												</div>
												<!-- Processing Fees -->
												<div class="col-md-12">
													<div class="form-group">
														<input type="radio" id="percentage_processing_fee" name="processing" onclick="myProcessingFee(this.value)" value="Percentage">
														<label for="percentage_processing_fee" style="font-size: 0.9em;">Processing Fee should be Percentage Based </label><br>
														<input type="radio" id="fixed_proceesing_fee" name="processing" onclick="myProcessingFee(this.value)" value=" Amount">
														<label for="fixed_proceesing_fee" style="font-size: 0.9em;">Processing Fee should be Fixed Amount Based </span></label><br>
													</div>
												</div>
												
												<div class="col-md-6 mb-3" id="formEnter">
													<label >Processing Fee You wish to charge</label>
													<div class="input-group">
														<input type="hidden" name="symbol_fee" id="symbol_fee">
								                      	<input type="number" step="any" class="form-control" name="loan_processing_fee" id="loan_processing_fee" min="1" required required>
								                      	<div class="input-group-append">
													    	<span class="input-group-text" id="feeSymbol"></span>
													  	</div>
													  	
								                    </div>
								                    <em>This is the <span id="resultspanP"></span> which will be deducted from the total borrowed amount.</em>
												</div>

							                    <div class="col-md-6">
							                    	<label for="loan_payment_options">Repayment Options</label>
							                        <select class="form-control" name="loan_payment_options" id="loan_payment_options" required>
							                            <option value=""></option>
							                            <option value="1">Daily</option>
							                            <option value="7">Weekly</option>
							                            <option value="28">Monthly</option>
							                            <option value="1">Lump-Sum</option>
							                        </select>
							                    </div>
							                    <div class="form-group col-md-6">
							                    	<label>Repayment From</label>
							                    	<input type="text" name="repayment_start_date" id="repayment_start_date" class="form-control" autocomplete="off" required>
							                    </div>

												<div class="col-md-6 mb-3">
													<label>Loan Guarantor</label>
													<select class="form-control" id="guarantor_id" name="guarantor_id" style="width: 100%">
														<option value="">Select Guarantors</option>
													</select>
													<em><a href="borrowers/add_guarantors" class="text-primary mt-2"> Add guarantors if non exists</a></em>
												</div>
												<div class="col-md-12 mb-3">
													<label>Loan Purpose</label>
													<textarea class="form-control" rows="5" name="loan_purpose" id="loan_purpose" required></textarea>
												</div>
												<div class="col-md-12">
													<div id="calculation_result"></div>
												</div>
												
												
											</div>
										</div>
										<div class="card-footer justify-content-between">
											<button class="btn btn-secondary" type="button" id="calculate">Calculate Loan</button>
											<button type="submit" class="btn btn-primary" id="saveLoan" disabled>Save changes</button>
										</div>
									</form>
      						</div>
      					</div>
      					<div class="col-md-1"></div>
      				</div>
      			</div>
      			<!-- calculator -->
      			<div class="container-fluid">
      				<style>
      					.body {
						  width: 400px;
						  margin: 4% auto;
						  font-family: 'Source Sans Pro', sans-serif;
						  letter-spacing: 5px;
						  font-size: 1.8rem;
						  -moz-user-select: none;
						  -webkit-user-select: none;
						  -ms-user-select: none;
						}

						.calculator {
						  padding: 20px;
						  -webkit-box-shadow: 0px 1px 4px 0px rgba(0, 0, 0, 0.2);
						  box-shadow: 0px 1px 4px 0px rgba(0, 0, 0, 0.2);
						  border-radius: 1px;
						}

						.input {
						  border: 1px solid #ddd;
						  border-radius: 1px;
						  height: 60px;
						  padding-right: 15px;
						  padding-top: 10px;
						  text-align: right;
						  margin-right: 6px;
						  font-size: 2.5rem;
						  overflow-x: auto;
						  transition: all .2s ease-in-out;
						}

						.input:hover {
						  border: 1px solid #bbb;
						  -webkit-box-shadow: inset 0px 1px 4px 0px rgba(0, 0, 0, 0.2);
						  box-shadow: inset 0px 1px 4px 0px rgba(0, 0, 0, 0.2);
						}

						.buttons {}

						.operators {}

						.operators div {
						  display: inline-block;
						  border: 1px solid #bbb;
						  border-radius: 1px;
						  width: 60px;
						  text-align: center;
						  padding: 10px;
						  margin: 20px 4px 10px 0;
						  cursor: pointer;
						  background-color: #ddd;
						  transition: border-color .2s ease-in-out, background-color .2s, box-shadow .2s;
						}

						.operators div:hover {
						  background-color: #ddd;
						  -webkit-box-shadow: 0px 1px 4px 0px rgba(0, 0, 0, 0.2);
						  box-shadow: 0px 1px 4px 0px rgba(0, 0, 0, 0.2);
						  border-color: #aaa;
						}

						.operators div:active {
						  font-weight: bold;
						}

						.leftPanel {
						  display: inline-block;
						}

						.numbers div {
						  display: inline-block;
						  border: 1px solid #ddd;
						  border-radius: 1px;
						  width: 80px;
						  text-align: center;
						  padding: 10px;
						  margin: 10px 4px 10px 0;
						  cursor: pointer;
						  background-color: #f9f9f9;
						  transition: border-color .2s ease-in-out, background-color .2s, box-shadow .2s;
						}

						.numbers div:hover {
						  background-color: #f1f1f1;
						  -webkit-box-shadow: 0px 1px 4px 0px rgba(0, 0, 0, 0.2);
						  box-shadow: 0px 1px 4px 0px rgba(0, 0, 0, 0.2);
						  border-color: #bbb;
						}

						.numbers div:active {
						  font-weight: bold;
						}

						div.equal {
						  display: inline-block;
						  border: 1px solid #3079ED;
						  border-radius: 1px;
						  width: 17%;
						  text-align: center;
						  padding: 127px 10px;
						  margin: 10px 6px 10px 0;
						  vertical-align: top;
						  cursor: pointer;
						  color: #FFF;
						  background-color: #4d90fe;
						  transition: all .2s ease-in-out;
						}

						div.equal:hover {
						  background-color: #307CF9;
						  -webkit-box-shadow: 0px 1px 4px 0px rgba(0, 0, 0, 0.2);
						  box-shadow: 0px 1px 4px 0px rgba(0, 0, 0, 0.2);
						  border-color: #1857BB;
						}

						div.equal:active {
						  font-weight: bold;
						}
      				</style>
      				<div class="row">
      					<div class="modal fade" id="modalCalculator">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title"> Calculator </h4>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									</div>
									<div class="modal-body">
				      					<div class="body">
											<div class="calculator">
											  	<div class="input" id="input"></div>
											  	<div class="buttons">
											    	<div class="operators">
														<div>+</div>
														<div>-</div>
														<div>&times;</div>
														<div>&divide;</div>
											    	</div>
											    	<div class="leftPanel text-dark">
														<div class="numbers">
															<div>7</div>
															<div>8</div>
															<div>9</div>
														</div>
														<div class="numbers">
															<div>4</div>
															<div>5</div>
															<div>6</div>
														</div>
														<div class="numbers">
															<div>1</div>
															<div>2</div>
															<div>3</div>
														</div>
														<div class="numbers">
															<div>0</div>
															<div>.</div>
															<div id="clear">C</div>
														</div>
											    	</div>
											    	<div class="equal" id="result">=</div>
											  	</div>
											</div>
				      					</div>
				      				</div>
				      			</div>
				      		</div>
				      	</div>
      				</div>
      			</div>
      			<!-- end of calculator -->
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
		function testInt(){
														
			var loan__period = document.getElementById('loan__period');
			var loan_interest_period_value = document.getElementById("loan_interest_period").value;
			var loan_duration_period_value = "";

			if (loan_interest_period_value == "Day")
			    loan_duration_period_value = "Days";
			    
			else if (loan_interest_period_value == "Week")
			    loan_duration_period_value = "Weeks";
			    
			else if (loan_interest_period_value == "Month")
			    loan_duration_period_value = "Months";

			else if (loan_interest_period_value == "Year")
			    loan_duration_period_value = "Years";

			if (loan_duration_period_value != "")
				selectedPeriod(loan__period, loan_duration_period_value);

		}
		function selectedPeriod(arg, value){
			for(var i=0; i < arg.options.length; i++){
			    if(arg.options[i].value == value)
			        arg.selectedIndex = i;
			}
		}

		function myProcessingFee(fees) {
			// document.getElementById('labelP').innerHTML =  fees ;
			document.getElementById("resultspanP").innerHTML = fees;
			if (fees === 'Percentage') {
				document.getElementById('feeSymbol').innerHTML = '%';
				document.getElementById('loan_processing_fee').setAttribute('max', '100');
				document.getElementById('symbol_fee').value = '%';
			}else{
				document.getElementById('feeSymbol').innerHTML = localStorage['currency_main'];
				document.getElementById('loan_processing_fee').setAttribute('max', '1000000000');
				document.getElementById('symbol_fee').value = localStorage['currency_main'];
			}
		}

		$(document).ready( function () {
		    $('#myTable').DataTable();
		    // select
		    $('.select2').select2();

			$("#release_date, #repayment_start_date").datepicker({
				format: 'yyyy-mm-dd',
				autoclose: true, 
        		todayHighlight: true,
        		startDate: '-3d'
			});

		});
		function ProcessBranch(){
			event.preventDefault();
			var branch_id = document.getElementById('branch_id');
			if(branch_id.value === ""){
				errorNow("Please Select Branch");
				branch_id.focus();
				return false;
			}else{
				alert(branch_id.value);
				// successNow("Taking to the branch");
				// setTimeout(function(){
				// 	window.location = "branches/branch?branch_id="+branch_id;
				// },1500);
			}
		}


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

	    function fetchBranchLoanBorrowers(branch_id_select){
			if (branch_id_select === "") {
				alert("Select Branch name");
				return false;
			}else{
				// we make an ajax call to find collecctors to assign the work to.
				$.ajax({
					url:'loans/fetchApplicants?<?php echo time()?>',
					method:"post",
					data:{branch_id_select:branch_id_select},
					success:function(data){
						$("#borrower_id").html(data);
					}

				})
			}
			document.getElementById('currency').value = localStorage['currency_main'];
		}
		fetchBranchLoanBorrowers("<?php echo base64_encode($BRANCHID)?>");

		 function fetchLoanTypes(loan_parent_id){
				// we make an ajax call to find collecctors to assign the work to.
			$.ajax({
				url:'loans/fetchApplicants?<?php echo time()?>',
				method:"post",
				data:{loan_parent_id:loan_parent_id},
				success:function(data){
					$("#loan_id").html(data);
				}
			})
			
		}
		fetchLoanTypes("<?php echo base64_encode($_SESSION['parent_id'])?>");
		

		function fetchGuarantor(borrower_card_id){
			if (borrower_card_id === "") {
				alert("Select Loan Plan");
				return false;
			}else{
				// we make an ajax call to find collecctors to assign the work to.
				$.ajax({
					url:'loans/fetchApplicants?<?php echo time()?>',
					method:"post",
					data:{borrower_card_id:borrower_card_id},
					success:function(data){
						$("#guarantor_id").html(data);

					}

				})
			}
		}
		function fetchLoanID(borrower_card_id_get_loan_number){
			
				$.ajax({
					url:'loans/fetchApplicants?<?php echo time()?>',
					method:"post",
					data:{borrower_card_id_get_loan_number:borrower_card_id_get_loan_number},
					success:function(data){
						$("#loan_number").val(data)
					}

				})
			// }
		}

		
		document.addEventListener('DOMContentLoaded', function (){
		    if (localStorage['currency_main']) { 
		        // document.getElementById('SelectedCurrency').innerHTML = localStorage['currency_main'];
		        document.getElementById('SelectedValue').innerHTML = localStorage['currency_main'];
		    }
		})

		//============== calculate the interest and repayments ======================
		var principle_amount = document.getElementById('principle_amount');
		var loan_interest    = document.getElementById('loan_interest');
		var interest_per_period  = document.getElementById('loan_interest_period');
		var loan_duration    = document.getElementById('loan_duration');
		var loan__period 	 = document.getElementById('loan__period');
		var loan_processing_fee = document.getElementById('loan_processing_fee');
		var calculate = document.getElementById('calculate');
		var interest = document.getElementById('interest');
		var loan_interest_method = document.getElementById('loan_interest_method');
		var loan_payment_options = document.getElementById('loan_payment_options');
		calculate.addEventListener("click", (event)=>{
			event.preventDefault();
			var symbol_fee = document.getElementById('symbol_fee').value;
			var symbol = document.getElementById('symbol').value;
        	var loan_number = document.getElementById('loan_number');
        	if(loan_number.value === ""){
            	errorNow("Add Loan Number");
            	loan_number.focus();
            	return false;
            }
			if (principle_amount.value === "") {
				errorNow("Enter borrowers requested amount");
				principle_amount.focus();
				return false;
			}

			if (interest.value == "") {
			    errorNow("Please Select Your Loan Interest Setting");
			    $(this).focus();
			    return false;
			}

			if (loan_interest.value === "") {
				errorNow("Enter Interest value");
				loan_interest.focus();
				return false;
			}

			if (interest_per_period.value === "") {
				errorNow("Please select how borrower interest will be compounded");
				return false;
			}

			if (loan_duration.value === "") {
				errorNow("How long will the loan run? Please select");
				return false;
			}

			if (loan__period.value === "") {
				errorNow("Please enter the duration of the loan");
				return false;
			}

			if (loan_processing_fee.value === "") {
				errorNow("Please add the processing fee");
				loan_processing_fee.focus();
				return false;
			}

			if (loan_payment_options.value === "") {
				errorNow("Please select payment circle.")
			}

			if (symbol_fee === '%') {
				symbol_fee = localStorage.getItem("currency_main");
			}else{
				symbol_fee = document.getElementById('symbol_fee').value;
			}

			if (symbol === '%') {
				symbol = localStorage.getItem("currency_main");
			}else{
				symbol = document.getElementById('symbol').value;
			}

			var xhr = new XMLHttpRequest();
			var url = 'loans/calculations';
			var data = 'principle_amount=' + principle_amount.value + '&loan_interest=' + loan_interest.value + '&symbol=' + symbol + '&interest_per_period=' + interest_per_period.value + '&loan_duration=' + loan_duration.value + '&loan__period=' + loan__period.value + '&loan_processing_fee=' + loan_processing_fee.value +'&loan_payment_options='+ loan_payment_options.value + '&symbol_fee=' + symbol_fee + '&loan_interest_method='+ loan_interest_method.value+'&interest_type='+interest.value;
			xhr.open("POST", url, true);
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					document.getElementById('calculation_result').innerHTML = xhr.responseText
					$("#saveLoan").removeAttr("disabled");
                	document.getElementById('calculate').innerHTML = 'Calculate';
				}
			}
			xhr.send(data);
        	document.getElementById('calculate').innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        	
		})


	</script>
	<script>
		$(function(){
			$("#loanForm").submit(function(e){
				e.preventDefault();
				// alert("Hello");
				var saveLoan = document.getElementById('saveLoan');
				var loanForm = document.getElementById('loanForm');
				var data = new FormData(loanForm);
				var url = 'loans/submitLoan';
				$.ajax({
					url:'loans/submitLoan?<?php echo time()?>',
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
		
		/* Loan Script*/
		"use strict";

	var input = document.getElementById('input'), // input/output button
	  number = document.querySelectorAll('.numbers div'), // number buttons
	  operator = document.querySelectorAll('.operators div'), // operator buttons
	  result = document.getElementById('result'), // equal button
	  clear = document.getElementById('clear'), // clear button
	  resultDisplayed = false; // flag to keep an eye on what output is displayed

// adding click handlers to number buttons
	for (var i = 0; i < number.length; i++) {
	  number[i].addEventListener("click", function(e) {

	    // storing current input string and its last character in variables - used later
	    var currentString = input.innerHTML;
	    var lastChar = currentString[currentString.length - 1];

	    // if result is not diplayed, just keep adding
	    if (resultDisplayed === false) {
	      input.innerHTML += e.target.innerHTML;
	    } else if (resultDisplayed === true && lastChar === "+" || lastChar === "-" || lastChar === "×" || lastChar === "÷") {
	      // if result is currently displayed and user pressed an operator
	      // we need to keep on adding to the string for next operation
	      resultDisplayed = false;
	      input.innerHTML += e.target.innerHTML;
	    } else {
	      // if result is currently displayed and user pressed a number
	      // we need clear the input string and add the new input to start the new opration
	      resultDisplayed = false;
	      input.innerHTML = "";
	      input.innerHTML += e.target.innerHTML;
	    }

	  });
	}

// adding click handlers to number buttons
	for (var i = 0; i < operator.length; i++) {
	  operator[i].addEventListener("click", function(e) {

	    // storing current input string and its last character in variables - used later
	    var currentString = input.innerHTML;
	    var lastChar = currentString[currentString.length - 1];

	    // if last character entered is an operator, replace it with the currently pressed one
	    if (lastChar === "+" || lastChar === "-" || lastChar === "×" || lastChar === "÷") {
	      var newString = currentString.substring(0, currentString.length - 1) + e.target.innerHTML;
	      input.innerHTML = newString;
	    } else if (currentString.length == 0) {
	      // if first key pressed is an opearator, don't do anything
	      console.log("enter a number first");
	    } else {
	      // else just add the operator pressed to the input
	      input.innerHTML += e.target.innerHTML;
	    }

	  });
	}

// on click of 'equal' button
	result.addEventListener("click", function() {

	  // this is the string that we will be processing eg. -10+26+33-56*34/23
	  var inputString = input.innerHTML;

	  // forming an array of numbers. eg for above string it will be: numbers = ["10", "26", "33", "56", "34", "23"]
	  var numbers = inputString.split(/\+|\-|\×|\÷/g);

	  // forming an array of operators. for above string it will be: operators = ["+", "+", "-", "*", "/"]
	  // first we replace all the numbers and dot with empty string and then split
	  var operators = inputString.replace(/[0-9]|\./g, "").split("");

	  console.log(inputString);
	  console.log(operators);
	  console.log(numbers);
	  console.log("----------------------------");

  // now we are looping through the array and doing one operation at a time.
  // first divide, then multiply, then subtraction and then addition
  // as we move we are alterning the original numbers and operators array
  // the final element remaining in the array will be the output

		  var divide = operators.indexOf("÷");
		  while (divide != -1) {
		    numbers.splice(divide, 2, numbers[divide] / numbers[divide + 1]);
		    operators.splice(divide, 1);
		    divide = operators.indexOf("÷");
		  }

		  var multiply = operators.indexOf("×");
		  while (multiply != -1) {
		    numbers.splice(multiply, 2, numbers[multiply] * numbers[multiply + 1]);
		    operators.splice(multiply, 1);
		    multiply = operators.indexOf("×");
		  }

		  var subtract = operators.indexOf("-");
		  while (subtract != -1) {
		    numbers.splice(subtract, 2, numbers[subtract] - numbers[subtract + 1]);
		    operators.splice(subtract, 1);
		    subtract = operators.indexOf("-");
		  }

		  var add = operators.indexOf("+");
		  while (add != -1) {
		    // using parseFloat is necessary, otherwise it will result in string concatenation :)
		    numbers.splice(add, 2, parseFloat(numbers[add]) + parseFloat(numbers[add + 1]));
		    operators.splice(add, 1);
		    add = operators.indexOf("+");
		  }

		  input.innerHTML = numbers[0]; // displaying the output

		  resultDisplayed = true; // turning flag if result is displayed
		});

		// clearing the input on press of clear
		clear.addEventListener("click", function() {
		  input.innerHTML = "";
		})
	</script>
</body>
</html>