<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
	
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
	<title>Loging Data</title>
	<?php include("../addon_header.php");?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light p-3"> 
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
  											<table class="table table-bordered" style="width: 100%" id="allTables">
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
  													<td><?php echo date("j F, Y", strtotime($time_login))?> - <small>(<?php echo time_ago_check($time_login)?>)</small></td>
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
      		</section>
		</div>
	<?php include("../addon_footer.php")?>
</body>
</html>