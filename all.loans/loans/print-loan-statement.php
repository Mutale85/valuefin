<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Loan Statment Receipt</title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />

	<style>
		
		/*Payslip table*/
		.tableDiv {
			margin: 2em auto;
			width: 70%;
		}
		.tableDiv h2 {
			margin-bottom: 1em;
		}
		.intro_table {
			width: 100%;
			border: 1px solid #ddd;
			padding: 1em;
		}
		.anotherTable {
			width: 100%;
			border: 1px solid #ddd;
			padding: 1em;
			border-top: none;
		}
		td, th {
			text-align: left;
			padding: 8px;
		}

		* {
			box-sizing: border-box;
		}

		.row {
			margin-left:-5px;
			margin-right:-5px;
		}

		.column {
			float: left;
			width: 50%;
			padding: 5px;
		}

		.row::after {
			content: "";
			clear: both;
			display: table;
		}

		table {
			border-collapse: collapse;
			border-spacing: 0;
			width: 100%;
			border: 1px solid #ddd;
		}

		th, td {
			text-align: left;
			padding: 16px;
		}

		tr:nth-child(even) {
			background-color: #f2f2f2;
		}
		
		@media print {

		  	html, body {
		    	height:100%; 
			    margin: 0 !important; 
			    padding: 0 !important;
			    overflow: hidden;
			    /*height: 100%;*/
		    	overflow: visible;
		  	}
		  	/*.pagebreak { page-break-before: always; }
		  	@page {
			  	margin-top: 2cm;
			}*/
			
		}
	</style>
</head>
<?php
	if (isset($_GET['loan_number']) AND isset($_GET['applicant_id'])) {
		$loan_number 	= $_GET['loan_number'];
		$parent_id 		= $_SESSION['parent_id'];
		$borrower_id 	= $_GET['applicant_id'];
	}
	$query = $connect->prepare("SELECT * FROM `organisations` WHERE parent_id = ? ");
	$query->execute(array($parent_id));
	if ($query->rowCount() > 0) {
		$row = $query->fetch();
		if ($row) {
			$organisation_name = $row['organisation_name'];
			$org_logo 		= $row['org_logo'];
			$admin_email 	= $row['admin_email'];
			$hq_address 	= $row['hq_address'];
			$hq_phone 		= $row['hq_phone'];

		}
	}
