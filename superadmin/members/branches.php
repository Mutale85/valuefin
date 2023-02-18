
<base href="http://localhost/valuefin.co/superadmin/">
<?php 
  	require ("../../includes/db.php");
	if(!isset($_SESSION['user_role'])){
        header("location:../signout");
        // echo "Not logged in";
    }

	// require ("../addons/tip.php"); 
	$option = $countries = '';
	$query = $connect->prepare("SELECT * FROM currencies");
	$query->execute();
	foreach ($query->fetchAll() as $row) {
		$option .= '<option value="'.$row['code'].'">'.$row['code'].'</option>';
		$countries .= '<option value="'.$row['id'].'">'.$row['country'].'</option>';
	}
	
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Branches</title>
	<?php include("../addon_header.php");?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light">
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
												<th>Phone</th>
												<th>Clicks</th>
												<th>Actions</th>
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
												<select class="form-control"  name="country" id="country" >
													<?php echo $countries;?>
												</select>
											</div>
										</div>
										
										<div class="form-group col-md-6 mb-3">
											<label>Branch Mobile</label>
											<input type="tel" id="phone" name="phone" class="form-control" onkeyup="complePhone(this.value)">
											<input type="hidden" name="phone_mobile" id="phone_mobile" class="form-control">
											<p id="result"></p>
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
	<?php include("../addon_footer.php")?>
	<script>
		$(document).ready( function () {
		    $('#adminsTable').DataTable();
		    $("#branchesTable").DataTable();
		    
		    $("#open_date").datepicker({

				format: 'yyyy-mm-dd',
				autoclose:true,
			});
		});

	
	
	//========================================= ADDING BRANCHES ==================================
	adBranch = function(){
	event.preventDefault();
		var xhr = new XMLHttpRequest();
		var url = 'members/parsers/submitNewBranch';
		var branchForm = document.getElementById('branchForm');
		xhr.open("POST", url, true);
		var branch_name = document.getElementById('branch_name').value;
		var data = new FormData(branchForm);
		xhr.onreadystatechange = function(){
			if (xhr.readyState == 4 && xhr.status == 200) {
				var result = xhr.responseText;
				successNow(result);
				
			}
		}
		xhr.send(data);
		document.getElementById("branchBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
	}


	//================================================= LOCALSTORAGE ==========================================
	var input = document.getElementById('currency');
	
	
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
		var url = 'members/parsers/fetchBranches';
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
			url:"members/parsers/edit",
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
				$("#country").val(data.country);
				$("#phone_mobile").val(data.phone_mobile);
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
					url:"members/parsers/edit",
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
			var url = 'members/parsers/updateBranch';
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
	        utilsScript: "../intl.17/build/js/utils.js",
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