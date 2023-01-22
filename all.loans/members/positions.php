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
	$branch_options = "";
	$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
	$sql->execute(array($_SESSION['parent_id']));
	foreach ($sql->fetchAll() as $row) {
		$branch_options .= '<option value="'.$row['id'].'">'.$row['branch_name'].'</option>';
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin Members</title>
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
		    height: 35px !important;
		}

		.select2-container--default .select2-selection--multiple .select2-selection__rendered li:first-child.select2-search.select2-search--inline {
		    width: 100%;
		    margin-left: .375rem;
		    height: 35px;
		}
		.select2-container--default .select2-selection--single {
		    background-color: #f8f9fa;
		    border: 1px solid #aaa;
		    border-radius: 4px;
		    height: 35px;
		}
		.select2-container--default .select2-selection--multiple .select2-selection__rendered {
		    box-sizing: border-box;
		    list-style: none;
		    margin: 0;
		    padding: .4em;
		    width: 100%;
		}
		img.img-rounded {
			border-radius: 50%;
		}
	</style>
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
      					<div class="col-md-12 mt-4 pb-2 d-flex justify-content-between">
  							<h1 class="h4"> <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $BRANCHID))?> BRANCH </h1>
  							<?php if($_SESSION['user_role'] == 'Admin'):?>
  								<a href="members/add_admin" class="btn btn-outline-primary" type="button"  ><i class="bi bi-person"></i> New Admin</a>
  							<?php endif;?>
  						</div>
      				</div>
      			</div>
      			<div class="container-fluid">
      				<div class="row">
	      				<div class="col-md-6">
	      					<div class="card card-info">
	      						<div class="card-header">
		      						<h4 class="card-title">Positions Form</h4>
		      					</div>
		      					<div class="card-body">
		      						<form method="post" id="positionForm">
		      							<div class="form-group">
		      								<label>Job Title</label>
		      								<input type="text" name="job_title" id="job_title" class="form-control">
		      								<input type="hidden" name="ID" id="ID">
		      								<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
		      							</div>
		      							<button type="submit" class="btn btn-primary shadow" id="submit">Submit</button>
		      						</form>
		      					</div>
	      					</div>
	      				</div>

	      				<div class="col-md-6">
	      					<div class="card card-warning">
	      						<div class="card-header">
		      						<h4 class="card-title">Positions</h4>
		      					</div>
		      					<div class="card-body">
		      						<div class="table table-reponsive">
		      							<table class="cell-table" id="positionTable" style="width: 100%">
		      								<thead>
		      									<tr>
		      										<th>#</th>
		      										<th>Position</th>
		      										<th>Edit</th>
		      										<th>Remove</th>
		      									</tr>
		      								</thead>
		      								<tbody class="text-dark">
		      							
		      						<?php
		      							$query = $connect->prepare("SELECT * FROM `positions` WHERE  parent_id = ?");
		      							$query->execute(array($_SESSION['parent_id']));
		      							if ($query->rowCount() > 0) {
		      								$i = 1;
		      								foreach($query->fetchAll() as $row){
		      									extract($row);
		      						?>
		      									<tr>
		      										<td><?php echo $i++?></td>
		      										<td><?php echo ucwords($title)?></td>
		      										<td><a href="" data-id="<?php echo $id?>" class="editPosition"><i class="bi bi-pencil-square"></i></a></td>
		      										<td><a href="" data-id="<?php echo $id?>" class="deletePosition text-danger"><i class="bi bi-trash"></i></a></td>
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
		    $('#positionTable').DataTable();
		    // $("#branchesTable").DataTable();
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
	

	 $(document).on("submit", "#positionForm", function(e){
		e.preventDefault();
		
		$.ajax({
			url:"members/submitPosition",
			method:"post",
			data:$(this).serialize(),
			beforeSend:function(){
				$("#submit").html("<i class='fa fa-spinner fa-spin'><i>");
			},
			success:function(data){
				if (data === 'done') {
					successNow("Job Position Added");
					setTimeout(function(){
						location.reload();
					}, 2000);
				}else if(data === 'updated'){
					successNow("Job Position Updated");
					setTimeout(function(){
						location.reload();
					}, 2000);
				}else{
					errorNow(data);
				}
			}
		})
	})

	 $(document).on("click", ".editPosition", function(e){
		e.preventDefault();
		var position_id = $(this).data("id");
		// alert(position_id);
		$.ajax({
			url:"members/edit",
			method:"post",
			data:{position_id:position_id},
			dataType:"JSON",
			
			success:function(data){
				$("#job_title").val(data.title);
				$("#ID").val(data.id);
			}
		})
	})

	$(document).on("click", ".deletePosition", function(e){
		e.preventDefault();
		var position_delete_id = $(this).data("id");
		$.ajax({
			url:"members/edit",
			method:"post",
			data:{position_delete_id:position_delete_id},
			beforeSend:function(){

			},
			success:function(data){
				if (data === 'done') {
					successNow("Position Deleted");
					setTimeout(function(){
						location.reload();
					}, 2000);
				}else{
					errorNow(data);
				}
			}
		})
	})



</script>
</body>
</html>