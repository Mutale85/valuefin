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
	$branch_options = "";
	$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
	$sql->execute(array($_SESSION['parent_id']));
	foreach ($sql->fetchAll() as $row) {
		$branch_options .= '<option value="'.$row['id'].'">'.$row['branch_name'].'</option>';
	}
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
  							<h4> <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $BRANCHID))?> BRANCH </h4>
  							<?php if($_SESSION['user_role'] == 'Admin'):?>
  								<a href="members/add-staff-members" class="btn btn-outline-primary addStaff" type="button"  ><i class="bi bi-person"></i> New Admin</a>
  							<?php endif;?>
  						</div>
      				</div>
      			</div>
      			
      			<div class="container-fluid border-top pt-3 gridViewDiv">
      				<div class="row">
  						<?php
		      				$query = $connect->prepare("SELECT * FROM admins WHERE parent_id = ? ");
							$query->execute(array($_SESSION['parent_id']));
							if ($query->rowCount() > 0) {
								foreach ($query->fetchAll() as $rows) {
									extract($rows);
									$_SESSION['parent_id'] = $parent_id;
									if ($photo == "") {
										$photo 	= 'dist/img/user2-160x160.jpg';
									}else{
										$photo 	= 'members/adminphotos/'.$photo;
									}
									$staff_id 	= $id;
									$parent_id 	= $parent_id;
									if ($position == 'Admin') {
										if($activate == '0'){
											$access = '<a href="'. $staff_id.'" class="nav-link allow_access ">Access  <span class="float-right badge bg-success"> Allow Access</span></a>';
										}elseif($activate == '1'){
											$access = '<a href="'. $staff_id.'" class="nav-link deny_access">Access  <span class="float-right badge bg-danger"> Deny Access</span></a>';
										}
									}
									
									if ($position == 'Admin') {
										$btn = '<li class="nav-item"><a href="members/staff-member-edit?staff_id='.base64_encode($staff_id).'" class="nav-link staff-member-edit" data-id="'.$id.'">Edit <span class="float-right badge bg-primary">Edit  <i class="bi bi-pencil-square"></i> </span></a></li>';
										$title = 'Super Admin';
									}else{
										$btn = '<li class="nav-item">
											<a href="members/staff-member-edit?staff_id='.base64_encode($staff_id).'" class="nav-link staff-member-edit" data-id="'.$id.'"> Edit <span class="float-right badge bg-primary">Edit <i class="bi bi-pencil-square"></i> </span></a></li>

											<li><a href="" class="nav-link deleteAdmin" data-id="'.$staff_id.'"> Remove  <span class="float-right badge bg-danger"> Remove <i class="bi bi-trash"></i> </span></a></li>
										';
										$title = $position;
									}
								?>
			      			
						        <div class="col-md-4">
							        <div class="card card-widget widget-user-2">
						              	<div class="widget-user-header bg-warning">
						                	<div class="widget-user-image">
						                  		<img class="img-circle elevation-2" src="<?php echo $photo?>" alt="<?php echo $photo?>" style="width:70px; height:70px; border-radius: 50%">
						                	</div>
						                	<h3 class="mr-4 widget-user-username"><?php echo $firstname; ?> <?php echo $lastname; ?></h3>
						                	<h5 class="mr-4 widget-user-desc"><?php echo $title?></h5>
						              	</div>
						              	<div class="card-footer p-0">

						                	<ul class="nav flex-column">
						                		<?php echo allowedBranches($connect, $staff_id, $parent_id)?>
							                  	<li class="nav-item">
							                    	<a href="javascript:void(0)" class="nav-link">
							                      		Phone <span class="float-right badge bg-secondary"><?php echo $phonenumber?></span>
							                    	</a>
							                  	</li>
							                  	<li class="nav-item">
							                    	<a href="javascript:void(0)" class="nav-link">
							                      		Email <span class="float-right badge bg-primary"><?php echo strtolower($email)?></span>
							                    	</a>
							                  	</li>
							                  	
						                  		<?php if ($_SESSION['user_role'] != 'Admin'):?>
						                  		<?php else:?>
							                  		<?php echo $access ?>
							                  	<?php endif;?>

						                  		<?php if ($_SESSION['user_role'] != 'Admin'):?>
						                  		<?php else:?>
							                  		<?php echo $btn?>
							                  	<?php endif;?>

							                  	<li class="nav-item">
							                    	<a href="<?php echo $staff_id?>" class="nav-link callForm" id="<?php echo $staff_id?>">
							                      		Add More Data <span class="float-right badge bg-success"><i class="bi bi-person-plus"></i> More Info</span>
							                    	</a>
							                  	</li>
							                  	<!-- <li class="border-bottom"></li>
							                  	<li class="nav-item">
							                    	<a href="new-payroll?parent_id=<?php echo $_SESSION['parent_id']?>&staff-id=<?php echo $staff_id?>" class="nav-link" id="<?php echo $staff_id?>">
							                      		Create Payroll <span class="float-right "><i class="bi bi-credit-card"></i> Payroll</span>
							                    	</a>
							                  	</li> -->
							                </ul>
						              	</div>
						            </div>
						        </div>
						<?php } }?>
      				</div>
      			</div>
      			<!-- Modal -->
      			<div class="modal fade" id="moreInfoModal">
			        <div class="modal-dialog modal-xl">
			          	<div class="modal-content">
			          		<form method="post" id="moreInfoForm" enctype="multipart/form-data">
					            <div class="modal-header">
					              	<h4 class="modal-title">Add More Info <i class="bi bi-person-plus"></i></h4>
					              	<button type="button" class="close" data-bs-dismiss="modal" onclick="clearForm()" aria-label="Close">
					                	<span aria-hidden="true">&times;</span>
					              	</button>
					            </div>
				            	<div class="modal-body">  									
  									<div class="card-body">
  										<div class="row">
  											<div class="form-group col-md-4 mb-3">
												<label>Man Number</label>
												<input type="text" name="man_number" id="man_number" class="form-control" value="" required>
											</div>
											<div class="form-group col-md-4 mb-3">
												<label>Bank Name</label>
												<input type="text" name="bank_name" id="bank_name" class="form-control" value="" required>
											</div>
											<div class="form-group col-md-4 mb-3">
												<label>Account Number</label>
												<input type="text" name="account_number" id="account_number" class="form-control" value="" required>
											</div>
											<div class="form-group col-md-4 mb-3">
												<label>Gender</label>
												<select class="form-control" name="gender" id="gender" required>
													<option value="" disabled selected>Select</option>
													<option value="Female">Female</option>
													<option value="Male">Male</option>
												</select>
											</div>
											<div class="form-group col-md-4 mb-3">
												<label>Country</label>
												<input type="text" name="country" id="country" class="form-control" value="" required>
											</div>
											<div class="form-group col-md-4 mb-3">
												<label>City</label>
												<input type="text" name="city" id="city" class="form-control" value="" required>
											</div>
										</div>										
										<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID?>">
										<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
										<input type="hidden" name="info_id" id="info_id" value="">
										<input type="hidden" name="staff_id" id="staff_id" value="">
										
									</div>
			            		</div>
					            <div class="modal-footer justify-content-between">
					              	<button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal" onclick="clearForm()">Close</button>
					              	<button type="submit" class="btn btn-outline-dark" id="allBtn">Save changes</button>
					            </div>
					        </form>
			          	</div>
			        </div>
			    </div>
			    <!-- End of Modal -->
			    <div class="modal fade" id="addStaffModal">
			        <div class="modal-dialog modal-xl">
			          	<div class="modal-content">
			          		<form method="post" id="adminsForm" enctype="multipart/form-data">
			          			<div class="modal-header">
					              	<h4 class="modal-title">Add New Staff Member <i class="bi bi-person-plus"></i></h4>
					              	<button type="button" class="close" data-bs-dismiss="modal" onclick="clearForm()" aria-label="Close">
					                	<span aria-hidden="true">&times;</span>
					              	</button>
					            </div>
				            	<div class="modal-body">  									
  									<div class="card-body">
  										<!-- Form Comin Here -->
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
													<input type="text" name="firstname" id="firstname" class="form-control" required>
												</div>
											</div>
											<div class="form-group col-md-4">
												<label for="form">Lastname</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-people"></i></span>
													<input type="text" name="lastname" id="lastname" class="form-control" required>
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
													<input type="hidden" name="phone" id="phone" class="form-control" required>
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
												$branch_options = $all_branch_options = "";
												$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
												$sql->execute(array($_SESSION['parent_id']));
												$results = $sql->fetchAll();
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
  								</div>

			          			<div class="modal-footer justify-content-between">
					              	<button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal" onclick="clearForm()">Close</button>
					              	<button type="submit" class="btn btn-outline-dark" id="adminBtn">Save changes</button>
					            </div>
			          		</form>
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
	<?php 
		if(isset($_GET['accountDetails'])){?>
			<script>
				var staff_id = "<?php echo $_GET['accountDetails']?>";
				document.getElementById('staff_id').value = "<?php echo $_GET['accountDetails']?>";
				
				$(function(){
					$.ajax({
						url:"members/edit",
						method:"post",
						data:{staff_info_id:staff_id},
						dataType:"JSON",
						success:function(data){
							$("#man_number").val(data.man_number);
							$("#bank_name").val(data.bank_name);
							$("#account_number").val(data.account_number);
							$("#gender").val(data.gender);
							$("#country").val(data.country);
							$("#city").val(data.city);
							$("#info_id").val(data.id);
						}
					})
				})
				$("#moreInfoModal").modal("show");
			</script>
	<?php	}

	?>
	<script>

		function clearForm(){
			$("#moreInfoForm")[0].reset();
		}
		$(document).ready( function () {
		    $('#adminsTable').DataTable();
		    $("#branchesTable").DataTable();
		    // select
		    $('.select2').select2();
		    //datepicker
		    $("#open_date").datepicker({

				format: 'yyyy-mm-dd'
			});

			// 
			$(".addStaff").click(function(e){
				e.preventDefault();
				$("#addStaffModal").modal("show");
			})
			// 

			$(".callForm").click(function(e){
				e.preventDefault();
				var staff_id = $(this).attr("href");
				document.getElementById('staff_id').value = staff_id;
				$("#moreInfoModal").modal("show");
				$.ajax({
					url:"members/edit",
					method:"post",
					data:{staff_info_id:staff_id},
					dataType:"JSON",

					success:function(data){
						$("#man_number").val(data.man_number);
						$("#bank_name").val(data.bank_name);
						$("#account_number").val(data.account_number);
						$("#gender").val(data.gender);
						$("#country").val(data.country);
						$("#city").val(data.city);
						$("#info_id").val(data.id);
					}
				})
			})

			// submit the form
			$("#moreInfoForm").submit(function(e){
				e.preventDefault();
				var moreInfoForm = document.getElementById('moreInfoForm');
				var data = new FormData(moreInfoForm);
				var url = 'members/members-info-submit';
				$.ajax({
					url:url+'?<?php echo time()?>',
					method:"post",
					data:data,
					cache : false,
					processData: false,
					contentType: false,
					beforeSend:function(){
						$("#allBtn").html("<i class='fa fa-spinner fa-spin'></i>");
						$("#allBtn").attr("disabled", "disabled");
					},
					success:function(data){
						successNow(data);
						$("#allBtn").html("Save Changes");
						$("#allBtn").removeAttr("disabled");
					}
				})
			})


		});

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
	

		// =============== ADDING STAFF MEMBERS =============

		$("#adminsForm").submit(function(e){
			e.preventDefault();
			var adminsForm = document.getElementById('adminsForm');
			var data = new FormData(adminsForm);
			var url = 'members/submitNewStaff';
			$.ajax({
				url:url+'?<?php echo time()?>',
				method:"post",
				data:data,
				cache : false,
				processData: false,
				contentType: false,
				beforeSend:function(){
					$("#adminBtn").html("<i class='fa fa-spinner fa-spin'></i>");
					$("#adminBtn").attr("disabled", "disabled");
				},
				success:function(data){
					successNow(data);
					$("#adminBtn").html("Save Changes");
					$("#adminBtn").removeAttr("disabled");
				}
			})
		})

	// ============================== ADD ADMINS ==========================================

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
	

	$(document).on("click", ".deleteAdmin", function(e){
		e.preventDefault();
		var delete_admin_id = $(this).data("id");
		// alert(delete_admin_id);
		if(confirm("Confirm you wish to remove staff member / employee")){
			$.ajax({
				url:"members/edit",
				method:"post",
				data:{delete_admin_id:delete_admin_id},
				beforeSend:function(){

				},
				success:function(data){
					if (data === 'done') {
						successNow("Staff Deleted");
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
	})

	$(document).on("click", ".allow_access", function(e){
		e.preventDefault();
		var allow_access_id = $(this).attr("href");
		if(confirm("Confirm giving permisions to access the system?")){
			$.ajax({
				url:"members/edit",
				method:"post",
				data:{allow_access_id:allow_access_id},
				beforeSend:function(){

				},
				success:function(data){
					if (data === 'done') {
						successNow("Staff Permitted to log in to the system");
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
	})

	$(document).on("click", ".deny_access", function(e){
		e.preventDefault();
		var deny_access_id = $(this).attr("href");
		if(confirm("Confirm revoking permisions to access the system?")){
			$.ajax({
				url:"members/edit",
				method:"post",
				data:{deny_access_id:deny_access_id},
				beforeSend:function(){

				},
				success:function(data){
					if (data === 'done') {
						successNow("Staff has been banned from having access to the system !");
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
	})

</script>
</body>
</html>