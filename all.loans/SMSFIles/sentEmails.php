<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php");  
	
  	if (isset($_GET['user_email']) && isset($_GET['username'])) {
  		$user_email = $_GET['user_email'];
  		$username = $_GET['username'];
  	}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $username?> - </title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
	<style>
		
		.cursor-pointer {
			cursor: pointer;
			font-size: 1em;
		}
		.select2-container--default.select2-container--focus .select2-selection--multiple, .select2-container--default.select2-container--focus .select2-selection--single {
		    border-color: #ff80ac;
		    height: 40px !important;
		}

		.select2-container--default .select2-selection--multiple .select2-selection__rendered li:first-child.select2-search.select2-search--inline {
		    width: 100%;
		    margin-left: .375rem;
		    height: 40px;
		}
		.select2-container--default .select2-selection--single {
		    background-color: #f8f9fa;
		    border: 1px solid #aaa;
		    border-radius: 4px;
		    height: 40px;
		}
		.select2-container--default .select2-selection--multiple .select2-selection__rendered {
		    box-sizing: border-box;
		    list-style: none;
		    margin: 0;
		    padding: .4em;
		    width: 100%;
		}
	</style>
</head>
<?php
	
?>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content bg-light mt-5">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 d-flex justify-content-between mt-5 border-bottom border-primary">
      						<h4>Emails Sent To <?php echo ucwords($username) ?></h4>
      					</div>
      				</div>
      			</div>
      			<!-- borrower form -->
      			<div class="container-fluid pt-3">
      				<div class="row">
      					<div class="col-md-12">
      						<div class="card mb-5">
      							<div class="card-header">
      								<h4 class="card-title"><?php echo ucwords($username) ?></h4>
      							</div>
      							<div class="card-body">
      								<div class="row">
		      							<?php 
		      								$output = '';
				      						$query = $connect->prepare("SELECT * FROM sent_emails WHERE receiver = ? AND parent_id = ?");
				      						$query->execute(array($user_email, $_SESSION['parent_id']));
				      						$count = $query->rowCount();
				      						if ($count > 0) {
				      							foreach ($query as $row) {
				      								extract($row);

				      								$output .= '
				      									<div class="col-md-4">
				      										<div class="card mb-4 border border-primary shadow">
				      											<div class="card-header">
								      								<h4 class="card-title"><i class="bi bi-chat-left"></i></h4>
								      							</div>
					      										<div class="card-body">
					      											<p> '.$message.'<p>
				      											</div>
				      											<div class="card-footer">
				      												<em><i class="bi bi-clock-history"></i> '.$date_sent.'</em>
				      											</div>
				      										</div>
				      									</div>
				      								';
				      							}
				      							echo $output;
				      						}else{
				      							echo "<h4 class='text-center'>".$count. "  Emails Sent </h4>";
				      							
				      						}
				      					?>
				      				</div>
	      						</div>
      						</div>
      					</div>
      				</div>
      			</div>
      			
				<!-- Editing Modal -->
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
		});

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