<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 
  	
	$loan_type = "";
	$parent_id = preg_replace("#[^0-9]#", "", $_SESSION['parent_id']);
	$query = $connect->prepare("SELECT * FROM loan_type WHERE parent_id = ?");
	$query->execute(array( $parent_id));
	$numRows = $query->rowCount();
	foreach ($query->fetchAll() as $row){
		$loan_type .= '<option value="'.$row['id'].'">'.$row['type_name'].'</option>';
	}
 	$option = '';
  	$query = $connect->prepare("SELECT * FROM currencies");
  	$query->execute();
  	foreach ($query->fetchAll() as $row) {
    	$option .= '<option value="'.$row['code'].'">'.$row['code'].'</option>';
  	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Loan Calculations</title>
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
			<section class="content bg-light mt-5">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 pb-2 d-flex justify-content-between border-bottom">
  							<h1 class="h4"><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?> Loan Calculator</h1>
  							<div class="form-group">
			                  	<label>Currency Set Up</label>
			                  	<select class="form-control select2" id="currency" onchange="storeCurrencyValue(this.value)" style="width: 100%">
			                    	<?php echo $option?>
			                  	</select>
  							</div>
      				</div>
      			</div>
      			<div class="container-fluid pt-3">
      				<h2 class="mb-3 text-secondary">Loan Fee Setup</h2>
      				<div class="row">

      					<div class="col-md-4">
      						<div class="card card-secondary card-outline mb-5">
      							<form action="" id="LoanFeesForm">
								
									<div class="card-header">
										<p>Loan Fees Form</p>
										<p>This is the amount you charge a borrower for processing the loan.</p>
								  	</div>
									<div class="card-body">
										<input type="hidden" name="id">
										<div class="form-group">
											<label class="control-label">Fee Name</label>
											<input type="text" name="fees_name"  id="fees_name"  class="form-control" required="required">
											<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
											<input type="hidden" name="branch_id" id="branch_id" value="<?php echo($BRANCHID)?>">
										</div>
										<div class="form-group">
											<input type="radio" id="percentage_fee" name="loan_fees_radio" onclick="myFunction(this.value)" value="Percentage">
											<label for="percentage_fee">Fee should be Percentage Based </label><br>
											<input type="radio" id="fixed_amount_fee" name="loan_fees_radio" onclick="myFunction(this.value)" value=" Amount">
											<label for="fixed_amount_fee">Fee should be Fixed Amount Based </span></label><br>
										</div>
										<script>
											function myFunction(fees) {
												document.getElementById('formEnter').style.display = 'block';
												document.getElementById('label').innerHTML = "Enter "+ fees + ' You wish to charge';
												document.getElementById("resultspan").innerHTML = fees;
												document.getElementById("fee_choice").value = fees;
												if (fees === 'Percentage') {
													document.getElementById('SelectedValue').innerHTML = '%';
													document.getElementById('loan_fees').setAttribute('max', '100');
													document.getElementById('symbol').value = '%';
												}else{
													document.getElementById('SelectedValue').innerHTML = localStorage['currency_main'];
													document.getElementById('loan_fees').setAttribute('max', '1000000000');
													document.getElementById('symbol').value = localStorage['currency_main'];
												}
											}
										</script>
										<div class="form-group" style="display: none;" id="formEnter">
											<label id="label"></label>
											<div class="input-group">
												<input type="hidden" name="symbol" id="symbol">
						                      	<input type="text" name="fee_choice" id="fee_choice" class="form-control" value="" readonly="readonly">
						                      	<input type="number" step="any" class="form-control text-right" name="loan_fees" id="loan_fees" min="0">
						                      	<div class="input-group-append">
											    	<span class="input-group-text" id="SelectedValue"></span>
											  	</div>
						                    </div>
						                    <em>This is the <span id="resultspan"></span> which will be deducted from the total borrowed amount.</em>
										</div>
									</div>
									<div class="card-footer">
										<div class="row">
											<div class="col-md-12">
												<button class="btn btn-sm btn-primary col-sm-3 offset-md-3" id="addFeebtn" type="submit" onclick="saveLoanFees()"> Save</button>
												<!-- <button class="btn btn-sm btn-default col-sm-3" type="button" data-target="modal" onclick="_reset()"> Cancel</button> -->
											</div>
										</div>
									</div>
									
								</form>
							</div>
      					</div>
      					<div class="col-md-8">
      						     						
      						<div class="card card-secondary card-outline mb-5">
      							<div class="card-body box-profile">
      								<!-- <div id="fetchLoanTypes"></div> -->
      								<div class="table table-responsive">
      									<table id="loanTypes" class="cell-border" style="width:100%">
									        <thead>
									            <tr>
									            	<th>#</th>
									                <th>Loan Name</th>
									                <th>Amount / Percentage</th>
									                <?php if($_SESSION['user_role'] == 'Admin'):?>
									                <th>Actions</th>
									                <?php else:?>
									                <?php endif;?>
									            </tr>
									        </thead>
									        <tbody>
									        	<?php
									        		$parent_id = preg_replace("#[^0-9]#", "", $_SESSION['parent_id']);
													$query = $connect->prepare("SELECT * FROM `loan_fees` WHERE  parent_id = ?");
													$query->execute(array( $parent_id));
													$numRows = $query->rowCount();
													$i = 1;
													if ($numRows > 0 ) {
														$loanData = array();
														$i = 1;
														foreach ($query->fetchAll() as $row) {?>
															<tr>
																<td><?php echo $i++?></td>
																<td><b><?php echo $row['loan_fees_name']?></b></td>
																<td>
																	<?php echo ucfirst($row['loan_fees'])?> <?php echo $row['symbol']?>
																</td>
																<td>
																	<a href="" class="editLoanFees text-primary" data-id="<?php echo $row['id']?>"><i class="bi bi-pencil-square"></i></a>
																	<a href="" class="deleteLoanFees text-danger" data-id="<?php echo $row['id']?>"><i class="bi bi-trash"></i></a>
																</td>
															</tr>
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
      			<div class="container-fluid pt-3">
      				<h2 class="mb-3 text-secondary">Loan Types</h2>
      				<div class="row">
      					<div class="col-md-4">
      						<div class="card card-warning card-outline mb-5">
      							<form action="" id="manage-loan-type">
								
									<div class="card-header">
										Loan Type Form
								  	</div>
									<div class="card-body">
										<input type="hidden" name="id">
										<div class="form-group">
											<label class="control-label">Type</label>
											<input type="text" name="type_name"  id="type_name"  class="form-control" required="required">
											<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
											<input type="hidden" name="id" id="id">
										</div>
										<div class="form-group">
											<label class="control-label">Description</label>
											<textarea name="description" id="description" cols="30" rows="6" class="form-control" placeholder="Description" required="required"></textarea>
										</div>
									</div>
									<div class="card-footer">
										<div class="row">
											<div class="col-md-12">
												<button class="btn btn-sm btn-primary col-sm-3 offset-md-3" id="addbtn" type="submit" onclick="saveLoanType()"> Save</button>
												<button class="btn btn-sm btn-default col-sm-3" type="button" data-target="modal" onclick="_reset()"> Cancel</button>
											</div>
										</div>
									</div>
									
								</form>
							</div>
      					</div>
      					<div class="col-md-8">
      						     						
      						<div class="card card-warning card-outline mb-5">
      							<div class="card-body box-profile">
      								<!-- <div id="fetchLoanTypes"></div> -->
      								<div class="table table-responsive">
      									<table id="loanTypes" class="cell-border" style="width:100%">
									        <thead>
									            <tr>
									            	<th>#</th>
									                <th>Loan Type</th>
									                <?php if($_SESSION['user_role'] == 'Admin'):?>
									                <th>Actions</th>
									                <?php else:?>
									                <?php endif;?>
									                <th>Description</th>
									            </tr>
									        </thead>
									        <tbody>
									        	<?php
									        		$parent_id = preg_replace("#[^0-9]#", "", $_SESSION['parent_id']);
													$query = $connect->prepare("SELECT * FROM loan_type WHERE parent_id = ?");
													$query->execute(array( $parent_id));
													$numRows = $query->rowCount();
													$i = 1;
													if ($numRows > 0 ) {
														$loanData = array();
														$i = 1;
														foreach ($query->fetchAll() as $row) {?>
															<tr>
																<td><?php echo $i++?></td>
																<td><b><?php echo $row['type_name']?></b></td>
																<td>
																	<?php echo ucfirst($row['description'])?>
																</td>
																<td>
																	<a href="" class="editLoanType text-primary" data-id="<?php echo $row['id']?>"><i class="bi bi-pencil-square"></i></a>
																	<a href="" class="deleteLoanType text-danger" data-id="<?php echo $row['id']?>"><i class="bi bi-trash"></i></a>
																</td>
															</tr>
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
      			<div class="container-fluid pt-3">
      				<h2 class="mb-3 text-secondary">Loan Interest and Period</h2>
      				<div class="row">
      					<div class="col-md-4">
      						<div class="card card-primary card-outline mb-5">
      							<form id="loanSetupForm" method="post">
	      							<div class="card-header">
							   			<h4>Plan's Form</h4>
								  	</div>
									<div class="card-body">
										<div class="form-group">
											<label>Loan Type</label>
											<select class="form-control" name="loan_type" id="loan_type" style="width: 100%" placehoder="Select Loan Type">
												<option value=""></option>
												<?php echo $loan_type?>
											</select>
										</div>
										<div class="form-group">
											<label class="control-label">Plan (months)</label>
											<input type="number" name="months" id="months" class="form-control text-right">
											<em class="text-secondary">The months a borrower should take to repay the loan.</em>
										</div>
										
										<div class="form-group">
											<label class="control-label">Interest</label>
											<div class="input-group">
											  	<input type="number" step="any" min="0" max="100" class="form-control text-right" name="interest_percentage" id="interest_percentage" aria-label="Interest">
											  	<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
											  	<input type="hidden" name="id" id="id">
											  	<div class="input-group-append">
											    	<span class="input-group-text">%</span>
											  	</div>
											</div>
											<em class="text-secondary">Interest rate per month</em>
										</div>
										<div class="form-group">
											<label class="control-label">Monthly Over due's Penalty</label>
											<div class="input-group">
											  	<input type="number" step="any" min="0" max="100" class="form-control text-right" aria-label="Penalty percentage" name="penalty_rate" id="penalty_rate">
											  	<div class="input-group-append">
											    	<span class="input-group-text">%</span>
											  	</div>
											</div>
											<em class="text-secondary">Penalty borrower pays when the loan overdues</em>
										</div>	
									</div>	
									<div class="card-footer">
										<div class="row">
											<div class="col-md-12">
												<button class="btn btn-sm btn-primary col-sm-3 offset-md-3" id="addbtn" onclick="saveLoanSetup()"> Save</button>
												<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="_reset()"> Cancel</button>
											</div>
										</div>
									</div>
								</form>
      						</div>
      					</div>
      					<div class="col-md-8">
      						<div class="card card-primary card-outline mb-5">
      							<div class="card-body box-profile">
      								<div class="table table-responsive">
	      								<table class="cell-border" style="width:100%" id="loanCalc">
											<thead class="">
												<tr>
													<th>#</th>
													<th>Loan Settings</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php 
													$x = 1;
													$query = $connect->prepare("SELECT * FROM loan_plans WHERE  parent_id = ?");
													$query->execute(array( $_SESSION['parent_id']));
													foreach ($query->fetchAll() as $row) {
														$months = $row['months'];
														// $months = $months / 12;
														// if($months < 1){
														// 	$months = $row['months']. " months";
														// }else{
														// 	$m = explode(".", $months);
														// 	$months = $m[0] . " yrs.";
														// 	if(isset($m[1])){
														// 		$months .= " and ".number_format(12 * ($m[1] %100 ), 0)."month/s";
														// 	}
														// }
														// for($i=1; $i<=$months; $i++){

														//     $months = floor((int) $months/12) . ' Yrs, ' .$i%12 . ' Months';

														//  }
														?>
														<tr>
															<td ><?php echo $x++ ?></td>
															<td>
																<p>Loan Type: <?php echo loanType($connect, $row['loan_type'], $_SESSION['parent_id'])?></p>
																<p><span class="text-secondary">Period:   </span><?php echo $months ?> Months</p>
																<p><span class="text-secondary">Interest: </span><?php echo $row['interest_percentage'] ?>% </p> 
																<p><span class="text-secondary">Penalty:  </span><?php echo $row['penalty_rate'] ?>%</p>
															</td>
															
															<td>
																<a href="" class="editLoanSets text-primary" data-id="<?php echo $row['id']?>"><i class="bi bi-pencil-square"></i></a>
																	<a href="" class="deleteLoanCalc text-danger" data-id="<?php echo $row['id']?>"><i class="bi bi-trash"></i></a>
															</td>
														</tr>
												<?php	}
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
		    $('#loanTypes').DataTable();
		    // select
		    $('.select2').select2();
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
	    saveLoanFees = () => {
	    	event.preventDefault();
	    	var fees_name = document.getElementById('fees_name');
	    	if (fees_name.value === "") {
	    		errorNow("Please enter Name of the Fees");
	    		fees_name.focus();
	    		return false;
	    	}
	    	var loan_fees = document.getElementById('loan_fees');
	    	if ($('input[name=loan_fees_radio]:checked').length < 1) {
			    errorNow("Please Select Your Loan Fees Setting");
			    return false;
			}
	    	
	    	var xhr = new XMLHttpRequest();
			var url = 'loans/actionsLoan';
			var LoanFeesForm = document.getElementById('LoanFeesForm');
			xhr.open("POST", url, true);
			var data = new FormData(LoanFeesForm);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					if (xhr.responseText === 'done') {
						successNow(fees_name.value + ' added to the database');
						$("#LoanFeesForm")[0].reset();
						document.getElementById("addFeebtn").innerHTML = 'Submit';
						// location.reload();
						// $("#modalLoan").modal("show");

					}else if(xhr.responseText === 'Updated'){
						successNow(fee_name.value + ' Updated');

					}else{

						errorNow(xhr.responseText);
						document.getElementById("addFeebtn").innerHTML = 'Submit';
						return false;
					}
					document.getElementById("addFeebtn").innerHTML = 'Submit';
					// load();
				}
				
			}
			xhr.send(data);
			document.getElementById("addFeebtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
	    	
	    }

		saveLoanType = function(){
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'loans/addLoanType';
			var branchForm = document.getElementById('manage-loan-type');
			xhr.open("POST", url, true);
			var type_name = document.getElementById('type_name').value;
			if (type_name == "") {
				errorNow("Loan type is required");
				return false;
			}
			var data = new FormData(branchForm);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					if (xhr.responseText === 'done') {
						successNow(type_name + ' added to the database');
						$("#manage-loan-type")[0].reset();
						document.getElementById("addbtn").innerHTML = 'Submit';
						// location.reload();
						// $("#modalLoan").modal("show");

					}else if(xhr.responseText === 'Updated'){
						successNow(type_name + ' Updated');

					}else{

						errorNow(xhr.responseText);
						document.getElementById("addbtn").innerHTML = 'Submit';
						return false;
					}
					document.getElementById("addbtn").innerHTML = 'Submit';
					// load();
				}
				
			}
			xhr.send(data);
			document.getElementById("addbtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}
		function _reset(){
			$('[name="id"]').val('');
			$('#manage-loan-type')[0].reset();
			$("#modalLoan").modal("hide");
			
			location.reload();
		}
		$(document).ready( function () {
		
		    $(document).on("click", ".editLoanType", function(e){
		    	e.preventDefault();
		    	var id = $(this).data('id');
		    	$("#modalLoan").modal("show");
		    	$.ajax({
		    		url: 'loans/editLoanType',
		    		method:'post',
		    		data:'editor_id='+id+'&loggedinID=<?php echo $_SESSION['parent_id']?>',
		    		dataType:"JSON",
		    		success:function(data){
		    			$("#type_name").val(data.type_name);
		    			$("#description").val(data.description);
		    			$("#id").val(data.id);
		    		}
		    	})
		    });
		    $(document).on("click", ".deleteLoanType", function(e){
		    	e.preventDefault();
		    	var id = $(this).data('id');
		    	if(confirm("Confirm deleting loan type")){
			    	$.ajax({
			    		url: 'loans/editLoanType',
			    		method:'post',
			    		data:'delete_id='+id+'&loggedParentId=<?php echo $_SESSION['parent_id']?>',
			    		
			    		success:function(data){
			    			if(data === 'done'){
			    				successNow("Loan Type Deleted");
			    				location.reload();
			    			}else{
			    				errorNow("Error Deleting Loan");
			    			}
			    		}
			    	})
			    }else{
			    	return false;
			    }
		    })
		});

	</script>
	<script>
		$(document).ready( function () {
		    $('#loanCalc').DataTable();
		    // select
		    $('.select2').select2();
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

		saveLoanSetup = function(){
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'loans/actionsLoan';
			var loanSetupForm = document.getElementById('loanSetupForm');
			xhr.open("POST", url, true);
			var loan_type = document.getElementById('loan_type');
			if (loan_type.value === "") {
				errorNow("Please Select Loan");
				loan_type.focus();
				return false;
			}

			var months = document.getElementById('months');
			if (months.value === "") {
				errorNow("Please Add months");
				months.focus();
				return false;
			}
			var interest_percentage = document.getElementById('interest_percentage');
			if (interest_percentage.value === "") {
				interest_percentage.focus();
				errorNow("Please Add percentage");
				return false;
			}
			var data = new FormData(loanSetupForm);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					if (xhr.responseText === 'done') {
						successNow(months.value + ' Months added to the database');
						$("#loanSetupForm")[0].reset();
						document.getElementById("addbtn").innerHTML = 'Submit';
						location.reload();
						// $("#modalLoan").modal("show");

					}else if(xhr.responseText === 'Updated'){
						successNow("Loan Settings Updated");

					}else{

						errorNow(xhr.responseText);
						document.getElementById("addbtn").innerHTML = 'Submit';
						return false;
					}
					document.getElementById("addbtn").innerHTML = 'Submit';
					// load();
				}
				
			}
			xhr.send(data);
			document.getElementById("addbtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}
		function _reset(){
			$('[name="id"]').val('');
			$('#loanSetupForm')[0].reset();
			location.reload();
		}

		$(document).ready( function () {
		
		    $(document).on("click", ".editLoanSets", function(e){
		    	e.preventDefault();
		    	var id = $(this).data('id');
		    	$("#modalLoan").modal("show");
		    	$.ajax({
		    		url: 'loans/actionsLoan',
		    		method:'post',
		    		data:'editor_id='+id+'&loggedinID=<?php echo $_SESSION['parent_id']?>',
		    		dataType:"JSON",
		    		success:function(data){
		    			$("#loan_type").val(data.loan_type);
		    			$("#months").val(data.months);
		    			$("#interest_percentage").val(data.interest_percentage);
		    			$("#penalty_rate").val(data.penalty_rate);
		    			$("#id").val(data.id);
		    		}
		    	})
		    });
		    $(document).on("click", ".deleteLoanCalc", function(e){
		    	e.preventDefault();
		    	var id = $(this).data('id');
		    	if(confirm("Confirm deleting loan set up")){
			    	$.ajax({
			    		url: 'loans/actionsLoan',
			    		method:'post',
			    		data:'delete_id='+id+'&loggedParentId=<?php echo $_SESSION['parent_id']?>',
			    		
			    		success:function(data){
			    			if(data === 'done'){
			    				successNow("Loan Type Deleted");
			    				location.reload();
			    			}else{
			    				errorNow("Error Deleting Loan");
			    			}
			    		}
			    	})
			    }else{
			    	return false;
			    }
		    })
		});

		function storeCurrencyValue(currency){
			
			var lg = localStorage['currency_main'] = currency;
			// document.getElementById('SelectedCurrency').innerHTML = currency;
			document.getElementById('SelectedValue').innerHTML = currency;

		}
		document.addEventListener('DOMContentLoaded', function (){
			var input = document.getElementById('currency');
		    if (localStorage['currency_main']) { 
		        input.value = localStorage['currency_main'];
		        // document.getElementById('SelectedCurrency').innerHTML = localStorage['currency_main'];
		        document.getElementById('SelectedValue').innerHTML = localStorage['currency_main'];
		    }
		})
	</script>
</body>
</html>