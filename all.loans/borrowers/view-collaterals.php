<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 

?>
<!DOCTYPE html>
<html>
<head>
	<title>View Collaterals Deposited of <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?></title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<div class="content-header">
		      <div class="container mt-4">
		        <div class="row mb-2 mt-5">
		          <div class="col-sm-6">
		            <h4 class="m-0">Collaterals</h4>
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
			<section class="content">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 border-bottom pb-2 mb-5 ">
      						<div class="d-flex justify-content-between">
      						
      					</div>
      				</div>
      			</div>
      			<div class="container-fluid mb-5">
      				<div class="row">
      					<div class="col-md-12">
      						<div class="table table-responsive">
	                  			<table class="cell-table table table-sm" id="collateralTable" style="width: 100%">
	                  				<thead>
	                  					<th>Owner</th>
	                  					<th>Type</th>
	                  					<th>Name</th>
	                  					<th>Registration</th>
	                  					<th>Value</th>
	                  					<th>Location</th>
	                  					<th>Condition</th>
	                  					<th>Description</th>
	                  					<th>Photo</th>
	                  					<th>Edit</th>
	                  					<th>Remove</th>
	                  				</thead>
	                  				<tbody class="text-dark">
	                  					
	                  				
	                  		<?php
	                  			
	                  			$sqlC = $connect->prepare("SELECT * FROM `collaterals` ");
								$sqlC->execute(array());
								if ($sqlC->rowCount() > 0) {
									$res = $sqlC->fetchAll();
									foreach ($res as $row) {
										extract($row);
									?>
										<tr>
											<td>
												<?php
													$query 	= $connect->prepare("SELECT * FROM borrowers_details WHERE id = ? AND parent_id = ? AND branch_id = ? AND display = '1' ");
					      							$query->execute(array($borrower_id, $parent_id, $branch_id));
					      							$count 	= $query->rowCount();
					      							$rows 	= $query->fetch();
					      							extract($rows);
												?>
												<table class="table table-borderless">
													<tr>
														<td>Names</td>
														<td><?php echo $borrower_firstname ?> <?php echo $borrower_lastname ?></td>
													</tr>
													<tr>
														<td>Address</td>
														<td><?php echo $borrower_address ?></td>
													</tr>
													<tr>
														<td>Phone</td>
														<td><?php echo $borrower_phone ?></td>
													</tr>
												</table>
											</td>
	                  						<td><?php echo $collateral_type ?></td>
	                  						<td><?php echo $product_name ?></td>
	                  						<td><?php echo date("j, F, Y", strtotime($register_date))?></td>
	                  						<td><small><?php echo $currency ?></small> <?php echo $product_value ?></td>
	                  						<td><?php echo $product_location ?></td>
	                  						<td><?php echo $product_condition ?></td>
	                  						<td><?php echo $description ?></td>
	                  						<td><a href="borrowers/collateral_uploads/<?php echo $photo ?>" target="_blank"><img src="borrowers/collateral_uploads/<?php echo $photo ?>" alt="<?php echo $photo ?>" width="130" height="130" class="img-fluid"> </a></td>
	                  						<td>
	                  							<a href="borrowers/borrower-details-edit?applicant-id=<?php echo base64_encode($borrower_id)?>&product_id=<?php echo base64_encode($id)?>" data-id="<?php echo $id?>" class="editCollateral text-primary"> <i class="bi bi-pen"></i> Edit</a>
	                  						</td>
	                  						<td>
	                  							<a href="borrowers/collateral_uploads/collateral?product_id=<?php echo $id?>" data-id="<?php echo $id?>" class="deleteCollateral text-danger"> Delete <i class="bi bi-trash"></i></a>
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
      		</section>
		</div>
		<aside class="control-sidebar control-sidebar-dark"></aside>
	</div>

	<?php include("../footer_links.php")?>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script src="plugins/toastr/toastr.min.js"></script>
	<script>
		$(function(){
			$("#collateralTable").DataTable();
		})
	</script>
</body>
</html>