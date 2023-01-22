<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 
	
	$option = '';
	$query = $connect->prepare("SELECT * FROM admins WHERE parent_id = ?");
	$query->execute(array($_SESSION['parent_id']));
	// we should check the user branch and show admins who belong to the branches

	foreach ($query->fetchAll() as $row) {
	    $option .= '<option value="'.$row['id'].'">'.$row['firstname'] .' '. $row['lastname'].'</option>';
	}

	$branch_options = "";
	$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ? ");
	$sql->execute(array($_SESSION['parent_id']));
	foreach ($sql->fetchAll() as $row) {
		$branch_options .= '<option value="'.$row['id'].'">'.$row['branch_name'].'</option>';
	}


	$country = '';
	$query = $connect->prepare("SELECT * FROM currencies");
	$query->execute();
	foreach ($query->fetchAll() as $row) {
	    $country .= '<option value="'.$row['id'].'">'.$row['country'].'</option>';
	}
	if (isset($_GET['guarantors_id'])) {
		$guarantors_id = $_GET['guarantors_id'];
		$query = $connect->prepare("SELECT * FROM guarantors WHERE parent_id = ? AND card_id = ?");
		$query->execute(array($_SESSION['parent_id'], $guarantors_id));
		$count = $query->rowCount();
		if ($count > 0) {
			$row = $query->fetch();
			$user_id = $row['id'];
			$branchID = $row['branch_id'];
			$parent_id = $row['parent_id'];
			// $working_status = preg_replace("#[^a-zA-Z]#", " ", ucwords($row['working_status']));
			$working_status = $row['working_status'];
			$firstname = $row['firstname'];
			$lastname = $row['lastname'];
			$email = $row['email'];
			$phone = $row['phone'];
			$dateofbirth    = $row['dateofbirth'];
			$address = $row['address'];
			$city = $row['city'];
			$country = $row['country'];
			$business = $row['business'];
			$gender  = $row['gender'];
			$date_added 	   = $row['date_added'];
			$files = $row['files'];
			$photo = $row['photo'];
			$loan_officers = $row['loan_officers'];
			if ($photo == "") {
				$src = 'dist/img/avatar2.png';
			}else{
				$src = 'fileuploads/'.$photo;
			}
		}
	}

	$sql = $connect->prepare("SELECT  * FROM allowed_branches WHERE staff_id = ? AND parent_id = ? ");
    $sql->execute(array($_SESSION['user_id'], $_SESSION['parent_id']));
    $br_options = "";
    foreach ($sql->fetchAll() as $row) {
      // we get the branch ID, and try to find other people who belong to 
    	$branch_id = $row['branch_id'];
    	if ($branchID == $row['branch_id']) {
    		$br_options .= '<option value="'.$branch_id.'" selected>'.ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id)).'</option>';
    	}else{
    		$br_options .= '<option value="'.$branch_id.'">'.ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id)).'</option>';
    	}
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

      				</div>
      			</div>
      			<!-- borrower form -->
      			<div class="container">
      				<div class="row">
      					<!-- <div class="col-md-1"></div> -->
      					<div class="col-md-12">
      						<div class="card card-primary">
      							<div class="card-header">
      								<h4 class="card-title">Edit Guarantor Details</h4>
      							</div>
      							<div class="card-body">
		      						<form class="" method="post" id="borrowerEditForm" enctype="multipart/form-data">
		      							<div class="form-group mb-3">
		      								<label>Initial Branch : <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $branchID))?></label>
		      								<input type="hidden" name="initial_branch" id="initial_branch" class="form-control" readonly="readonly" value="<?php echo $branchID?>">
		      								<div class="mb-3"></div>
		      								<label>Change Branch</label>
		      								<!-- <select  name="branch_id" id="branch_id" class="select2 form-control" data-placeholder="Select Branch" data-dropdown-css-class="select2-purple" style="width:100%;" required="required">
		      									<option value=""></option>
		      									<?php echo $br_options?>
		      								</select> -->
		      								<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-circle"></i></span>
			      								<select name="branch_id" id="branch_id" class=" form-control" required="required">
			      									<?php echo $br_options?>
			      								</select>
			      							</div>
		      								<!-- <em>Do nothing if you are not changing borrower's branch</em> -->
		      								<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
		      							</div>
										<div class="form-group">
											<label for="form">Firstname</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-person"></i></span>
												<input type="text"  name="firstname" id="firstname" class="form-control" value="<?php echo $firstname?>">
											</div>
											<input type="hidden" name="user_id"  value="<?php echo $user_id?>">
										</div>
										<div class="form-group">
											<label for="form">Lastname</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-person"></i></span>
												<input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo $lastname ?>">
											</div>
										</div>
										<p class="text-white">OR</p>
										<div class="form-group">
											<label for="form">Business Name</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-briefcase"></i></span>
												<input type="text" name="business" id="business" class="form-control" value="<?php echo $business?>">
											</div>
										</div>

										<div class="border-bottom pb-2 mb-4"></div>
										<div class="form-group">
											<label>Gender</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
												<select id="gender" name="gender" class="form-control">
													<option value="<?php echo $gender?>"><?php echo ucwords($gender)?></option>
													<option value="male">Male</option>
													<option value="female">Female</option>
												</select>
											</div>
										</div>
										<div class="form-group mb-3">
											<label for="form">ID No:</label>
											<div class="input-group mb-1">
												<span class="input-group-text"><i class="bi bi-file-person"></i></span>
												<input type="text" name="ID" id="ID" class="form-control" value="<?php echo $guarantors_id?>">
											</div>
											<em>This can be NRC, PASSPORT or the document you deam fit</em>
										</div>
										<div class="form-group">
											<label for="form">Country</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-geo"></i></span>
												<select name="country" id="country" class="form-control">
													<option value="<?php echo $country ?>"><?php echo getCountryName($connect, $country)?></option>
													<?php echo $country;?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="form">City</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-geo"></i></span>
												<input type="text" name="city" id="city" class="form-control" value="<?php echo $city?>">
											</div>
										</div>
										<div class="form-group">
											<label for="form">Home Address</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-geo"></i></span>
												<textarea type="text" name="address" id="address" class="form-control" rows="5" ><?php echo $address?> </textarea>
											</div>
										</div>
										<div class="form-group">
											<label for="form">Email</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-at"></i></span>
												<input type="email" name="email" id="email" class="form-control" value="<?php echo $email?>">
											</div>
										</div>
										<div class="form-group mb-3">
											<label for="form">Phone</label>
											<div class="input-group  mb-2">
												<span class="input-group-text"><i class="bi bi-phone"></i></span>
												<input type="text"  name="phone" id="phone" class="form-control" value="<?php echo $phone?>">
											</div>
											<em>Add country code without the symbol to be able to send SMSs</em>
										</div>
										<div class="form-group">
											<label for="form">Date of Birth</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-calendar"></i></span>
												<input type="text"  name="dateofbirth" id="dateofbirth" class="form-control" value="<?php echo $dateofbirth?>">
											</div>
										</div>
										<div class="form-group">
											<label>Working Status</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
												<select id="working_status" name="working_status" class="form-control">
													<option value="<?php echo $working_status?>"><?php echo $working_status?></option>
													<option value="employee">Employee</option>
													<option value="government_employee">Government Employee</option>
													<option value="private_employee">Private Sector Employee</option>
													<option value="pensioner">Pensioner</option>
													<option value="student">Student</option>
													<option value="owner">Owner</option>
													<option value="unemployeed">Unemployed</option>
													<option value="business_person">Business Person</option>
												</select>
											</div>
										</div>
										<div class="form-group mb-3">
											<label for="form">Guarantor Photo</label>
											<div class=" border p-3">
												<button class="btn btn-warning mb-3" type="button" id="selectImage">Change Image <i class="bi bi-file-person"></i></button><br>
												<input type="file" name="photo" id="photo" class="form-control"  style="display: none;" onchange="preview_image(event)">
												<input type="hidden" name="photo" value="<?php echo $photo?>">
												<!-- <div id="output"></div> -->
												<img src="<?php echo $src?>" id="output_image" class="shadow-sm img-fluid img-responsive" alt="pic" width="140">
											</div>
											<em>Add a clear face of the applicant</em>
										</div>
										<div class="form-group mb-3">
											<label for="form">Guarantor Files</label>
											<div class="border-bottom pb-2">
												<button class="btn btn-outline-secondary mb-3" type="button" id="selectFiles">Change Files <i class="bi bi-files"></i></button>
												<input type="file" name="files[]" id="files" class="form-control" style="display: none;" multiple onchange="updateList()" value="">
												<input type="hidden" name="borrower_files" value="<?php echo $borrower_borrower_files ?>">
												<div id="fileList" class="mb-3"><?php echo guarantorAddedFiles($connect, $user_id, $_SESSION['parent_id'], $BRANCHID)?></div>

											</div>
										</div>
										<div class="mb-4">
											<button type="button" class="btn btn-outline-secondary" id="borrowerEditBtn" onclick="EditBorrower(event)">Save changes</button>
										</div>
									</form>
								</div>
							</div>
      					</div>
      					<!-- <div class="col-md-1"></div> -->
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
		    $('.select2').select2();
		} );
	</script>
	<script>
		$(function(){
			$("#borrower_dateofbirth").datepicker({

				format: 'yyyy/mm/dd'
			});
		})
	</script>
	<script>

		// =========================== find loan officers -==============

		// function fetchLoanOfficers(branch_id){
		// 	if (branch_id === "") {
		// 		alert("Select Branch name");
		// 		return false;
		// 	}else{
		// 		// we make an ajax call to find collecctors to assign the work to.
		// 		document.getElementById('initial_branch').value = branch_id;
		// 		document.getElementById("assigned_officers").value = "";
		// 		// $("#borrowerEditBtn").attr("disabled", "disabled");
		// 		$.ajax({
		// 			url:'borrowers/fetchBranchMembers?<?php echo time()?>',
		// 			method:"post",
		// 			data:{branch_id:branch_id},
		// 			success:function(data){
		// 				$("#showloadOfficers").html(data);
		// 			}
		// 		})
		// 	}
		// }

	  	$(document).ready( function () {
		    $('#myTable').DataTable();
		    $('.select2').select2();
		} );

		$(function(){
			$("#borrower_dateofbirth").datepicker({

				format: 'yyyy-mm-dd'
			});
		})

		var selectImage = document.getElementById('selectImage');
  		var fileInput = document.getElementById('borrower_borrower_photo');
  		selectImage.addEventListener("click", (e) => {
  			$('#borrower_borrower_photo').click();
  		});

		
	    function preview_image(event) {
			var reader = new FileReader();
			reader.onload = function(){
				var output = document.getElementById('output_image');
				output.src = reader.result;
			}
			reader.readAsDataURL(event.target.files[0]);
		}

		document.getElementById('selectFiles').addEventListener("click", (e)=> {
			document.getElementById('borrower_borrower_files').click();
		})

	    updateList = function() {
			var input = document.getElementById('borrower_borrower_files');
			var output = document.getElementById('fileList');

			output.innerHTML = '<ul>';
			for (var i = 0; i < input.files.length; ++i) {
			output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
			}
			output.innerHTML += '</ul>';
		}

		// if ($('.custom-control-input').filter(':checked').length < 1){
  //   		alert("Please Check at least one Check Box");
    		
		// 	// return false;
		// }else{
		// 	$("#borrowerEditBtn").removeAttr("disabled");
		// 	// return false;
		// }

		EditBorrower = function() {
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'borrowers/submitEditedBorrowerData?<?php echo time()?>';
			var borrowerEditForm = document.getElementById('borrowerEditForm');

			
			xhr.open("POST", url, true);
			var data = new FormData(borrowerEditForm);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					if (xhr.responseText === 'done') {
						successNow("Borrower Information Updated");

						$("#modal-primary").modal("hide");

						document.getElementById("borrowerEditBtn").innerHTML = 'Submit';

					}else{
						errorNow(xhr.responseText);
						$("#modal-primary").modal("hide");
						document.getElementById("borrowerEditBtn").innerHTML = 'Submit';
						return false;
					}
					
				}
			}
			xhr.send(data);
			document.getElementById("borrowerEditBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}
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