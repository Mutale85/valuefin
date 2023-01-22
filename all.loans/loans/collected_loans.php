<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php");  
		
?>
<!DOCTYPE html>
<html>
<head>
	<title>Collected Funds</title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<div class="content-header">
		      <div class="container-fluid mt-4">
		        <div class="row mb-2 mt-5">
		          <div class="col-sm-6">
		            <h4 class="m-0">Collected Funds</h4>
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
      					<div class="col-md-12 mt-4 pb-2">
  							

  							<?php
								$from_period = $to_period = "";
								if (isset($_GET['from_period']) AND isset($_GET['to_period'])) {
									$from_period = $_GET['from_period'];
									$to_period = $_GET['to_period'];
								}
							?>
							<form method="get" id="searchForm" class="border p-4">
								<h4 class="mb-3">Calendar</h4>
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
									<a href="loans/collected_loans" class="btn btn-outline-primary">Reset</a>
								</div>
							</form>	
  						</div>
      				</div>
      			</div>	
      			<div class="container-fluid pt-3">
      				<div class="row">
      					<div class="col-md-12"> 						
      						<div class="card card-success mb-5">
      							<div class="card-header">
      								<h4 class="card-title">Collected Loans</h4>
      							</div>
      							<div class="card-body box-profile">
      								<div class="table table-responsive">
      									<table id="loanTypes" class="cell-border text-dark" style="width:100%">
									        <thead>
									            <tr>
									            	<th>#</th>
									                <th>Month</th>
									                <th>Amount</th>
									                <th>Date Collected</th>
									            </tr>
									        </thead>
									        <tbody>
									        	<?php
									        		$status = 'Released';
													$parent_id = $_SESSION['parent_id'];
													if (isset($_GET['from_period']) AND isset($_GET['to_period'])) {
														$query = $connect->prepare("SELECT * FROM collected_funds WHERE date_added BETWEEN ? AND ? AND branch_id = ? AND parent_id = ? ");
														$query->execute(array($_GET['from_period'], $_GET['to_period'], $BRANCHID, $parent_id));

														// get Total 
														$query2 = $connect->prepare("SELECT SUM(amount) AS total_amount FROM collected_funds WHERE date_added BETWEEN ? AND ? AND branch_id = ? AND parent_id = ? ");
														$query2->execute(array($_GET['from_period'], $_GET['to_period'], $BRANCHID, $parent_id));
														$rows = $query2->fetch();
														$total_amount = $rows['total_amount'];

													}else{
														$query = $connect->prepare("SELECT * FROM collected_funds WHERE branch_id = ? AND parent_id = ? ");
														$query->execute(array($BRANCHID, $parent_id));

														$query2 = $connect->prepare("SELECT SUM(amount) AS total_amount FROM collected_funds WHERE branch_id = ? AND parent_id = ? ");
														$query2->execute(array($BRANCHID, $parent_id));
														$rows = $query2->fetch();
														$total_amount = $rows['total_amount'];
													}
													// $query = $connect->prepare("SELECT * FROM collected_funds WHERE branch_id = ? AND parent_id = ? ");
													// $query->execute(array($BRANCHID, $parent_id));
													$numRows = $query->rowCount();
													$i = 1;
													if ($numRows > 0 ) {
														
														$i = 1;
														foreach ($query->fetchAll() as $row) {
															extract($row);
														?>
															<tr>
																<td><?php echo $i++?></td>
																<td><?php echo $month ?></td>
																<td><?php echo $currency ?> <?php echo $amount?></td>
																<td><?php echo date("l, jS \of F Y ", strtotime($date_added))?></td>
															</tr>
													<?php
														}
													}
									        	?>
									     	</tbody>
									     	<tfoot>
									     		<tr>
									     			<th>Total Collected</th>
									     			<th></th>
									     			<th><?php echo $currency?> <?php echo $total_amount?></th>
									     			<th>
									     				<?php if(isset($_GET['from_period']) AND isset($_GET['to_period'])):?>
									     					<?php echo date("j F, Y", strtotime($from_period)) .' - '. date("j F, Y", strtotime($to_period))?>
									     				<?php else: ?>
									     					All Period
									     				<?php endif;?>
									     					
									     			</th>
									     		</tr>
									     	</tfoot>
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