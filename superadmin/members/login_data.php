<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 
	
	$option = $countries = '';
	$query = $connect->prepare("SELECT * FROM currencies");
	$query->execute();
	foreach ($query->fetchAll() as $row) {
		$option .= '<option value="'.$row['code'].'">'.$row['code'].'</option>';
		$countries .= '<option value="'.$row['id'].'">'.$row['country'].'</option>';
	}
	$branch_options = $all_branch_options = "";
	$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
	$sql->execute(array($_SESSION['parent_id']));
	$results = $sql->fetchAll();
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin Loging Data</title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
	
</head>
<?php
	
?>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content  mt-5">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4">
  							<div class="card card-warning">
  								<div class="card-header">
  									<h4 class="card-title"> Last Login</h4>
  								</div>
  								<div class="card-body">
  									<?php
  										$query = $connect->prepare("SELECT * FROM `login_table` WHERE parent_id = ?");
  										$query->execute(array($_SESSION['parent_id']));
  										if ($query->rowCount() > 0) {
  									?>
  										<div class="table table-responsive">
  											<table class="cell-table" style="width: 100%" id="adminsTable">
  												<thead>
  													<tr>
  														<th>Last Login</th>
  														<th>Email</th>
  														<th>IP</th>
  														<th>Location</th>
  														<th>Logout Time</th>
  													</tr>
  												</thead>
  												<tbody class="text-dark">
  									<?php
  											foreach ($query->fetchAll() as $row) {
  												extract($row);
  									?>
  												<tr>
  													<td><?php echo date("j, F Y", strtotime($time_login))?> | <?php echo time_ago_check($time_login)?></td>
  													<td><?php echo $email?></td>
  													<td><?php echo $user_ip?></td>
  													<td><?php echo $user_country?></td>
  													<td><?php echo $logout_time ?></td>
  												</tr>
  									<?php
  											}
  									?>			</tbody>
  											</table>
  										</div>
  									<?php
  										}
  									?>
  								</div>
  							</div>
  						</div>
      				</div>
      			</div>
      			
      			

				<!-- End of edit modal -->
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
		    $('#adminsTable').DataTable();
		    $("#branchesTable").DataTable();
		    // select
		    $('.select2').select2();
		    //datepicker
		    $("#open_date").datepicker({

				format: 'yyyy-mm-dd'
			});

			$(".listView").click(function(e){
				e.preventDefault();
				$(".gridViewDiv").hide();
				$(".listViewDiv").show();
			})

			$(".gridView").click(function(e){
				e.preventDefault();
				$(".gridViewDiv").show();
				$(".listViewDiv").hide();
			})
		});

	// ================================= DISPLAYS ======================================
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