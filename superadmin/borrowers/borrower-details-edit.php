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
			$output = $country;
		}
		return $output;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Borrowers Info - <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?></title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
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

		.btn:hover {
  			box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
		}


		/* CSS */
		.button-59 {
			align-items: center;
			background-color: #fff;
			border: 2px solid #000;
			box-sizing: border-box;
			color: #000;
			cursor: pointer;
			display: inline-flex;
			fill: #000;
			font-family: Inter,sans-serif;
			font-size: 16px;
			font-weight: 600;
			height: 48px;
			justify-content: center;
			letter-spacing: -.8px;
			line-height: 24px;
			min-width: 140px;
			outline: 0;
			padding: 0 17px;
			text-align: center;
			text-decoration: none;
			transition: all .3s;
			user-select: none;
			-webkit-user-select: none;
			touch-action: manipulation;
		}

		.button-59:focus {
		  	color: #171e29;
		}

		.button-59:hover {
		  	border-color: #06f;
		  	color: #06f;
		  	fill: #06f;
		}

		.button-59:active {
		  	border-color: #06f;
		  	color: #06f;
		  	fill: #06f;
		}

		/*@media (min-width: 768px) {
		  .button-59 {
		    min-width: 170px;
		  }
		}*/


		/* CSS */
		.button-23 {
		  background-color: #FFFFFF;
		  border: 1px solid #222222;
		  border-radius: 8px;
		  box-sizing: border-box;
		  color: #222222;
		  cursor: pointer;
		  display: inline-block;
		  font-family: Circular,-apple-system,BlinkMacSystemFont,Roboto,"Helvetica Neue",sans-serif;
		  font-size: 16px;
		  font-weight: 600;
		  line-height: 20px;
		  margin: 0;
		  outline: none;
		  padding: 13px 23px;
		  position: relative;
		  text-align: center;
		  text-decoration: none;
		  touch-action: manipulation;
		  transition: box-shadow .2s,-ms-transform .1s,-webkit-transform .1s,transform .1s;
		  user-select: none;
		  -webkit-user-select: none;
		  width: auto;
		}

		.button-23:focus-visible {
		  box-shadow: #222222 0 0 0 2px, rgba(255, 255, 255, 0.8) 0 0 0 4px;
		  transition: box-shadow .2s;
		}

		.button-23:active {
		  background-color: #F7F7F7;
		  border-color: #000000;
		  transform: scale(.96);
		}

		.button-23:disabled {
		  border-color: #DDDDDD;
		  color: #DDDDDD;
		  cursor: not-allowed;
		  opacity: 1;
		}
	</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<?php
			if (isset($_GET['applicant-id'])) {
				$branch_id =  base64_decode($_COOKIE['SelectedBranch']);
				$applicant_id = base64_decode($_GET['applicant-id']);
				$_SESSION['applicant_id'] = $applicant_id;
				$parent_id = $_SESSION['parent_id'];
				$query = $connect->prepare("SELECT * FROM borrowers_details WHERE id = ? AND branch_id = ? AND parent_id = ?");
				$query->execute(array($applicant_id, $branch_id,  $parent_id));
				$row = $query->fetch();
				if ($row) {
					extract($row);
		?>
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
      					
      					<div class="col-md-8">
      						<div class="bg-light text-dark border p-4">
      							
      							<form class="" method="post" id="personDetailsForm" enctype="multipart/form-data">
									
									<div class="card-body-one mb-5" id="body_one">
										<h3 class="text-center mb-5 border p-2 text-primary">Personal Details - Step 1 of 5</h3>
		      							<div class="row">
		      								<div class="form-group col-md-12 mb-3">
												<div class="p-3">
													<button class="btn btn-warning mb-3" type="button" id="selectImage">Click Here <i class="bi bi-file-person"></i></button><br>
													<input type="file" name="borrower_photo" id="borrower_photo" class="form-control"  style="display: none;" onchange="preview_borrower_image(event)" accept="image/png, image/jpg, image/jpeg">
													<img src="fileuploads/<?php echo $borrower_photo ?>" id="output_image" class="shadow-sm img-fluid img-responsive" alt="pic" width="120">
												</div>
												<em>Add a clear face of the applicant</em>
											</div>
											<div class="form-groups col-md-6">
												<label>Title</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
													<select id="borrower_title" name="borrower_title" class="form-control">
														<option value="<?php echo $borrower_title?>" selected><?php echo $borrower_title?></option>
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
													<input type="text" name="borrower_firstname" id="borrower_firstname" class="form-control" value="<?php echo $borrower_firstname?>">
												</div>
												<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID ?>">
			      								<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
			      								<input type="hidden" name="applicant_id" id="applicant_id" value="<?php echo $applicant_id?>">
											</div>
											<div class="form-groups col-md-6">
												<label for="form">Last-name</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-person"></i></span>
													<input type="text" name="borrower_lastname" id="borrower_lastname" class="form-control" value="<?php echo  $borrower_lastname?>">
												</div>
											</div>
											<div class="border-bottom pb-2 mb-4"></div>
											<div class="form-groups col-md-6">
												<label>Gender</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
													<select id="borrower_gender" name="borrower_gender" class="form-control">
														<option value="<?php echo $borrower_gender ?>" selected><?php echo $borrower_gender ?></option>
														<option value="Male">Male</option>
														<option value="Female">Female</option>
													</select>
												</div>
											</div>
											<div class="form-groups col-md-6 mb-3">
												<label for="form">NRC / PASSPORT</label>
												<div class="input-group mb-1">
													<span class="input-group-text"><i class="bi bi-file-person"></i></span>
													<input type="text" name="borrower_ID" id="borrower_ID" class="form-control" value="<?php echo $borrower_ID?>">
												</div>
											</div>
											<div class="form-groups col-md-6">
												<label for="form">Email</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-at"></i></span>
													<input type="email" name="borrower_email" id="borrower_email" class="form-control" value="<?php echo  $borrower_email?>">
												</div>
											</div>
											<div class="form-groups col-md-6 mb-3">											
												<label for="form">Phone</label>
												<div class="input-group mb-3">
													<input type="tel" id="phone" name="phone" class="form-control" onkeyup="complePhone(this.value)" value="<?php echo  $borrower_phone?>">
													<input type="hidden" name="borrower_phone" id="borrower_phone" class="form-control" readonly value="<?php echo  $borrower_phone?>">
												</div>
												<p id="result"></p>
												<p id="error4" style="color: red; display: none;">Enter Your Valid Mobile Number</p>
											</div>
											<div class="form-groups col-md-6">
												<label for="form">Home Address</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-geo"></i></span>
													<textarea type="text" name="borrower_address" id="borrower_address" class="form-control" rows="2"><?php echo  $borrower_address?></textarea>
												</div>
											</div>
											<div class="form-groups col-md-6">
												<label for="form">Country</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-geo"></i></span>
													<select name="borrower_country" id="borrower_country" class="form-control">
														<option value="<?php echo $borrower_country ?>" selected><?php echo getcCountryName($connect, $borrower_country) ?></option>
														<?php echo $country;?>
													</select>
												</div>
											</div>
											<div class="form-groups col-md-6">
												<label for="form">City</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-geo"></i></span>
													<input type="text" name="borrower_city" id="borrower_city" class="form-control" value="<?php echo $borrower_city ?>">
												</div>
											</div>
											<div class="form-groups col-md-6">
												<label for="form">Date of Birth</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-calendar"></i></span>
													<input type="text"  name="borrower_dateofbirth" id="borrower_dateofbirth" class="form-control" value="<?php echo $borrower_dateofbirth ?>">
												</div>
											</div>
											<div class="form-groups col-md-6">
												<label>Working Status</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
													<select id="borrower_working_status" name="borrower_working_status" class="form-control borrower_working_status">
														<option value="<?php echo $borrower_working_status ?>"></option>
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
															<button type="button" class="close text-danger" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
														</div>
														<div class="modal-body">
															
															<div class="form-group">
																<label class="text-dark">Name of Employer</label>
																<input type="text" name="borrower_employer_name" id="borrower_employer_name" class="form-control" placeholder="Employer's Name" value="<?php echo $borrower_employer_name ?>">
															</div>
															<div class="form-group">
																<label class="text-dark">Employer's Phone</label>
																<input type="text" name="borrower_employer_phone" id="borrower_employer_phone" class="form-control" placeholder="Employer's Name" value="<?php echo $borrower_employer_phone ?>">
															</div>
															<div class="form-group">
																<label class="text-dark">Address of Employer</label>
																<textarea type="text" name="borrower_employer_address" id="borrower_employer_address" class="form-control" placeholder="Employer's Address" rows="2"><?php echo $borrower_employer_address ?></textarea>
															</div>
														</div>
														<div class="modal-footer justify-content-between">
															<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Save</button>
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
															<button type="button" class="close text-danger" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
														</div>
														<div class="modal-body">
															<div class="row">
																<div class="form-group col-md-12 ">
																	<label class="text-dark" for="form">Business Name</label>
																	<div class="input-group mb-3">
																		<span class="input-group-text"><i class="bi bi-briefcase"></i></span>
																		<input type="text" name="borrower_business" id="borrower_business" class="form-control" value="<?php echo $borrower_business ?>">
																	</div>
																</div>
																<div class="form-group col-md-12 ">
																	<label class="text-dark">Type of Business</label>
																	<input type="text" name="borrower_business_type" id="borrower_business_type" class="form-control" placeholder="Employer's Name" value="<?php echo $borrower_business_type ?>">
																</div>
																<div class="form-group col-md-12 ">
																	<label class="text-dark">Address of Business</label>
																	<textarea type="text" name="borrower_business_address" id="borrower_business_address" class="form-control" placeholder="Employer's Address" rows="2"><?php echo $borrower_business_address ?></textarea>
																</div>
															</div>
														</div>
														<div class="modal-footer justify-content-between">
															<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Save </button>
															<!-- <button type="button" class="btn btn-primary">Save changes</button> -->
														</div>
													</div>
												</div>
											</div>
											<!-- End of Modal -->
										</div>
										<div class="d-flex justify-content-between">
											<div></div>
											<div><button class="btn btn button-23" type="submit" onclick="save_1()" id="borrowerBtn">Next Step</button></div>
										</div>
									</div>
								</form>
								<form method="post" id="bankDetailsForm">
									<input type="hidden" name="applicant_id" id="applicant_id" value="<?php echo $applicant_id?>">
									<div class="card-body-two mt-3" style="display: none;" id="body_two">
										<h3 class="text-center mb-5 border p-2 text-primary">Bank Details - Step 2 of 5</h3>
										<div class="row">
											<div class="form-group col-md-6">
												<label class="text-dark">Bank Name</label>
												<input type="text" name="borrower_bank_name" id="borrower_bank_name" class="form-control" placeholder="Bank Name" value="<?php echo $borrower_bank_name ?>">
											</div>
											<div class="form-group col-md-6">
												<label class="text-dark">Account Number</label>
												<input type="text" name="borrower_account_number" id="borrower_account_number" class="form-control" placeholder="Account Number" value="<?php echo $borrower_account_number ?>">
											</div>
											<div class="form-group col-md-6">
												<label class="text-dark">Sort Code</label>
												<input type="text" name="borrower_sort_code" id="borrower_sort_code" class="form-control" value="<?php echo $borrower_sort_code ?>">
											</div>
											<div class="form-group col-md-6">
												<label class="text-dark">Branch Name</label>
												<input type="text" name="borrower_branch_name" id="borrower_branch_name" class="form-control" placeholder="Branch Name" value="<?php echo $borrower_branch_name ?>">
											</div>
										</div>
										<div class="d-flex justify-content-between">
											<div><button class="btn btn button-23" type="button" onclick="back_to_1()">Previous</button></div>
											<div><button class="btn btn button-23" type="submit" onclick="save_2(event)" id="bankSave">Next Step</button></div>
										</div>
									</div>
								</form>
								<form method="post" id="next_of_kin_Form">
									<input type="hidden" name="applicant_id" id="applicant_id" value="<?php echo $applicant_id?>">
									<div class="card-body-three mt-3" style="display: none;" id="body_three">
										<h3 class="text-center mb-5 border p-2 text-primary">Next of Kin Details - Step 3 of 5</h3>
										<div class="row">
											<div class="form-group col-md-6">
												<label>Full Names</label>
												<input type="text" name="next_of_kin_fullnames" id="next_of_kin_fullnames" class="form-control" value="<?php echo $next_of_kin_fullnames ?>">
											</div>
											<div class="form-group col-md-6">
												<label>NRC No:</label>
												<input type="text" name="next_of_kin_nrc" id="next_of_kin_nrc" class="form-control" value="<?php echo $next_of_kin_nrc ?>">
											</div>
											<div class="form-group col-md-6">
												<label>Phone No.</label>
												<input type="text" name="next_of_kin_phone" id="next_of_kin_phone" class="form-control" value="<?php echo $next_of_kin_phone ?>" >
											</div>
											<div class="form-group col-md-6">
												<label>Relationship</label>
												<select name="next_of_kin_relationship" id="next_of_kin_relationship" class="form-control" required>
													<option value="<?php echo $next_of_kin_relationship?>" selected><?php echo $next_of_kin_relationship?></option>
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
												<textarea type="text" name="next_of_kin_address" id="next_of_kin_address" class="form-control" rows="2" placeholder="Enter Address"><?php echo $next_of_kin_address ?></textarea>
											</div>
										</div>
										<div class="d-flex justify-content-between">
											<div><button class="btn btn button-23" type="button" onclick="back_to_2()">Previous</button></div>
											<div><button class="btn btn button-23" type="submit" onclick="save_3(event)" id="save_3_btn">Next Step</button></div>
										</div>
									</div>
								</form>
								<div class="mt-4 mb-5" style="display: none;" id="body_four">
									<h3 class="text-center mb-5 border p-2 text-primary">Collateral & Guarantor - Step 4 of 5</h3>
									<div class="d-flex justify-content-between">
										<button class="btn btn button-59" type="button" id="addCollateral">Click to Add Collateral</button> 
						                <button class="btn btn button-59" type="button" id="addGuarantor">Click to Add Guarantor</button>
						            </div>
						            <div class="d-flex justify-content-between mt-5">
										<div><button class="btn btn button-23" type="button" onclick="back_to_3()">Previous</button></div>
										<button class="btn btn button-23 " type="submit" onclick="next_to_5()">Next Step</button>
									</div>
								</div>

								<div class="files_form" style="display: none;" id="body_five">
									<h3 class="text-center mb-5 border p-2 text-primary"> Attachments - Step 4 of 5</h3>

									<?php
										$sql = $connect->prepare("SELECT * FROM borrowers_files WHERE borrower_id = ? AND parent_id = ? AND branch_id = ? ");
										$sql->execute(array($applicant_id, $parent_id, $branch_id));
										if ($sql->rowCount() > 0) {
											foreach ($sql as $filerows) {
										?>
											<div class="mb-3">	
												<li><a href="borrowers/uploads/<?php echo $filerows['file_name']?>" target="_blank"> <?php echo $filerows['file_name']?></a> <span class="float-right"> <a href="<?php echo $filerows['id']?>" class="text-danger removeFile"> <i class="bi bi-trash"></i> </a></span> </li>
											</div>
										<?php
											}
										}
									?>

									<form action="" class="dropzone" id="filesForm"></form>
									<div class="d-flex justify-content-between">
										<div><button class="btn btn button-23" type="button" onclick="back_to_4()">Previous</button></div>
										<button class="btn btn button-23 " type="submit" id="startUpload">Save and Complete</button>
									</div>
								</div>
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

		<!--================================= Add Collateral Data =============================== -->
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
									<?php 
										$branch_id =  base64_decode($_COOKIE['SelectedBranch']);
										$applicant_id = base64_decode($_GET['applicant-id']);
										$parent_id = $_SESSION['parent_id'];
										$sql = $connect->prepare("SELECT * FROM `collaterals` WHERE borrower_id = ? AND branch_id = ? AND parent_id = ? ");
										$sql->execute(array($applicant_id, $branch_id, $parent_id));
										if ($sql->rowCount() > 0) {
											$c_row = $sql->fetch();
											extract($c_row);

											if ($photo != "") {
												$img = 'borrowers/collateral_uploads/'.$photo;
											}else{
												$img = 'dist/img/avatar2.png';
											}
										
									?>
									<div class="modal-body mb-5">
										<div class="row" id="collateralRow">
											<div class="form-group col-md-6">
												<label for="collateral_type">Type</label>
													<input type="hidden" name="collateral_id" id="collateral_id" value="<?php echo $id?>">
													<select class="form-control" name="collateral_type" id="collateral_type">
														<option value="<?php echo $collateral_type?>"><?php echo $collateral_type?></option>
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
												<input type="hidden" name="borrower_id" id="borrower_id" value="<?php echo $applicant_id?>">
											</div>
											<div class="form-group col-md-6">
												<label for="product_name">Product name</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-wallet"></i></span>
													<input type="text"  name="product_name" id="product_name" class="form-control" value="<?php echo $product_name?>">
												</div>
											</div>
											
											<div class="form-group col-md-6">
												<label for="form">Register Date</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-calendar"></i></span>
													<input type="text" name="register_date" id="register_date" class="form-control" value="<?php echo $register_date?>">
												</div>
											</div>
											
											<div class="form-group col-md-6">
												<label for="product_value">Value</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><?php echo getCurrency($connect, $_SESSION['parent_id']) ?></span>
													<input type="number" step="any" name="product_value" id="product_value" class="form-control" placeholder="Amount" value="<?php echo $product_value?>">
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
													<option value="<?php echo $product_location?>"><?php echo $product_location?></option>
													<option value="Deposited into Branch">Deposited into Branch</option>
													<option value="Collateral with Borrower">Collateral with Borrower</option>
													<option value="Returned to Borrower">Returned to Borrower</option>
													<option value="Repossession Initiated">Repossession Initiated</option>
													<option value="Repossesed">Repossessed</option>
													<option value="Under Auction">Under Auction</option>
													<option value="Sold">Sold</option>
													<option value="Lost">Lost</option>
												</select>
											</div>
											
											<div class="form-groups col-md-6">
												<label for="form">When?</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-calendar"></i></span>
													<input type="text" name="action_date" id="action_date" class="form-control" value="<?php echo $action_date?>">
												</div>
											</div>
											<div class="form-group col-md-12">
												<label>Address</label>
												<input type="text" name="address" id="address" class="form-control" value="<?php echo $address?>">
												<em>If collateral is with the borrower, add address where it is located</em>
											</div>
											<div class="col-md-12 bg-secondary p-2 mt-5 mb-5 shadow-md">
												<!-- <div class="border-bottom border-primary mb-3 mt-3"></div> -->
												<h4> Product Details</h4>
											</div>
											<div class="form-group col-md-6">
												<label>Serial #</label>
												<input type="text" name="serial_number" id="serial_number" class="form-control" value="<?php echo $serial_number?>">
											</div>
											<div class="form-group col-md-6">
												<label>Model name </label>
												<input type="text" name="model_name" id="model_name" class="form-control" value="<?php echo $model_name?>">
											</div>
											<div class="form-group col-md-6">
												<label>Model #</label>
												<input type="text" name="model_number" id="model_number" class="form-control" value="<?php echo $model_number?>">
											</div>
											<div class="form-group col-md-6">
												<label>Product Color</label>
												<input type="text" name="color" id="color" class="form-control" value="<?php echo $color?>">
											</div>
											<div class="form-group col-md-6">
												<label>Manufacture Date</label>
												<input type="text" name="manufature_date" id="manufature_date" class="form-control" value="<?php echo $manufature_date?>">
											</div>
											<div class="form-group col-md-6">
												<label>Product condition</label>
												<select class="form-control" name="product_condition" name="product_condition">
													<option value="<?php echo $product_condition?>"><?php echo $product_condition?></option>
													<option value="Excellent">Excellent</option>
													<option value="Good">Good</option>
													<option value="Fair">Fair</option>
													<option value="Damaged">Damaged</option>
												</select>
											</div>
											<div class="form-group col-md-12">
												<label>Product Description</label>
												<textarea class="form-control" rows="4" name="description" id="description" ><?php echo $description ?></textarea>
											</div>
											
											<div class="form-group col-md-6 mb-3">
												<label for="form">Collateral Photo</label>
												<div class=" border p-3">
													<button class="btn btn-warning mb-3" type="button" id="selectCollateralImage">Select Image <i class="bi bi-file-person"></i></button><br>
													<input type="file" name="photo" id="photo" class="form-control"  style="display: none;" onchange="preview_collateral_image(event)">
													<input type="hidden" name="col_photo" id="col_photo" value="<?php echo $photo?>">
													<img src="<?php echo $img?>" id="collateral_image" class="shadow-sm img-fluid img-responsive" alt="pic" width="140">
												</div>
												<em>Add a clear photo of the product</em>
											</div>
											<div class="form-group col-md-6 mb-3">
												<label for="form">Collateral Files</label>
												<div class="border p-3">
													<button class="btn btn-warning" type="button" id="selectFiles">Select Files <i class="bi bi-files"></i></button>
													<input type="file" name="files[]" id="files" class="form-control" style="display: none;" multiple onchange="javascript:updateList()">

													<div id="fileList">
														<?php
															$sql = $connect->prepare("SELECT * FROM collaterals_files WHERE borrower_id = ? AND collateral_id = ? ");
															$sql->execute(array($applicant_id, $id));
															if ($sql->rowCount() > 0) {
																foreach ($sql as $colrows) {
															?>
																<div class="mb-3">	
																	<li><a href="borrowers/collateral_uploads/<?php echo $colrows['filename']?>" target="_blank"> <?php echo $colrows['filename']?></a> <span class="float-right"> <a href="<?php echo $colrows['id']?>" class="text-danger removeCollateralFile" id="<?php echo $colrows['borrower_id'] ?>"> <i class="bi bi-trash"></i> </a></span> </li>
																</div> 
															<?php
																}
															}
														?>
													</div>
												</div>
												<!-- We will be back to finish the editing files -->
												<em>Add receipts, and any documents to support ownership</em>
											</div>
											<div class="col-md-12 bg-secondary p-2 mt-5 mb-5 shadow-md">
												<h4 >For Vehicles only </h4>
											</div>
											<div class="form-group col-md-4">
												<label>Registration Number</label>
												<input type="text" name="vehicle_reg_number" id="vehicle_reg_number" class="form-control" value="<?php echo $vehicle_reg_number ?>">
											</div>
											<div class="form-group col-md-4">
												<label>Millage</label>
												<input type="text" name="millage" id="millage" class="form-control" placeholder="Millage" value="<?php echo $millage ?>">
											</div>
											<div class="form-group col-md-4">
												<label>Engine No.</label>
												<input type="text" name="vehicle_engine_num" id="vehicle_engine_num" class="form-control" value="<?php echo $vehicle_engine_num ?>">
											</div>

										</div>
									</div>
									<div class="modal-footer d-flex justify-content-between">
										<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
										<button class="btn btn-primary" type="submit" id="cBtn" onclick="addCollateral(event)">Save Changes</button>
									</div>
								</form>
								<?php
									}else{

									}
								?>
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
							<?php 
								$branch_id 		=  base64_decode($_COOKIE['SelectedBranch']);
								$applicant_id 	= base64_decode($_GET['applicant-id']);
								$parent_id 		= $_SESSION['parent_id'];
								$query = $connect->prepare("SELECT * FROM guarantors WHERE `borrower_id` = ? AND `branch_id` = ? AND `parent_id` = ? ");
								$query->execute(array($borrower_id, $branch_id, $parent_id));
								if ($query->rowCount() > 0) {
									$row_ = $query->fetch();
									if ($row_) {
										extract($row_);
									
							    if ($photo == "") {
							    	$src = 'dist/img/avatar2.png';
							    }else{
							    	$src = 'borrowers/guarantor_uploads/'.$photo;
							    }
   
							?>
      						<div class="modal-body">		
								<div class="row">
									<div class="form-group col-12 mb-3">
										<label for="form">Guarantor Photo</label>
										<div class=" border p-3">
											<button class="btn btn-warning mb-3" type="button" id="selectGuarantorImage">Select Image <i class="bi bi-file-person"></i></button><br>
											<input type="file" name="photo_image" id="photo_image" class="form-control"  style="display: none;" onchange="preview_image(event)" accept="image/png, image/jpg, image/jpeg">
											<img src="<?php echo $src?>" id="guarantor_image" class="shadow-sm img-fluid img-responsive" alt="pic" width="140">
										</div>
										<em>Add a clear face</em>
										<input type="hidden" name="photo" id="photo" value="<?php echo $photo?>">
										<input type="hidden" name="guarantor_id" id="guarantor_id" value="<?php echo $id?>">
	      								<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID ?>">
	      								<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
	      							</div>
									<div class="form-group col-6">
										<label for="form">First name</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-person"></i></span>
											<input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo $firstname ?>">
										</div>
									</div>
									<div class="form-group col-6">
										<label for="form">Last name</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-person"></i></span>
											<input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo $lastname ?>">
										</div>
									</div>
									<div class="form-group col-6">
										<label>Gender</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
											<select id="gender" name="gender" class="form-control" required>
												<option value="<?php echo $gender ?>" selected><?php echo $gender ?></option>
												<option value="Male">Male</option>
												<option value="Female">Female</option>
											</select>
										</div>
									</div>
									<div class="form-group col-6">
										<label for="form">Date of Birth</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-calendar"></i></span>
											<input type="date" name="dateofbirth" id="dateofbirth" class="form-control" value="<?php echo $dateofbirth ?>">
										</div>
									</div>
									<div class="form-group col-6">
										<label for="form">Email</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-at"></i></span>
											<input type="email" name="email" id="email" class="form-control" value="<?php echo $email ?>">
										</div>
									</div>
									<div class="form-group col-6 mb-3">
										<label for="form">Phone</label>
										<div class="input-group  mb-2">
											<span class="input-group-text"><i class="bi bi-phone"></i></span>
											<input type="text"  name="phone" id="phone" class="form-control" value="<?php echo $phone ?>">
										</div>
									</div>
									<div class="form-group col-6 mb-3">
										<label for="form">NRC or Passport</label>
										<div class="input-group mb-1">
											<span class="input-group-text"><i class="bi bi-file-person"></i></span>
											<input type="text" name="identity_number" id="identity_number" class="form-control" required value="<?php echo $identity_number ?>">
										</div>
									</div>
									<div class="form-group col-6">
										<label>Working Status</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
											<select id="working_status" name="working_status" class="form-control">
												<option value="<?php echo $working_status ?>"><?php echo $working_status ?></option>
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
												<option value="<?php echo $country ?>"><?php echo getcCountryName($connect, $country) ?></option>
												<?php
													$query = $connect->prepare("SELECT * FROM currencies");
													$query->execute();
													foreach ($query->fetchAll() as $row) {
													    echo '<option value="'.$row['id'].'">'.$row['country'].'</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group col-6">
										<label for="form">City</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-geo"></i></span>
											<input type="text" name="city" id="city" class="form-control" value="<?php echo $city ?>">
										</div>
									</div>
									<div class="form-group col-6">
										<label for="form">Home Address</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-geo"></i></span>
											<textarea type="text" name="address" id="address" class="form-control" rows="2"> <?php echo $address ?> </textarea>
										</div>
									</div>
									
									<div class="form-group col-6 mb-3">
										<label for="form">Guarantor Files</label><br>
										<div class="">
											<button class="btn btn-warning" type="button" id="selectGuarantorFiles">Select Files <i class="bi bi-files"></i></button>
											<input type="file" name="guarantor_files[]" id="guarantor_files" class="form-control" style="display: none;" multiple onchange="javascript:updateGuarantorList()">

											<div id="guarantor_fileList">
												<?php
													$sql = $connect->prepare("SELECT * FROM guarantor_files WHERE borrower_id = ? AND guarantor_id = ? ");
													$sql->execute(array($applicant_id, $id));
													if ($sql->rowCount() > 0) {
														foreach ($sql as $grows) {
													?>
														<div class="mb-3">	
															<li><a href="borrowers/guarantor_uploads/<?php echo $grows['file_name']?>" target="_blank"> <?php echo $grows['file_name']?></a> <span class="float-right"> <a href="<?php echo $grows['id']?>" class="text-danger removeGuarantoFile" id="<?php echo $grows['borrower_id'] ?>"> <i class="bi bi-trash"></i> </a></span> </li>
														</div> 
													<?php
														}
													}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer d-flex justify-content-between ">
								<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close </button>
								<button class="btn btn-warning btn-lg"  type="submit" id="addBtn" onclick="addGuarantor(event)">Save Changes</button>
							</div>
							<?php
								}
							}
							?>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
			}else{
				header("location:../");
			}
		}

			if (isset($_GET['applicant-id']) && isset($_GET['product_id'])) {?>
				<script>
					$(function (){
				    	
				    	$("#collateralModal").modal('show');
				    	
				    	$("#collateralModal .modal-dialog").addClass("modal-fullscreen");
				    })
				</script>
		<?php	}elseif (isset($_GET['applicant-id']) && isset($_GET['guarantor_id'])) {?>
					<script>
						$(function (){
					    	
					    	$("#gurantorModal").modal('show');
					    	
					    	$("#gurantorModal .modal-dialog").addClass("modal-fullscreen");
					    })
					</script>
		<?php	}
		?>

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
					// document.getElementById("title_span").innerHTML = localStorage.getItem("borrower_title");
				}
			});
			// document.getElementById("title_span").innerHTML = localStorage.getItem("borrower_title");
			if(localStorage.getItem('borrower_title')){
		        $('#borrower_title').val(localStorage.getItem('borrower_title'));
		    }

			$("#borrower_firstname").keyup(function(){
				var borrower_firstname = $(this).val();
				if( borrower_firstname !== ""){
					localStorage.setItem("borrower_firstname", borrower_firstname);
					// document.getElementById("firstname_span").innerHTML = localStorage.getItem("borrower_firstname");
				}else{
					// document.getElementById("firstname_span").innerHTML = 'Mutale';
				}
			})
			// document.getElementById("borrower_firstname").value = localStorage.getItem("borrower_firstname");
			// document.getElementById("firstname_span").innerHTML = localStorage.getItem("borrower_firstname");

			$("#borrower_lastname").keyup(function(){
				var borrower_lastname = $(this).val();
				if( borrower_lastname !== ""){
					localStorage.setItem("borrower_lastname", borrower_lastname);
					// document.getElementById("lastname_span").innerHTML = localStorage.getItem("borrower_lastname");
				}else{
					// document.getElementById("lastname_span").innerHTML = 'Mulenga';
				}
			})
			// document.getElementById("borrower_lastname").value = localStorage.getItem("borrower_lastname");
			// document.getElementById("lastname_span").innerHTML = localStorage.getItem("borrower_lastname");

			$("#borrower_gender").change(function(){
				var borrower_gender = $(this).val();
				if( borrower_gender !== ""){
					localStorage.setItem("borrower_gender", this.value);
					// document.getElementById("gender_span").innerHTML = localStorage.getItem("borrower_gender");
				}
			});
			// document.getElementById("gender_span").innerHTML = localStorage.getItem("borrower_gender");
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
					// document.getElementById("country_span").innerHTML = localStorage.getItem("borrower_country_name");
				}
			});
			
			if(localStorage.getItem('borrower_country')){
				// document.getElementById("country_span").innerHTML = localStorage.getItem('borrower_country_name');
		        $('#borrower_country').val(localStorage.getItem('borrower_country'));
		    }

		    // ID 

		    $("#borrower_ID").keyup(function(){
				var borrower_ID = $(this).val();
				if( borrower_ID !== ""){
					localStorage.setItem("borrower_ID", borrower_ID);
					// document.getElementById("ID_span").innerHTML = localStorage.getItem("borrower_ID");
					document.getElementById("borrower_ID2").value = localStorage.getItem("borrower_ID");
					document.getElementById("borrower_ID3").value = localStorage.getItem("borrower_ID");
				}else{
					
				}
			})
			
			// document.getElementById("borrower_ID").value = localStorage.getItem("borrower_ID");
			// document.getElementById("ID_span").innerHTML = localStorage.getItem("borrower_ID");
			// Address
			$("#borrower_address").keyup(function(){
				var borrower_address = $(this).val();
				if( borrower_address !== ""){
					localStorage.setItem("borrower_address", borrower_address);
					// document.getElementById("address_span").innerHTML = localStorage.getItem("borrower_address");
				}else{
					// document.getElementById("address_span").innerHTML = 'Add Address';
				}
			})
			if(localStorage.getItem("borrower_address")){
				document.getElementById("borrower_address").value = localStorage.getItem("borrower_address");
				// document.getElementById("address_span").innerHTML = localStorage.getItem("borrower_address");
			}else{
				// document.getElementById("address_span").innerHTML = "Add Physical Address";
			}

			// City
			$("#borrower_city").keyup(function(){
				var borrower_city = $(this).val();
				if( borrower_city !== ""){
					localStorage.setItem("borrower_city", borrower_city);
					// document.getElementById("city_span").innerHTML = localStorage.getItem("borrower_city");
				}else{
					// document.getElementById("city_span").innerHTML = 'Add city';
				}
			})
			if(localStorage.getItem("borrower_city")){
				document.getElementById("borrower_city").value = localStorage.getItem("borrower_city");
				// document.getElementById("city_span").innerHTML = localStorage.getItem("borrower_city");
			}else{
				// document.getElementById("city_span").innerHTML = "Add Physical Address";
			}

			// email
			$("#borrower_email").keyup(function(){
				var borrower_email = $(this).val();
				if( borrower_email !== ""){
					localStorage.setItem("borrower_email", borrower_email);
					// document.getElementById("email_span").innerHTML = localStorage.getItem("borrower_email");
				}else{
					// document.getElementById("email_span").innerHTML = 'Add email';
				}
			})
			if(localStorage.getItem("borrower_email")){
				document.getElementById("borrower_email").value = localStorage.getItem("borrower_email");
				// document.getElementById("email_span").innerHTML = localStorage.getItem("borrower_email");
			}else{
				// document.getElementById("email_span").innerHTML = "Add Email";
			}

			// phone
			$("#borrower_phone").keyup(function(){
				var borrower_phone = $(this).val();
				if( borrower_phone !== ""){
					localStorage.setItem("borrower_phone", borrower_phone);
					// document.getElementById("phone_span").innerHTML = localStorage.getItem("borrower_phone");
				}else{
					// document.getElementById("phone_span").innerHTML = 'Add phone';
				}
			})
			if(localStorage.getItem("borrower_phone")){
				document.getElementById("borrower_phone").value = localStorage.getItem("borrower_phone");
				document.getElementById("phone").value = localStorage.getItem("borrower_phone");
				// document.getElementById("phone_span").innerHTML = localStorage.getItem("borrower_phone");
			}else{
				// document.getElementById("phone_span").innerHTML = "Add phone";
			}

			$("#borrower_dateofbirth").change(function(){
				var borrower_dateofbirth = $(this).val();
				if( borrower_dateofbirth !== ""){
					localStorage.setItem("borrower_dateofbirth", this.value);
					// document.getElementById("dateofbirth_span").innerHTML = localStorage.getItem("borrower_dateofbirth");
				}
			});
			// document.getElementById("dateofbirth_span").innerHTML = localStorage.getItem("borrower_dateofbirth");
			if(localStorage.getItem('borrower_dateofbirth')){
		        $('#borrower_dateofbirth').val(localStorage.getItem('borrower_dateofbirth'));
		    }

		    // working / employee
		 //    $("#borrower_working_status").change(function(e){
			// 	if($(this).val() === "Employee"){
			// 		$("#employeeDetailsModal").modal("show");
			// $("#working_span").html("Employer Details");
			// 	}else if($(this).val() === "Business"){
			// 		$("#businessDetailsModal").modal("show");
			// 		$("#working_span").html("Business Details");
			// 	}
			// })

			$("#borrower_working_status").change(function(){
				var borrower_working_status = $(this).val();
				if($(this).val() === "Employee"){
					$("#employeeDetailsModal").modal("show");
					// $("#working_span").html("Employer Details");
				}else if($(this).val() === "Business"){
					$("#businessDetailsModal").modal("show");
					// $("#working_span").html("Business Details");
				}
				if( borrower_working_status !== ""){
					localStorage.setItem("borrower_working_status", this.value);
					// document.getElementById("working_span").innerHTML = localStorage.getItem("borrower_working_status");
				}
			});
			// document.getElementById("working_span").innerHTML = localStorage.getItem("borrower_working_status");
			if(localStorage.getItem('borrower_working_status')){
		        $('#borrower_working_status').val(localStorage.getItem('working_span'));
		    }

		    $("#borrower_employer_name").keyup(function(){
				var borrower_employer_name = $(this).val();
				if( borrower_employer_name !== ""){
					localStorage.setItem("borrower_employer_name", borrower_employer_name);
					// document.getElementById("general_span_1").innerHTML = localStorage.getItem("borrower_employer_name");
				}else{
					// document.getElementById("general_span_1").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_employer_name")){
				document.getElementById("borrower_employer_name").value = localStorage.getItem("borrower_employer_name");
				// document.getElementById("general_span_1").innerHTML = localStorage.getItem("borrower_employer_name");
			}else{
				// document.getElementById("general_span_1").innerHTML = "---";
			}

			$("#borrower_employer_phone").keyup(function(){
				var borrower_employer_phone = $(this).val();
				if( borrower_employer_phone !== ""){
					localStorage.setItem("borrower_employer_phone", borrower_employer_phone);
					// document.getElementById("general_span_2").innerHTML = localStorage.getItem("borrower_employer_phone");
				}else{
					// document.getElementById("general_span_2").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_employer_phone")){
				document.getElementById("borrower_employer_phone").value = localStorage.getItem("borrower_employer_phone");
				// document.getElementById("general_span_2").innerHTML = localStorage.getItem("borrower_employer_phone");
			}else{
				// document.getElementById("general_span_2").innerHTML = "---";
			}

			$("#borrower_employer_address").keyup(function(){
				var borrower_employer_address = $(this).val();
				if( borrower_employer_address !== ""){
					localStorage.setItem("borrower_employer_address", borrower_employer_address);
					// document.getElementById("general_span_3").innerHTML = localStorage.getItem("borrower_employer_address");
				}else{
					// document.getElementById("general_span_3").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_employer_address")){
				document.getElementById("borrower_employer_address").value = localStorage.getItem("borrower_employer_address");
				// document.getElementById("general_span_3").innerHTML = localStorage.getItem("borrower_employer_address");
			}else{
				// document.getElementById("general_span_3").innerHTML = "---";
			}
			
			// business 
			
			$("#borrower_business").keyup(function(){
				var borrower_business = $(this).val();
				if( borrower_business !== ""){
					localStorage.setItem("borrower_business", borrower_business);
					// document.getElementById("general_span_1").innerHTML = localStorage.getItem("borrower_business");
				}else{
					// document.getElementById("general_span_1").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_business")){
				document.getElementById("borrower_business").value = localStorage.getItem("borrower_business");
				// document.getElementById("general_span_1").innerHTML = localStorage.getItem("borrower_business");
			}else{
				// document.getElementById("general_span_1").innerHTML = "---";
			}

			$("#borrower_business_type").keyup(function(){
				var borrower_business_type = $(this).val();
				if( borrower_business_type !== ""){
					localStorage.setItem("borrower_business_type", borrower_business_type);
					// document.getElementById("general_span_2").innerHTML = localStorage.getItem("borrower_business_type");
				}else{
					// document.getElementById("general_span_2").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_business_type")){
				document.getElementById("borrower_business_type").value = localStorage.getItem("borrower_business_type");
				// document.getElementById("general_span_2").innerHTML = localStorage.getItem("borrower_business_type");
			}else{
				// document.getElementById("general_span_2").innerHTML = "---";
			}

			$("#borrower_business_address").keyup(function(){
				var borrower_business_address = $(this).val();
				if( borrower_business_address !== ""){
					localStorage.setItem("borrower_business_address", borrower_business_address);
					// document.getElementById("general_span_3").innerHTML = localStorage.getItem("borrower_business_address");
				}else{
					// document.getElementById("general_span_3").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_business_address")){
				document.getElementById("borrower_business_address").value = localStorage.getItem("borrower_business_address");
				// document.getElementById("general_span_3").innerHTML = localStorage.getItem("borrower_business_address");
			}else{
				// document.getElementById("general_span_3").innerHTML = "---";
			}

			// Bank Details 
			$("#borrower_bank_name").keyup(function(){
				var borrower_bank_name = $(this).val();
				if( borrower_bank_name !== ""){
					localStorage.setItem("borrower_bank_name", borrower_bank_name);
					// document.getElementById("bank_name_span").innerHTML = localStorage.getItem("borrower_bank_name");
				}else{
					// document.getElementById("bank_name_span").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_bank_name")){
				document.getElementById("borrower_bank_name").value = localStorage.getItem("borrower_bank_name");
				// document.getElementById("bank_name_span").innerHTML = localStorage.getItem("borrower_bank_name");
			}else{
				// document.getElementById("bank_name_span").innerHTML = "---";
			}

			$("#borrower_account_number").keyup(function(){
				var borrower_account_number = $(this).val();
				if( borrower_account_number !== ""){
					localStorage.setItem("borrower_account_number", borrower_account_number);
					// document.getElementById("account_number_span").innerHTML = localStorage.getItem("borrower_account_number");
				}else{
					// document.getElementById("account_number_span").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_account_number")){
				document.getElementById("borrower_account_number").value = localStorage.getItem("borrower_account_number");
				// document.getElementById("account_number_span").innerHTML = localStorage.getItem("borrower_account_number");
			}else{
				// document.getElementById("account_number_span").innerHTML = "---";
			}

			$("#borrower_branch_name").keyup(function(){
				var borrower_branch_name = $(this).val();
				if( borrower_branch_name !== ""){
					localStorage.setItem("borrower_branch_name", borrower_branch_name);
					// document.getElementById("branch_name_span").innerHTML = localStorage.getItem("borrower_branch_name");
				}else{
					// document.getElementById("branch_name_span").innerHTML = '---';
				}
			})
			if(localStorage.getItem("borrower_branch_name")){
				document.getElementById("borrower_branch_name").value = localStorage.getItem("borrower_branch_name");
				// document.getElementById("branch_name_span").innerHTML = localStorage.getItem("borrower_branch_name");
			}else{
				// document.getElementById("branch_name_span").innerHTML = "---";
			}

			// NEXT OF KEEN

			$("#next_of_kin_fullnames").keyup(function(){
				var next_of_kin_fullnames = $(this).val();
				if( next_of_kin_fullnames !== ""){
					localStorage.setItem("next_of_kin_fullnames", next_of_kin_fullnames);
					// document.getElementById("next_of_kin_fullnames_span").innerHTML = localStorage.getItem("next_of_kin_fullnames");
				}else{
					// document.getElementById("next_of_kin_fullnames_span").innerHTML = '---';
				}
			})
			if(localStorage.getItem("next_of_kin_fullnames")){
				document.getElementById("next_of_kin_fullnames").value = localStorage.getItem("next_of_kin_fullnames");
				// document.getElementById("next_of_kin_fullnames_span").innerHTML = localStorage.getItem("next_of_kin_fullnames");
			}else{
				// document.getElementById("next_of_kin_fullnames_span").innerHTML = "---";
			}

			$("#next_of_kin_nrc").keyup(function(){
				var next_of_kin_nrc = $(this).val();
				if( next_of_kin_nrc !== ""){
					localStorage.setItem("next_of_kin_nrc", next_of_kin_nrc);
					// document.getElementById("next_of_kin_nrc_span").innerHTML = localStorage.getItem("next_of_kin_nrc");
				}else{
					// document.getElementById("next_of_kin_nrc_span").innerHTML = '---';
				}
			})
			if(localStorage.getItem("next_of_kin_nrc")){
				document.getElementById("next_of_kin_nrc").value = localStorage.getItem("next_of_kin_nrc");
				// document.getElementById("next_of_kin_nrc_span").innerHTML = localStorage.getItem("next_of_kin_nrc");
			}else{
				// document.getElementById("next_of_kin_nrc_span").innerHTML = "---";
			}



			$("#next_of_kin_relationship").change(function(){
				var next_of_kin_relationship = $(this).val();
				if( next_of_kin_relationship !== ""){
					localStorage.setItem("next_of_kin_relationship", this.value);
					// document.getElementById("next_of_kin_relationship_span").innerHTML = localStorage.getItem("next_of_kin_relationship");
				}
			});
			// document.getElementById("next_of_kin_relationship_span").innerHTML = localStorage.getItem("next_of_kin_relationship");
			if(localStorage.getItem('next_of_kin_relationship')){
		        // $('#next_of_kin_relationship').val(localStorage.getItem('next_of_kin_relationship_span'));
		    }

			$("#next_of_kin_phone").keyup(function(){
				var next_of_kin_phone = $(this).val();
				if( next_of_kin_phone !== ""){
					localStorage.setItem("next_of_kin_phone", next_of_kin_phone);
					// document.getElementById("next_of_kin_phone_span").innerHTML = localStorage.getItem("next_of_kin_phone");
				}else{
					// document.getElementById("next_of_kin_phone_span").innerHTML = '---';
				}
			})
			if(localStorage.getItem("next_of_kin_phone")){
				document.getElementById("next_of_kin_phone").value = localStorage.getItem("next_of_kin_phone");
				// document.getElementById("next_of_kin_phone_span").innerHTML = localStorage.getItem("next_of_kin_phone");
			}else{
				// document.getElementById("next_of_kin_phone_span").innerHTML = "---";
			}


			$("#next_of_kin_address").keyup(function(){
				var next_of_kin_address = $(this).val();
				if( next_of_kin_address !== ""){
					localStorage.setItem("next_of_kin_address", next_of_kin_address);
					// document.getElementById("next_of_kin_address_span").innerHTML = localStorage.getItem("next_of_kin_address");
				}else{
					// document.getElementById("next_of_kin_address_span").innerHTML = '---';
				}
			})
			if(localStorage.getItem("next_of_kin_address")){
				document.getElementById("next_of_kin_address").value = localStorage.getItem("next_of_kin_address");
				// document.getElementById("next_of_kin_address_span").innerHTML = localStorage.getItem("next_of_kin_address");
			}else{
				// document.getElementById("next_of_kin_address_span").innerHTML = "---";
			}
		   
		})

		function _reset() {
			$('#personDetailsForm')[0].reset();
			$('#bankDetailsForm')[0].reset();
			$('#next_of_kin_Form')[0].reset();
			window.localStorage.removeItem("borrower_title");
			window.localStorage.removeItem("borrower_firstname");
			window.localStorage.removeItem("borrower_lastname");
			window.localStorage.removeItem("borrower_email");
			window.localStorage.removeItem("borrower_phone");
			window.localStorage.removeItem("borrower_gender");
			window.localStorage.removeItem("borrower_ID");
			window.localStorage.removeItem("borrower_address");
			window.localStorage.removeItem("borrower_dateofbirth");
			window.localStorage.removeItem("borrower_city");
			window.localStorage.removeItem("borrower_country");
			window.localStorage.removeItem("borrower_country_name");
			window.localStorage.removeItem("borrower_working_status");

			window.localStorage.removeItem("borrower_bank_name");
			window.localStorage.removeItem("borrower_account_number");
			window.localStorage.removeItem("borrower_branch_name");

			window.localStorage.removeItem("borrower_business");
			window.localStorage.removeItem("borrower_business_address");
			window.localStorage.removeItem("borrower_business_type");

			window.localStorage.removeItem("borrower_employer_name");
			window.localStorage.removeItem("borrower_employer_phone");
			window.localStorage.removeItem("borrower_employer_address");

			window.localStorage.removeItem("next_of_kin_address");
			window.localStorage.removeItem("next_of_kin_nrc");
			window.localStorage.removeItem("next_of_kin_relationship");
			window.localStorage.removeItem("next_of_kin_phone");
			window.localStorage.removeItem("next_of_kin_address");
			setTimeout(function(){
				successNow("Data Saved, Please Add another User");
				location.reload();
			}, 2000);

			document.cookie = "BORROWERID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
			
		}


		//==========================DROP ZONE================================================

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
		        _reset();
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
			var url = 'borrowers/submitEditedOne';
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
					document.getElementById("borrowerBtn").innerHTML = 'Next Step';	
					$("#body_one").hide();
					$("#body_two").show();
					localStorage.setItem("ID", "<?php echo $applicant_id?>");
					
				}
			}
			xhr.send(data);
			document.getElementById("borrowerBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
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
					document.getElementById("bankSave").innerHTML = 'Next Step';	
					$("#body_two").hide();
					$("#body_three").show();
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
					document.getElementById("save_3_btn").innerHTML = 'Next Step';	
					$("#body_three").hide();
					$("#body_four").show();
				}
			}
			xhr.send(data);
			document.getElementById("save_3_btn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}
		


		//=================== SAVING FILES ===============================

		function back_to_1(){
			$("#body_one").show();
			$("#body_two").hide();
		}

		function back_to_2(){
			$("#body_two").show();
			$("#body_three").hide();
		}

		function back_to_3(){
			$("#body_three").show();
			$("#body_four").hide();
		}
		function next_to_5(){
			$("#body_four").hide();
			$("#body_five").show();
		}
		function back_to_4(){
			$("#body_four").show();
			$("#body_five").hide();
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

	   
	   // images ------ 
	   	
		var selectCollateralImage = document.getElementById('selectCollateralImage');
  		var fileInput = document.getElementById('photo');
  		selectCollateralImage.addEventListener("click", (e) => {
  			$('#photo').click();
  		});

		function preview_collateral_image(event) {
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
					$("#collateralRow").load(location.href + " #collateralRow");
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


		//=========================== REMOVE FILE =========================
		$(function(){
			$(document).on("click", ".removeFile", function(e){
				e.preventDefault();
				var fileId = $(this).attr("href");
				if (confirm("You are about to delete the document, you wont undo this action")) {
					var url = 'borrowers/removeFiles'
					$.ajax({
						url:url,
						method:"post",
						data:{fileId:fileId},
						success:function(data){
							successNow(data);
							$("#body_four").load(location.href + " #body_four");
						}
					})
				}
			})

			$(document).on("click", ".removeCollateralFile", function(e){
				e.preventDefault();
				var CollateralFileId = $(this).attr("href");
				var borrower_id = $(this).attr("id");
				// successNow(CollateralFileId);
				if (confirm("You are about to delete the document, you wont undo this action")) {
					var url = 'borrowers/removeFiles'
					$.ajax({
						url:url,
						method:"post",
						data:{CollateralFileId:CollateralFileId, borrower_id:borrower_id},
						success:function(data){
							successNow(data);
							$("#fileList").load(location.href + " #fileList");
						}
					})
				}
			})

			$(document).on("click", ".removeGuarantoFile", function(e){
				e.preventDefault();
				var GuarantorFileId = $(this).attr("href");
				var borrower_id = $(this).attr("id");
				// successNow(GuarantorFileId);
				if (confirm("You are about to delete the document, you wont undo this action")) {
					var url = 'borrowers/removeFiles'
					$.ajax({
						url:url,
						method:"post",
						data:{GuarantorFileId:GuarantorFileId, borrower_id:borrower_id},
						success:function(data){
							successNow(data);
							$("#guarantor_fileList").load(location.href + " #guarantor_fileList");
						}
					})
				}
			})

			
			
		})
	</script>
</body>
</html>