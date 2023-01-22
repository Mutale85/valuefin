<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 
	
	$option = '';
	$query = $connect->prepare("SELECT * FROM admins WHERE parent_id = ?");
	$query->execute(array($_SESSION['parent_id']));
	foreach ($query->fetchAll() as $row) {
	    $option .= '<option value="'.$row['id'].'">'.$row['firstname'].' '.$row['lastname'].'</option>';
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>View Borrowers</title>
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

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 border-bottom pb-2 ">
      						<div class="d-flex justify-content-between">
      							<h1 class="h3"><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $BRANCHID))?> Group Borrowers</h1>
      						<button class="btn btn-sm btn-outline-secondary" type="button" data-toggle="modal" data-target="#modal-primary"><i class="bi-people"></i>  New Group Borrowers</button>
      						</div>
      						
      					</div>
      				</div>
      			</div>
      			<!-- borrower form -->
      			<div class="container-fluid">
      				<div class="row">
      					
      					<div class="col-md-12">
      						<?php 
      							$query = $connect->prepare("SELECT * FROM group_borrowers WHERE parent_id = ? AND branch_id = ? ");
      							$query->execute(array($_SESSION['parent_id'], $BRANCHID));
      							$count = $query->rowCount();
      							$res = $query->fetchAll();
      						?>
      						<div class="card card-warning">
      							<div class="card-header">
      								<h4 class="card-title">Groups</h4>
      							</div>
      							<div class="card-body">
		      						<div class="table table-responsive">
			      						<table id="myTable" class="cell-table table-sm" style="width:100%">
									        <thead>
									            <tr>
									            	<th>View</th>
									                <th>Group Name</th>
									                <th>Unique ID#</th>
									                <th>Members</th>
									                <th>Group Leader</th>
									                <th>Collector</th>
									                <th>Branch</th>
									                <th>Loan Officers</th>
									                <th>Action</th>
									            </tr>
									        </thead>
									        <tbody class="text-dark">
									        	<?php 
									        		if ($count > 0) {
									        			foreach ($res as $row) {
									        				$branch_id = $row['branch_id'];
									        				$parent_id = $row['parent_id'];
									        				$g_id = $row['id'];
									        				$group_unique_id = $row['group_id'];
									        				$sql = $connect->prepare("SELECT * FROM allowed_branches WHERE branch_id = ? AND parent_id = ? AND staff_id = ? ");
															$sql->execute(array($branch_id, $parent_id, $_SESSION['user_id']));
															if($sql->rowCount() > 0){
																$rows = $sql->fetch();
																if ($rows) {

									        	?>
									        				<tr>
									        					<td>
									        						<a href="borrowers/view_group_borrowers?group_id=<?php echo $row['group_id']?>&parent_id=<?php echo $parent_id?>&group_leader_id=<?php echo $row['group_leader_id']?>" class="btn btn-outline-primary">View Loans</a>
									        					</td>
									        					<td><?php echo $row['group_name']?></td>
									        					<td><?php echo $row['group_id']?></td>
									        					<td><?php echo getBorrowersGroupMembersByID($connect, $g_id, $branch_id, $group_unique_id, $parent_id)?> </td>
									        					<td><?php echo getBorrowerFullNames($connect, $row['group_leader_id'], $_SESSION['parent_id'])?></td>
									        					<td><?php echo $row['collectors_name']?></td>
									        					<td><?php echo ucwords(getBranchName($connect, $parent_id, $branch_id))?> </td> 
									        					<td><?php echo groupBorrowersLoanOfficers($connect, $g_id, $branch_id, $group_unique_id, $parent_id)?></td>	 
									        					<td>
									        						<a href="borrowers/edit_group_borrower?group_id=<?php echo base64_encode($group_unique_id)?>&branch_id=<?php echo base64_encode($branch_id) ?>" class="btn btn-xs btn-outline-secondary editUsers" data-id="<?php echo $row['id']?>" data-role="update" id=""><i class="bi bi-pencil-square"></i>
									        						</a>
									        						<a href="<?php echo $row['id']?>" class="btn btn-xs btn-outline-secondary"><i class="fa fa-trash text-danger"></i></a>
									        					</td>
									        				</tr>
									        	<?php
									        					}
										        			}else{

										        			}
									        			}
									        		}
									        	?>

									     	</tbody>
									     	<tfoot>
									            <tr>
									            	<th>View</th>
									                <th>Group Name</th>
									                <th>Unique ID#</th>
									                <th>Members</th>
									                <th>Group Leader</th>
									                <th>Collector</th>
									                <th>Branch</th>
									                <th>Loan Officers</th>
									                <th>Action</th>
									            </tr>
									        </tfoot>
									    </table>
									 </div>
								</div>
							</div>
      					</div>
      				</div>
      			</div>
      			<!-- Modal Form -->
      			<?php
					$sql = $connect->prepare("SELECT  * FROM allowed_branches WHERE staff_id = ? AND parent_id = ? ");
	                $sql->execute(array($_SESSION['user_id'], $_SESSION['parent_id']));
	                $br_options = "";
	                foreach ($sql->fetchAll() as $row) {
	                  // we get the branch ID, and try to find other people who belong to 
	                  	$branch_id = $row['branch_id'];
	                  	$br_options .= '<option value="'.$branch_id.'">'.ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id)).'</option>';
	                }
				?>
      			<div class="modal fade" id="modal-primary">
					<div class="modal-dialog modal-lg">
						<div class="modal-content bg-secondary">
							<div class="modal-header">
								<h4 class="modal-title">Add New Group To <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $BRANCHID))?></h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
	      						<form class="" method="post" id="groupBorrowerForm" enctype="multipart/form-data">
									<div class="form-group mb-3">
	      								<!-- <label>Select Branch</label>
	      								<select  name="branch_id" id="branch_id" onchange="fetchLoanGroupOfficers(this.value)" oninput="fetchLoanGroupBorrowers(this.value)" class="select2 form-control" data-placeholder="Select Branch" data-dropdown-css-class="select2-purple" style="width:100%;" required="required">
	      									<option value=""></option>
	      									<?php echo $br_options?>
	      								</select> -->
	      								<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID?>">
	      								<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
	      							</div>
									<div class="form-group">
										<label for="form">Group name</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-people"></i></span>
											<input type="text" name="group_name" id="group_name" class="form-control">
										</div>
									</div>
									<div class="form-group mb-3">
										<label for="form">Group ID</label>
										<div class="input-group mb-2">
											<span class="input-group-text"><i class="bi bi-people"></i></span>
											<input type="text" aria-label="First name" name="group_id" id="group_id" class="form-control" value="0001">
										</div>
										<em>Give a unique ID to the group. Only numbers and are allowed</em>
									</div>
									
									<div class="form-group mb-3">
										<label>Borrowers</label>
										<div class="select2-purple">
											<select class="select2" multiple="multiple" name="borrowers_id[]" id="borrowers_id" data-placeholder="Select Borrowers" data-dropdown-css-class="select2-purple" style="width: 100%; ">
											</select>
										</div>
									</div>
									<input type="hidden" name="borrowers_names" id="borrowers_names" value="">
									
									<div class="border-bottom pb-2 mb-4"></div>
									<div class="form-group mb-3">
										<label>Group Leader</label>
										<div class="">
											<select class="select2"  name="group_leader_id" id="group_leader_id" data-placeholder="Select Borrowers" data-dropdown-css-class="select2-purple" style="width: 100%;">
												
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label for="form">Collector Name</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-person"></i></span>
											<input type="text" name="collectors_name" id="collectors_name" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="form">Description</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-info-square"></i></span>
											<textarea type="text" name="description" id="description" class="form-control" rows="5"> </textarea>
										</div>
									</div>
									<div class="form-group mb-3">
										<button class="btn btn-warning mb-3" type="button" id="selectImage">Add Group Photo <i class="bi bi-file-person"></i></button><br>
										<input type="file" name="group_photo" id="group_photo" class="form-control"  style="display: none;" onchange="preview_group_image(event)" accept="image/png, image/jpeg, image/jpg">
										<img src="dist/img/avatar2.png" id="output_image" class="shadow-sm img-fluid img-responsive" alt="pic" width="140">
									</div>
									<div id="showloanOfficers" class="form-group mb-3"></div>
									
									<button class="btn btn-outline-warning w-50" type="submit" id="borrowerBtn" onclick="addGroupBorrowers(event)">Submit</button>
									<button class="btn btn-outline-warning w-50" type="submit" id="borrowerUpdateBtn" onclick="updateGroupBorrowers(event)" style="display: none;">Update</button>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- Editing Modal -->
				<div class="modal fade" id="modalEdit">
					<div class="modal-dialog modal-lg">
						<div class="modal-content bg-secondary">
							<div class="modal-header">
								<h4 class="modal-title">Edit Group Borrower Information</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form method="post" id="editData">
									
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- End of edit modal -->
				<!-- <button type="button" class="btn btn-danger toastrDefaultError">Toast</button> -->
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
		    // select
		    $('.select2').select2();
		});

		addGroupBorrowers = function() {
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'borrowers/submitGroupBorrower';
			var groupBorrowerForm = document.getElementById('groupBorrowerForm');
			xhr.open("POST", url, true);
			var group_name = document.getElementById('group_name').value;
			var data = new FormData(groupBorrowerForm);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					if (xhr.responseText === 'done') {
						successNow(group_name + ' added to the database');
						// alert("Borrower Submited");
						// window.location = "view_borrowers";
						// $("#groupBorrowerForm")[0].reset();
						setTimeout(function(){
							location.reload();
						},1500);
						document.getElementById("borrowerBtn").innerHTML = 'Submit';

					}else{
						// alert(xhr.responseText);
						errorNow(xhr.responseText);
						// $("#groupBorrowerForm")[0].reset();
						document.getElementById("borrowerBtn").innerHTML = 'Submit';
						return false;
					}
					
				}
			}
			xhr.send(data);
			document.getElementById("borrowerBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}


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
			// $('.toastrDefaultError').click(function() {
	  //     		toastr.error("Error");
	  //     		toastr.options.progressBar = true;
	  //     		toastr.options.positionClass = "toast-top-center";

	  //  //    		toastr.options = {
			// 	// 	"closeButton": false,
			// 	// 	"debug": false,
			// 	// 	"newestOnTop": false,
			// 	// 	"progressBar": false,
			// 	// 	"positionClass": "toast-top-center",
			// 	// 	"preventDuplicates": false,
			// 	// 	"onclick": null,
			// 	// 	"showDuration": "300",
			// 	// 	"hideDuration": "1000",
			// 	// 	"timeOut": "5000",
			// 	// 	"extendedTimeOut": "1000",
			// 	// 	"showEasing": "swing",
			// 	// 	"hideEasing": "linear",
			// 	// 	"showMethod": "fadeIn",
			// 	// 	"hideMethod": "fadeOut"
			// 	// }
	  //   	});

	  	function fetchLoanGroupOfficers(branch_id){
			if (branch_id === "") {
				alert("Select Branch name");
				return false;
			}else{
				// we make an ajax call to find collecctors to assign the work to.
				$.ajax({
					url:'borrowers/fetchBranchMembers?<?php echo time()?>',
					method:"post",
					data:{branch_id:branch_id},
					success:function(data){
						$("#showloanOfficers").html("<h4 class='mb-2'>Add Loan Officers</h4>"+ data);
					}

				})
			}
		}
		fetchLoanGroupOfficers("<?php echo $BRANCHID?>");


		function fetchLoanGroupBorrowers(branch_id_select){
			if (branch_id_select === "") {
				alert("Select Branch name");
				return false;
			}else{
				// we make an ajax call to find collecctors to assign the work to.
				$.ajax({
					url:'borrowers/fetchBranchMembers?<?php echo time()?>',
					method:"post",
					data:{branch_id_select:branch_id_select},
					success:function(data){
						$("#borrowers_id").html(data);
						$("#group_leader_id").html(data);
					}

				})
			}
		}
		fetchLoanGroupBorrowers("<?php echo $BRANCHID?>");

		var selectImage = document.getElementById('selectImage');
  		var fileInput = document.getElementById('group_photo');
  		selectImage.addEventListener("click", (e) => {
  			$('#group_photo').click();
  		});

		function preview_group_image(event) {
			var reader = new FileReader();
			reader.onload = function(){
				var output = document.getElementById('output_image');
				output.src = reader.result;
			}
			reader.readAsDataURL(event.target.files[0]);
		}
		
	</script>
</body>
</html>