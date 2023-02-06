<?php 
  	require ("../includes/db.php");
	require ("../includes/tip.php");
  	
	$option = '';
	$query = $connect->prepare("SELECT * FROM admins WHERE id = ? AND parent_id = ?");
	$staff_id 	= base64_decode($_GET['staff_id']);
	$parent_id 	= $_SESSION['parent_id'];
	$query->execute(array($staff_id, $parent_id));
	$row = $query->fetch();
	if ($row) {
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$email = $row['email'];
		$phone = $row['phonenumber'];
		$user_role = $row['position'];
		if ($row['photo'] == "") {
			$photo = '../../dist/img/avatar.png';
		}else{
			$photo = $row['photo'];
		}

	}
	
	$branch_options = $all_branch_options ="";
	$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
	$sql->execute(array($parent_id));
	$results = $sql->fetchAll();
	

	$branches = array();
	foreach($results as $row) {
	    $branches[] = $row['id'];  
	}

	

	$query = $connect->prepare("SELECT * FROM allowed_branches WHERE staff_id = ? AND parent_id = ? ");
	$query->execute(array($staff_id, $parent_id));
	if ($query->rowCount() > 0) {
		foreach($query->fetchAll() as $row) {
	        $branch_options .= '
				<div class="form-check">
                  	<input class="form-check-input" type="checkbox"  name="branchID[]" id="branchID" value="'.$row['branch_id'].'" checked>
                  	<label class="form-check-label">'.branchName($connect, $parent_id, $row['branch_id']).'</label>
                </div>
			';
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin Members</title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content bg-light">
      			<div class="container mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4">
      						<div class="d-flex justify-content-between">
      							<h4>Edit <?php echo $firstname?> Info</h4>
      						</div>
      					</div>
      				</div>
      			</div>
      			<div class="container mb-5">
      				<div class="row">
  						<div class="col-md-12 mb-5">
  							<div class="card card-primary">
  								<div class="card-header">
  									<h4 class="card-title"><?php echo getStaffMemberNames($connect, $staff_id, $parent_id)?></h4>
  								</div>
	  							<form class="" method="post" id="adminsForm" enctype="multipart/form-data">
		  							<div class="card-body">
		  								<div class="row">
											<div class="form-group col-md-12 mb-3">
												<label for="form">Staff Photo</label>
												<div class="">
													<button class="btn btn-outline-secondary mb-3" type="button" id="selectImage">Change Image <i class="bi bi-file-person"></i></button><br>
													<input type="file" name="photo" id="photo" class="form-control" onchange="preview_admin_image(event)" style="display: none;">
													<img src="members/adminphotos/<?php echo $photo?>" width="150" alt="profile" id="output_image">
												</div>
												<em>Add Employees clear face</em>
												<input type="hidden" name="photo_hidden" id="photo_hidden" value="<?php echo $photo?>">
												<input type="hidden" name="staff_id" id="staff_id" value="<?php echo $staff_id?>">
												<input type="hidden" name="parent_id" id="parent_id" value="<?php echo($_SESSION['parent_id'])?>">
											</div>
											<div class="form-group col-md-6">
												<label for="form">Firstname</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-people"></i></span>
													<input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo $firstname?>">
												</div>
											</div>
											<div class="form-group col-md-6">
												<label for="form">Lastname</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-people"></i></span>
													<input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo $lastname?>">
												</div>
											</div>
											
											<div class="form-group col-md-6">
												<label for="form">Email</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-at"></i></span>
													<input type="text" name="email" id="email" class="form-control" value="<?php echo $email?>">
												</div>
											</div>
											<div class="form-group col-md-6 mb-3">
												<label>Staff Roll</label>
												<?php 
													$queryPos = $connect->prepare("SELECT * FROM `positions` WHERE  parent_id = ?");
					      							$queryPos->execute(array($_SESSION['parent_id']));
					      							
												?>
													<select class="form-control"  name="staff_role" id="staff_role" required>
														<option value="<?php echo $user_role ?>" selected><?php echo $user_role ?></option>
														<option value="Admin">Admin</option>
													<?php
														if ($queryPos->rowCount() > 0) {
					      									foreach ($queryPos->fetchAll() as $row) {
					      										extract($row);
													?>
															<option value="<?php echo $title?>"><?php echo $title?></option>
													<?php 
															}
														}
													?>
												</select>
											</div>
											<div class="form-group col-md-6">
												<label for="form">Phone</label>
												<div class="input-group mb-3">
													<input type="text" name="phone" id="phone" class="form-control" value="<?php echo $phone?>">
												</div>
												<p id="result"></p>
											</div>
											<div class="form-group col-md-6">
												<label>Staff Permisions</label>
												<select class="form-control" name="staff_permission" id="staff_permission" required>
													<option value="">---Select---</option>
													<option value="yes">Create Login Password</option>
													<option value="no">Don't Create Login Password</option>
												</select>
											</div>
											
											<div class="form-group col-md-12 mb-3">
												<label>Branch Permission</label>
												<table class="table table-borderless table-sm">
													<?php echo getAllowedBranches($connect, $parent_id, $staff_id);?>
												</table>
												
											</div>
											
										</div>
										
									</div>
									<div class="card-footer">
										<button class="btn btn-primary" type="submit" id="adminUpdateBtn" onclick="updateAdmins(event)">Update</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
      		</section>
		</div>
		<aside class="control-sidebar control-sidebar-dark"></aside>
	</div>
	<?php include("../footer_links.php")?>
	<script src="plugins/toastr/toastr.min.js"></script>
	<script>
		updateAdmins = function() {
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'members/editStaff';
			var adminsForm = document.getElementById('adminsForm');
			xhr.open("POST", url, true);
			var firstname = document.getElementById('firstname').value;
			var data = new FormData(adminsForm);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					if (xhr.responseText === 'done') {
						successNow(firstname + ' updated and added to the database');
						$("#adminsForm")[0].reset();
						document.getElementById("adminBtn").innerHTML = 'Update';
					}else{
						errorNow(xhr.responseText);
						document.getElementById("adminBtn").innerHTML = 'Update';
						return false;
					}
					
				}
			}
			xhr.send(data);
			document.getElementById("adminBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
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

	    var selectImage = document.getElementById('selectImage');
		var fileInput = document.getElementById('photo');
		selectImage.addEventListener("click", (e) => {
			$('#photo').click();
		});

		function preview_admin_image(event) {
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