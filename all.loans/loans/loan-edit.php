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
</head>
<?php
	
?>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<div class="content-header">
		      <div class="container mt-4">
		        <div class="row mb-2 mt-5">
		          <div class="col-sm-6">
		            <h4 class="m-0">Loan Application</h4>
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
      			<div class="container mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 pb-2 d-flex justify-content-between border-bottom">
  							<div></div>
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
											<?php
												if (isset($_GET['applicant_id'])) {
													$branch_id 		= $BRANCHID;
													$applicant_id 	= $_GET['applicant_id'];
													$parent_id 		= $_SESSION['parent_id'];
													$query = $connect->prepare("SELECT * FROM borrowers_details WHERE id = ? AND branch_id = ? AND parent_id = ?");
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
														<img src="fileuploads/<?php echo $borrower_photo ?>" id="output_image" class="shadow-sm img-fluid img-responsive" alt="pic" style="width: 120px; height: 120px; border-radius: 50%;">
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
													<label for="form">NRC / PASSPORT</label>
													<div class="input-group mb-1">
														<span class="input-group-text"><i class="bi bi-file-person"></i></span>
														<input type="text" name="borrower_ID" id="borrower_ID" class="form-control" readonly value="<?php echo $borrower_ID?>">
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
													<h3 class="mb-3 text-danger">Charges</h3>
												</div>
												<div class="col-md-4 mb-3">
													<label>Loan Type</label>
													<select class="form-control" id="loan_id" name="loan_id" style="width: 100%" required>
														<option value="">Select Type</option>
													</select>
                                                	<em>To add loan types <a href="loans/loan_settings"> Click Here</a></em>
												</div>
												
												<div class="col-md-4 mb-3">
													<label>Principle Amount</label>
													<div class="input-group">
														<div class="input-group-append">
													    	<span class="input-group-text"><?php echo getCurrency($connect, $parent_id) ?></span>
													  	</div>
														<input type="hidden" name="currency" id="currency" value="<?php echo getCurrency($connect, $parent_id) ?>">
								                      	<input type="number" step="any" class="form-control" name="principle_amount" id="principle_amount" min="1" required>
								                    </div>
												</div>
												<div class="col-md-4 mb-3">
													<label>Interest Method</label>
													<select class="form-control" name="loan_interest_method" id="loan_interest_method" required>
														<option value="" selected disabled>Select</option>
							                            <option value="Flat Rate"> Flat Rate</option>
							                           <!--  <option value="Reducing Rate">Reducing Balance</option>
							                            <option value="Compound Rate">Compound Interest</option> -->
							                        </select>
												</div>
												
												<div class="col-md-6 mb-3" id="formEnter">
													<label> Interest Rate</label>
													<div class="input-group">
														<input type="hidden" name="symbol" id="symbol">
								                      	<input type="number" step="any" class="form-control" name="loan_interest" id="loan_interest" min="0" required value="1">
								                      	<div class="input-group-append">
													    	<span class="input-group-text" id="selectedSymbol">%</span>
													  	</div>
													  	<select class="form-control" name="loan_interest_period" id="loan_interest_period" required onchange="testInt()" required>
													  		<option selected disabled>Select One</option>
													  		<option value="perWeek">Per Week</option>
								                            <option value="perMonth">Per Month</option>
								                            <option value="perYear">Per Year</option>
								                        </select>
								                    </div>
												</div>
												
												<div class="col-md-6 mb-3" id="formEnter">
													<label>Loan Duration - Weeks</label>
													
								                    <input type="text" step="any" class="form-control" name="weeks" id="weeks" placeholder="Weeks" onkeyup="getWeeks(this.value)">
								                </div>
								                <div class="col-md-6 mb-3" id="formEnter">
								                	<label>Loan Duration - Months</label>
							                      	<input type="text" step="any" class="form-control" name="months" id="months" placeholder="Months" onkeyup="getMonths(this.value)">
							                    </div>
							                    <div class="col-md-6 mb-3" id="formEnter">
							                    	<label>Loan Duration - Years</label>
							                      	<input type="text" step="any" class="form-control" name="years" id="years" placeholder="Years" onkeyup="getYears(this.value)">
							                    </div>

						                      	<!-- <div class="input-group-append">
											    	<span class="input-group-text">Period</span>
											  	</div>
											  	<select class="form-control" name="loan__period" id="loan__period" required>
											  		<option value=""></option>
											  		<option value="Week">Week(s)</option>
						                            <option value="Month">Month(s)</option>
						                            <option value="Year">Year(s)</option>
						                        </select> -->
						                        <script>
						                        	var weeks 	= document.getElementById('weeks').value;
						                        	var months 	= document.getElementById('months').value;
						                        	var years 	= document.getElementById('years').value;
						                        	function getWeeks(week){
						                        		if(week !== ""){
						                        			myweek = parseInt(week);
						                        			myMonth = myweek / 4;
						                        			myYear = myweek / 52;
						                        			document.getElementById('months').value = myMonth;
						                        			document.getElementById('years').value = myYear;
						                        		}
						                        	}

						                        	function getMonths(month){
						                        		if(month !== ""){
						                        			myMonth = parseInt(month);
						                        			myWeek = myMonth * 4;
						                        			myYear = myMonth / 12;
						                        			document.getElementById('weeks').value = myWeek;
						                        			document.getElementById('years').value = myYear;
						                        		}
						                        	}

						                        	function getYears(year){
						                        		if(year !== ""){
						                        			myYear = parseInt(year);
						                        			myWeek = myYear * 52;
						                        			myYear = myYear * 12;
						                        			document.getElementById('weeks').value = myWeek;
						                        			document.getElementById('months').value = myYear;
						                        		}
						                        	}
						                        </script>
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
								                      	<input type="number" step="any" class="form-control" name="loan_processing_fee" id="loan_processing_fee" min="1">
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
							                            <option value="Weekly">Weekly</option>
							                            <option value="Monthly">Monthly</option>
							                            <option value="Lump-Sum">Lump-Sum</option>
							                        </select>
							                    </div>
							                    <div class="form-group col-md-6">
							                    	<label>Repayment From</label>
							                    	<input type="text" name="repayment_start_date" id="repayment_start_date" class="form-control" autocomplete="off" required>
							                    </div>
							                    <div class="col-md-6 mb-3">
													<label>Release Method:</label>
													<select class="form-control" name="release_method" required>
														<option value="Mobile Money">Mobile Money</option>
														<option value="Cash">Cash</option>
														<option value="Cheque">Cheque</option>
														<option value="Wire Transfer">Wire Transfer</option>
													</select>
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
										<?php
											}
										}
										?>
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
				document.getElementById('feeSymbol').innerHTML = "<?php echo getCurrency($connect, $parent_id) ?>";
				document.getElementById('loan_processing_fee').setAttribute('max', '1000000000');
				document.getElementById('symbol_fee').value = "<?php echo getCurrency($connect, $parent_id) ?>";
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
		// var interest = document.getElementById('interest');
		var loan_interest_method = document.getElementById('loan_interest_method');
		var loan_payment_options = document.getElementById('loan_payment_options');
		
		calculate.addEventListener("click", (event)=>{
			event.preventDefault();
			var symbol_fee = document.getElementById('symbol_fee').value;
			var symbol = document.getElementById('symbol').value;
        	var loan_number = document.getElementById('loan_number');
        	var weeks_ = document.getElementById('weeks').value;
			var months_ = document.getElementById('months').value;
			var years_ = document.getElementById('years').value;
        	// if(loan_number.value === ""){
         //    	errorNow("Add Loan Number");
         //    	loan_number.focus();
         //    	return false;
         //    }
			if (principle_amount.value === "") {
				errorNow("Enter borrowers requested amount");
				principle_amount.focus();
				return false;
			}

			// if (interest.value == "") {
			//     errorNow("Please Select Your Loan Interest Setting");
			//     $(this).focus();
			//     return false;
			// }

			if (loan_interest.value === "") {
				errorNow("Enter Interest value");
				loan_interest.focus();
				return false;
			}

			if (interest_per_period.value === "") {
				errorNow("Please select how borrower interest will be compounded");
				return false;
			}


			if (symbol_fee === '%') {
				symbol_fee = "<?php echo getCurrency($connect, $parent_id) ?>";
			}else{
				symbol_fee = document.getElementById('symbol_fee').value;
			}

			if (symbol === '%') {
				symbol = "<?php echo getCurrency($connect, $parent_id) ?>";
			}else{
				symbol = document.getElementById('symbol').value;
			}

			var xhr = new XMLHttpRequest();
			var url = 'loans/calculate';
			// var data = 'principle_amount=' + principle_amount.value + '&loan_interest=' + loan_interest.value + '&symbol=' + symbol + '&interest_per_period=' + interest_per_period.value + '&loan_duration=' + loan_duration.value + '&loan__period=' + loan__period.value + '&loan_processing_fee=' + loan_processing_fee.value +'&loan_payment_options='+ loan_payment_options.value + '&symbol_fee=' + symbol_fee + '&loan_interest_method='+ loan_interest_method.value+'&interest_type='+interest.value;
			var data = 'principle_amount=' + principle_amount.value + '&loan_interest=' + loan_interest.value + '&interest_per_period=' + interest_per_period.value + '&weeks='+weeks_+'&months='+months_+ '&years='+years_+ '&loan_payment_options='+ loan_payment_options.value +'&loan_interest_method='+ loan_interest_method.value;
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
					url:'loans/submitnewLoan?<?php echo time()?>',
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
	</script>
</body>
</html>