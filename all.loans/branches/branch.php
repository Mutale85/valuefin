<base href="https://loans.chumasolutions.com">
<?php 
  	require ("../includes/db.php");

	if (!isset($_COOKIE['userLoggedin']) && !isset($_SESSION['email'])) {?>
    	<script>
      		window.location = '../';
    	</script>
	<?php
  	}

	$option = $countries = '';
	$query = $connect->prepare("SELECT * FROM currencies");
	$query->execute();
	foreach ($query->fetchAll() as $row) {
		$option .= '<option value="'.$row['code'].'">'.$row['code'].'</option>';
		$countries .= '<option value="'.$row['id'].'">'.$row['country'].'</option>';
	}
	$branch_unique_id = "";
	$sql = $connect->prepare("SELECT branch_unique_id FROM branches ORDER BY id DESC ");
	$sql->execute(array($_SESSION['parent_id']));
	if ($sql->rowCount() > 0) {
		$row = $sql->fetch();
		if ($row) {
			$branch_unique_id = $row['branch_unique_id']+1;
		}
	}else{
		$branch_unique_id = 1000;
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Branches</title>
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
<?php
	
?>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content mt-5">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 pb-2 d-flex justify-content-between">
  							
  						</div>
      				</div>
      			</div>
      			<?php if($_SESSION['user_role'] == 'Admin'):?>
  								
  				<?php endif;?>	
      			<div class="container">
      				<div class="row">
      					<div class="col-md-12">
      						<div class="card card-warning card-outline mb-5">
      							<div class="card-body box-profile">
      								<a href="members/branches#branchForm" class="btn btn-outline-warning mb-3" type="button" ><i class="bi bi-plus-circle"></i> Add Branch</a>
      								<div class="table table-responsive mb-5 mt-5">
										<table id="branchesTable" class="cell-border" style="width:100%">
									        <thead>
									            <tr>
									            	<th>Branch Name</th>
									                <th>Location</th>
									                <th>Landline</th>
									                <th>Mobile</th>
									                <th>Login</th>
									               <?php if($_SESSION['user_role'] == 'Admin'):?> 
									                	<th>Actions</th>
									            	<?php endif;?>
									            </tr>
									        </thead>
									        <tbody id="fetchBranches" class="text-dark">

									        </tbody>
									    </table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="card card-primary">
								<div class="card-header">
									<h4 class="card-title">Add Branches</h4>
								</div>
								<form method="post" id="branchForm">
									<div class="card-body">
	  									<div class="row">
	      									<div class="form-group col-md-6 mb-3">
	      										<label>Branch name</label>
	      										<input type="text" name="branch_name" id="branch_name" class="form-control">
	      										<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
	      										<input type="hidden" name="branch_id" id="branch_id">
	      									</div>
	      									
	      									<input type="hidden" name="branch_unique_id" id="branch_unique_id" class="form-control" value="<?php echo $branch_unique_id?>" readonly>
	      									<div class="form-group col-md-6 mb-3">
	      										<label>Open Date</label>
	      										<input type="text" name="open_date" id="open_date" class="form-control">
	      									</div>
	      									<div class="form-group col-md-6 mb-3">
	      										<label>Branch Address</label>
	      										<input type="text" name="address" id="address" class="form-control">
	      									</div>
	      									<div class="form-group col-md-6 mb-3">
	      										<label>Branch City</label>
	      										<input type="text" name="city" id="city" class="form-control" placeholder="City">
	      									</div>
	      									<div class="form-group col-md-6 mb-3">
												<label>Country</label>
												<div class="">
													<select class="select2"  name="country" id="country" data-placeholder="Select Borrowers" data-dropdown-css-class="select2-purple" style="width: 100%;">
														<?php echo $countries;?>
													</select>
												</div>
											</div>
	      									<div class="form-group col-md-6 mb-3">
	      										<label>Branch landline</label>
	      										<input type="text" name="phone_landline" id="phone_landline" class="form-control" placeholder="Landine number">
	      									</div>
	      									<div class="form-group col-md-6 mb-3">
	      										<label>Branch Mobile</label>
	      										<input type="tel" id="phone" name="phone" class="form-control" onkeyup="complePhone(this.value)">
	      										<input type="hidden" name="phone_mobile" id="phone_mobile" class="form-control">
	      										<p id="result"></p>
	      									</div>

	      									<div class="form-group col-md-6 mb-3">
												<label>Currency</label>
												<div class="">
													<select class="select2"  name="currency" id="currency" data-placeholder="Select Borrowers" data-dropdown-css-class="select2-purple" style="width: 100%;">
														<?php echo $option;?>
													</select>
												</div>
											</div>
	      								</div>
	      							</div>
	      							<div class="card-footer justify-content-between">
										<button class="btn btn-secondary" type="submit" id="branchBtn" onclick="adBranch(event)">Submit</button>
										<button class="btn btn-secondary" type="submit" id="updatebranchBtn" onclick="updateBranch(event)" style="display: none;">Update</button>
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
		    $('#adminsTable').DataTable();
		    $("#branchesTable").DataTable();
		    // select
		    $('.select2').select2();
		    //datepicker
		    $("#open_date").datepicker({

				format: 'yyyy-mm-dd',
				autoclose:true,
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
	
	//========================================= ADDING BRANCHES ==================================
	adBranch = function(){
	event.preventDefault();
		var xhr = new XMLHttpRequest();
		var url = 'members/addBranch';
		var branchForm = document.getElementById('branchForm');
		xhr.open("POST", url, true);
		var branch_name = document.getElementById('branch_name').value;
		var data = new FormData(branchForm);
		xhr.onreadystatechange = function(){
			if (xhr.readyState == 4 && xhr.status == 200) {
				if (xhr.responseText === 'done') {
					successNow(branch_name + ' added to the database');
					setTimeout(function(){
						location.reload();
					}, 2000);
					
				}else{
					// alert(xhr.responseText);
					errorNow(xhr.responseText);
					setTimeout(function(){
						location.reload();
					}, 2000);
					// $("#groupBorrowerForm")[0].reset();
					document.getElementById("branchBtn").innerHTML = 'Submit';
					return false;
				}
				
			}
		}
		xhr.send(data);
		document.getElementById("branchBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
	}


	//================================================= LOCALSTORAGE ==========================================
	var input = document.getElementById('currency');
	input.onchange = function () {
		localStorage['currency'] = this.value;
		// alert(this.value);
	}
	document.addEventListener('DOMContentLoaded', function () {
	 	var input = document.getElementById('currency');
	 	if (localStorage['currency']) { 
	     	input.value = localStorage['currency'];
	 	}
	 	input.onchange = function () {
	      	localStorage['currency'] = this.value;
	  	}
	});

	var country_input = document.getElementById('country');
	country_input.onchange = function () {
		localStorage['country'] = this.value;
	}
	document.addEventListener('DOMContentLoaded', function () {
	 	var country_input = document.getElementById('country');
	 	if (localStorage['country']) { 
	     	country_input.value = localStorage['country'];
	 	}
	 	country_input.onchange = function () {
	      	localStorage['country'] = this.value;
	  	}
	});

	
	// ================================================================== DISPLAY BRANCHES ===========================

	function manageBranches(){
		var xhr = new XMLHttpRequest();
		var url = 'members/fetchBranches';
		
		xhr.open("POST", url, true);
		var branch_name = document.getElementById('branch_name').value;
		var data = 'member_id=<?php echo $_SESSION['parent_id']?>';
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.onreadystatechange = function(){
			if (xhr.readyState == 4 && xhr.status == 200) {
				document.getElementById('fetchBranches').innerHTML = xhr.responseText
			}
		}
		xhr.send(data);
	}
	manageBranches();

	$(document).on("click", ".editBranch", function(e){
		e.preventDefault();
		var branch_id = $(this).data("id");
		$("#modalBranches").modal("show");
		$.ajax({
			url:"members/edit",
			method:"post",
			data:{branch_id:branch_id},
			dataType:"JSON",
			beforeSend:function(){

			},
			success:function(data){
				$("#branch_name").val(data.branch_name);
				$("#open_date").val(data.open_date);
				$("#address").val(data.address);
				$("#city").val(data.city);
				$("#phone_landline").val(data.phone_landline);
				$("#phone_mobile").val(data.phone_mobile);
				$("#min_amount").val(data.min_amount);
				$("#max_amount").val(data.max_amount);
				$("#min_interest").val(data.min_interest);
				$("#max_interest").val(data.max_interest);
				$("#branch_id").val(data.id);
				$("#updatebranchBtn").show();
				$("#branchBtn").hide();
			}
		})
	})

	//=========================== DELETING ===========================
		$(document).on("click", ".deleteBranch", function(e){
			e.preventDefault();
			var delete_branch_id = $(this).data("id");
			if (confirm("This will delete everything associated with the branch")) {
				$.ajax({
					url:"members/edit",
					method:"post",
					data:{delete_branch_id:delete_branch_id},
					beforeSend:function(){

					},
					success:function(data){
						if (data === 'done') {
							successNow("Branch Removed With all its members and loans");
							setTimeout(function(){
								location.reload();
							}, 2000);
						}else{
							errorNow(data);
						}
					}
				})
			}else{
				return false;
			}
			// this will delete everything about the branch.
		})

	//======================================================== EDIT AND UPDATE BRANCH DETAILS ======================================
		updateBranch = function(){
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'members/updateBranch';
			var branchForm = document.getElementById('branchForm');
			xhr.open("POST", url, true);
			var branch_name = document.getElementById('branch_name').value;
			var data = new FormData(branchForm);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					if (xhr.responseText === 'done') {
						successNow(branch_name + ' Update and added to the database');
						manageBranches();
						document.getElementById("updatebranchBtn").innerHTML = 'Update';

					}else{
						errorNow(xhr.responseText);
						document.getElementById("updatebranchBtn").innerHTML = 'Update';
						return false;
					}
					
				}
			}
			xhr.send(data);
			document.getElementById("updatebranchBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}



		//================================================================= deleting branch details ends here


		//============================================================== photo preview ============================----

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
	      var result = document.querySelector("#result");
	      var phone_mobile = document.getElementById("phone_mobile");
	      if (phone == "") {
	        result.textContent = "Add Your Number";
	        return false;
	      }
	        if (isValid === true) {
	          result.textContent = "Number: " + number + ", is valid";
	          phone_mobile.value = number;
	        }else if(isValid === false){
	          result.textContent = "Number: " + number + ", is invalid";
	          phone_mobile.value = number;
	        }
	    }
	
	</script>
</body>
</html>