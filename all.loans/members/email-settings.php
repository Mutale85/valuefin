<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Email Settings</title>
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
			<section class="content mt-5">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					
      					<div class="col-md-12 d-flex justify-content-between mt-5 border-bottom border-primary">
      						<h4>Create Sender Email</h4>
      					</div>
      				</div>
      			</div>
      			<!-- borrower form -->
      			<div class="container-fluid pt-3">
      				<div class="row">
      					<div class="col-md-5">
      						<div class="card mb-5">
      							<div class="card-header">
      								<h4 class="card-title">Email Settings Form</h4>
      							</div>
      							<div class="card-body">
	      							<form method="post" id="emailSettingForm">
	      								<div class="form-group mb-3">
	      									<label>Sender Name</label>
	      									<input type="text" name="sender_name" id="sender_name" class="form-control" placeholder="Enter name" required>
	      								</div>
	      								<div class="form-group mb-3">
	      									<label>SMTP Server</label>
	      									<input type="text" name="smtp_server" id="smtp_server" class="form-control" placeholder="smtp.gmail.com" required>
	      								</div>
	      								<div class="form-group mb-3">
	      									<label>SMTP Port</label>
	      									<input type="text" name="smtp_port" id="smtp_port" class="form-control" placeholder="587 or 486" required>
	      								</div>
	      								<div class="form-group mb-3">
	      									<label>Email: Enter your Google account or personalised Email</label>
	      									<input type="text" name="sender_email" id="sender_email" class="form-control" placeholder="example@gmail.com" required>
	      								</div>
	      								<div class="form-group mb-3">
	      									<label>Password: Enter your Google account or personalised password</label>
	      									<input type="text" name="sender_password" id="sender_password" class="form-control" placeholder="Password" required>
	      									<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
	      									<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID?>">
	      									<input type="hidden" name="ID" id="ID">
	      								</div>
	      								<button type="submit" class="btn btn-primary" id="createBTN">Create</button>
	      							</form>
	      						</div>
      						</div>
      					</div>
      					<div class="col-md-7">
      						<div class="card card-warning">
      							<div class="card-header">
      								<h4 class="card-title">Email Settings</h4>
      							</div>
      							<div class="card-body">
  									<div class="table table-responsive">
  										<table class="cell-table" id="ServerTable" style="width: 100%">
  											<thead>
  												<tr>
  													<th>Sender name</th>
  													<th>SMTP Server</th>
  													<th>SMTP Port</th>
  													<th>Sender Email</th>
  													<th>Sender Password</th>
  													<td>Edit</td>
  													<td>Remove</td>
  												</tr>
  											</thead>
  											<tbody class="text-dark">
  											
		      								<?php
		      									$query = $connect->prepare("SELECT * FROM emailSettingForm WHERE parent_id = ?");
		      									$query->execute(array($_SESSION['parent_id']));
		      									if ($query->rowCount() > 0) {
		      										foreach ($query->fetchAll() as $row) {
		      											extract($row);
		      										?>
		      										<tr>
		      											<td><?php echo $sender_name?></td>
		      											<td><?php echo $smtp_server ?></td>
		      											<td><?php echo $smtp_port ?></td>
		      											<td><?php echo $sender_email ?></td>
		      											<td><?php echo $sender_password ?></td>
		      											<td><a href="<?php echo $id?>" class="editServer"><i class="bi bi-pencil-square"> </i></a></td>
		      											<td><a href="<?php echo $id?>" class="deleteServer text-danger"><i class="bi bi-trash"> </i></a></td>
		      										</tr>
		      									<?php
		      										}
		      									}else{
		      										
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
		<aside class="control-sidebar control-sidebar-dark"></aside>
	</div>
	<?php include("../footer_links.php")?>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	<script src="plugins/select2/js/select2.full.min.js"></script>
	<script src="plugins/toastr/toastr.min.js"></script>
	<script>
		$(document).ready( function () {
		    $('#myTable').DataTable();
		    $('#ServerTable').DataTable();
		});
		
		$(function(){
		
		

			$(document).on("submit", "#emailSettingForm", function(e){
				e.preventDefault();
				let myForm = document.getElementById('emailSettingForm');
				let formData = new FormData(myForm);
				$.ajax({
					url:"SMSFIles/emailSettings",
					method:"post",
					data:formData,
					cache:false,
					processData:false,
					contentType:false,
					beforeSend:function(){

						$("#createBTN").html("<i class='fa-spinner fa-spin'></i>");
					},
					success:function(response){
						if(response === 'done'){
							successNow("SMTP Saved");
							setTimeout(function(){
								location.reload();
							}, 2000);
						}else if(response === 'Server Updated'){
							successNow("SMTP Server Updated");
							setTimeout(function(){
								location.reload();
							}, 2000);
						}else{
							errorNow(response);
						}
						
						
						$("#createBTN").html("Submit");
					}
				})
			});

			$(document).on("click", ".editServer", function(e){
				e.preventDefault();
				var serverId = $(this).attr("href");
				$.ajax({
					url:"SMSFIles/editEmailServer",
					method:"post",
					data: {serverId:serverId},
					dataType:"JSON",
					success:function(data){
						$("#sender_name").val(data.sender_name);
						$("#smtp_server").val(data.smtp_server);
						$("#smtp_port").val(data.smtp_port);
						$("#sender_email").val(data.sender_email);
						$("#sender_password").val(data.sender_password);
						$("#ID").val(data.id);
					}
				})
				// alert(serverId);
			})

			$(document).on("click", ".deleteServer", function(e){
				e.preventDefault();
				var deleteserverId = $(this).attr("href");
				if(confirm("Confirm you wish to deleted email settings?")){
					$.ajax({
						url:"SMSFIles/editEmailServer",
						method:"post",
						data: {deleteserverId:deleteserverId},
						
						success:function(data){
							if (data === 'deleted') {
								successNow("SMTP Removed");
								setTimeout(function(){
									location.reload();
								}, 2000);
							}else{
								errorNow(data);
							}
						}
					})
				}else{
					return false;
				}
			})
		})
		

		function errorNow(msge){
	  		toastr.error(msge)
	  		toastr.options.progressBar = true;
	  		toastr.options.positionClass = "toast-top-center";
	  	}

	  	function successNow(msge){
	  		toastr.success(msge)
	  		toastr.options.progressBar = true;
	  		toastr.options.positionClass = "toast-top-center";
	  	}
    	
	</script>
</body>
</html>