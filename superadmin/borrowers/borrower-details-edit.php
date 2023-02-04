<?php 
	require ("../../includes/db.php");
	require ("../addons/tip.php"); 

	if(isset($_GET['applicant-id'])){
		$nrc = base64_decode($_GET['applicant-id']);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include("../addon_header.php");?>
	<!-- <style>
		.select-style {
		    width: 70px;
		    padding: 0;
		    margin: 0;
		    display: inline-block;
		    vertical-align: middle;
		    background: url("http://grumbletum.com/places/arrowdown.gif") no-repeat 100% 30%;
		}
		.select-style select {
		    width: 100%;
		    padding: 0;
		    margin: 0;
		    background-color: transparent;
		    background-image: none;
		    border: none;
		    box-shadow: none;
		    -webkit-appearance: none;
		       -moz-appearance: none;
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
	</style> -->
</head>
<body class="layout-fixed">
	<?php include("../addon_top_min_nav.php")?>

  	<?php include("../addon_side_nav.php")?>
	<?php 
		$country = $phone = '';
		$query = $connect->prepare("SELECT * FROM currencies");
		$query->execute();
		foreach ($query->fetchAll() as $row) {
			$country .= '<option value="'.$row['id'].'">'.$row['country'].'</option>';
		}

		if (isset($_COOKIE['BORROWERID'])) {
			$ID = $_COOKIE['BORROWERID'];
			  $new_client_id = $_COOKIE['BORROWERID'];
		}else{
			$ID = '';
			$new_client_id = '';
		}
	?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
		<section class="content">
			<div class="container-fluid">
				<div class="card bold">
					<div class="card-header">Edit Details</div>
					<div class="card-body">
						<div class="row">
							
							<div class="col-md-12">
								<div class="bg-light text-dark border p-4">
									
									<form class="" method="post" id="clientsDetailsForm" enctype="multipart/form-data">
										
										<div class="card-body-one mb-5" id="body_one">
											<h3 class="text-center mb-5 border p-2 text-primary">Client Details</h3>
											<div class="row">
												<div class="form-group col-md-12 mb-3">
													<div class="p-3">
														<button class="btn btn-warning mb-3" type="button" id="selectImage">Click Here <i class="bi bi-file-person"></i></button><br>
														<input type="file" name="borrower_photo" id="borrower_photo" class="form-control"  style="display: none;" onchange="preview_borrower_image(event)" accept="image/png, image/jpg, image/jpeg">
														<img src="dist/img/avatar2.png" id="output_image" class="shadow-sm img-fluid img-responsive" alt="pic" width="120">
													</div>
													<em>Add a clear face of the applicant</em>
												</div>
												<div class="form-groups col-md-6">
													<label>Title</label>
													<div class="input-group mb-3">
														<span class="input-group-text"><i class="bi bi-circle"></i></span>
														<select id="borrower_title" name="borrower_title" class="form-control">
															<option value="">Select</option>
															<option value="Mr">Mr</option>
															<option value="Mrs">Mrs</option>
															<option value="Miss">Miss</option>
															<option value="Dr">Dr.</option>
															<option value="Reverend">Reverend</option>
															<option value="Professor">Professor</option>
														</select>
													</div>
												</div>
												<div class="form-groups col-md-6">
													<label for="form">First-name</label>
													<div class="input-group mb-3">
														<span class="input-group-text"><i class="bi bi-person"></i></span>
														<input type="text" name="borrower_firstname" id="borrower_firstname" class="form-control">
													</div>
													<input type="hidden" name="edit_id" id="edit_id">
													<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID ?>">
													<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
													<input type="hidden" name="loan_officers_id" id="loan_officers_id" value="<?php echo $_SESSION['user_id']?>">
												</div>
												<div class="form-groups col-md-6">
													<label for="form">Last-name</label>
													<div class="input-group mb-3">
														<span class="input-group-text"><i class="bi bi-person"></i></span>
														<input type="text" name="borrower_lastname" id="borrower_lastname" class="form-control">
													</div>
												</div>
												<div class="border-bottom pb-2 mb-4"></div>
												<div class="form-groups col-md-6">
													<label>Gender</label>
													<div class="input-group mb-3">
														<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
														<select id="borrower_gender" name="borrower_gender" class="form-control">
															<option value="">Select</option>
															<option value="Male">Male</option>
															<option value="Female">Female</option>
														</select>
													</div>
												</div>
												<div class="form-groups col-md-6 mb-3">
													<label for="form">NRC</label>
													<div class="input-group mb-1">
														<span class="input-group-text"><i class="bi bi-file-person"></i></span>
														<input type="text" name="borrower_nrc_number" id="borrower_nrc_number" class="form-control">
													</div>
												</div>
												<div class="form-groups col-md-6 mb-3">											
													<label for="form">Phone</label>
													<input type="tel" id="phone" name="phone" class="form-control" onkeyup="complePhone(this.value)">
													<input type="hidden" name="borrower_phone" id="borrower_phone" class="form-control" readonly>
													<p id="result"></p>
													<p id="error4" style="color: red; display: none;">Enter Your Valid Mobile Number</p>
												</div>
												<div class="form-groups col-md-6">
													<label for="form">Email</label>
													<div class="input-group mb-3">
														<span class="input-group-text"><i class="bi bi-at"></i></span>
														<input type="email" name="borrower_email" id="borrower_email" class="form-control">
													</div>
												</div>
												
												<div class="form-groups col-md-6">
													<label for="form">Date of Birth</label>
													<div class="input-group mb-3">
														<span class="input-group-text"><i class="bi bi-calendar"></i></span>
														<input type="text" name="borrower_dateofbirth" id="borrower_dateofbirth" class="form-control" onchange="calculateAge(this.value)">
														<input type="hidden" name="borrower_age" id="borrower_age" class="form-control">
													</div>
												</div>
												<div class="form-groups col-md-6">
													<label for="form">Home Address</label>
													<div class="input-group mb-3">
														<span class="input-group-text"><i class="bi bi-geo"></i></span>
														<textarea type="text" name="borrower_address" id="borrower_address" class="form-control" rows="2"> </textarea>
													</div>
												</div>
												<div class="form-groups col-md-6">
													<label for="form">Name of Business</label>
													<div class="input-group mb-3">
														<span class="input-group-text"><i class="bi bi-at"></i></span>
														<input type="text" name="borrower_business" id="borrower_business" class="form-control">
													</div>
												</div>
												
												<div class="form-groups col-md-6">
													<label for="form">Shop Number</label>
													<div class="input-group mb-3">
														<span class="input-group-text"><i class="bi bi-geo"></i></span>
														<textarea type="text" name="borrower_shop_number" id="borrower_shop_number" class="form-control" rows="2"> </textarea>
													</div>
												</div>
												<div class="form-groups col-md-6">
													<label for="form">Products</label>
													<div class="input-group mb-3">
														<span class="input-group-text"><i class="bi bi-geo"></i></span>
														<textarea type="text" name="borrower_products" id="borrower_products" class="form-control" rows="2"> </textarea>
													</div>
												</div>
												<div class="form-groups col-md-6 mb-3">
													<label for="form">Upload NRC Front</label>
													
													<input type="file" name="borrower_nrc_front" id="borrower_nrc_front" class="form-control" accept="image/png, image/jpg, image/jpeg, application/pdf">
													
												</div>
												<div class="form-groups col-md-6 mb-3">
													<label for="form">Upload NRC Back </label>
													
													<input type="file" name="borrower_nrc_back" id="borrower_nrc_back" class="form-control" accept="image/png, image/jpg, image/jpeg, application/pdf">
													
												</div>
											</div>
											
										</div>
									
										<div class="card-body-three mt-3" style="display: block;" id="body_three">
											<h3 class="text-center mb-5 border p-2 text-primary"> Next of Kin Details </h3>
											<div class="row">
												<div class="form-group col-md-6">
													<label>Full Names</label>
													<input type="text" name="next_of_kin_fullnames" id="next_of_kin_fullnames" class="form-control" placeholder="Full Names">
												</div>
												<div class="form-group col-md-6">
													<label>NRC No:</label>
													<input type="text" name="next_of_kin_nrc" id="next_of_kin_nrc" class="form-control" placeholder="NRC Number">
												</div>
												<div class="form-group col-md-6">
													<label>Phone No.</label>
													<input type="text" name="next_of_kin_phone" id="next_of_kin_phone" class="form-control" placeholder="Phone Number">
												</div>
												<div class="form-group col-md-6">
													<label>Relationship</label>
													<select name="next_of_kin_relationship" id="next_of_kin_relationship" class="form-control" required>
														<option value="">Select</option>
														<option value="Husband">Husband</option>
														<option value="Wife">Wife</option>
														<option value="Father">Father</option>
														<option value="Mother">Mother</option>
														<option value="Brother">Brother</option>
														<option value="Sister">Sister</option>
														<option value="Cousin">Cousin</option>
														<option value="Uncle">Uncle</option>
														<option value="Auntie">Auntie</option>
														<option value="Son">Son</option>
														<option value="Daughter">Daughter</option>
													</select>
												</div>
												<div class="form-group col-md-12">
													<label>Physical Address</label>
													<textarea type="text" name="next_of_kin_address" id="next_of_kin_address" class="form-control" rows="2" placeholder="Enter Address"></textarea>
												</div>
											</div>
											
											<div class="d-flex justify-content-between">
												<div><button class="btn btn-secondary" type="submit" id="submitBtn">Save Details</button></div>
											</div>
										</div>
									</form>
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<?php include("../addon_footer.php")?>
	
	<script>

		// ======================= SCRIPT FOR LOCAL STORAGE AND DISPLAY ============
		var selectImage = document.getElementById('selectImage');
  		var fileInput = document.getElementById('borrower_photo');
  		selectImage.addEventListener("click", (e) => {
  			$('#borrower_photo').click();
  		});

		function preview_borrower_image(event) {
			var reader = new FileReader();
			reader.onload = function(){
				var output = document.getElementById('output_image');
				output.src = reader.result;
			}
			reader.readAsDataURL(event.target.files[0]);
		}

		
		$("#borrower_dateofbirth").datepicker({
			format: 'yyyy-mm-dd',
			autoclose:true,
			changeMonth: true,
      		changeYear: true,
			
			// startDate: '+3d',
		});


		function calculateAge(dob) {
			var today = new Date();
			var birthDate = new Date(dob);
			var age = today.getFullYear() - birthDate.getFullYear();
			var m = today.getMonth() - birthDate.getMonth();
			if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
				age--;
			}
			// return age;
			const borrower_age = document.getElementById('borrower_age').value = age;
		}

		//======================= HIDE SHOW BUTTONS ======================

		function successNow(msg){
			toastr.success(msg);
	      	toastr.options.progressBar = true;
	      	toastr.options.positionClass = "toast-top-center";
	    }

		function errorNow(msg){
			toastr.error(msg);
	      	toastr.options.progressBar = true;
	      	toastr.options.positionClass = "toast-top-center";
	    }
		
		
		 // Telephone number with country code
	    var input = document.querySelector("#phone");
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

	    function complePhone(phone){
			var number = iti.getNumber(intlTelInputUtils.numberFormat.E164);
			var isValid = iti.isValidNumber();
			result = document.querySelector("#result");
			borrower_phone = document.getElementById("borrower_phone");
			if (phone == "") {
				result.textContent = "Add Your Number";
				return false;
			}
			
			if (isValid === true) {
				result.textContent = "Number: " + number + ", is valid";
				borrower_phone.value = number;
				
			}else if(isValid === false){
				result.textContent = "Number: " + number + ", is invalid";
				borrower_phone.value = number;
				
			}

	    }
	    
	</script>

	<script>

		$(document).ready(function() {
			$("#clientsDetailsForm").submit(function(event) {
				event.preventDefault(); //prevent default form submission
				var clientsDetailsForm = document.getElementById('clientsDetailsForm');
				var url = 'borrowers/processing/submitClientEditedDetails';
				var formData = new FormData(clientsDetailsForm);
				$.ajax({
					type: "POST",
					url: url, //server-side script
					data: formData,
					contentType: false,
					processData: false,
					cache:false,
					beforeSend: function() {
						successNow("Uploading files and submitting form...");
						$('#submitBtn').html('Processing...');
					},
					success: function(response) {
						successNow(response);
						$('#submitBtn').html('Save Details');
						_reset();
					},
					error: function(response) {
						errorNow("An error occurred while submitting the form. "+ response);
						$('#submitBtn').html('Save Details');
					}
					
				});
			});
		});
	</script>

	<script>
		// function preview_borrower_image(event) {
		// 	var reader = new FileReader();
		// 	reader.onload = function(){
		// 		var output2 = document.getElementById('output_image2');
		// 		output2.src = reader.result;
		// 	}
		// 	reader.readAsDataURL(event.target.files[0]);
		// }
		function getPersonalDetails(client_nrc){
			$.ajax({
				url:'borrowers/processing/editClientDetails',
				method:'post',
				data:{client_nrc:client_nrc},
				dataType:'Json',
				success:function(data){
					$("#borrower_title").val(data.borrower_title);
					document.getElementById("borrower_firstname").value = data.borrower_firstname;
					document.getElementById("borrower_lastname").value = data.borrower_lastname;
					$("#borrower_email").val(data.borrower_email);
					$("#phone").val(data.borrower_phone);
					$("#borrower_gender").val(data.borrower_gender);
					$("#borrower_nrc_number").val(data.borrower_id);
					$("#borrower_address").val(data.borrower_address);
					$("#borrower_dateofbirth").val(data.borrower_dateofbirth);
					$("#edit_id").val(data.id);
					
					var img = document.getElementById('output_image');
					img.src = 'borrowers/uploads/'+data.borrower_photo;
				}
			})
		}
		getPersonalDetails('<?php echo $nrc?>');

		function getBusinessDetails(client_nrc){
			$.ajax({
				url:'borrowers/processing/editClientBusinessDetails',
				method:'post',
				data:{client_nrc:client_nrc},
				dataType:'Json',
				success:function(data){
					$("#borrower_business").val(data.borrower_business);
					$("#borrower_shop_number").val(data.borrower_shop_number);
					$("#borrower_products").val(data.borrower_products);
					
				}
			})
		}
		getBusinessDetails('<?php echo $nrc?>');

		function getKinDetails(client_nrc){
			$.ajax({
				url:'borrowers/processing/editClientKinDetails',
				method:'post',
				data:{client_nrc:client_nrc},
				dataType:'Json',
				success:function(data){
					
					$("#next_of_kin_fullnames").val(data.next_of_kin_fullnames);
					$("#next_of_kin_address").val(data.next_of_kin_address);
					$("#next_of_kin_nrc").val(data.next_of_kin_nrc);
					$("#next_of_kin_relationship").val(data.next_of_kin_relationship);
					$("#next_of_kin_phone").val(data.next_of_kin_phone);
					$("#next_of_kin_address").val(data.next_of_kin_address);
				}
			})
		}
		getKinDetails('<?php echo $nrc?>');
	</script>

</body>
</html>