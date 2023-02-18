<?php 
	require ("../../includes/db.php");
	require ("../addons/tip.php"); 
?>
<!DOCTYPE html>
<html>
<head>
	<?php include("../addon_header.php");?>
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
				<div class="row">
					
					<div class="col-md-6">
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
												<input type="text" aria-label="First name" name="borrower_firstname" id="borrower_firstname" class="form-control">
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
												<input type="text" aria-label="Last name" name="borrower_lastname" id="borrower_lastname" class="form-control">
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
												<input type="text" aria-label="Last name" name="borrower_nrc_number" id="borrower_nrc_number" class="form-control">
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
											<label for="form">Products</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-geo"></i></span>
												<textarea type="text" name="borrower_shop_number" id="borrower_shop_number" class="form-control" rows="2"> </textarea>
											</div>
										</div>
										<div class="form-groups col-md-6">
											<label for="form">Shop Number</label>
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
					<!-- End of Col-6-Form -->
					<div class="col-sm-6">
						<div class="bg-light p-1">
							<div class="card card-primary card-outline">
								<div class="card-header">
									<h4  class="card-title">Customer Profile</h4>
								</div>
								<div class="card-body box-profile">
									<div class="text-center">
										<img src="dist/img/avatar2.png" id="output_image2" class="profile-user-img img-fluid img-circle" alt="pic" style="width: 120px; height: 120px;">
									</div>
									<h3 class="profile-username text-center"><span id="title_span">Mr </span> <span id="firstname_span">Nina</span> <span id="lastname_span">Mulenga</span> </h3>
									<p class="text-muted text-center"><span id="city_span"></span></p>
									<ul class="list-group list-group-unbordered mb-3">
										<li class="list-group-item">
											<b>Gender</b> <a class="float-right" id="gender_span"></a>
										</li>
										<li class="list-group-item">
											<b>Date of Birth</b> <a class="float-right" id="dateofbirth_span"></a>
										</li>
										<li class="list-group-item">
											<b>NRC</b> <a class="float-right" id="ID_span"></a>
										</li>
										
										<li class="list-group-item">
											<b>Home Address</b> <a class="float-right" id="address_span"></a>
										</li>
										<li class="list-group-item">
											<b>Phone No.</b> <a class="float-right" id="phone_span"></a>
										</li>
										<li class="list-group-item">
											<b>Email</b> <a class="float-right" id="email_span">---</a>
										</li>
										
									</ul>
									<div class="border-top border-dark mt-4 mb-4"></div>
									<h4 class="text-secondary"><span id="working_span">Business Details</span></h4>

									<ul class="list-group list-group-bordered mb-3">
										<li class="list-group-item">
											<b>Business Name:</b> <b id="general_1"></b> <a class="float-right" id="general_span_1"></a>
										</li>
										<li class="list-group-item">
											<b>Products:</b><b id="general_2"></b> <a class="float-right" id="general_span_2"></a>
										</li>
										<li class="list-group-item">
											<b>Shop Location:</b> <b id="general_3"></b> <a class="float-right" id="general_span_3"></a>
										</li>
									</ul>

									<div class="border-top border-dark mt-4 mb-4"></div>
									
									<h4 class="text-secondary"><span id="next_of_kin_span">Next of Kin</span></h4>
									<ul class="list-group list-group-unbordered mb-3">
										<li class="list-group-item">
											<b id="">Full Names</b> <a class="float-right" id="next_of_kin_fullnames_span"></a>
										</li>
										<li class="list-group-item">
											<b id="">NRC</b> <a class="float-right" id="next_of_kin_nrc_span"></a>
										</li>
										<li class="list-group-item">
											<b id="">Relationship</b> <a class="float-right" id="next_of_kin_relationship_span"></a>
										</li>
										<li class="list-group-item">
											<b id="">Phonenumber</b> <a class="float-right" id="next_of_kin_phone_span"></a>
										</li>
										<li class="list-group-item">
											<b id="">Physical Address</b> <a class="float-right" id="next_of_kin_address_span"></a>
										</li>
									</ul>
									
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
				var output2 = document.getElementById('output_image2');
				output.src = reader.result;
				output2.src = reader.result;
			}
			reader.readAsDataURL(event.target.files[0]);
		}

		$(function(){
			
			$("#borrower_title").change(function(){
				var borrower_title = $(this).val();
				if( borrower_title !== ""){
					localStorage.setItem("borrower_title", this.value);
					document.getElementById("title_span").innerHTML = localStorage.getItem("borrower_title");
				}
			});
			document.getElementById("title_span").innerHTML = localStorage.getItem("borrower_title");
			if(localStorage.getItem('borrower_title')){
		        $('#borrower_title').val(localStorage.getItem('borrower_title'));
		    }

			$("#borrower_firstname").keyup(function(){
				var borrower_firstname = $(this).val();
				if( borrower_firstname !== ""){
					localStorage.setItem("borrower_firstname", borrower_firstname);
					document.getElementById("firstname_span").innerHTML = localStorage.getItem("borrower_firstname");
				}else{
					document.getElementById("firstname_span").innerHTML = 'Mutale';
				}
			})
			document.getElementById("borrower_firstname").value = localStorage.getItem("borrower_firstname");
			document.getElementById("firstname_span").innerHTML = localStorage.getItem("borrower_firstname");

			$("#borrower_lastname").keyup(function(){
				var borrower_lastname = $(this).val();
				if( borrower_lastname !== ""){
					localStorage.setItem("borrower_lastname", borrower_lastname);
					document.getElementById("lastname_span").innerHTML = localStorage.getItem("borrower_lastname");
				}else{
					document.getElementById("lastname_span").innerHTML = 'Mulenga';
				}
			})
			document.getElementById("borrower_lastname").value = localStorage.getItem("borrower_lastname");
			document.getElementById("lastname_span").innerHTML = localStorage.getItem("borrower_lastname");

			$("#borrower_gender").change(function(){
				var borrower_gender = $(this).val();
				if( borrower_gender !== ""){
					localStorage.setItem("borrower_gender", this.value);
					document.getElementById("gender_span").innerHTML = localStorage.getItem("borrower_gender");
				}
			});
			document.getElementById("gender_span").innerHTML = localStorage.getItem("borrower_gender");
			if(localStorage.getItem('borrower_gender')){
		        $('#borrower_gender').val(localStorage.getItem('borrower_gender'));
		    }
		    // Country
		    $("#borrower_country").change(function(){
				var borrower_country = $(this).val();
				var country_name = $(this).find('option').filter(':selected').text();
				if( borrower_country !== ""){
					localStorage.setItem("borrower_country", this.value);
					localStorage.setItem("borrower_country_name", country_name);
					document.getElementById("country_span").innerHTML = localStorage.getItem("borrower_country_name");
				}
			});
			
			if(localStorage.getItem('borrower_country')){
				document.getElementById("country_span").innerHTML = localStorage.getItem('borrower_country_name');
		        $('#borrower_country').val(localStorage.getItem('borrower_country'));
		    }

		    // ID 

		    $("#borrower_nrc_number").keyup(function(){
				var borrower_nrc_number = $(this).val();
				if( borrower_nrc_number !== ""){
					localStorage.setItem("borrower_nrc_number", borrower_nrc_number);
					document.getElementById("ID_span").innerHTML = localStorage.getItem("borrower_nrc_number");
				}else{
					document.getElementById("ID_span").innerHTML = 'Add ID';
				}
			})
			
			document.getElementById("borrower_nrc_number").value = localStorage.getItem("borrower_nrc_number");
			document.getElementById("ID_span").innerHTML = localStorage.getItem("borrower_nrc_number");
			// Address
			$("#borrower_address").keyup(function(){
				var borrower_address = $(this).val();
				if( borrower_address !== ""){
					localStorage.setItem("borrower_address", borrower_address);
					document.getElementById("address_span").innerHTML = localStorage.getItem("borrower_address");
				}else{
					document.getElementById("address_span").innerHTML = 'Add Address';
				}
			})
			if(localStorage.getItem("borrower_address")){
				document.getElementById("borrower_address").value = localStorage.getItem("borrower_address");
				document.getElementById("address_span").innerHTML = localStorage.getItem("borrower_address");
			}else{
				document.getElementById("address_span").innerHTML = "Add Physical Address";
			}

			
			// email
			$("#borrower_email").keyup(function(){
				var borrower_email = $(this).val();
				if( borrower_email !== ""){
					localStorage.setItem("borrower_email", borrower_email);
					document.getElementById("email_span").innerHTML = localStorage.getItem("borrower_email");
				}else{
					document.getElementById("email_span").innerHTML = 'Add email';
				}
			})
			if(localStorage.getItem("borrower_email")){
				document.getElementById("borrower_email").value = localStorage.getItem("borrower_email");
				document.getElementById("email_span").innerHTML = localStorage.getItem("borrower_email");
			}else{
				document.getElementById("email_span").innerHTML = "Add Email";
			}

			// phone
			$("#borrower_phone").keyup(function(){
				var borrower_phone = $(this).val();
				if( borrower_phone !== ""){
					localStorage.setItem("borrower_phone", borrower_phone);
					document.getElementById("phone_span").innerHTML = localStorage.getItem("borrower_phone");
				}else{
					document.getElementById("phone_span").innerHTML = 'Add phone';
				}
			})
			if(localStorage.getItem("borrower_phone")){
				document.getElementById("borrower_phone").value = localStorage.getItem("borrower_phone");
				document.getElementById("phone").value = localStorage.getItem("borrower_phone");
				document.getElementById("phone_span").innerHTML = localStorage.getItem("borrower_phone");
			}else{
				document.getElementById("phone_span").innerHTML = "Add phone";
			}

			$("#borrower_dateofbirth").change(function(){
				var borrower_dateofbirth = $(this).val();
				if( borrower_dateofbirth !== ""){
					localStorage.setItem("borrower_dateofbirth", this.value);
					document.getElementById("dateofbirth_span").innerHTML = localStorage.getItem("borrower_dateofbirth");
				}
			});

			document.getElementById("dateofbirth_span").innerHTML = localStorage.getItem("borrower_dateofbirth");
			if(localStorage.getItem('borrower_dateofbirth')){
		        $('#borrower_dateofbirth').val(localStorage.getItem('borrower_dateofbirth'));
		    }

		    // working / employee

		    $("#borrower_business").keyup(function(){
				var borrower_business = $(this).val();
				if( borrower_business !== ""){
					localStorage.setItem("borrower_business", borrower_business);
					document.getElementById("general_span_1").innerHTML = localStorage.getItem("borrower_business");
				}else{
					document.getElementById("general_span_1").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_business")){
				document.getElementById("borrower_business").value = localStorage.getItem("borrower_business");
				document.getElementById("general_span_1").innerHTML = localStorage.getItem("borrower_business");
			}else{
				document.getElementById("general_span_1").innerHTML = "---";
			}

			$("#borrower_shop_number").keyup(function(){
				var borrower_shop_number = $(this).val();
				if( borrower_shop_number !== ""){
					localStorage.setItem("borrower_shop_number", borrower_shop_number);
					document.getElementById("general_span_2").innerHTML = localStorage.getItem("borrower_shop_number");
				}else{
					document.getElementById("general_span_2").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_shop_number")){
				document.getElementById("borrower_shop_number").value = localStorage.getItem("borrower_shop_number");
				document.getElementById("general_span_2").innerHTML = localStorage.getItem("borrower_shop_number");
			}else{
				document.getElementById("general_span_2").innerHTML = "---";
			}

			$("#borrower_products").keyup(function(){
				var borrower_products = $(this).val();
				if( borrower_products !== ""){
					localStorage.setItem("borrower_products", borrower_products);
					document.getElementById("general_span_3").innerHTML = localStorage.getItem("borrower_products");
				}else{
					document.getElementById("general_span_3").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_products")){
				document.getElementById("borrower_products").value = localStorage.getItem("borrower_products");
				document.getElementById("general_span_3").innerHTML = localStorage.getItem("borrower_products");
			}else{
				document.getElementById("general_span_3").innerHTML = "---";
			}
			
			
			// NEXT OF KEEN

			$("#next_of_kin_fullnames").keyup(function(){
				var next_of_kin_fullnames = $(this).val();
				if( next_of_kin_fullnames !== ""){
					localStorage.setItem("next_of_kin_fullnames", next_of_kin_fullnames);
					document.getElementById("next_of_kin_fullnames_span").innerHTML = localStorage.getItem("next_of_kin_fullnames");
				}else{
					document.getElementById("next_of_kin_fullnames_span").innerHTML = '---';
				}
			})
			if(localStorage.getItem("next_of_kin_fullnames")){
				document.getElementById("next_of_kin_fullnames").value = localStorage.getItem("next_of_kin_fullnames");
				document.getElementById("next_of_kin_fullnames_span").innerHTML = localStorage.getItem("next_of_kin_fullnames");
			}else{
				document.getElementById("next_of_kin_fullnames_span").innerHTML = "---";
			}

			$("#next_of_kin_nrc").keyup(function(){
				var next_of_kin_nrc = $(this).val();
				if( next_of_kin_nrc !== ""){
					localStorage.setItem("next_of_kin_nrc", next_of_kin_nrc);
					document.getElementById("next_of_kin_nrc_span").innerHTML = localStorage.getItem("next_of_kin_nrc");
				}else{
					document.getElementById("next_of_kin_nrc_span").innerHTML = '---';
				}
			})
			if(localStorage.getItem("next_of_kin_nrc")){
				document.getElementById("next_of_kin_nrc").value = localStorage.getItem("next_of_kin_nrc");
				document.getElementById("next_of_kin_nrc_span").innerHTML = localStorage.getItem("next_of_kin_nrc");
			}else{
				document.getElementById("next_of_kin_nrc_span").innerHTML = "---";
			}



			$("#next_of_kin_relationship").change(function(){
				var next_of_kin_relationship = $(this).val();
				if( next_of_kin_relationship !== ""){
					localStorage.setItem("next_of_kin_relationship", this.value);
					document.getElementById("next_of_kin_relationship_span").innerHTML = localStorage.getItem("next_of_kin_relationship");
				}
			});
			document.getElementById("next_of_kin_relationship_span").innerHTML = localStorage.getItem("next_of_kin_relationship");
			if(localStorage.getItem('next_of_kin_relationship')){
		        $('#next_of_kin_relationship').val(localStorage.getItem('next_of_kin_relationship_span'));
		    }

		 	
			$("#next_of_kin_phone").keyup(function(){
				var next_of_kin_phone = $(this).val();
				if( next_of_kin_phone !== ""){
					localStorage.setItem("next_of_kin_phone", next_of_kin_phone);
					document.getElementById("next_of_kin_phone_span").innerHTML = localStorage.getItem("next_of_kin_phone");
				}else{
					document.getElementById("next_of_kin_phone_span").innerHTML = '---';
				}
			})
			if(localStorage.getItem("next_of_kin_phone")){
				document.getElementById("next_of_kin_phone").value = localStorage.getItem("next_of_kin_phone");
				document.getElementById("next_of_kin_phone_span").innerHTML = localStorage.getItem("next_of_kin_phone");
			}else{
				document.getElementById("next_of_kin_phone_span").innerHTML = "---";
			}

			$("#next_of_kin_address").keyup(function(){
				var next_of_kin_address = $(this).val();
				if( next_of_kin_address !== ""){
					localStorage.setItem("next_of_kin_address", next_of_kin_address);
					document.getElementById("next_of_kin_address_span").innerHTML = localStorage.getItem("next_of_kin_address");
				}else{
					document.getElementById("next_of_kin_address_span").innerHTML = '---';
				}
			})
			if(localStorage.getItem("next_of_kin_address")){
				document.getElementById("next_of_kin_address").value = localStorage.getItem("next_of_kin_address");
				document.getElementById("next_of_kin_address_span").innerHTML = localStorage.getItem("next_of_kin_address");
			}else{
				document.getElementById("next_of_kin_address_span").innerHTML = "---";
			}
		   
		})

		function _reset() {
			$('#clientsDetailsForm')[0].reset();
			window.localStorage.removeItem("borrower_title");
			window.localStorage.removeItem("borrower_firstname");
			window.localStorage.removeItem("borrower_lastname");
			window.localStorage.removeItem("borrower_email");
			window.localStorage.removeItem("borrower_phone");
			window.localStorage.removeItem("borrower_gender");
			window.localStorage.removeItem("borrower_nrc_number");
			window.localStorage.removeItem("borrower_address");
			window.localStorage.removeItem("borrower_dateofbirth");
			
			window.localStorage.removeItem("borrower_business");
			window.localStorage.removeItem("borrower_shop_number");
			window.localStorage.removeItem("borrower_products");

			window.localStorage.removeItem("next_of_kin_fullnames");
			window.localStorage.removeItem("next_of_kin_address");
			window.localStorage.removeItem("next_of_kin_nrc");
			window.localStorage.removeItem("next_of_kin_relationship");
			window.localStorage.removeItem("next_of_kin_phone");
			window.localStorage.removeItem("next_of_kin_address");
			setTimeout(function(){
				// successNow("Data Saved, Please Add another User");
				location.reload();
			}, 2000);

			// document.cookie = "BORROWERID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
			
		}

		
		$("#borrower_dateofbirth").datepicker({
			format: 'yyyy-mm-dd',
			autoclose:true,
			changeMonth: true,
      		changeYear: true,
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
				localStorage.setItem("borrower_phone", number);
				document.getElementById("phone_span").innerHTML = localStorage.getItem("borrower_phone");
			}else if(isValid === false){
				result.textContent = "Number: " + number + ", is invalid";
				borrower_phone.value = number;
				localStorage.setItem("borrower_phone", number);
				document.getElementById("phone_span").innerHTML = localStorage.getItem("borrower_phone");
			}

			if(localStorage.getItem("borrower_phone")){
				document.getElementById("borrower_phone").value = localStorage.getItem("borrower_phone");
				document.getElementById("phone_span").innerHTML = localStorage.getItem("borrower_phone");
			}else{
				document.getElementById("phone_span").innerHTML = "Add phone";
			}
	    }
	    
	</script>

	<script>

		$(document).ready(function() {
			$("#clientsDetailsForm").submit(function(event) {
				event.preventDefault(); //prevent default form submission
				var clientsDetailsForm = document.getElementById('clientsDetailsForm');
				var url = 'borrowers/processing/submitClientDetails';
				var formData = new FormData(clientsDetailsForm);
				$.ajax({
					type: "POST",
					url: url, //server-side script
					data: formData,
					contentType: false,
					processData: false,
					cache:false,
					beforeSend: function() {
						// successNow("Uploading files and submitting form...");
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
</body>
</html>