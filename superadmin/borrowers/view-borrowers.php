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
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="clientsTable">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Details</th>
                                    <th>Business</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $query = $connect->prepare("SELECT * FROM borrowers_details");
                                $query->execute();
                                foreach($query->fetchAll() as $row){
                                    extract($row);        
                            ?>
                                  <tr>
                                    <td><img src="borrowers/uploads/<?php echo $borrower_photo ?>" alt="<?php echo $borrower_photo ?>" class="img-fluid img-responsive" width="50"></td>
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
												<th>Date of Birth</th>
												<td><?php echo $borrower_dateofbirth?> </td>
											</tr>
											<tr>
												<th>Aged</th>
												<td><?php echo calculateUserAge($borrower_dateofbirth)?> Years</td>
											</tr>
											<tr>
												<th>Phone number</th>
												<td><?php echo $borrower_phone?></td>
											</tr>
											<tr>
												<th>Email</th>
												<td><?php echo $borrower_email?></td>
											</tr>
										</table>
									</td>
									<td>
										<?php 
											echo getBusinessDetails($connect, $borrower_id);
										?>
									
									</td>
									<td>
										<table class="table table-borderless">
											<tr>
												<td><a href="borrower-details-edit?applicant-id<?php echo base64_encode($borrower_id)?>" class="btn btn-info"><i class="bi bi-pencil"></i> Edit Details</a></td>
											</tr>
											<tr>
												<td><a href="" class="btn btn-primary"><i class="bi bi-binoculars"></i> View Details</a></td>
											</tr>
											<tr>
												<td><a href="" class="btn btn-warning"><i class="bi bi-person-workspace"></i> Issue Loan</a></td>
											</tr>
											<tr>
												<td><a href="" class="btn btn-danger"><i class="bi bi-trash"></i> Delete Info</a></td>
											</tr>	
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
            
        </section>
	</div>

	<?php include("../addon_footer.php")?>
	<script>
        $("#clientsTable").DataTable();
    </script>
</body>
</html>