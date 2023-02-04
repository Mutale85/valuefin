<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>All loan applications</title>
	<?php include("../addon_header.php");?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light">     					
			<div class="container-fluid">
				<div class="row">
                    <div class="col-md-12">
                        <div class="bg-light p-1">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h4  class="card-title">Loan Applications</h4>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body box-profile">
                                    <table class="table table-bordered" id="loansTable">
                                        <thead>
                                            <tr>
                                                <th>Loan ID</th>
                                                <th>Client's Name</th>
                                                <th>Application Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $query = $connect->prepare("SELECT * FROM loan_applications WHERE branch_id = ? AND parent_id = ?");
                                                $query->execute([$BRANCHID, $_SESSION['parent_id']]);
                                                if($query->rowCount() > 0){
                                                    foreach($query->fetchAll() as $row){
                                                        extract($row);
                                                        if($status === 'pending'){
                                                            $output = '<span class="text-secondary">'.strtoupper($status).'</span>';
                                                        }else if($status === 'approved'){
                                                            $output = '<span class="text-success">'.strtoupper($status).'</span>';
                                                        }else{
                                                            $output = '<span class="text-danger">'.strtoupper($status).'</span>';
                                                        }
                                            ?>
                                            <tr>
                                                <td><a href="borrowers/loan-request?client-id=<?php echo base64_encode($applicant_id)?>&application_id=<?php echo base64_encode($id)?>&status=<?php echo $status?>"><?php echo $applicant_id ?></td>
                                                <td><?php echo getBorrowerFullNamesByCardId($connect, $applicant_id) ?></td>
                                                <td><?php echo date('j F, Y', strtotime($date_submitted))?></td>
                                                <td><?php echo ucwords($output)?></td>
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

	<?php include("../addon_footer.php")?>
	<script>
		$("#loansTable").DataTable();
	</script>
</body>
</html>