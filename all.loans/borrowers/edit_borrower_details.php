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
	if (isset($_GET['borrower_id'])) {
		$borrower_id = $_GET['borrower_id'];
		$query = $connect->prepare("SELECT * FROM borrowers WHERE parent_id = ? AND borrower_ID = ?");
		$query->execute(array($_SESSION['parent_id'], $borrower_id));
		$count = $query->rowCount();
		if ($count > 0) {
			$row = $query->fetch();
			$user_id 					= $row['id'];
			$branchID 					= $row['branch_id'];
			$parent_id 					= $row['parent_id'];
			$working_status 			= preg_replace("#[^a-zA-Z]#", " ", ucwords($row['borrower_working_status']));
			$borrower_working_status 	= $row['borrower_working_status'];
			$borrower_firstname 		= $row['borrower_firstname'];
			$borrower_lastname 			= $row['borrower_lastname'];
			$borrower_email 			= $row['borrower_email'];
			$borrower_phone 			= $row['borrower_phone'];
			$borrower_dateofbirth    	= $row['borrower_dateofbirth'];
			$borrower_address 			= $row['borrower_address'];
			$borrower_city 				= $row['borrower_city'];
			$borrower_country 			= $row['borrower_country'];
			$borrower_business 			= $row['borrower_business'];
			$borrower_gender  			= $row['borrower_gender'];
			$date_added 	   			= $row['date_added'];
			$borrower_borrower_files 	= $row['borrower_borrower_files'];
			$borrower_borrower_photo 	= $row['borrower_borrower_photo'];
			$loan_officers = $row['loan_officers'];
			if ($borrower_borrower_photo == "") {
				$src = 'dist/img/avatar2.png';
			}else{
				$src = 'fileuploads/'.$borrower_borrower_photo;
			}
		}
	}

	$sql = $connect->prepare("SELECT  * FROM allowed_branches WHERE staff_id = ? AND parent_id = ? ");
    $sql->execute(array($_SESSION['user_id'], $_SESSION['parent_id']));
    $br_options = "";
    foreach ($sql->fetchAll() as $row) {
      // we get the branch ID, and try to find other people who belong to 
      	$branch_id = $row['branch_id'];
      	$br_options .= '<option value="'.$branch_id.'">'.ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id)).'</option>';

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
	<link rel="stylesheet" href="intl.17/build/css/intlTelInput.css">
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
			<section class="content bg-light">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					
      				</div>
      			</div>
      			<!-- borrower form -->
      			<div class="container">
      				<div class="row">
      					<div class="col-md-12">
      						<div class="card card-primary">
      							<div class="card-header">
      								<h4 class="card-title"><?php echo getBorrowerFullNamesByCardId($connect, $borrower_id)?></h4>
      							</div>
      							<div class="card-body">
      								<label>Initial Branch : <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $branchID))?></label>
		      						<form class="shadow-lg p-4" method="post" id="borrowerEditForm" enctype="multipart/form-data">
		      							<div class="row">
		      								<div class="form-group col-md-12 mb-3">
												<label for="form">Borrower Photo</label>
												<div class=" border p-3">
													<button class="btn btn-warning mb-3" type="button" id="selectImage">Change Image <i class="bi bi-file-person"></i></button><br>
													<input type="file" name="borrower_borrower_photo" id="borrower_borrower_photo" class="form-control"  style="display: none;" onchange="preview_image(event)">
													<input type="hidden" name="borrower_photo" value="<?php echo $borrower_borrower_photo?>">
													<img src="<?php echo $src?>" id="output_image" class="shadow-sm img-fluid img-responsive" alt="pic" width="140">
												</div>
												<em>Add a clear face of the applicant</em>
											</div>
			      							<div class="form-group col-md-6 mb-3">
			      								
			      								<input type="hidden" name="initial_branch" id="initial_branch" class="form-control" readonly="readonly" value="<?php echo $branchID?>">
			      								<label>Change Branch</label>
			      								<select  name="branch_id" id="branch_id" onchange="fetchLoanOfficers(this.value)"  class="select2 form-control" data-placeholder="Select Branch" data-dropdown-css-class="select2-purple" style="width:100%;" required="required">
			      									<option value=""></option>
			      									<?php echo $br_options?>
			      								</select>
			      								<em>Do nothing if you are not changing borrower's branch</em>
			      								<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
			      							</div>
											<div class="form-group col-md-6">
												<label for="form">Firstname</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-person"></i></span>
													<input type="text"  name="borrower_firstname" id="borrower_firstname" class="form-control" value="<?php echo $borrower_firstname?>">
												</div>
												<input type="hidden" name="user_id"  value="<?php echo $user_id?>">
											</div>
											<div class="form-group col-md-6">
												<label for="form">Lastname</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-person"></i></span>
													<input type="text" name="borrower_lastname" id="borrower_lastname" class="form-control" value="<?php echo $borrower_lastname ?>">
												</div>
											</div>
											<div class="form-group col-md-6">
												<label for="form">Business Name</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-briefcase"></i></span>
													<input type="text" name="borrower_business" id="borrower_business" class="form-control" value="<?php echo $borrower_business?>">
												</div>
											</div>

											<div class="border-bottom pb-2 mb-4"></div>
											<div class="form-group col-md-6">
												<label>Gender</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
													<select id="borrower_gender" name="borrower_gender" class="form-control">
														<option value="<?php echo $borrower_gender?>"><?php echo ucwords($borrower_gender)?></option>
														<option value="male">Male</option>
														<option value="female">Female</option>
													</select>
												</div>
											</div>
											<div class="form-group col-md-6 mb-3">
												<label for="form">ID No:</label>
												<div class="input-group mb-1">
													<span class="input-group-text"><i class="bi bi-file-person"></i></span>
													<input type="text" name="borrower_ID" id="borrower_ID" class="form-control" value="<?php echo $borrower_id?>">
												</div>
												<em>This can be NRC, PASSPORT or the document you deam fit</em>
											</div>
											<div class="form-group col-md-6">
												<label for="form">Country</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-geo"></i></span>
													<select name="borrower_country" id="borrower_country" class="form-control">
														<option value="<?php echo $borrower_country ?>"><?php echo getCountryName($connect, $borrower_country)?></option>
														<?php echo $country;?>
													</select>
												</div>
											</div>
											<div class="form-group col-md-6">
												<label for="form">City</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-geo"></i></span>
													<input type="text" name="borrower_city" id="borrower_city" class="form-control" value="<?php echo $borrower_city?>">
												</div>
											</div>
											
											<div class="form-group col-md-6">
												<label for="form">Email</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-at"></i></span>
													<input type="email" name="borrower_email" id="borrower_email" class="form-control" value="<?php echo $borrower_email?>">
												</div>
											</div>
											<div class="form-group col-md-6 mb-3">
												<label for="form">Phone</label>
												<div class="input-group mb-3">
													<input type="tel" id="phone" name="phone" class="form-control" onkeyup="complePhone(this.value)">
													<input type="hidden" name="borrower_phone" id="borrower_phone" class="form-control" value="<?php echo $borrower_phone?>" readonly>
												</div>
												<p id="result"><?php echo $borrower_phone?></p>
											</div>
											<div class="form-group col-md-6">
												<label for="form">Date of Birth</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-calendar"></i></span>
													<input type="text"  name="borrower_dateofbirth" id="borrower_dateofbirth" class="form-control" value="<?php echo $borrower_dateofbirth?>">
												</div>
											</div>
											<div class="form-group col-md-6">
												<label>Working Status</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
													<select id="borrower_working_status" name="borrower_working_status" class="form-control">
														<option value="<?php echo $borrower_working_status?>"><?php echo $working_status?></option>
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
											<div class="form-group col-md-12">
												<label for="form">Home Address</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-geo"></i></span>
													<textarea type="text" name="borrower_address" id="borrower_address" class="form-control" rows="5" ><?php echo $borrower_address?> </textarea>
												</div>
											</div>
											<div class="form-group col-md-12 mb-3">
												<label for="form">Borrower Files</label>
												<div class="border-bottom pb-2">
													<button class="btn btn-outline-secondary mb-3" type="button" id="selectFiles">Change Files <i class="bi bi-files"></i></button>
													<input type="file" name="borrower_borrower_files[]" id="borrower_borrower_files" class="form-control" style="display: none;" multiple onchange="updateList()" value="">
													<input type="hidden" name="borrower_files" value="<?php echo $borrower_borrower_files ?>">
													<div id="fileList" class="mb-3"><?php echo borrowerAddedFiles($connect, $user_id)?></div>

												</div>
											</div>
											<div class="form-group col-md-12 mb-5">
													<!-- if the borrowe is assinge the  -->
												<label>Loan Officers</label>
												<div id="showloadOfficers"></div>
												<input type="hidden" name="assigned_officers" id="assigned_officers" value="<?php echo $loan_officers?>">
												<em>If you are changing branch of the borrower, please select Loan officers who belong to the branch.</em>
												<div class="border-bottom pd-2 mt-3"></div>
											</div>
											<div class="mb-4">
												<button type="button" class="btn btn-outline-secondary" id="borrowerEditBtn" onclick="EditBorrower(event)">Save changes</button>
											</div>
										</div>
									</form>
								</div>
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
	<script src="intl.17/build/js/intlTelInput.js"></script>
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

		function fetchLoanOfficers(branch_id){
			if (branch_id === "") {
				alert("Select Branch name");
				return false;
			}else{
				// we make an ajax call to find collecctors to assign the work to.
				document.getElementById('initial_branch').value = branch_id;
				document.getElementById("assigned_officers").value = "";
				// $("#borrowerEditBtn").attr("disabled", "disabled");
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
			// var num = iti.getNumber(),
			var number = iti.getNumber(intlTelInputUtils.numberFormat.E164);
			var isValid = iti.isValidNumber();
			var result = document.querySelector("#result");
			var borrower_phone = document.getElementById("borrower_phone");
			if (phone === "") {
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
</body>
</html>