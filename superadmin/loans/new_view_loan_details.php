<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php");  
	
	if (isset($_GET['borrower_id'])) {
		$borrower_id = preg_replace("#[^0-9]#", "/", $_GET['borrower_id']);
		//check if id is found.
		$query = $connect->prepare("SELECT * FROM borrowers WHERE borrower_ID = ?");
		$query->execute(array($borrower_id));
		$count = $query->rowCount();
		if ($count > 0) {
			$row = $query->fetch();
			$user_id = $row['id'];
			$working_status = preg_replace("#[^a-zA-Z]#", " ", ucwords($row['borrower_working_status']));
			$borrower_working_status = $row['borrower_working_status'];
			$borrower_firstname = $row['borrower_firstname'];
			$borrower_lastname = $row['borrower_lastname'];
			$borrower_email = $row['borrower_email'];
			$borrower_phone = $row['borrower_phone'];
			$borrower_dateofbirth    = $row['borrower_dateofbirth'];
			$borrower_address = $row['borrower_address'];
			$borrower_city = $row['borrower_city'];
			$borrower_country = $row['borrower_country'];
			$borrower_business = $row['borrower_business'];
			$borrower_gender  = $row['borrower_gender'];
			$date_added 	   = $row['date_added'];
			$borrower_borrower_files = $row['borrower_borrower_files'];
			$borrower_borrower_photo = $row['borrower_borrower_photo'];
			$loan_officers = $row['loan_officers'];
		}else{
			header("location:./");
		}
		
		$country = '';
		$query = $connect->prepare("SELECT * FROM currencies");
		$query->execute();
		foreach ($query->fetchAll() as $row) {
		    $country .= '<option value="'.$row['id'].'">'.$row['country'].'</option>';
		}

		$option = '';
		$query = $connect->prepare("SELECT * FROM admins");
		$query->execute();
		foreach ($query->fetchAll() as $row) {
		    $option .= '<option value="'.$row['id'].'">'.$row['firstname'].' '.$row['lastname'].'</option>';
		}

		$branch_options = "";
		$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ? ");
		$sql->execute(array($_SESSION['parent_id']));
		foreach ($sql->fetchAll() as $row) {
			$branch_options .= '<option value="'.$row['id'].'">'.$row['branch_name'].'</option>';
		}

		$branch_options = "";
		$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
		$sql->execute(array($_SESSION['parent_id']));
		foreach ($sql->fetchAll() as $row) {
			$branch_options .= '<option value="'.$row['id'].'">'.$row['branch_name'].'</option>';
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>New View Loans</title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
	<style>
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

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content bg-light">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 border-bottom">
      						<h1 class="h3"><?php echo getBorrowerFullNamesByCardId($connect, $_GET['borrower_id'])?></h1>
      					</div>
      				</div>
      			</div>
      			<!-- borrower form -->
      			<div class="container-fluid">
      				<div class="row">
      					<!-- <div class="col-md-12 mb-5 mt-5"> -->
  						<div class="col-md-4">
  							<div class="card card-primary card-outline mb-5">
          						<div class="card-body box-profile">
					                <div class="text-center">
					                  	<img class="profile-user-img img-fluid img-circle" src="<?php echo borrowerPicture($connect, $user_id)?>" alt="User profile picture" style="width: 100px; height: 100px;">
					                </div>

					                <h3 class="profile-username text-center"><?php echo ucwords( getBorrowerFullNames($connect, $user_id))?></h3>

					                <p class="text-muted text-center"><?php echo $working_status?></p>

					                <ul class="list-group list-group-unbordered mb-3">
					                	<li class="list-group-item">
					                    	<b>C_ID: </b> <a href="<?php echo preg_replace("#[^0-9]#", "-", $borrower_id) ?>" class="float-right"><?php echo $borrower_id;?></a>
					                  	</li>
					                  	<li class="list-group-item">
					                    	<b>DOB: </b> <a href="<?php echo preg_replace("#[^0-9]#", "-", $borrower_id) ?>" class="float-right"><?php echo date("d/m/Y", strtotime($borrower_dateofbirth))?>,  <?php echo userAge($borrower_dateofbirth)?></a>
					                  	</li>
					                  	<li class="list-group-item">
					                    	<b>Email:</b> <a href="<?php echo preg_replace("#[^0-9]#", "-", $borrower_id) ?>" class="float-right"><?php echo $borrower_email?></a>
					                  	</li>
					                  	<li class="list-group-item">
					                    	<b>Phone:</b> <a href="<?php echo preg_replace("#[^0-9]#", "-", $borrower_id) ?>" class="float-right"><?php echo $borrower_phone?></a>
					                  	</li>
					                </ul>

					                <a href="loans/add_loan?borrower_id=<?php echo $_GET['borrower_id']?>" class="btn btn-primary"><b>Issue New Loan</b> </a>
					            </div>
        					</div>
  						</div>
  						<div class="col-md-8">
  							<div class="card card-primary card-outline mb-5">
  								<div class="card-body">
  									<ul class="list-group list-group-unbordered mb-3">
					                  	<li class="list-group-item">
					                    	<b>Address:</b> 
					                    	<a class="float-right"><?php echo $borrower_address?></a>
					                    	<br>
					                    	<b>City:</b> 
					                    	<a class="float-right"><?php echo $borrower_city?></a>
					                    	<br>
					                    	<b>Country:</b> 
					                    	<a class="float-right"><?php echo getCountryName($connect, $borrower_country) ?></a>
					                  	</li>
					                  	<?php echo getLoanOfficer($connect, $user_id)?>
					                  	<li class="list-group-item">
					                  		<b>Business:</b>
					                  		<a class="float-right"><?php echo $borrower_business?></a>
					                  	</li>
					                  	<li class="list-group-item">
					                  		<b>Added date:</b>
					                  		<a class="float-right"><?php echo $date_added ?></a>
					                  	</li>
					                  	
					                  	<?php echo borrowerFiles($connect, $user_id)?>
					                </ul>
					                <a href="borrowers/edit_borrower_details?borrower_id=<?php echo $borrower_id?>" class="btn btn-outline-primary btn-block" ><b>Edit Info</b></a>
  								</div>
  							</div>
  						</div>
      					<!-- </div> -->
      					<div class="col-md-12 mt-5 mb-5">
      						<div class="card card-primary">
      							<div class="card-header">
      								<h4 class="card-title"><?php echo getBorrowerFullNamesByCardId($connect, $_GET['borrower_id'])?> Loans</h4>
      							</div>
      							<div class="card-body">
		      						<div class="table table-responsive">
			      						<table id="loansTable" class="cell-border table table-sm" style="width:100%">
											<thead>
												<th>Loan#</th>
												<th>From</th>
												<th>Maturity</th>
												<th>Principal</th>
												<th>Interest Rate</th>
												<th>Interest</th>
												<th>Processing Fee</th>
												<th>Due</th>
												<th>Paid</th>
												<th>Balance</th>
												<th>Status</th>
												<th>Actions</th>
											</thead>
											<tbody>
												
											
		      								<?php
		      									// 
		      									$query = $connect->prepare("SELECT * FROM loans WHERE branch_id = ? AND parent_id = ? AND borrower_id = ? AND loan_number = ? ");
												$query->execute(array($BRANCHID,  $_SESSION['parent_id'], $_GET['borrower_id'], $_GET['loan_id']));
												$results = $query->fetchAll();
												if ($query->rowCount() > 0) {
													$this_loan_id = $this_loan_number = "";
													foreach ($results as $row) {
														extract($row);
														
														$this_loan_id = $id;
														$this_loan_number = $loan_number;
														$sql = $connect->prepare("SELECT *, SUM(amount) AS total_paid FROM `loan_payments` WHERE loan_id = ? AND loan_number = ? AND borrower_id = ? ");
														$sql->execute(array($this_loan_id, $this_loan_number, $borrower_id));
														if ($sql->rowCount() > 0) {
															$rows = $sql->fetch();
															if ($rows) {
																extract($rows);
																$paid = $total_paid;
															}
														}else{
															$paid = 0.00;
														}
														// loanType($connect, $loan_id, $parent_id);
														$l_status = preg_replace("#[^a-zA-Z]#", " ", $loan_status);
														if($l_status == 'Released') {
															$status = '<div class="text-success">'.$l_status.'</div>';
														}elseif ($l_status == 'Rejected') {
															$status = '<div class="text-danger">'.$l_status.'</div>';
														}elseif ($l_status == 'Completed') {
															$status = '<div class="text-secondary">'.$l_status.'</div>';
														}else{
															$status = "Pending Approval";
														}
													?>
													<tr>
														<td>
															<?php echo $this_loan_number ?>
														</td>
														<td>
															<?php echo date("d/m/Y", strtotime($release_date)); ?>	
														</td>
														<td> 
															<?php
															echo date("d/m/Y", strtotime("+".$loan_duration." ".$loan__period."", strtotime($release_date)));
															?>
														</td>
														<td><small><?php echo $currency ?></small> <?php echo number_format($principle_amount, 2)?></td>
														<td><?php echo $loan_interest ?>% </td>
														<td><small><?php echo $currency ?></small> <strong><?php echo number_format($total_interest_amount, 2) ?></strong></td>
														<td><small><?php echo $currency ?></small> <?php echo number_format($loan_processing_fee, 2) ?></td>
														<td><small><?php echo $currency ?></small> <strong><?php echo number_format($total_payable_amount, 2) ?></strong></td>
														<td><small><?php echo $currency ?></small> <?php echo $paid?></td>
														<td><small><?php echo $currency ?></small> <strong><?php echo number_format($total_payable_amount - $paid, 2) ?></strong></td>
														<td>
															<?php echo strtoupper($status)?>
														</td>
														<td>
															<a href="<?php echo $this_loan_id?>" data-loan_id="<?php echo $this_loan_id?>" data-borrower_id="<?php echo $_GET['borrower_id']?>" class="btn btn-outline-primary btn-xs takeAction" id="<?php echo $this_loan_id?>">Action <i class="bi bi-subtract"></i></a>		
														</td>
													</tr>
												<?php
													}
												}
		      								?>
		      								</tbody>
		      								<tfoot>
												<th>Loan#</th>
												<th>From</th>
												<th>Maturity</th>
												<th>Principal</th>
												<th>Interest Rate</th>
												<th>Interest</th>
												<th>Processing Fee</th>
												<th>Due</th>
												<th>Paid</th>
												<th>Balance</th>
												<th>Status</th>
												<th>Actions</th>
											</tfoot>
										</table>
									 </div>
								</div>
      						</div>
      					</div>
      					<!-- Tabs for more information -->
      					<div class="col-12 col-sm-12">
				            <div class="card card-primary card-tabs">
				              <div class="card-header p-0 pt-1">
				                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
				                  <li class="pt-2 px-3"><h3 class="card-title">Loan #: <?php echo $this_loan_number ?></h3></li>
				                  <li class="nav-item">
				                    <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Repayments</a>
				                  </li>
				                  <li class="nav-item">
				                    <a class="nav-link getLoanTerms" data-loan_number="<?php echo $this_loan_number?>" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Loan Terms</a>
				                  </li>
				                  <li class="nav-item">
				                    <a class="nav-link" id="custom-tabs-two-messages-tab" data-toggle="pill" href="#custom-tabs-two-messages" role="tab" aria-controls="custom-tabs-two-messages" aria-selected="false">Loan Schedule</a>
				                  </li>
				                  <li class="nav-item">
				                    <a class="nav-link" id="custom-tabs-two-settings-tab" data-toggle="pill" href="#custom-tabs-two-settings" role="tab" aria-controls="custom-tabs-two-settings" aria-selected="false">Loan Collateral</a>
				                  </li>
				                </ul>
				              </div>
				              <div class="card-body">
				                <div class="tab-content" id="custom-tabs-two-tabContent">
				                  	<div class="tab-pane fade show active" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
				                     	<button class="btn btn-outline-primary mb-5" data-toggle="modal" data-target="#paymentModal" type="button" data-borrower_id="<?php echo $_GET['borrower_id']?>">Add Payment</button>
				                     	<div class="table table-responsive">
		                     				<table class="cell-border table table-sm" id="paymentsTable" width="100%">
		                     					<thead>
		                     						<th>Collection Date</th>
		                     						<th>Collected By</th>
		                     						<th>Payment Method</th>
		                     						<th>Amount</th>
		                     						<th>Actions</th>
		                     						<th>View Receipt</th>
		                     					</thead>
		                     					<tbody>
				                     			<?php
				                     				$sql = $connect->prepare("SELECT * FROM `loan_payments` WHERE loan_id = ? AND loan_number = ? AND borrower_id = ? ");
				                     				$sql->execute(array($this_loan_id, $this_loan_number, $borrower_id));
				                     				if ($sql->rowCount() > 0) {

				                     					foreach ($sql->fetchAll() as $rows) {
				                     						extract($rows);
				                     						?>
				                     						<tr>
					                     						<td><?php echo $paid_date ?></td>
					                     						<td><?php echo getStaffMemberNames($connect, $collected_by, $parent_id)?></td>
					                     						<td><?php echo $payment_method ?></td>
					                     						<td><small><?php echo $currency ?></small> <?php echo number_format($amount, 2)?></td>
					                     						<td>
					                     							<a href="<?php echo $id?>" class="editPayment" data-id="<?php echo $id?>"><i class="bi bi-pencil-square"></i></a>
					                     							<a href="<?php echo $id?>" class="deletePayment" data-id="<?php echo $id?>"><i class="bi bi-trash"></i></a>
					                     						</td>
					                     						<td>
					                     							<a href="loans/payment_receitp?loan_number=<?php echo $loan_number?>&branch_id=<?php echo $BRANCHID?>&parent_id=<?php echo $parent_id?>&borrower_id=<?php echo $_GET['borrower_id']?>" class="pdf"> <i class="bi bi-printer"></i> View</a>
					                     						</td>
					                     					</tr>
				                     					<?php
				                     					}	
					                     			}else{?>
					                     				
					                     		<?php
					                     			}
				                     			?>
				                     			</tbody>
		                     					<tfoot>
		                     						<th>Collection Date</th>
		                     						<th>Collected By</th>
		                     						<th>Payment Method</th>
		                     						<th>Amount</th>
		                     						<th>Actions</th>
		                     						<th>View Receipt</th>
		                     					</tfoot>
		                     				</table>
		                     			</div>
				                  	</div>
				                  	<div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
				                     	<!-- <h4>Loan Terms</h4> -->
				                     	<div class="table table-responsive">
				                     		<table class="table table-bordered table-sm">
				                     	<?php 
				                     	// branch_id`, `parent_id`, `loan_id`, `borrower_id`, `loan_number`, `principle_amount`, `release_method`, `release_date`, `loan_interest_method`, `interest_type`, `currency`, `loan_interest`, `loan_interest_period`, `loan__period`, `processing_fee_type`, `loan_processing_fee`, `guarantor_id`, `loan_purpose`, `repayments`, `annual_p_rate`, `total_interest_amount`, `total_payable_amount`, `recurring_amount`, `loan_status`
				                     		$fees = "";
				                     		foreach ($results as $rows) {

				                     			extract($rows);
				                     			
				                     		?>
				                     		<tr>
				                     			<th>Processing Fee</th>
				                     			<td>
				                     				<?php 
				                     					if ($processing_fee_type == 'Amount') {

				                     						$fees =  $currency.' '.$loan_processing_fee;
						                     			}elseif ($processing_fee_type == 'Percentage') {
						                     				$fees =  ($principle_amount*$loan_processing_fee)/100;
						                     			}
						                     			// echo $fees;
						                     			echo $currency.' '.$loan_processing_fee;
				                     				?>
				                     			</td>
				                     		</tr>
				                     		<tr>
				                     			<th>Loan Type:</th>
				                     			<td><?php echo loanType($connect, $loan_id, $parent_id) ?></td>
				                     		</tr>
				                     		<tr>
				                     			<th>Description</th>
				                     			<td><?php echo $loan_purpose ?></td>
				                     		</tr>
				                     		<tr>
				                     			<th>Loan Status</th>
				                     			<td><?php echo $loan_status ?></td>
				                     		</tr>
				                     		
				                     		<tr>
				                     			<th>Payment Method</th>
				                     			<td><?php echo $release_method ?></td>
				                     		</tr>
				                     		<tr>
				                     			<th>Principal Amount</th>
				                     			<td><small><?php echo $currency ?> </small><?php echo $principle_amount ?></td>
				                     		</tr>
				                     		<tr>
				                     			<th>Loan Release Date</th>
				                     			<td><?php echo $release_date ?> | 0r | <?php echo date("j, F Y", strtotime($release_date)) ?></td>
				                     		</tr>
				                     		<tr>
				                     			<th>Loan Interest Reate</th>
				                     			<td><?php echo $loan_interest ?> (Nominal APR: <?php echo $loan_interest*12 ?>  )</td>
				                     		</tr>
				                     		<tr>
				                     			<th>Loan Duration</th>
				                     			<td><?php echo $loan_duration ?> <?php echo $loan__period ?></td>
				                     		</tr>
				                     		<tr>
				                     			<th>Repayment Cycle</th>
				                     			<td><?php echo $loan_payment_options ?></td>
				                     		</tr>
				                     			
				                     	<?php	
				                     		}
				                     	?>
				                     		</table>
				                     	</div>
				                  	</div>
				                  	<div class="tab-pane fade" id="custom-tabs-two-messages" role="tabpanel" aria-labelledby="custom-tabs-two-messages-tab">
				                     	<h4 class="mb-4 mt-4">Principal Amount - <?php echo $currency?> <?php echo number_format($principle_amount,2) ?></h4>
				                     	<div class="border-bottom border-primary mb-4"></div>
				                     	<div class="box-body table-responsive no-padding">
				                     		<table class="cell-border table table-sm" id="ScheduleTable" style="width: 100%">
				                     			<thead>
				                     				<tr>
				                     					<th>Repayments</th>
				                     					<th>Dates:</th>
				                     					<th><?php echo $currency ?> <?php echo $loan_payment_options ?> Amount</th>
				                     					<th><?php echo $currency ?> <?php echo $loan_payment_options ?> Interest</th>
				                     				</tr>
				                     			</thead>
				                     			<tbody>
				                     				<?php
				                     					$i = 1;
				                     					if ($loan_payment_options == "Daily") {
				                     						$p_d = 'days';
				                     					}elseif($loan_payment_options == "Weekly"){
				                     						$p_d = 'weeks';
				                     					}elseif ($loan_payment_options == 'Monthly') {
				                     						$p_d = 'months';
				                     					}
				                     					$period = 1;
				                     					$num = 1;
				                     					$query = $connect->prepare("SELECT * FROM loan_schedules  WHERE loab_id = ?");
				                     					for ($number_of_payments = 1; $number_of_payments <= $repayments; $number_of_payments++) {

				                     					?>
				                     						<!-- <input type="hidden" name="due_date[]" id="due_date" value='<?php echo date("Y-m-d", strtotime("+".$num++." ".$p_d."", strtotime($release_date))); ?>'> -->
				                     						<!-- <input type="hidden" name="loan_id[]" id="loan_id" value="<?php echo $_GET['loan_id']?>"> -->
				                     						<tr>
				                     							<td><?php echo $number_of_payments;?></td>
				                     							<td><?php echo date("d/m/Y", strtotime("+".$period++." ".$p_d."", strtotime($release_date))); ?></td>
				                     							<td><small class="text-fade"><?php echo $currency ?></small> <?php echo number_format($recurring_amount, 2) ?></td>
				                     							<td><small class="text-fade"><?php echo $currency ?></small> <?php echo number_format( ($total_interest_amount/$repayments), 2) ?></td>
				                     						</tr>
														<?
														}
				                     				?>
				                     			</tbody>
				                     			<tfoot>
				                     				<tr>
				                     					<th>Payment Mode</th>
				                     					<th><?php echo $loan_payment_options ?></th>
				                     					<th>Loan Duration</th>
				                     					<th><?php echo $loan_duration ?> <?php echo $loan__period ?></th>
				                     				</tr>
				                     			</tfoot>
				                     			<tfoot>
				                     				<tr>
					                     				<th>Loan Interest</th>
					                     				<th></th>
					                     				<th><small class="text-fade"><?php echo $currency ?></small> <?php echo number_format($total_payable_amount, 2) ?></th>
					                     				<th><small class="text-fade"><?php echo $currency ?></small> <?php echo number_format($total_interest_amount, 2) ?></th>
					                     			</tr>
				                     			</tfoot>
				                     		</table>        
					                    </div>
				                  	</div>
				                  	<div class="tab-pane fade" id="custom-tabs-two-settings" role="tabpanel" aria-labelledby="custom-tabs-two-settings-tab">
				                  		<div class="mb-3">
				                  			<a href="collaterals/collateral?loan_number=<?php echo $_GET['loan_id']?>&borrower_id=<?php echo $_GET['borrower_id']?>" class="btn btn-primary shadow">Add Collateral</a>
				                  		</div>
				                  		<div class="table table-responsive">
				                  			<table class="cell-border table table-sm" id="collaTable" style="width: 100%">
				                  				<thead>
				                  					<th>Type</th>
				                  					<th>Name</th>
				                  					<th>Registration</th>
				                  					<th>Product Value</th>
				                  					<th>Location</th>
				                  					<th>Condition</th>
				                  					<th>Description</th>
				                  					<th>Photo</th>
				                  					<th>Edit</th>
				                  					<th>Remove</th>
				                  				</thead>
				                  				<tbody>
				                  					
				                  				
				                  		<?php
				                  			$sqlC = $connect->prepare("SELECT * FROM `collaterals` WHERE loan_number = ? AND borrower_id = ? ");
											$sqlC->execute(array($_GET['loan_id'], $_GET['borrower_id']));
											if ($sqlC->rowCount() > 0) {
												$res = $sqlC->fetchAll();
												foreach ($res as $row) {
													extract($row);
													// `collateral_type`, `branch_id`, `parent_id`, `loan_number`, `borrower_id`, `product_name`, `register_date`, `product_value`, `currency`, `product_location`, `action_date`, `address`, `serial_number`, `model_name`, `model_number`, `color`, `manufature_date`, `product_condition`, `description`, `photo`, `files`, `vehicle_reg_number`, `millage`, `vehicle_engine_num
												?>
													<tr>
				                  						<td><?php echo $collateral_type ?></td>
				                  						<td><?php echo $product_name ?></td>
				                  						<td><?php echo date("j, F, Y", strtotime($register_date))?></td>
				                  						<td><small><?php echo $currency ?></small> <?php echo $product_value ?></td>
				                  						<td><?php echo $product_location ?></td>
				                  						<td><?php echo $product_condition ?></td>
				                  						<td><?php echo $description ?></td>
				                  						<td><img src="collaterals/files/<?php echo $photo ?>" alt="<?php echo $photo ?>" width="130" height="130" class="img-fluid"> </td>
				                  						<td>
				                  							<a href="collaterals/collateral_edit?product_id=<?php echo $id?>&loan_number=<?php echo $loan_number?>&borrower_id=<?php echo $borrower_id?>" data-id="<?php echo $id?>" class="editCollateral text-primary"> Edit <i class="bi bi-pencil-square"></i></a>
				                  						</td>
				                  						<td>
				                  							<a href="collaterals/collateral?product_id=<?php echo $id?>" data-id="<?php echo $id?>" class="deleteCollateral text-danger"> Delete <i class="bi bi-trash"></i></a>
				                  						</td>
				                  					</tr>
											<?php
												}
											}else{
												echo "You have not added collateral for this loan";
											}
				                  		?>
				                  				</tbody>
				                  			</table>
				                  		</div>
				                     	
				                  	</div>
				                </div>
				              </div>
				              <!-- /.card -->
				            </div>
				        </div>
				        <!-- End of Tabs -->
				        <!-- Modal For Loan Approval -->
				        <div class="modal fade" id="modal-primary">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title">Loan Action</h4>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<form method="post" id="loanActionForm">
											<div class="form-group mb-3">
												<label>Aprove or Reject Application</label>
												<select class="form-control" name="loan_status" id="loan_status">
													<option value="Released">Approve and Pay</option>
													<option value="Rejected">Reject and Close</option>
													<option value="Completed">Loan Completed</option>
												</select>
												<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID ?>">
												<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id'] ?>">
												<input type="hidden" name="loan_id" id="loan_id" value="">
												<input type="hidden" name="borrower_id" id="borrower_id" value="">
											</div>
											<button class="btn btn-outline-primary" type="submit" id="submit">Submit</button>
										</form>
									</div>
									<div class="modal-footer">
										
									</div>
								</div>
							</div>
						</div>
						<!-- End of Loan Approval Modals -->

						<div class="modal fade" id="paymentModal">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title">Loan Payment Form</h4>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<form method="post" id="loanPaymentForm">
											<div class="form-group mb-3">
												<label>Amount</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="bi bi-wallet2"></i></span>
													</div>
													<input type="number" name="amount" id="amount" step="any" min="1" class="form-control" required>
													<input type="hidden" name="edit_id" id="edit_id">
													<!-- <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID?>">
													<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>"> -->

												</div>
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
													<input type="text" name="paid_date" class="form-control" id="datemask2" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask>
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
												<input type="hidden" name="loan_id" id="loan_id" value="<?php echo $this_loan_id ?>">
												<input type="hidden" name="loan_number" id="loan_number" value="<?php echo $_GET['loan_id'] ?>">
												<input type="hidden" name="borrower_id" id="borrower_id" value="<?php echo $_GET['borrower_id']?>">
												<!-- <input type="hidden" name="balance" id="balance" value="<?php echo $total_payable_amount ?>"> -->
											</div>
											<button class="btn btn-outline-primary" type="submit" id="addPay">Submit</button>
										</form>
									</div>
									<div class="modal-footer">
										
									</div>
								</div>
							</div>
						</div>
						<!-- End of Loan Modal -->
						<!-- Start of Loan Delete Modal -->
						<div class="modal fade" id="modal-danger">
					        <div class="modal-dialog">
					          	<div class="modal-content bg-danger">
					            	<div class="modal-body">
					              		<p>Confirm you wish to delete this Payment?</p>
					              		<input type="hidden" name="delete_id" id="delete_id">
					            	</div>
					            	<div class="modal-footer justify-content-between">
					              		<button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
					              		<button type="button" class="btn btn-outline-light" id="submitTodelete">DELETE</button>
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
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	<script src="plugins/toastr/toastr.min.js"></script>
	<script src="plugins/select2/js/select2.full.min.js"></script>
	<script src="plugins/inputmask/jquery.inputmask.min.js"></script>
	<script>
		$(document).ready( function () {
		    $('#loansTable').DataTable();
		    $("#paymentsTable").DataTable();
		    $("#collaTable").DataTable();
		    $("#ScheduleTable").DataTable();
		    $('.select2').select2();
		
			$("#borrower_dateofbirth").datepicker({

				format: 'yyyy-mm-dd'
			});
			$('#datemask2').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })

			$(document).on("click", ".takeAction", function(e){
				e.preventDefault();
				var loan_id = $(this).data("loan_id");
				var borrower_id = $(this).data("borrower_id");

				$("#modal-primary").modal("show");
				$("#loan_id").val(loan_id);
				$("#borrower_id").val(borrower_id);
				
			})

			// submit 

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
    					$("#submit").html('<i class="fa fa-spinner fa-spin"></i>');
    				},
					success:function(data){
						if (data === 'success') {
							successNow("Loan Actioned");
							$("#submit").html("Submit");
							setTimeout(function(){
								location.reload();
							}, 2000);
						}else{
							errorNow(data);
						}
						
					}
				})
			})
			// loanPaymentForm ------------

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
						if (data === 'success') {
							successNow("Payment Submitted");
							$("#addPay").html("Submit");
							setTimeout(function(){
								location.reload();
							}, 2000);
						}else if(data === 'updated'){
							successNow("Payment Update");
							setTimeout(function(){
								location.reload();
							}, 2000);
							
						}else{
							errorNow(data);
						}
						
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
					if (data === 'success') {
						successNow("Payment Deleted");
						$("#submitTodelete").html("DELETE");
						setTimeout(function(){
							location.reload();
						}, 2000);
					}else{
						errorNow(data);
					}
					
				}
			})	
		})

		
		function errorNow(msge){
      		toastr.error(msge)
      		toastr.options.progressBar = true;
      		toastr.options.positionClass = "toast-top-center";
      		toastr.options.showDuration = 1000;
      	}

      	function successNow(msge){
      		toastr.success(msge)
      		toastr.options.progressBar = true;
      		toastr.options.positionClass = "toast-top-center";
      		toastr.options.showDuration = 1000;
      	}

   //    	function failed(msge) {
   //    		toastr["error"]("Sorry, not processing<br /><br /><button type='button' class='btn clear'>Close</button>', 'Failed");
   //    		toastr.options = {
			//   "closeButton": true,
			//   "debug": false,
			//   "newestOnTop": false,
			//   "progressBar": false,
			//   "positionClass": "toast-top-center",
			//   "preventDuplicates": false,
			//   "onclick": null,
			//   "showDuration": "300",
			//   "hideDuration": "1000",
			//   "timeOut": 0,
			//   "extendedTimeOut": 0,
			//   "showEasing": "swing",
			//   "hideEasing": "linear",
			//   "showMethod": "fadeIn",
			//   "hideMethod": "fadeOut",
			//   "tapToDismiss": false
			// }
   //    	}
   //    	function goodMsg(mgs) {
   //    		toastr["error"]("Sorry", mgs+" <br /><br /><button type='button' class='btn clear'>Close</button>", "Failed");
   //    		toastr.options = {
			//   "closeButton": true,
			//   "debug": false,
			//   "newestOnTop": false,
			//   "progressBar": false,
			//   "positionClass": "toast-top-center",
			//   "preventDuplicates": false,
			//   "onclick": null,
			//   "showDuration": "300",
			//   "hideDuration": "1000",
			//   "timeOut": 0,
			//   "extendedTimeOut": 0,
			//   "showEasing": "swing",
			//   "hideEasing": "linear",
			//   "showMethod": "fadeIn",
			//   "hideMethod": "fadeOut",
			//   "tapToDismiss": false
			// }
   //    	}
   //    		toastr.options = {
			// 	"closeButton": false,
			// 	"debug": false,
			// 	"newestOnTop": false,
			// 	"progressBar": false,
			// 	"positionClass": "toast-top-center",
			// 	"preventDuplicates": false,
			// 	"onclick": null,
			// 	"showDuration": "300",
			// 	"hideDuration": "1000",
			// 	"timeOut": "5000",
			// 	"extendedTimeOut": "1000",
			// 	"showEasing": "swing",
			// 	"hideEasing": "linear",
			// 	"showMethod": "fadeIn",
			// 	"hideMethod": "fadeOut"
			// }
	</script>
</body>
</html>