?>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content mt-5">
      			<div class="container-fluid mt-5">
			        <div class="row">
			          <div class="col-12 mt-5">
			            <div class="invoice p-3 mb-3">
			              	<div class="row">
				                <div class="col-12">
				                  <h4>
				                    <img src="members/adminphotos/<?php echo $org_logo?>" alt="<?php echo $org_logo?>" class="img-fluid img-responsive" width="100">
				                    <small class="float-right">Date: <?php echo date("d/m/Y") ?></small>
				                  </h4>
				                </div>
			              	</div>
			              	<div class="row invoice-info">
				                <div class="col-sm-4 invoice-col">
				                  	From
				                  	<!-- 
				                  		6846 
				                  	-->
									<address>
					                    <strong><?php echo $organisation_name ?></strong><br>
					                    <?php echo nl2br($hq_address) ?><br>
					                    
					                    Phone: <?php echo $hq_phone?><br>
					                    Email: <?php echo $admin_email?>
				                  	</address>
				                </div>
			                
				                <div class="col-sm-4 invoice-col">
				                  	To
				                  	<?php echo getBorrowerAddress($connect, $borrower_id, $parent_id)?>
				                </div>
				                
				                <div class="col-sm-4 invoice-col">
				                  <?php echo createReceiptNumber($connect, $loan_number, $parent_id)?>
				                </div>
			              	</div>
			              <!-- Table row -->
			              	<?php
			              		$sql = $connect->prepare("SELECT * FROM `loan_payments` WHERE borrower_id = ? AND loan_number = ? AND parent_id = ? ");
	         					$sql->execute(array($borrower_id, $loan_number, $parent_id));
			              	?>
				            <div class="row">
				                <div class="col-12 table-responsive">
				                  	<table class="table table-striped">
				                    	<thead>
				                    		<tr>
						                      	<th>Serial #</th>
						                      	<th>Amount Paid</th>
						                      	<th>Payment Date</th>
						                      	<th>Collected By</th>
				                    		</tr>
				                    	</thead>
				                    	<tbody>

				                    	<?php
				                    		$i = 1;
				                    		foreach ($sql->fetchAll() as $rows) {
				         						extract($rows);
				         				?>
			         						<tr>
			         							<td><?php echo $i++?></td>
			             						<td><?php echo number_format($amount, 2)?></td>
			             						<td><?php echo $paid_date?></td>
			             						<td><?php echo getStaffMemberNames($connect, $collected_by, $parent_id)?> </td>
			             					</tr>
				         				<?php
				         					}
				                    	?>
				                    	</tbody>
				                  	</table>
				                </div>
				            </div>
				              

				            <div class="row">
				                <div class="col-6">
				                  	<p class="lead">Payment Methods:</p>
				                  	<?php
					              		$sqln = $connect->prepare("SELECT * FROM `loan_payments` WHERE borrower_id = ? AND loan_number = ? AND parent_id = ? ");
		             					$sqln->execute(array($borrower_id, $loan_number, $parent_id));
		             					$r = $sqln->fetch();
		             					if ($r) {
		             						extract($r);
		             				?>
		             				<p><?php echo $payment_method ?></p>
					                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
					                    <?php echo $comment?>
					                </p>
					                <?php	
		             					}
					              	?>
				                </div>
				                
				                <div class="col-6">
				                	<?php 
				                		if($balance == '0.00'):
				                			
				                		else:
				                			echo checkPaymentDate($connect, $loan_number, $parent_id);
				                		endif;
				                	?>

				                  	<div class="table-responsive">
					                    <table class="table">
					                      	
					                      	<tr class="text-dark">
					                        	<th>Paid:</th>
					                        	<td><?php echo getCurrency($connect, $parent_id)?> <?php echo number_format(getTotalPaidForStatement($connect, $borrower_id, $loan_number, $parent_id) , 2)?></td>
					                      	</tr>
					                      	<?php
					                      		$paid = getTotalPaidForStatement($connect, $borrower_id, $loan_number, $parent_id);
					                      		$payble = getTotalPayablePrinciple($connect, $parent_id, $loan_number);
					                      		$balance = $payble - $paid;
					                      	?>
					                      	<tr>
					                        	<th>Balance:</th>
					                        	<td><?php echo getCurrency($connect, $parent_id)?> <?php echo number_format($balance, 2)?></td>
					                      	</tr>
					                    </table>
				                  	</div>
				                </div>
				            </div>
				            <div id="sig-canvass" class="d-flex justify-content-between mt-5 mb-4">
		                		<div class="coy_sign">
			                		<h4 class="text-left">Chuma Solutions</h4><br>
			                		<table class="table table-borderless">
			                			<tr>
			                				<th>Sign</th><td>__________________________</td>
			                			</tr>
			                			<tr>
			                				<th>Name</th><td>__________________________</td>
			                			</tr>
			                			<tr>
			                				<th>Date</th><td>__________________________</td>
			                			</tr>
			                		</table>
			                	</div>
			                	<div class="client_sign">
		                			<h4 class="text-left">Client's Signature</h4><br>
		                			<table class="table table-borderless">
			                			<tr>
			                				<th>Sign</th><td>__________________________</td>
			                			</tr>
			                			<tr>
			                				<th>Name</th><td>__________________________</td>
			                			</tr>
			                			<tr>
			                				<th>Date</th><td>__________________________</td>
			                			</tr>
			                		</table>
		                		</div>
		                	</div>
			          	</div>
			        </div>
			    </div>
			</section>
		</div>
    </div>
    <script>
  		window.addEventListener("load", window.print());
	 </script>
</body>
</html>