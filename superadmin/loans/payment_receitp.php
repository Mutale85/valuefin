<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Payment Receipt</title>
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
		.button-55 {
		  align-self: center;
		  background-color: #fff;
		  background-image: none;
		  background-position: 0 90%;
		  background-repeat: repeat no-repeat;
		  background-size: 4px 3px;
		  border-radius: 15px 225px 255px 15px 15px 255px 225px 15px;
		  border-style: solid;
		  border-width: 2px;
		  box-shadow: rgba(0, 0, 0, .2) 15px 28px 25px -18px;
		  box-sizing: border-box;
		  color: #41403e;
		  cursor: pointer;
		  display: inline-block;
		  font-family: Neucha, sans-serif;
		  font-size: 1rem;
		  line-height: 23px;
		  outline: none;
		  padding: .75rem;
		  text-decoration: none;
		  transition: all 235ms ease-in-out;
		  border-bottom-left-radius: 15px 255px;
		  border-bottom-right-radius: 225px 15px;
		  border-top-left-radius: 255px 15px;
		  border-top-right-radius: 15px 225px;
		  user-select: none;
		  -webkit-user-select: none;
		  touch-action: manipulation;
		}

		.button-55:hover {
		  box-shadow: rgba(0, 0, 0, .3) 2px 8px 8px -5px;
		  transform: translate3d(0, 2px, 0);
		}

		.button-55:focus {
		  box-shadow: rgba(0, 0, 0, .3) 2px 8px 4px -6px;
		}
		
	</style>
</head>
<?php
	if (isset($_GET['loan_number']) AND isset($_GET['borrower_id']) AND isset($_GET['payment_id'])) {
		$loan_number 	= $_GET['loan_number'];
		$parent_id 		= $_SESSION['parent_id'];
		$borrower_id 	= base64_decode($_GET['borrower_id']);
		$payment_id 	= $_GET['payment_id'];
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
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content mt-5">
      			<div class="container-fluid mt-5">
			        <div class="row">
			          <div class="col-12 mt-5">
			            <div class="callout callout-info">
			              <h5><i class="fas fa-info"></i> Note:</h5>
			              This page has been enhanced for printing. Click the print button at the bottom of the invoice to print.
			            </div>


			            <!-- Main content -->
			            <div class="invoice p-3 mb-3">
			              	<div class="row">
				                <div class="col-12">
				                  <h4>
				                    <img src="members/adminphotos/<?php echo $org_logo?>" alt="<?php echo $org_logo?>" class="img-fluid img-responsive" style="width: 100px; border-radius: 50%;">
				                    <small class="float-right">Date: <?php echo date("d/m/Y") ?></small>
				                  </h4>
				                </div>
			              	</div>
			              	<div class="row invoice-info">
				                <div class="col-sm-4 invoice-col">
				                  	From
				                  
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
			              		$sql = $connect->prepare("SELECT * FROM `loan_payments` WHERE id = ? AND loan_number = ? AND parent_id = ? ");
	         					$sql->execute(array($payment_id, $loan_number, $parent_id));
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
					              		$sqln = $connect->prepare("SELECT * FROM `loan_payments` WHERE id = ? AND loan_number = ? AND parent_id = ? ");
		             					$sqln->execute(array($payment_id, $loan_number, $parent_id));
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
					                        	<td><small><?php echo getCurrency($connect, $parent_id)?></small> <?php echo number_format(getTotalPaid($connect, $payment_id, $loan_number, $parent_id), 2)?></td>
					                      	</tr>
					                      	<tr>
					                        	<th>Balance:</th>
					                        	<td><small><?php echo getCurrency($connect, $parent_id)?></small> <?php echo number_format($balance, 2)?></td>
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
		                	<div class="d-flex. justify-content-between">
		                		<a href="loans/print-receipt?loan_number=<?php echo $loan_number?>&borrower_id=<?php echo base64_encode($borrower_id)?>&payment_id=<?php echo $payment_id?>" target="_blank" class="button-55" role="button"> <i class="bi bi-printer"></i> PRINT DOCUEMUNT</a>
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
</body>
</html>