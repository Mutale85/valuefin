<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
  	$country = $phone = '';
	$query = $connect->prepare("SELECT * FROM currencies");
	$query->execute();
	foreach ($query->fetchAll() as $row) {
	    $country .= '<option value="'.$row['id'].'">'.$row['country'].'</option>';
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>View Borrowers of <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?></title>
	<?php include("../addon_header.php");?>
</head>

<body class="layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light p-2">
            <div class="container-fluid">
                <div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">Clients List</h3>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped" id="clientsTable">
										<thead>
											<tr>
												<th>Photo</th>
												<th>Details</th>
												<th>Kin Details</th>
												<th>Business</th>
												<th>Actions</th>
											</tr>
										</thead>
										<tbody >
										<?php 
											$query = $connect->prepare("SELECT * FROM borrowers_details WHERE branch_id = ? AND parent_id = ?");
											$query->execute([$BRANCHID, $_SESSION['parent_id']]);
											foreach($query->fetchAll() as $row){
												extract($row);        
										?>
											<tr class=" border-bottom">
												<td><img src="<?php echo getClientsImage($connect, $borrower_id) ?>" alt="<?php echo $borrower_photo ?>" class="img-fluid img-responsive" style="width:50px;height:50px;border-radius:50%;"></td>
												<td>
													<table class="table">
														<tr>
															<th>Fullnames</th>
															<td><?php echo $borrower_firstname?> <?php echo $borrower_lastname?></td>
														</tr>
														<tr>
															<th>NRC Number</th>
															<td><?php echo $borrower_id?> </td>
														</tr>
														<tr>
															<th>Gender</th>
															<td><?php echo $borrower_gender?> </td>
														</tr>
														<tr>
															<th>Birthday</th>
															<td><?php echo $borrower_dateofbirth?> </td>
														</tr>
														<tr>
															<th>Age</th>
															<td><?php echo calculateUserAge($borrower_dateofbirth)?> Years</td>
														</tr>
														<tr>
															<th>Phone</th>
															<td><?php echo $borrower_phone?></td>
														</tr>
														<tr>
															<th>Email</th>
															<td><?php echo $borrower_email?></td>
														</tr>
														<tr>
															<th>Home Address</th>
															<td><?php echo $borrower_address?></td>
														</tr>
													</table>
												</td>
												<td>
												<?php
														echo getNextofKinDetails($connect, $borrower_id) 
													?>
												</td>
												<td>
													<?php 
														echo getBusinessDetails($connect, $borrower_id);
													?>
													
												</td>
												<td>
													
													<table class="table table-borderless">
													<?php 
														$query = $connect->prepare("SELECT * FROM loan_applications WHERE branch_id = ? AND parent_id = ? AND applicant_id = ? AND status = 'approved' and repayment_status = '0' ");
														$query->execute([$BRANCHID, $_SESSION['parent_id'], $borrower_id]);
														if($query->rowCount()){
															$rows = $query->fetch(); 
													?>
														<tr>
															<td><a href="borrowers/loan-request?client-id=<?php echo base64_encode($borrower_id)?>&application_id=<?php echo base64_encode($rows['id'])?>&status=<?php echo $rows['status']?>" class="btn btn-success">APPROVED</a></td>
														</tr>
														<tr>
															<td>Client has a been issued a loan.</td>
														</tr>
													<?php
														}else{
													?>
														<tr>
															<td><a href="borrowers/borrower-details-edit?applicant-id=<?php echo base64_encode($borrower_id)?>" class="btn btn-info"><i class="bi bi-pencil"></i> Edit Info</a></td>
														</tr>
														<!-- <tr>
															<td><a href="" class="btn btn-primary"><i class="bi bi-printer"></i> Print Info</a></td>
														</tr> -->
														<tr>
															<td><a href="borrowers/loan-application?applicant-id=<?php echo base64_encode($borrower_id)?>&branch-id=<?php echo base64_encode($BRANCHID)?>&parent-id=<?php echo base64_encode($_SESSION['parent_id'])?>" class="btn btn-warning"><i class="bi bi-person-workspace"></i> Give Loan</a></td>
														</tr>
														<tr>
															<td><a href="" class="btn btn-danger"><i class="bi bi-trash"></i> Trash Info</a></td>
														</tr>
														<?php 
															}
														?>	
													</table>
													
												</td>
											</tr>

										<?php
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

	<?php include("../addon_footer.php")?>
	<script>
        $("#clientsTable").DataTable();
    </script>
</body>
</html>