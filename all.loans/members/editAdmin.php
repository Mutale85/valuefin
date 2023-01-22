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
		/*phone*/
		.select-style select {
		    width: 100%;
		    padding: 0;
		    margin: 0;
		    background-color: transparent;
		    background-image: none;
		    border: none;
		    box-shadow: none;
		    -webkit-appearance: none;
		       -moz-appearance: none; // FF have a bug
		            appearance: none;
		}
		.iti { width: 50%; }
		.intl-tel-input {
		  background-color: black;
		}
		.intl-tel-input .selected-flag {
		  	z-index: 4;
		  	background-color: black;
		}
		.iti__selected-dial-code {
			color: red;
		}
		.intl-tel-input .country-list {
		  	z-index: 5;
		  	background-color: black;
		}
		.input-group .intl-tel-input .form-control {
			border-top-left-radius: 4px;
			border-top-right-radius: 0;
			border-bottom-left-radius: 4px;
			border-bottom-right-radius: 0;
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
      					<div class="col-md-12 mt-4">
      						<div class="d-flex justify-content-between">
      							
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
													<!-- <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" onkeyup="complePhone(this.value)"> -->
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
											
											<div class="form-group col-md-6 mb-3">
												<label>Branch Permission</label>
												<?php echo $branch_options;?>
											</div>
											<div class="form-group col-md-6 mb-3">
												<label>All Branches</label>
												
												<?php 
													foreach ($results as $row) {
														$all_branch_options .= '
																	<div class="form-check">
												                      	<input class="form-check-input" type="checkbox"  name="branchID[]" id="branchID" value="'.$row['id'].'">
												                      	<label class="form-check-label">'.branchName($connect, $parent_id, $row['id']).'</label>
												                    </div>
																'; 
													}
													echo $all_branch_options;
												?>
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

		// var input = document.querySelector("#phoneNumber");
	 //      var iti = intlTelInput(input, {
	 //        autoHideDialCode: true,
	 //        autoPlaceholder: true,
	 //        separateDialCode: true,
	 //        nationalMode: true,
	 //        allowDropdown: true,
	 //        autoPlaceholder: "polite",
	 //        dropdownContainer: document.body,
	 //          geoIpLookup: function(callback) {
	 //            $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
	 //              var countryCode = (resp && resp.country) ? resp.country : "";
	 //              callback(countryCode);
	 //            });
	 //          },
	 //        nationalMode: false,
	 //        placeholderNumberType: "MOBILE",
	 //        preferredCountries: ['zm'],
	 //        separateDialCode: true,
	 //        utilsScript: "intl.17/build/js/utils.js",
	 //    });

	 //    function complePhone(phone_number){
	 //      // var num = iti.getNumber(),
	 //      var number = iti.getNumber(intlTelInputUtils.numberFormat.E164);
	 //      var isValid = iti.isValidNumber();
	 //      result = document.querySelector("#result");
	 //      phone = document.getElementById("phone");
	 //      if (phone_number == "") {
	 //        result.textContent = "Add Your Number";
	 //        return false;
	 //      }
	 //        if (isValid === true) {
	 //          result.textContent = "Number: " + number + ", is valid";
	 //          phone.value = number;
	 //        }else if(isValid === false){
	 //          result.textContent = "Number: " + number + ", is invalid";
	 //          phone.value = number;
	 //        }
	 //    }	
	</script>
</body>
</html>