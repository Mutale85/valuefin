<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
	
	$branch_options = $all_branch_options = "";
	$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
	$sql->execute(array($_SESSION['parent_id']));
	$results = $sql->fetchAll();
	
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<?php include("../addon_header.php");?>
	<style>
		img.img-rounded {
			border-radius: 50%;
		}
	</style>
</head>
<?php
	
?>
<body class="hold-transition sidebar-mini layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light">
			<div class="container">
				<div class="card card-primary">
					<div class="card-header">
						<h4 class="card-title">New Personnel Form</h4>
					</div>
					<form class="" method="post" id="adminsForm" enctype="multipart/form-data">
						<div class="card-body">
							<div class="row">
								<div class="form-group col-md-12 mb-3">
									<label for="form">Staff Photo</label>
									<div class="">
										<button class="btn btn-secondary mb-2" type="button" id="selectImage"><i class="bi bi-file-person"></i> Add Image</button>
										<br>
										<input type="file" name="photo" id="photo" class="form-control" onchange="preview_admin_image(event)" style="display: none;">
										<img src="dist/img/avatar5.png" width="120" alt="profile" id="output_image">
									</div>
									<em>Add a clear face of the staff</em>
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
									<label for="form">Phone</label>
									<div class="input-group mb-3">
										<input type="text" name="phoneNumber" id="phoneNumber" class="form-control" onkeyup="getPhone(this.value)">
										<input type="hidden" name="phone" id="phone" class="form-control">
									</div>
									<p id="result"></p>
								</div>
								<div class="form-group col-md-4">
									<label for="form">NRC Number</label>
									<div class="input-group mb-3">
										<span class="input-group-text"><i class="bi bi-file"></i></span>
										<input type="text" name="nrc_number" id="nrc_number" class="form-control" required>
									</div>
								</div>
								<div class="form-group col-md-4">
									<label for="form">NRC Copy</label>
									<div class="input-group mb-3">
										<span class="input-group-text"><i class="bi bi-file-pdf"></i></span>
										<input type="file" name="nrc_copy" id="nrc_copy" class="form-control" required>
									</div>
								</div>
								
								<div class="form-group col-md-2 mb-3">
									<label>Staff Role</label>
									<select class="form-control" name="user_role" id="user_role" required>
										<option value="Loan Officer">Loan Officer</option>
										<option value="Admin">Admin</option>
										<option value="superAdmin">Super Admin</option>
									</select>
								</div>
								<div class="form-group col-md-2">
									<label>Login Permisions</label>
									<select class="form-control" name="staff_permission" id="staff_permission" required>
										<option value="yes">Create Login Password</option>
										<option value="no">Don't Create Login Password</option>
									</select>
								</div>
								<div class="form-group col-md-12">
									<label for="form">Home Address</label>
									<div class="input-group mb-3">
										<span class="input-group-text"><i class="bi bi-map"></i></span>
										<textarea type="text" name="home_address" id="home_address" class="form-control" rows="4"></textarea>
									</div>
								</div>
								<div class="form-group col-md-4 mb-3">
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
							<button class="btn btn-primary " type="submit" id="adminBtn">Create New Staff Account</button>
						</div>
					</form>
				</div>
			</div>
		</section>
	</div>
	<?php include("../addon_footer.php")?>
	<script>
		
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
	        utilsScript: "../intl.17/build/js/utils.js",
	    });

	    function getPhone(phone_number){
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
	<script>
		// ============================== ADD ADMINS ==========================================
		$(document).ready(function() {
			$("#adminsForm").submit(function(event) {
				event.preventDefault(); 
				var adminsForm = document.getElementById('adminsForm');
				var url = 'members/parsers/submitAdmin';
				var formData = new FormData(adminsForm);
				$.ajax({
					type: "POST",
					url: url, 
					data: formData,
					contentType: false,
					processData: false,
					cache:false,
					beforeSend: function() {
						document.getElementById("adminBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
					},
					success: function(response) {
						successToast(response);
						document.getElementById("adminBtn").innerHTML = 'Submit';
					},
					error: function(response) {
						errorToast("An error occurred while submitting the form. "+ response);
						document.getElementById("adminBtn").innerHTML = 'Submit';
					}
					
				});
			});
		});
	</script>
</body>
</html>