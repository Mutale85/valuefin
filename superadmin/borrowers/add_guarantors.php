<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 

	$country = '';
	$query = $connect->prepare("SELECT * FROM currencies");
	$query->execute();
	foreach ($query->fetchAll() as $row) {
	    $country .= '<option value="'.$row['id'].'">'.$row['country'].'</option>';
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Guarantors</title>
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
<?php
	$option = '';
	$query = $connect->prepare("SELECT * FROM borrowers WHERE branch_id = ?");
	$query->execute(array($BRANCHID));
	foreach ($query->fetchAll() as $row) {
	    $option .= '<option value="'.$row['borrower_ID'].'">'.$row['borrower_firstname'] .' '. $row['borrower_lastname'].' - ID No:  '.$row['borrower_ID'].'</option>';
	}
?>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 border-bottom pb-2 ">
      						<div class="d-flex justify-content-between">
      						<h1 class="h3"><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $BRANCHID))?> Add Guarantors</h1>
      						</div>
      					</div>

      				</div>
      			</div>
      			<!-- borrower form -->
      			<div class="container-fluid">
      				<div class="row">
      					
      					<div class="col-md-12">
      						<div class="card card-primary ">
      							<div class="card-header">
  									<h4 class="card-title">Add Guarantor</h4>
  								</div>
      							<form class="" method="post" id="guarantorForm" enctype="multipart/form-data">
      								
      								<div class="card-body">
      									<div class="row">
      										<div class="form-group col-6 mb-3">
												<label for="form">Guarantor Photo</label>
												<div class=" border p-3">
													<button class="btn btn-warning mb-3" type="button" id="selectImage">Select Image <i class="bi bi-file-person"></i></button><br>
													<input type="file" name="photo" id="photo" class="form-control"  style="display: none;" onchange="preview_image(event)" accept="images/png, images/jpg, images/jpeg">
													<img src="dist/img/avatar2.png" id="output_image" class="shadow-sm img-fluid img-responsive" alt="pic" width="140">
												</div>
												<em>Add a clear face</em>
											</div>
			      							<div class="form-group col-6 mb-3">
			      								<label>Select Borrower</label>
			      								<select  name="borrower_id" id="borrower_id" class="select2 form-control" data-placeholder="Select Borrower" data-dropdown-css-class="select2-purple" style="width:100%;" required="required">
			      									<option value=""></option>
			      									<?php echo $option;?>
			      								</select>
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
												<label for="form">Business Name</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-briefcase"></i></span>
													<input type="text" aria-label="Last name" name="business" id="business" class="form-control">
												</div>
											</div>
											<div class="form-group col-6">
												<label>Gender</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
													<select id="gender" name="gender" class="form-control">
														<option value="">Select</option>
														<option value="male">Male</option>
														<option value="female">Female</option>
													</select>
												</div>
											</div>
											<div class="form-group col-6 mb-3">
												<label for="form">ID No:</label>
												<div class="input-group mb-1">
													<span class="input-group-text"><i class="bi bi-file-person"></i></span>
													<input type="text" name="ID" id="ID" class="form-control" required>
												</div>
												<em>This can be NRC, PASSPORT or the document you deam fit</em>
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
												<em>Add country code without the symbol to be able to send SMSs</em>
											</div>
											<div class="form-group col-6">
												<label for="form">Date of Birth</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-calendar"></i></span>
													<input type="text" aria-label="dateofbirth" name="dateofbirth" id="dateofbirth" class="form-control">
												</div>
											</div>
											<div class="form-group col-6">
												<label>Working Status</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
													<select id="working_status" name="working_status" class="form-control">
														<option value=""></option>
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
											
											<div class="form-group col-12 mb-3">
												<label for="form">Guarantor Files</label>
												<div class="border p-3">
													<button class="btn btn-warning" type="button" id="selectFiles">Select Files <i class="bi bi-files"></i></button>
													<input type="file" name="files[]" id="files" class="form-control" style="display: none;" multiple onchange="javascript:updateList()">

													<div id="fileList"></div>
												</div>
											</div>
										</div>
										<div class="card-footer">
											<button class="btn btn-warning btn-lg w-50"  type="submit" id="addBtn" onclick="addBorrower(event)">Submit</button>
										</div>
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
		    $('.select2').select2();
		} );
	</script>
	<script>
		$(function(){
			$("#dateofbirth").datepicker({

				format: 'yyyy-mm-dd',
				autoclose:true
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
		fetchLoanOfficers("<?php echo $BRANCHID ?>");

	  	var input = document.getElementById('country');
	  	input.onchange = function () {
	    	localStorage['country'] = this.value;
	    	alert(this.value);
	  	}
	  	document.addEventListener('DOMContentLoaded', function () {
	     	var input = document.getElementById('country');
	     	if (localStorage['country']) { 
	         	input.value = localStorage['country'];
	     	}
	     	input.onchange = function () {
	          	localStorage['country'] = this.value;
	      	}
	  	});
	   
	   // images ------ 
	   	
		var selectImage = document.getElementById('selectImage');
  		var fileInput = document.getElementById('photo');
  		selectImage.addEventListener("click", (e) => {
  			$('#photo').click();
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
		
		addBorrower = function() {
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'borrowers/submitGuarantor';
			var guarantorForm = document.getElementById('guarantorForm');
			var firstname = document.getElementById('firstname');
			var ID = document.getElementById('ID');
			if (firstname.value === "") {
				errorNow("Firstname is required");
				firstname.focus();
				return false;
			}

			if (ID.value === "") {
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
			// $('.toastrDefaultError').click(function() {
	  //     		toastr.error("Error");
	  //     		toastr.options.progressBar = true;
	  //     		toastr.options.positionClass = "toast-top-center";

	  //  //    		toastr.options = {
			// 	// 	"closeButton": false,
			// 	// 	"debug": false,
			// 	// 	"newestOnTop": false,
			// 	// 	"progressBar": false,
			// 	// 	"positionClass": "toast-top-center",
			// 	// 	"preventDuplicates": false,
			// 	// 	"onclick": null,
			// 	// 	"showDuration": "300",
			// 	// 	"hideDuration": "1000",
			// 	// 	"timeOut": "5000",
			// 	// 	"extendedTimeOut": "1000",
			// 	// 	"showEasing": "swing",
			// 	// 	"hideEasing": "linear",
			// 	// 	"showMethod": "fadeIn",
			// 	// 	"hideMethod": "fadeOut"
			// 	// }
	  //   	});
		
		 $(document).on("click", ".removeBorrower", function(e){
	    	e.preventDefault();
	    	var id_number = $(this).attr('id');
	    	var branch_id = $(this).data('branch_id');
	    	var delete_id = $(this).data('id');
	    	var loggedParentId = '<?php echo $_SESSION['parent_id']?>';
	    	if(confirm("Confirm removing borrower? All loan information will also be removed")){
		    	$.ajax({
		    		url: 'borrowers/action',
		    		method:'post',
		    		
		    		data: {id_number:id_number, branch_id:branch_id, delete_id:delete_id, loggedParentId:loggedParentId},
		    		
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
	</script>
</body>
</html>