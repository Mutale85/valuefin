<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 
  	
	$today = date("Y-m-d");
	$parent_id = $_SESSION['parent_id'];
	$query = $connect->prepare("SELECT * FROM loan_schedules WHERE parent_id = ? AND date_due = ? ");
	$query->execute(array($parent_id, $today));
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Due Loans</title>
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
      					<div class="col-md-12 mt-4">
  							<h4><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?> Due Loans</h4>
  						</div>
      				</div>
      			</div>	
      			<div class="container-fluid pt-3">
      				<div class="row">
      					<div class="col-md-12"> 						
      						<div class="card card-warning mb-5">
      							<div class="card-header">
      								<h4 class="card-title">Due Loans</h4>
      							</div>
      							<div class="card-body box-profile">
      								<div class="table table-responsive">
      									<table id="loanTypes" class="cell-border" style="width:100%">
									        <thead>
									            <tr>
									            	<th>#</th>
									                <th>Loan ID</th>
									                <th>Date</th>
									            </tr>
									        </thead>
									        <tbody>
									        	<?php
									        		function getBorrowerIdFromLoanNumber($connect, $loan_number) {
									        			$output = '';
									        			$query = $connect->prepare("SELECT * FROM `loans` WHERE loan_number = ? ");
									        			$query->execute(array($loan_number));
									        			$row = $query->fetch();
									        			if ($row) {
									        				$output = $row['borrower_id'];
									        			}
									        			return $output;
									        		}

													$numRows = $query->rowCount();
													$i = 1;
													if ($numRows > 0 ) {
														$loanData = array();
														$i = 1;
														foreach ($query->fetchAll() as $row) {
															$loan_number = $row['loan_id'];
														?>
															<tr>
																<td><?php echo $i++?></td>
																<td><a href="loans/view_loan_details?loan_number=<?php echo $loan_number?>&borrower_id=<?php echo getBorrowerIdFromLoanNumber($connect, $loan_number)?>" class="text-primary"><?php echo $loan_number?></a></td>
																<td>
																	<?php echo ucfirst($row['date_due'])?>
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

	</script>
</body>
</html>