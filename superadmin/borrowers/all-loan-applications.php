<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>All loan applications</title>
	<?php 
        include("../addon_header.php");
        if(isset($_GET['branch_id'])){
			$get_branch = base64_decode($_GET['branch_id']);
			$parent_id = $_SESSION['parent_id'];
			$branch = getBranchName($connect, $parent_id, $get_branch);
			
		}

		if(isset($_GET['allbranches'])){
			$parent_id = base64_decode($_GET['parent_id']);
			$branch = 'All Branches';
		}
    ?>
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
                                    <h4  class="card-title">Loan Applications - <?php echo $branch?></h4>
                                    <div class="card-tools">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                <b><i class="bi bi-building-check"></i> Branches</b>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                                <?php 
                                                    $query = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
                                                    $query->execute([$parent_id]);
                                                    foreach($query->fetchAll() as $row){
                                                        extract($row);
                                                ?>
                                                    <a href="borrowers/all-loan-applications?branch_id=<?php echo base64_encode($id)?>&parent_id=<?php echo base64_encode($member_id)?>" class="dropdown-item"><?php echo getBranchName($connect, $parent_id, $id) ?></a>
                                                
                                                <?php }?>
                                                    <a class="dropdown-divider"></a>
                                                    <a href="borrowers/all-loan-applications?allbranches=ALL&parent_id=<?php echo base64_encode($member_id)?>" class="dropdown-item">All branches data</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    if(isset($_GET['branch_id'])){	
                                ?>
                                    <div class="card-body box-profile">
                                        <table class="table table-bordered" id="allTables">
                                            <thead>
                                                <tr>
                                                    
                                                    <th>Client</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $query = $connect->prepare("SELECT * FROM loan_applications WHERE branch_id = ? AND parent_id = ?");
                                                    $query->execute([$get_branch, $_SESSION['parent_id']]);
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
                                                    <td><a href="borrowers/loan-request?client-id=<?php echo base64_encode($applicant_id)?>&application_id=<?php echo base64_encode($id)?>&status=<?php echo $status?>"> <?php echo getBorrowerFullNamesByCardId($connect, $applicant_id) ?> <small>  ( NRC: <?php echo $applicant_id ?>)</small></td>
                                                    <td><?php echo date('j F, Y', strtotime($date_submitted))?></td>
                                                    <td><?php echo ucwords($output)?></td>
                                                    <td><a href="borrowers/loan-request?client-id=<?php echo base64_encode($applicant_id)?>&application_id=<?php echo base64_encode($id)?>&status=<?php echo $status?>"><i class="bi bi-archive"></i> View Application</a></td>
                                                </tr>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php 
								}else if($_GET['allbranches']){
							?>
                                <!-- All branches -->
                                    <div class="card-body box-profile">
                                        <table class="table table-bordered" id="allTables">
                                            <thead>
                                                <tr>
                                                    
                                                    <th>Client</th>
                                                    <th>Branch</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $query = $connect->prepare("SELECT * FROM loan_applications WHERE parent_id = ?");
                                                    $query->execute([$parent_id]);
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
                                                    <td><a href="borrowers/loan-request?client-id=<?php echo base64_encode($applicant_id)?>&application_id=<?php echo base64_encode($id)?>&status=<?php echo $status?>"> <?php echo getBorrowerFullNamesByCardId($connect, $applicant_id) ?> <small>  ( NRC: <?php echo $applicant_id ?>)</small></td>
                                                    <td><?php echo getBranchName($connect, $parent_id, $branch_id)?></td>
                                                    <td><?php echo date('j F, Y', strtotime($date_submitted))?></td>
                                                    <td><?php echo ucwords($output)?></td>
                                                    <td><a href="borrowers/loan-request?client-id=<?php echo base64_encode($applicant_id)?>&application_id=<?php echo base64_encode($id)?>&status=<?php echo $status?>"><i class="bi bi-archive"></i> View Application</a></td>
                                                </tr>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</section>
	</div>

	<?php include("../addon_footer.php")?>
	
</body>
</html>