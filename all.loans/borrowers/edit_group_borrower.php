<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 
	
	$parent_id = $branchID = $group_name = $id = $group_id = $collectors_name = $group_leader_id = $description = $groupID = $group_photo = "";
	if (isset($_GET['group_id']) && isset($_GET['branch_id'])) {
		$group_id = base64_decode($_GET['group_id']);
		$branch_id = base64_decode($_GET['branch_id']);
		$query = $connect->prepare("SELECT * FROM group_borrowers WHERE group_id = ? AND branch_id = ? AND parent_id = ?");
		$query->execute(array($group_id, $branch_id, $_SESSION['parent_id']));
		$row = $query->fetch();
		if ($row) {
			$group_name = $row['group_name'];
			$group_leader_id =  $row['group_leader_id'];
			$collectors_name =  $row['collectors_name'];
			$description =  $row['description'];
			$parent_id = $row['parent_id'];
			$id = $row['id'];
			$branchID = $row['branch_id'];
			$parent_id = $row['parent_id'];
			$groupID = $row['group_id'];
			$group_photo = $row['group_photo'];
			if ($row['group_photo'] == "") {
				$src = 'dist/img/avatar2.png';
			}else{
				$src = 'fileuploads/'.$row['group_photo'];
			}
			$loan_officers_id = $row['loan_officers_id'];
		}
	}

	$query = $connect->prepare("SELECT * FROM branches WHERE id = ? AND member_id = ?");
	
	$sql = $connect->prepare("SELECT  * FROM allowed_branches WHERE parent_id = ?  GROUP BY branch_id ");
    $sql->execute(array($_SESSION['parent_id']));
    $br_options = "";
    foreach ($sql->fetchAll() as $row) {
      // we get the branch ID, and try to find other people who belong to 
      	$branch_id = $row['branch_id'];
      	$query->execute(array($branch_id, $_SESSION['parent_id']));
      	$row = $query->fetch();
      	if ($row) {
      		$branch_name = $row['branch_name'];
      	}
      	$br_options .= '<option value="'.$branch_id.'">'.$branch_name.'</option>';

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
			<section class="content bg-light">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 border-bottom pb-2 ">
      						<div class="d-flex justify-content-between">
      							<h1 class="h3">Edit Group Borrowers</h1>
      						</div>
      						
      					</div>
      				</div>
      			</div>
      			<div class="container">
      				<div class="row">
      					<div class="col-md-12">
      				
	      						<form class="" method="post" id="groupBorrowerForm" enctype="multipart/form-data">
	      							<h4>Current Branch: <?php echo ucwords(getBranchName($connect, $parent_id, $branchID))?></h4>
	      							<div class="form-group">
	      								<div class="custom-control custom-checkbox">
										   <input class="custom-control-input" type="checkbox" id="customCheckbox" onclick="optionCheck()">
										   <label for="customCheckbox" class="custom-control-label">Transfer Group to Another Branch ?</label>
									    </div>
	      							</div>
	      							<div class="form-group" style="display: none;" id="branch_options">
	      								<input type="hidden" name="initial_branch" id="initial_branch" class="form-control" readonly="readonly" value="<?php echo $branchID?>">
	      								<div class="mb-3"></div>
	      								<label>Change Branch</label>
	      								<select  name="branch_id" id="branch_id" onchange="fetchGroupLoanOfficers(this.value)" class="form-control">
	      									<option value="">Not Changing Branch</option>
	      									<?php echo $br_options?>
	      								</select>
	      								<em>Do nothing if you are not changing Group's branch</em>
	      								<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
	      							</div>
									<div class="form-group">
										<label for="form">Group name</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-people"></i></span>
											<input type="text" name="group_name" id="group_name" class="form-control" value="<?php echo $group_name?>">
										</div>
									</div>
									
									<input type="hidden" aria-label="First name" name="group_id" id="group_id" class="form-control" value="<?php echo $group_id?>" readonly>
	
									<div class="form-group mb-3">
										<label>Borrowers</label>
										<!-- we will also look for other clients who belong to the branch and display them here -->
										<div class="select2-purple">
											<select class="select2" multiple="multiple" name="borrowers_id[]" id="borrowers_id" data-placeholder="Select Borrowers" data-dropdown-css-class="select2-purple" style="width: 100%; ">
												
											</select>
										</div>
									</div>
									<input type="hidden" name="id" id="id" value="<?php echo $id?>">
									
									<div class="border-bottom pb-2 mb-4"></div>
									<div class="form-group mb-3">
										<label>Group Leader</label>
										<!-- we will also look for other clients who belong to the branch and display them here -->
										<select class="select2"  name="group_leader_id" id="group_leader_id" data-placeholder="Select Borrowers" data-dropdown-css-class="select2-purple" style="width: 100%;">
											<option value=""></option>
											<?php echo $option_two;?>
										</select>
									</div>
									
									<div class="form-group">
										<label for="form">Collector Name</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-person"></i></span>
											<input type="text" name="collectors_name" id="collectors_name" class="form-control" value="<?php echo $collectors_name?>">
										</div>
									</div>
									<div class="form-group mb-3">
										<!-- <label>Group Photo</label> -->
										<button class="btn btn-warning mb-3" type="button" id="selectImage">Add Group Photo <i class="bi bi-file-person"></i></button><br>
										<input type="file" name="group_photo" id="group_photo" class="form-control"  style="display: none;" onchange="preview_group_image(event)" accept="image/png, image/jpeg, image/jpg">
										<img src="<?php echo $src?>" id="output_image" class="shadow-sm img-fluid img-responsive" alt="pic" width="140">
										<input type="hidden" name="photo" id="photo" value="<?php echo $group_photo?>">
									</div>
									<div class="form-group">
										<label for="form">Description</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-info-square"></i></span>
											<textarea type="text" name="description" id="description" class="form-control" rows="5"> <?php echo $description ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<div id="fetchLoanOfficers"></div>
										<input type="hidden" name="officers" id="officers" value="<?php echo $loan_officers_id?>">
									</div>
									<button class="btn btn-outline-secondary w-50" type="submit" id="borrowerUpdateBtn" onclick="updateGroupBorrowers(event)">Update</button>
								</form>
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

		updateGroupBorrowers = function() {
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'borrowers/editsubmitedGroupBorrower';
			var groupBorrowerForm = document.getElementById('groupBorrowerForm');
			xhr.open("POST", url, true);
			var group_name = document.getElementById('group_name').value;
			var data = new FormData(groupBorrowerForm);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					if (xhr.responseText === 'done') {
						successNow(group_name + ' Edited and saved to the database');
						// alert("Borrower Submited");
						// window.location = "borrowers/add_group_borrower";
						// $("#groupBorrowerForm")[0].reset();
						document.getElementById("borrowerBtn").innerHTML = 'Update';

					}else{
						// alert(xhr.responseText);
						errorNow(xhr.responseText);
						// $("#groupBorrowerForm")[0].reset();
						document.getElementById("borrowerBtn").innerHTML = 'Update';
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
	    var customCheckbox = document.getElementById("customCheckbox");
	    var text = document.getElementById('branch_options');
	    function optionCheck(){
	    
	    	if (customCheckbox.checked == true){
				text.style.display = "block";
				// confirm("You should also change loan officers with those on the new branch");
			} else {
				text.style.display = "none";
				fetchGroupBorrowersLoanManagers("<?php echo $groupID ?>");
			}
	    }

	    function fetchGroupBorrowers(groupID, branchID){
			$.ajax({
				url:'borrowers/fetchGroupLoanOfficersMembers?<?php echo time()?>',
				method:"post",
				data:{groupID:groupID, branchID},
				success:function(data){
					$("#borrowers_id").html(data);
				}
			})
		}
		fetchGroupBorrowers("<?php echo $groupID ?>", "<?php echo $branchID ?>");


	    function fetchGroupBorrowersLeader(groupID){
	    	var fetchLeader = 'fetchLeader';
			$.ajax({
				url:'borrowers/fetchGroupLoanOfficersMembers?<?php echo time()?>',
				method:"post",
				data:{fetchLeader:fetchLeader, groupID:groupID},
				success:function(data){
					$("#group_leader_id").html(data);
				}
			})
		}
		fetchGroupBorrowersLeader("<?php echo $groupID ?>");

		function fetchGroupBorrowersLoanManagers(the_group_id){
	    	var fetchLoanOfficers = 'fetchLoanOfficers';
	    	var the_branch_id = '<?php echo $branchID?>';
	    	var the_main_id = '<?php echo $id?>';
			$.ajax({
				url:'borrowers/action?<?php echo time()?>',
				method:"post",
				data:{the_group_id:the_group_id, the_branch_id:the_branch_id, the_main_id:the_main_id},
				success:function(data){
					$("#fetchLoanOfficers").html(data);
				}
			})
		}
		fetchGroupBorrowersLoanManagers("<?php echo $groupID ?>");


		function fetchGroupLoanOfficers(branch_id){
			if (branch_id === "") {
				// alert("Select Branch name");
				fetchGroupBorrowersLoanManagers("<?php echo $groupID ?>");
				// return false;
			}else{
				// we make an ajax call to find collecctors to assign the work to.
				$.ajax({
					url:'borrowers/fetchGroupLoanOfficersMembers?<?php echo time()?>',
					method:"post",
					data:{branch_id:branch_id},
					success:function(data){
						$("#fetchLoanOfficers").html(data);
					}

				})
			}
		}

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
		
	</script>
</body>
</html>