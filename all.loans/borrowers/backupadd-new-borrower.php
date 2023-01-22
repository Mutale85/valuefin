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


	$country = $phone = '';
	$query = $connect->prepare("SELECT * FROM currencies");
	$query->execute();
	foreach ($query->fetchAll() as $row) {
	    $country .= '<option value="'.$row['id'].'">'.$row['country'].'</option>';
	}

	$query = $connect->prepare("SELECT * FROM countries ORDER BY country_name");
	$query->execute();
	foreach ($query->fetchAll() as $row) {
		extract($row);
	    $phone .= '<option value="'.$dial_code.'">'.$country_name.'('.$dial_code.')</option>';
	}

	function getcCountryName($connect, $id){
		$output = "";
		$query = $connect->prepare("SELECT * FROM currencies WHERE id = ?");
		$query->execute(array($id));
		$row = $query->fetch();
		if ($row) {
			extract($row);
			$output = $country_name;
		}
		return $output;
	}

	if (isset($_COOKIE['BORROWERID'])) {
		$ID = $_COOKIE['BORROWERID'];
	}else{
		$ID = '';
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Borrower - <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?></title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
	<!-- <link rel="stylesheet" href="plugins/dropzone/dropzone.css">	 -->
	<script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
	<link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
	<style>
		
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

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<div class="content-header mt-5">
		      <div class="container-fluid mt-4">
		        <div class="row mb-2 mt-5">
		          <div class="col-sm-6">
		            <h4 class="m-0"><?php echo ucwords(getOrganisationName($connect, $_SESSION['parent_id']))?></h4>
		          </div>
		          <div class="col-sm-6">
		            <ol class="breadcrumb float-sm-right">
		              <li class="breadcrumb-item"><a href="./" id="timeRemaining">Home</a></li>
		              <li class="breadcrumb-item active"><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?> </li>
		            </ol>
		          </div>
		        </div>
		      </div>
		    </div>
			<section class="content ">
      			
      			<!-- borrower form -->
      			<div class="container-fluid">
      				<div class="row">
      					
      					<div class="col-md-6">
      						<div class="bg-light text-dark border p-4">
      							
      							<form class="" method="post" id="personDetailsForm" enctype="multipart/form-data">
									
									<div class="card-body-one mb-5" id="body_one">
										<h4>Personal Details</h4>
		      							<div class="row">
		      								<div class="form-groups col-md-12 mb-3">
												<label for="form">Borrower Photo</label>
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
													<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
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
												<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID ?>">
			      								<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
			      								<input type="hidden" name="savedID" id="savedID" value="<?php echo $ID?>">
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
												<label for="form">ID No:</label>
												<div class="input-group mb-1">
													<span class="input-group-text"><i class="bi bi-file-person"></i></span>
													<input type="text" aria-label="Last name" name="borrower_ID" id="borrower_ID" class="form-control">
												</div>
												<em>This can be NRC, PASSPORT or the document you allow</em>
											</div>
											<div class="form-groups col-md-6">
												<label for="form">Home Address</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-geo"></i></span>
													<textarea type="text" name="borrower_address" id="borrower_address" class="form-control" rows="2"> </textarea>
												</div>
											</div>
											<div class="form-groups col-md-6">
												<label for="form">Country</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-geo"></i></span>
													<select name="borrower_country" id="borrower_country" class="form-control">
														<option value=""></option>
														<?php echo $country;?>
													</select>
												</div>
											</div>
											<div class="form-groups col-md-6">
												<label for="form">City</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-geo"></i></span>
													<input type="text" name="borrower_city" id="borrower_city" class="form-control">
												</div>
											</div>
											
											<div class="form-groups col-md-6">
												<label for="form">Email</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-at"></i></span>
													<input type="email" name="borrower_email" id="borrower_email" class="form-control">
												</div>
											</div>
											<div class="form-groups col-md-6 mb-3">											
												<label for="form">Phone</label>
												<div class="input-group mb-3">
													<input type="tel" id="phone" name="phone" class="form-control" onkeyup="complePhone(this.value)">
													<input type="hidden" name="borrower_phone" id="borrower_phone" class="form-control" readonly>
												</div>
												<p id="result"></p>
												<p id="error4" style="color: red; display: none;">Enter Your Valid Mobile Number</p>
											</div>
											<div class="form-groups col-md-6">
												<label for="form">Date of Birth</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-calendar"></i></span>
													<input type="text" aria-label="dateofbirth" name="borrower_dateofbirth" id="borrower_dateofbirth" class="form-control">
												</div>
											</div>
											<div class="form-groups col-md-6">
												<label>Working Status</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
													<select id="borrower_working_status" name="borrower_working_status" class="form-control borrower_working_status">
														<option value=""></option>
														<option value="Employee">Employee</option>
														<option value="Business">Business Person</option>
													</select>
												</div>
											</div>
											<!-- employeeDetails Modal -->
											<div class="modal fade" id="employeeDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title text-dark">Employers Details</h5>
															<button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
														</div>
														<div class="modal-body">
															
															<div class="form-group">
																<label class="text-dark">Name of Employer</label>
																<input type="text" name="borrower_employer_name" id="borrower_employer_name" class="form-control" placeholder="Employer's Name">
															</div>
															<div class="form-group">
																<label class="text-dark">Employer's Phone</label>
																<input type="text" name="borrower_employer_phone" id="borrower_employer_phone" class="form-control" placeholder="Employer's Name">
															</div>
															<div class="form-group">
																<label class="text-dark">Address of Employer</label>
																<textarea type="text" name="borrower_employer_address" id="borrower_employer_address" class="form-control" placeholder="Employer's Address" rows="2"></textarea>
															</div>
														</div>
														<div class="modal-footer justify-content-between">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Save</button>
														</div>
													</div>
												</div>
											</div>

											<!-- businessDetails Modal -->
											<div class="modal fade" id="businessDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title text-dark">Business Details</h5>
															<button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
														</div>
														<div class="modal-body">
															<div class="row">
																<div class="form-group col-md-12 ">
																	<label class="text-dark" for="form">Business Name</label>
																	<div class="input-group mb-3">
																		<span class="input-group-text"><i class="bi bi-briefcase"></i></span>
																		<input type="text" aria-label="Last name" name="borrower_business" id="borrower_business" class="form-control">
																	</div>
																</div>
																<div class="form-group col-md-12 ">
																	<label class="text-dark">Type of Business</label>
																	<input type="text" name="borrower_business_type" id="borrower_business_type" class="form-control" placeholder="Employer's Name">
																</div>
																<div class="form-group col-md-12 ">
																	<label class="text-dark">Address of Business</label>
																	<textarea type="text" name="borrower_business_address" id="borrower_business_address" class="form-control" placeholder="Employer's Address" rows="2"></textarea>
																</div>
															</div>
														</div>
														<div class="modal-footer justify-content-between">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Save </button>
															<!-- <button type="button" class="btn btn-primary">Save changes</button> -->
														</div>
													</div>
												</div>
											</div>
											<!-- End of Modal -->
										</div>
										<div class="d-flex justify-content-between">
											<!-- <div></div> -->
											<div><button class="btn btn-warning" type="submit" onclick="save_1()" id="borrowerBtn">Save Data</button></div>
										</div>
									</div>
								</form>
								<form method="post" id="bankDetailsForm">
									<div class="card-body-two border p-3 mt-3" style="display: block;" id="body_two">
										<h4 class="">Bank Details</h4>
										<div class="row">
											<div class="form-group col-md-4">
												<label class="text-dark">Bank Name</label>
												<input type="text" name="borrower_bank_name" id="borrower_bank_name" class="form-control" placeholder="Bank Name">
											</div>
											<div class="form-group col-md-4">
												<label class="text-dark">Account Number</label>
												<input type="text" name="borrower_account_number" id="borrower_account_number" class="form-control" placeholder="Account Number">
											</div>
											<div class="form-group col-md-4">
												<label class="text-dark">Branch Name</label>
												<input type="text" name="borrower_branch_name" id="borrower_branch_name" class="form-control" placeholder="Branch Name">
											</div>
											<input type="hidden" name="borrower_ID2" id="borrower_ID2">
										</div>
										<div class="d-flex justify-content-between">
											<!-- <div><button class="btn btn-warning" type="button" onclick="back_to_1()">Back</button></div> -->
											<div><button class="btn btn-warning" type="submit" onclick="save_2(event)" id="bankSave">Save Data</button></div>
										</div>
									</div>
								</form>
								<form method="post" id="next_of_kin_Form">
									<input type="hidden" name="borrower_ID3" id="borrower_ID3">
									<div class="card-body-three border border-warning p-3 mt-3" style="display: nones;" id="body_three">
										<h4 class=""> Next of Kin Details</h4>
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
											<!-- <div><button class="btn btn-warning" type="button" onclick="back_to_2()">Back</button></div> -->
											<div><button class="btn btn-warning" type="submit" onclick="save_3(event)" id="save_3_btn">Save Data</button></div>
										</div>
									</div>
								</form>
								<div class="files_form" style="display: nones;" id="body_four">
									<form action="" class="dropzone" id="filesForm"></form>
									<div class="d-flex justify-content-between">
										<!-- <div><button class="btn btn-warning" type="button" onclick="back_to_3()">Back</button></div> -->
										<button class="btn btn-warning " type="submit" id="startUpload">Save Files</button>
									</div>
								</div>
      						</div>
      					</div>
      					<!-- End of Col-6-Form -->
      					<div class="col-sm-6">
      						<div class="bg-warning p-4">
      							<h4>Customer Profile</h4>
      							<div class="border-top mb-3"></div>
      							
      							<div class="card card-primary card-outline">
					              	<div class="card-body box-profile">
					                	<div class="text-center">
					                 		<img src="dist/img/avatar2.png" id="output_image2" class="profile-user-img img-fluid img-circle" alt="pic" style="width: 120px; height: 120px;">
					                	</div>

					                	<h3 class="profile-username text-center"><span id="title_span">Mr </span> <span id="firstname_span">Nina</span> <span id="lastname_span">Mcintire</span> </h3>
					                	<p class="text-muted text-center"><span id="city_span">Lusaka</span></p>
					                	<ul class="list-group list-group-unbordered mb-3">
					                  		<li class="list-group-item">
					                    		<b>Gender</b> <a class="float-right" id="gender_span">Male</a>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b>DoB</b> <a class="float-right" id="dateofbirth_span">09 July, 1985</a>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b>ID</b> <a class="float-right" id="ID_span">245/78/89</a>
					                  		</li>
					                  		
					                  		<li class="list-group-item">
					                    		<b>Home Address</b> <a class="float-right" id="address_span">Plot number 17, 1st Street, No. 7, Wusakile. Kitwe</a>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b>Country</b> <a class="float-right" id="country_span">Zambia</a>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b>Phone No.</b> <a class="float-right" id="phone_span">260977217191</a>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b>Email</b> <a class="float-right" id="email_span">---</a>
					                  		</li>
					                  		
					                	</ul>
					                	<div class="border-top border-dark mt-4 mb-4"></div>
					                	<h4 class="text-secondary"><span id="working_span">Business Details</span></h4>

					                	<ul class="list-group list-group-bordered mb-3">
					                  		<li class="list-group-item">
					                    		<b id="general_1"></b> <a class="float-right" id="general_span_1"></a>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="general_2"></b> <a class="float-right" id="general_span_2"></a>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="general_3"></b> <a class="float-right" id="general_span_3"></a>
					                  		</li>
					                	</ul>

					                	<div class="border-top border-dark mt-4 mb-4"></div>
					                	<h4 class="text-secondary"><span id="bank_span">Bank Details</span></h4>
					                	<ul class="list-group list-group-unbordered mb-3">
					                  		<li class="list-group-item">
					                    		<b id="">Bank Name</b> <a class="float-right" id="bank_name_span">SABSA</a>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">ACC No.</b> <a class="float-right" id="account_number_span">****9037</a>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">Branch</b> <a class="float-right" id="branch_name_span">East Park Mall</a>
					                  		</li>
					                	</ul>

					                	<div class="border-top border-dark mt-4 mb-4"></div>
					                	<h4 class="text-secondary"><span id="next_of_kin_span">Next of Kin</span></h4>
					                	<ul class="list-group list-group-unbordered mb-3">
					                  		<li class="list-group-item">
					                    		<b id="">Full Names</b> <a class="float-right" id="next_of_kin_fullnames_span">SABSA</a>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">NRC</b> <a class="float-right" id="next_of_kin_nrc_span">90378/0039/1</a>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">Relationship</b> <a class="float-right" id="next_of_kin_relationship_span">Sister</a>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">Phonenumber</b> <a class="float-right" id="next_of_kin_phone_span">Sister</a>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">Physical Address</b> <a class="float-right" id="next_of_kin_address_span">Plot 256. Milandu Avenue. Choma</a>
					                  		</li>
					                	</ul>
					                	<div class="mt-4 d-flex justify-content-between ">
					                		<button class="btn btn-warning" id="addCollateral">Add Collateral</button> 
					                		<button class="btn btn-warning" id="addGuarantor">Add Guarantor</button>
					                		<!-- <button class="btn btn-warning">Issue Loan</button> -->
					                		<a href="borrowers/print-application?applicant_id=?<?php echo base64_encode($_COOKIE['BORROWERID'])?>" target="_blank" class="btn btn-warning">Print Application</a>
					                	</div>
					              	</div>
				            	</div>
				            	 <!-- /.card-body -->
      						</div>
      					</div>
      				</div>
      			</div>
      		</section>
      		<!-- 
      			0974988498
				1.KYC details to include the  following
				-Employment details(Name and address for Employer)
				-For Business owners(type of business)
				-Next of Kin details(name,relationship with client, contact number, physical address)
				-Bank details (bank name,account number,branch)
				-Guarantor details to include (name of guarantor, name of employer, position and contact details.
      		-->
		</div>
		<aside class="control-sidebar control-sidebar-dark"></aside>

		<!-- Add Collateral Data -->
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="modal fade" id="collateralModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">Collateral</h4>
								</div>
								<form class="" method="post" id="collateralForm" enctype="multipart/form-data">
								
									<div class="modal-body mb-5">
										<div class="row">
											<div class="form-group col-md-6">
												<label for="collateral_type">Type</label>
												
													<select class="form-control" name="collateral_type" id="collateral_type">
														<option value="Automobiles">
														Automobiles
														</option>
														<option value="Electronic Items">
														Electronic Items
														</option>
														<option value="Insurance policies">
														Insurance policies
														</option>
														<option value="Investments">
														Investments
														</option>
														<option value="Machinery and equipment">
														Machinery and equipment
														</option>
														<option value="Real estate">
														Real estate
														</option>
														<option value="Valuables and collectibles">
														Valuables and collectibles
														</option>
													</select>
												<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID ?>">
												<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
												<input type="hidden" name="borrower_id" id="borrower_id" value="<?php echo $_COOKIE['BORROWERID']?>">
											</div>
											<div class="form-group col-md-6">
												<label for="product_name">Product name</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-wallet"></i></span>
													<input type="text"  name="product_name" id="product_name" class="form-control">
												</div>
											</div>
											
											<div class="form-group col-md-6">
												<label for="form">Register Date</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-calendar"></i></span>
													<input type="text" name="register_date" id="register_date" class="form-control">
												</div>
											</div>
											
											<div class="form-group col-md-6">
												<label for="product_value">Value</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><?php echo getCurrency($connect, $_SESSION['parent_id']) ?></span>
													<input type="number" step="any" name="product_value" id="product_value" class="form-control" placeholder="Amount">
													<input type="hidden" name="currency" value="<?php echo getCurrency($connect, $_SESSION['parent_id']) ?>">
												</div>
											</div>
											<div class="col-md-12 bg-secondary p-2 mt-5 mb-5 shadow-md">
												<!-- <div class="border-bottom border-primary mb-3 mt-3"></div> -->
												<h4 >Collateral Status</h4>
											</div>
											<div class="form-group col-md-6">
												<label for="status">Where is the Collateral ?</label>
												<select class="form-control" name="product_location" name="product_location">
													<option value="Deposited into Branch">Deposited into Branch</option>
													<option value="Collateral with Borrower">Collateral with Borrower</option>
													<option value="Returned to Borrower">Returned to Borrower</option>
													<option value="Repossession Initiated">Repossession Initiated</option>
													<option value="Repossesed">Repossesed</option>
													<option value="Under Auction">Under Auction</option>
													<option value="Sold">Sold</option>
													<option value="Lost">Lost</option>
												</select>
											</div>
											
											<div class="form-groups col-md-6">
												<label for="form">When?</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-calendar"></i></span>
													<input type="text" name="action_date" id="action_date" class="form-control">
												</div>
											</div>
											<div class="form-group col-md-12">
												<label>Address</label>
												<input type="text" name="address" id="address" class="form-control">
												<em>If collateral is with the borrower, add address where it is located</em>
											</div>
											<div class="col-md-12 bg-secondary p-2 mt-5 mb-5 shadow-md">
												<!-- <div class="border-bottom border-primary mb-3 mt-3"></div> -->
												<h4> Product Details</h4>
											</div>
											<div class="form-group col-md-6">
												<label>Serial #</label>
												<input type="text" name="serial_number" id="serial_number" class="form-control">
											</div>
											<div class="form-group col-md-6">
												<label>Model name </label>
												<input type="text" name="model_name" id="model_name" class="form-control">
											</div>
											<div class="form-group col-md-6">
												<label>Model #</label>
												<input type="text" name="model_number" id="model_number" class="form-control">
											</div>
											<div class="form-group col-md-6">
												<label>Product Color</label>
												<input type="text" name="color" id="color" class="form-control">
											</div>
											<div class="form-group col-md-6">
												<label>Manufature Date</label>
												<input type="text" name="manufature_date" id="manufature_date" class="form-control">
											</div>
											<div class="form-group col-md-6">
												<label>Product condition</label>
												<select class="form-control" name="product_condition" name="product_condition">
													<option value=""></option>
													<option value="Excellent">Excellent</option>
													<option value="Good">Good</option>
													<option value="Fair">Fair</option>
													<option value="Damaged">Damaged</option>
												</select>
											</div>
											<div class="form-group col-md-4">
												<label>Product Description</label>
												<textarea class="form-control" rows="4" name="description" id="description"></textarea>
											</div>
											
											<div class="form-group col-md-4 mb-3">
												<label for="form">Collateral Photo</label>
												<div class=" border p-3">
													<button class="btn btn-warning mb-3" type="button" id="selectCollateralImage">Select Image <i class="bi bi-file-person"></i></button><br>
													<input type="file" name="photo" id="photo" class="form-control"  style="display: none;" onchange="preview_image(event)">
													<img src="dist/img/avatar2.png" id="collateral_image" class="shadow-sm img-fluid img-responsive" alt="pic" width="140">
												</div>
												<em>Add a clear photo of the product</em>
											</div>
											<div class="form-group col-md-4 mb-3">
												<label for="form">Collateral Files</label>
												<div class="border p-3">
													<button class="btn btn-warning" type="button" id="selectFiles">Select Files <i class="bi bi-files"></i></button>
													<input type="file" name="files[]" id="files" class="form-control" style="display: none;" multiple onchange="javascript:updateList()">

													<div id="fileList"></div>
												</div>
												<em>Add reciepts, and any documents to support ownership</em>
											</div>
											<div class="col-md-12 bg-secondary p-2 mt-5 mb-5 shadow-md">
												<!-- <div class="border-bottom border-primary mb-3 mt-3"></div> -->
												<h4 >For Vehicles only </h4>
											</div>
											<div class="form-group col-md-4">
												<label>Registration Number</label>
												<input type="text" name="vehicle_reg_number" id="vehicle_reg_number" class="form-control" placeholder="Reg Number">
											</div>
											<div class="form-group col-md-4">
												<label>Millage</label>
												<input type="text" name="millage" id="millage" class="form-control" placeholder="Millage">
											</div>
											<div class="form-group col-md-4">
												<label>Engine No.</label>
												<input type="text" name="vehicle_engine_num" id="vehicle_engine_num" class="form-control" placeholder="Engine No.">
											</div>

										</div>
									</div>
									<div class="modal-footer mb-0">
										<button class="btn btn-primary w-100" type="submit" id="cBtn" onclick="addCollateral(event)">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- ADD GUARANTORS -->
			<div class="modal fade" id="gurantorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Add Guarantor</h4>
						</div>
						<form class="" method="post" id="guarantorForm" enctype="multipart/form-data">
      						<div class="modal-body">		
								<div class="row">
									<div class="form-group col-12 mb-3">
										<label for="form">Guarantor Photo</label>
										<div class=" border p-3">
											<button class="btn btn-warning mb-3" type="button" id="selectGuarantorImage">Select Image <i class="bi bi-file-person"></i></button><br>
											<input type="file" name="photo_image" id="photo_image" class="form-control"  style="display: none;" onchange="preview_image(event)" accept="image/png, image/jpg, image/jpeg">
											<img src="dist/img/avatar2.png" id="guarantor_image" class="shadow-sm img-fluid img-responsive" alt="pic" width="140">
										</div>
										<em>Add a clear face</em>
									
	      								<input type="hidden" name="borrower_id" id="borrower_id" value="<?php echo $_COOKIE['BORROWERID']?>">
	      								<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID ?>">
	      								<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
	      							</div>
									<div class="form-group col-6">
										<label for="form">Firstname</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-person"></i></span>
											<input type="text" aria-label="First name" name="firstname" id="firstname" class="form-control">
										</div>
									</div>
									<div class="form-group col-6">
										<label for="form">Lastname</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-person"></i></span>
											<input type="text" aria-label="Last name" name="lastname" id="lastname" class="form-control">
										</div>
									</div>
									<div class="form-group col-6">
										<label>Gender</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
											<select id="gender" name="gender" class="form-control" required>
												<option value="">Select</option>
												<option value="Male">Male</option>
												<option value="Female">Female</option>
											</select>
										</div>
									</div>
									<div class="form-group col-6">
										<label for="form">Date of Birth</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-calendar"></i></span>
											<input type="date" name="dateofbirth" id="dateofbirth" class="form-control">
										</div>
									</div>
									<div class="form-group col-6">
										<label for="form">Email</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-at"></i></span>
											<input type="email" name="email" id="email" class="form-control">
										</div>
									</div>
									<div class="form-group col-6 mb-3">
										<label for="form">Phone</label>
										<div class="input-group  mb-2">
											<span class="input-group-text"><i class="bi bi-phone"></i></span>
											<input type="text"  name="phone" id="phone" class="form-control">
										</div>
									</div>
									<div class="form-group col-6 mb-3">
										<label for="form">NRC or Passport</label>
										<div class="input-group mb-1">
											<span class="input-group-text"><i class="bi bi-file-person"></i></span>
											<input type="text" name="identity_number" id="identity_number" class="form-control" required>
										</div>
									</div>
									<div class="form-group col-6">
										<label>Working Status</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
											<select id="working_status" name="working_status" class="form-control">
												<option value=""></option>
												<option value="Employee">Employee</option>
												<option value="Business Person">Business Person</option>
											</select>
										</div>
									</div>
									
									<div class="form-group col-6">
										<label for="form">Country</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-geo"></i></span>
											<select name="country" id="country" class="form-control">
												<option value=""></option>
												<?php echo $country;?>
											</select>
										</div>
									</div>
									<div class="form-group col-6">
										<label for="form">City</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-geo"></i></span>
											<input type="text" name="city" id="city" class="form-control">
										</div>
									</div>
									<div class="form-group col-6">
										<label for="form">Home Address</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-geo"></i></span>
											<textarea type="text" name="address" id="address" class="form-control" rows="2"> </textarea>
										</div>
									</div>
									
									<div class="form-group col-6 mb-3">
										<label for="form">Guarantor Files</label><br>
										<div class="">
											<button class="btn btn-warning" type="button" id="selectGuarantorFiles">Select Files <i class="bi bi-files"></i></button>
											<input type="file" name="guarantor_files[]" id="guarantor_files" class="form-control" style="display: none;" multiple onchange="javascript:updateGuarantorList()">

											<div id="guarantor_fileList"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button class="btn btn-warning btn-lg"  type="submit" id="addBtn" onclick="addGuarantor(event)">Save Changes</button>
							</div>
						</form>
					</div>
				</div>
			</div>

		<!-- END OF GUARANTOR -->
	<?php include("../footer_links.php")?>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	<script src="plugins/toastr/toastr.min.js"></script>
	<!-- <script src="plugins/dropzone/dropzone.js"></script> -->
	
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
			$("#register_date, #action_date, #manufature_date").datepicker({

				format: 'yyyy-mm-dd',
				autoclose:true
			});
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
				// var country_name = $(this).text();
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

		    $("#borrower_ID").keyup(function(){
				var borrower_ID = $(this).val();
				if( borrower_ID !== ""){
					localStorage.setItem("borrower_ID", borrower_ID);
					document.getElementById("ID_span").innerHTML = localStorage.getItem("borrower_ID");
					document.getElementById("borrower_ID2").value = localStorage.getItem("borrower_ID");
					document.getElementById("borrower_ID3").value = localStorage.getItem("borrower_ID");
				}else{
					document.getElementById("ID_span").innerHTML = 'Add ID';
				}
			})
			document.getElementById("borrower_ID2").value = localStorage.getItem("borrower_ID");
			document.getElementById("borrower_ID3").value = localStorage.getItem("borrower_ID");
			document.getElementById("borrower_ID").value = localStorage.getItem("borrower_ID");
			document.getElementById("ID_span").innerHTML = localStorage.getItem("borrower_ID");
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

			// City
			$("#borrower_city").keyup(function(){
				var borrower_city = $(this).val();
				if( borrower_city !== ""){
					localStorage.setItem("borrower_city", borrower_city);
					document.getElementById("city_span").innerHTML = localStorage.getItem("borrower_city");
				}else{
					document.getElementById("city_span").innerHTML = 'Add city';
				}
			})
			if(localStorage.getItem("borrower_city")){
				document.getElementById("borrower_city").value = localStorage.getItem("borrower_city");
				document.getElementById("city_span").innerHTML = localStorage.getItem("borrower_city");
			}else{
				document.getElementById("city_span").innerHTML = "Add Physical Address";
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
		 //    $("#borrower_working_status").change(function(e){
			// 	if($(this).val() === "Employee"){
			// 		$("#employeeDetailsModal").modal("show");
			// 		$("#working_span").html("Employer Details");
			// 	}else if($(this).val() === "Business"){
			// 		$("#businessDetailsModal").modal("show");
			// 		$("#working_span").html("Business Details");
			// 	}
			// })

			$("#borrower_working_status").change(function(){
				var borrower_working_status = $(this).val();
				if($(this).val() === "Employee"){
					$("#employeeDetailsModal").modal("show");
					$("#working_span").html("Employer Details");
				}else if($(this).val() === "Business"){
					$("#businessDetailsModal").modal("show");
					$("#working_span").html("Business Details");
				}
				if( borrower_working_status !== ""){
					localStorage.setItem("borrower_working_status", this.value);
					document.getElementById("working_span").innerHTML = localStorage.getItem("borrower_working_status");
				}
			});
			document.getElementById("working_span").innerHTML = localStorage.getItem("borrower_working_status");
			if(localStorage.getItem('borrower_working_status')){
		        $('#borrower_working_status').val(localStorage.getItem('working_span'));
		    }

		    $("#borrower_employer_name").keyup(function(){
				var borrower_employer_name = $(this).val();
				if( borrower_employer_name !== ""){
					localStorage.setItem("borrower_employer_name", borrower_employer_name);
					document.getElementById("general_span_1").innerHTML = localStorage.getItem("borrower_employer_name");
				}else{
					document.getElementById("general_span_1").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_employer_name")){
				document.getElementById("borrower_employer_name").value = localStorage.getItem("borrower_employer_name");
				document.getElementById("general_span_1").innerHTML = localStorage.getItem("borrower_employer_name");
			}else{
				document.getElementById("general_span_1").innerHTML = "---";
			}

			$("#borrower_employer_phone").keyup(function(){
				var borrower_employer_phone = $(this).val();
				if( borrower_employer_phone !== ""){
					localStorage.setItem("borrower_employer_phone", borrower_employer_phone);
					document.getElementById("general_span_2").innerHTML = localStorage.getItem("borrower_employer_phone");
				}else{
					document.getElementById("general_span_2").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_employer_phone")){
				document.getElementById("borrower_employer_phone").value = localStorage.getItem("borrower_employer_phone");
				document.getElementById("general_span_2").innerHTML = localStorage.getItem("borrower_employer_phone");
			}else{
				document.getElementById("general_span_2").innerHTML = "---";
			}

			$("#borrower_employer_address").keyup(function(){
				var borrower_employer_address = $(this).val();
				if( borrower_employer_address !== ""){
					localStorage.setItem("borrower_employer_address", borrower_employer_address);
					document.getElementById("general_span_3").innerHTML = localStorage.getItem("borrower_employer_address");
				}else{
					document.getElementById("general_span_3").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_employer_address")){
				document.getElementById("borrower_employer_address").value = localStorage.getItem("borrower_employer_address");
				document.getElementById("general_span_3").innerHTML = localStorage.getItem("borrower_employer_address");
			}else{
				document.getElementById("general_span_3").innerHTML = "---";
			}
			
			// business 
			
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

			$("#borrower_business_type").keyup(function(){
				var borrower_business_type = $(this).val();
				if( borrower_business_type !== ""){
					localStorage.setItem("borrower_business_type", borrower_business_type);
					document.getElementById("general_span_2").innerHTML = localStorage.getItem("borrower_business_type");
				}else{
					document.getElementById("general_span_2").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_business_type")){
				document.getElementById("borrower_business_type").value = localStorage.getItem("borrower_business_type");
				document.getElementById("general_span_2").innerHTML = localStorage.getItem("borrower_business_type");
			}else{
				document.getElementById("general_span_2").innerHTML = "---";
			}

			$("#borrower_business_address").keyup(function(){
				var borrower_business_address = $(this).val();
				if( borrower_business_address !== ""){
					localStorage.setItem("borrower_business_address", borrower_business_address);
					document.getElementById("general_span_3").innerHTML = localStorage.getItem("borrower_business_address");
				}else{
					document.getElementById("general_span_3").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_business_address")){
				document.getElementById("borrower_business_address").value = localStorage.getItem("borrower_business_address");
				document.getElementById("general_span_3").innerHTML = localStorage.getItem("borrower_business_address");
			}else{
				document.getElementById("general_span_3").innerHTML = "---";
			}

			// Bank Details 
			$("#borrower_bank_name").keyup(function(){
				var borrower_bank_name = $(this).val();
				if( borrower_bank_name !== ""){
					localStorage.setItem("borrower_bank_name", borrower_bank_name);
					document.getElementById("bank_name_span").innerHTML = localStorage.getItem("borrower_bank_name");
				}else{
					document.getElementById("bank_name_span").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_bank_name")){
				document.getElementById("borrower_bank_name").value = localStorage.getItem("borrower_bank_name");
				document.getElementById("bank_name_span").innerHTML = localStorage.getItem("borrower_bank_name");
			}else{
				document.getElementById("bank_name_span").innerHTML = "---";
			}

			$("#borrower_account_number").keyup(function(){
				var borrower_account_number = $(this).val();
				if( borrower_account_number !== ""){
					localStorage.setItem("borrower_account_number", borrower_account_number);
					document.getElementById("account_number_span").innerHTML = localStorage.getItem("borrower_account_number");
				}else{
					document.getElementById("account_number_span").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_account_number")){
				document.getElementById("borrower_account_number").value = localStorage.getItem("borrower_account_number");
				document.getElementById("account_number_span").innerHTML = localStorage.getItem("borrower_account_number");
			}else{
				document.getElementById("account_number_span").innerHTML = "---";
			}

			$("#borrower_branch_name").keyup(function(){
				var borrower_branch_name = $(this).val();
				if( borrower_branch_name !== ""){
					localStorage.setItem("borrower_branch_name", borrower_branch_name);
					document.getElementById("branch_name_span").innerHTML = localStorage.getItem("borrower_branch_name");
				}else{
					document.getElementById("branch_name_span").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_branch_name")){
				document.getElementById("borrower_branch_name").value = localStorage.getItem("borrower_branch_name");
				document.getElementById("branch_name_span").innerHTML = localStorage.getItem("borrower_branch_name");
			}else{
				document.getElementById("branch_name_span").innerHTML = "---";
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


		//==========================================================================

		Dropzone.autoDiscover = false;

		$(function() {
		    //Dropzone class
		    var accept = ".pdf,.doc,.docx, .jpg, .png, .jpeg";
		    var myDropzone = new Dropzone(".dropzone", {
		        url: "borrowers/uploadFiles.php",
		        paramName: "file",
		        maxFilesize: 10,
		        maxFiles: 10,
		        // acceptedFiles: "image/*,application/pdf, .doc, .docx, .pdf, .xls, .xlsx, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/pdf",
		        acceptedFiles:accept,
		        autoProcessQueue: false
		    });

		    
		    $('#startUpload').click(function(){           
		        myDropzone.processQueue();
		    });
		});
		
		$(document).ready( function () {
		
			$("#borrower_dateofbirth").datepicker({
				format: 'yyyy-mm-dd',
				autoclose:true
			});

			$(".lightmode").click(function(e){
				e.preventDefault();
				localStorage['changeMode'] = "bg-dark";
				var mode = localStorage['changeMode'];
				$(".card").removeClass(mode);
			})
			
		})


		// =========================== find loan officers -==============
		function _reset() {
			$('#personDetailsForm')[0].reset();
			$("#modal-primary").modal("hide");
			location.reload();
		}

		function fetchLoanOfficers(branch_id){
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
						$("#showloadOfficers").html(data);
					}
				})
			}
		}
		// fetchLoanOfficers('<?php echo $BRANCHID;?>');

	  	var input = document.getElementById('borrower_country');
	  	input.onchange = function () {
	    	localStorage['borrower_country'] = this.value;
	    	alert(this.value);
	  	}
	  	document.addEventListener('DOMContentLoaded', function () {
	     	var input = document.getElementById('borrower_country');
	     	if (localStorage['borrower_country']) { 
	         	input.value = localStorage['borrower_country'];
	     	}
	     	input.onchange = function () {
	          	localStorage['borrower_country'] = this.value;
	      	}
	  	});
	   
	   // ================================== SAVING FILES ========================= ------ 
	   	
		
		save_1 = function() {
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'borrowers/submitOne';
			var personDetailsForm = document.getElementById('personDetailsForm');
			var borrower_firstname = document.getElementById('borrower_firstname');
			var borrower_working_status = document.getElementById('borrower_working_status');
			xhr.open("POST", url, true);
			var data = new FormData(personDetailsForm);
			if (borrower_firstname.value === "") {
				errorNow("Names are required");
				borrower_firstname.focus();
				return false;
			}
			if (borrower_working_status.value === "") {
				errorNow("Borrower Working Status is required");
				borrower_working_status.focus();
				return false;
			}
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					successNow(xhr.responseText);
					document.getElementById("borrowerBtn").innerHTML = 'Submit';	
					// $("#body_one").hide();
					// $("#body_two").show();
					localStorage.setItem("ID", "<?php echo $ID?>");
					$("#savedID").val("<?php echo $ID?>");
					document.getElementById("borrowerBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
				}
			}
			xhr.send(data);
			document.getElementById("borrowerBtn").innerHTML = 'Submit Data';
		}
		
		if(localStorage.getItem("ID")){
			document.getElementById("savedID").innerHTML = localStorage.getItem("ID");
		}

		save_2 = function() {
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'borrowers/submitTwo';
			var bankDetailsForm = document.getElementById('bankDetailsForm');
			var borrower_bank_name = document.getElementById('borrower_bank_name');
			var borrower_account_number = document.getElementById('borrower_account_number');
			var borrower_branch_name = document.getElementById('borrower_branch_name');
			xhr.open("POST", url, true);
			var data = new FormData(bankDetailsForm);
			if (borrower_bank_name.value === "") {
				errorNow("Bank name");
				borrower_bank_name.focus();
				return false;
			}
			if (borrower_account_number.value === "") {
				errorNow("Account Number");
				borrower_account_number.focus();
				return false;
			}
			
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					successNow(xhr.responseText);
					document.getElementById("bankSave").innerHTML = 'Submit';	
					// $("#body_two").hide();
					// $("#body_three").show();
				}
			}
			xhr.send(data);
			document.getElementById("bankSave").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}


		save_3 = function() {
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'borrowers/submitThree';
			var next_of_kin_Form = document.getElementById('next_of_kin_Form');
			var next_of_kin_fullnames = document.getElementById('next_of_kin_fullnames');
			var next_of_kin_phone = document.getElementById('next_of_kin_phone');
			xhr.open("POST", url, true);
			var data = new FormData(next_of_kin_Form);
			if (next_of_kin_fullnames.value === "") {
				errorNow("Full names are required");
				next_of_kin_fullnames.focus();
				return false;
			}
			if (next_of_kin_phone.value === "") {
				errorNow("Phone Number is required");
				next_of_kin_phone.focus();
				return false;
			}
			
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					successNow(xhr.responseText);
					document.getElementById("save_3_btn").innerHTML = 'Submit';	
					// $("#body_three").hide();
					// $("#body_four").show();
				}
			}
			xhr.send(data);
			document.getElementById("save_3_btn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}
		


		//=================== SAVING FILES ===============================

		// function back_to_1(){
		// 	$("#body_one").show();
		// 	$("#body_two").hide();
		// }

		// function back_to_2(){
		// 	$("#body_two").show();
		// 	$("#body_three").hide();
		// }

		// function back_to_3(){
		// 	$("#body_three").show();
		// 	$("#body_four").hide();
		// }

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
		
		
		$(document).on("click", ".removeBorrower", function(e){
	    	e.preventDefault();
	    	var borrower_id_number = $(this).attr('id');
	    	var branch_id = $(this).data('branch_id');
	    	var delete_id = $(this).data('id');
	    	var loggedParentId = '<?php echo $_SESSION['parent_id']?>';
	    	if(confirm("Confirm removing borrower? All loan information will also be removed")){
		    	$.ajax({
		    		url: 'borrowers/action',
		    		method:'post',
		    		
		    		data: {borrower_id_number:borrower_id_number, branch_id:branch_id, delete_id:delete_id, loggedParentId:loggedParentId},
		    		
		    		success:function(data){
		    			if(data === 'done'){
		    				successNow("Borrower Removed from the system");
		    				location.reload();
		    			}else{
		    				errorNow("Error Deleting Borrower");
		    			}
		    		}
		    	})
		    }else{
		    	return false;
		    }
	    })

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
	        utilsScript: "intl.17/build/js/utils.js",
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


	    //========================= CREATE COLLATERAL ==================== FUNCTIONS

	    $(function (){
	    	$("#addCollateral").click(function(){
	    		$("#collateralModal").modal('show');
	    	})
	    	$("#addGuarantor").click(function(){
	    		$("#gurantorModal").modal("show");
	    	})
	    })

	   //  var input = document.getElementById('collateral_type');
	  	// input.onchange = function () {
	   //  	localStorage['collateral_type'] = this.value;
	   //  	alert(this.value);
	  	// }
	  	// document.addEventListener('DOMContentLoaded', function () {
	   //   	var input = document.getElementById('collateral_type');
	   //   	if (localStorage['collateral_type']) { 
	   //       	input.value = localStorage['collateral_type'];
	   //   	}
	   //   	input.onchange = function () {
	   //        	localStorage['collateral_type'] = this.value;
	   //    	}
	  	// });
	   
	   // images ------ 
	   	
		var selectCollateralImage = document.getElementById('selectCollateralImage');
  		var fileInput = document.getElementById('photo');
  		selectCollateralImage.addEventListener("click", (e) => {
  			$('#photo').click();
  		});

		function preview_image(event) {
			var reader = new FileReader();
			reader.onload = function(){
				var output = document.getElementById('collateral_image');
				output.src = reader.result;
			}
			reader.readAsDataURL(event.target.files[0]);
		}

		document.getElementById('selectFiles').addEventListener("click", (e)=> {
			document.getElementById('files').click();
		})

	    updateList = function() {
			var input = document.getElementById('files');
			var output = document.getElementById('fileList');

			output.innerHTML = '<ul>';
			for (var i = 0; i < input.files.length; ++i) {
			output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
			}
			output.innerHTML += '</ul>';
		}
	</script>
	<script>		
		addCollateral = function() {
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'borrowers/submitCollateral?<?php echo time()?>';
			var collateralForm = document.getElementById('collateralForm');
			var collateral_type = document.getElementById('collateral_type');
			var product_value = document.getElementById('product_value');
			var product_name = document.getElementById('product_name');
			xhr.open("POST", url, true);
			var data = new FormData(collateralForm);
			if (collateral_type.value === "") {
				errorNow("Collateral type is required");
				collateral_type.focus();
				return false;
			}
			if (product_name.value === "") {
				errorNow("Product name is required");
				product_name.focus();
				return false;
			}
			if (product_value.value === "") {
				errorNow("Product Value is required");
				product_value.focus();
				return false;
			}
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					
					errorNow(xhr.responseText);
					$("#collateralForm")[0].reset();
					document.getElementById("cBtn").innerHTML = 'Submit';
				}
			}
			xhr.send(data);
			document.getElementById("cBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}

		// ============================ GUARANTORS =============================== 

		var selectImage = document.getElementById('selectGuarantorImage');
  		var fileInput = document.getElementById('photo_image');
  		selectImage.addEventListener("click", (e) => {
  			$('#photo_image').click();
  		});

		function preview_image(event) {
			var reader = new FileReader();
			reader.onload = function(){
				var output = document.getElementById('guarantor_image');
				output.src = reader.result;
			}
			reader.readAsDataURL(event.target.files[0]);
		}

		document.getElementById('selectGuarantorFiles').addEventListener("click", (e)=> {
			document.getElementById('guarantor_files').click();
		})

	    updateGuarantorList = function() {
			var input = document.getElementById('guarantor_files');
			var output = document.getElementById('guarantor_fileList');

			output.innerHTML = '<ul>';
			for (var i = 0; i < input.files.length; ++i) {
			output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
			}
			output.innerHTML += '</ul>';
		}
		
		addGuarantor = function() {
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'borrowers/submitGuarantor';
			var guarantorForm = document.getElementById('guarantorForm');
			var firstname = document.getElementById('firstname');
			var identity_number = document.getElementById('identity_number');
			if (firstname.value === "") {
				errorNow("Firstname is required");
				firstname.focus();
				return false;
			}

			if (identity_number.value === "") {
				errorNow("Enter NRC ro ID Number");
				ID.focus();
				return false;
			}

			xhr.open("POST", url, true);
			var data = new FormData(guarantorForm);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					if (xhr.responseText === 'done') {
						successNow(firstname.value + ' added to the database');
						
						setTimeout(function(){
							location.reload();
						}, 1000)

					}else{
						// alert(xhr.responseText);
						errorNow(xhr.responseText);
						// $("#guarantorForm")[0].reset();
						document.getElementById("addBtn").innerHTML = 'Submit';
						return false;
					}
					
				}
			}
			xhr.send(data);
			document.getElementById("addBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}

	</script>
</body>
</html>