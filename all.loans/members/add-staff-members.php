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
		.iti { width: 100%; }
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
<?php
	
?>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content bg-light mt-5">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 pb-2 d-flex justify-content-between">
  							
  						</div>
      				</div>
      			</div>
      			
      			
				<!-- Editing Modal -->
				<div class="container">
					<div class="card card-primary">
						<div class="card-header">
							<h4 class="card-title">New Employee Form</h4>
						</div>
						<form class="" method="post" id="adminsForm" enctype="multipart/form-data">
							<div class="card-body">
								<div class="row">
									<div class="form-group col-md-12 mb-3">
										<label for="form">Staff Photo</label>
										<div class="">
											<button class="btn btn-secondary mb-2" type="button" id="selectImage">Select Image <i class="bi bi-file-person"></i></button>
											<br>
											<input type="file" name="photo" id="photo" class="form-control" onchange="preview_admin_image(event)" style="display: none;">
											<img src="dist/img/avatar5.png" width="150" alt="profile" id="output_image">
										</div>
										<em>Add a clear face of the admin</em>
									</div>
									<input type="hidden" name="parent_id" id="parent_id" value="<?php echo($_SESSION['parent_id'])?>">
									<div class="form-group col-md-4">
										<label for="form">Firstname</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-people"></i></span>
											<input type="text" name="firstname" id="firstname" class="form-control">
										</div>
									</div>
									<div class="form-group col-md-4">
										<label for="form">Lastname</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-people"></i></span>
											<input type="text" name="lastname" id="lastname" class="form-control">
										</div>
									</div>
									
									<div class="form-group col-md-4">
										<label for="form">Email</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-at"></i></span>
											<input type="text" name="email" id="email" class="form-control">
										</div>
									</div>
									<div class="form-group col-md-4">
										<label for="form">Phone</label>
										<div class="input-group mb-3">
											<input type="text" name="phoneNumber" id="phoneNumber" class="form-control" onkeyup="complePhone(this.value)">
											<input type="hidden" name="phone" id="phone" class="form-control">
										</div>
										<p id="result"></p>
									</div>
									
									<div class="form-group col-md-4 mb-3">
										<label>Staff Role</label>
										<?php 
											$queryPos = $connect->prepare("SELECT * FROM `positions` WHERE  parent_id = ?");
			      							$queryPos->execute(array($_SESSION['parent_id']));
			      							
										?>
										<select class="form-control"  name="staff_role" id="staff_role" required>
											<option value=""></option>
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
										<em>To add more positions <a href="members/positions">Click Here</a></em>
										<p>Admin has the exlusive rights to make changes to everything in the system</p>
									</div>
									<div class="form-group col-md-4">
										<label>Staff Permisions</label>
										<select class="form-control" name="staff_permission" id="staff_permission" required>
											<option value=""></option>
											<option value="yes">Create Login Password</option>
											<option value="no">Don't Create Login Password</option>
										</select>
									</div>
									<div class="form-group col-md-6 mb-3">
										<label>Branch Permission</label>
											
										<?php 
										foreach ($results as $row) {
												$all_branch_options .= '
															<div class="form-check">
										                      	<input class="form-check-input" type="checkbox"  name="branches[]" id="branches" value="'.$row['id'].'">
										                      	<label class="form-check-label">'.$row['branch_name'].'</label>
										                    </div>
														'; 
											}
											echo $all_branch_options;
										?>

										<em>These are the branches the staff will be able to see.</em>
										<p><em>To add more Branches <a href="members/branches"> Click Here</a></em></p>
									</div>
									
								</div>
								
							</div>
							<div class="card-footer justify-content-between">
								<button class="btn btn-primary btn-lg " type="submit" id="adminBtn" onclick="addAdmin(event)">Submit</button>
							</div>
						</form>
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
				format: 'yyyy-mm-dd',
				autoclose:true
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
		

	//============================================================== photo preview ============================----
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
	

	// ============================== ADD ADMINS ==========================================

	addAdmin = function() {
		event.preventDefault();
		var xhr = new XMLHttpRequest();
		var url = 'members/addStaff';
		var adminsForm = document.getElementById('adminsForm');
		xhr.open("POST", url, true);
		var firstname = document.getElementById('firstname').value;
		var data = new FormData(adminsForm);
		xhr.onreadystatechange = function(){
			if (xhr.readyState == 4 && xhr.status == 200) {
			
					successNow(firstname + ' added to the database');
					$("#adminsForm")[0].reset();
					// manageStaff();
					document.getElementById("adminBtn").innerHTML = 'Submit';
				
			}
		}
		xhr.send(data);
		document.getElementById("adminBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
	}

	// ======================== display admins ------------

	function manageStaff(){
		var xhr = new XMLHttpRequest();
		var url = 'members/fetchStaffs?<?php echo time()?>';
		
		xhr.open("POST", url, true);
		var branch_name = document.getElementById('branch_name').value;
		var data = 'parent_id=<?php echo $_SESSION['parent_id']?>';
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.onreadystatechange = function(){
			if (xhr.readyState == 4 && xhr.status == 200) {
				document.getElementById('fetchStaffs').innerHTML = xhr.responseText
			}
		}
		xhr.send(data);
	}
	// manageStaff();
	$.extend( true, $.fn.dataTable.defaults, {
	    "searching": true,
	    "ordering": false
	} );


		var input = document.querySelector("#phoneNumber");
	      var iti = intlTelInput(input, {
	        autoHideDialCode: true,
	        autoPlaceholder: true,
	        separateDialCode: true,
	        nationalMode: true,
	        allowDropdown: true,
	        autoPlaceholder: "polite",
	        dropdownContainer: document.body,
	          geoIpLookup: function(callback) {
	            $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
	              var countryCode = (resp && resp.country) ? resp.country : "";
	              callback(countryCode);
	            });
	          },
	        nationalMode: false,
	        placeholderNumberType: "MOBILE",
	        preferredCountries: ['zm'],
	        separateDialCode: true,
	        utilsScript: "intl.17/build/js/utils.js",
	    });

	    function complePhone(phone_number){
	      // var num = iti.getNumber(),
	      var number = iti.getNumber(intlTelInputUtils.numberFormat.E164);
	      var isValid = iti.isValidNumber();
	      result = document.querySelector("#result");
	      phone = document.getElementById("phone");
	      if (phone_number == "") {
	        result.textContent = "Add Your Number";
	        return false;
	      }
	        if (isValid === true) {
	          result.textContent = "Number: " + number + ", is valid";
	          phone.value = number;
	        }else if(isValid === false){
	          result.textContent = "Number: " + number + ", is invalid";
	          phone.value = number;
	        }
	    }
	</script>
</body>
</html>