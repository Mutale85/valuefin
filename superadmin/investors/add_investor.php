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
		$branch_options .= '<option data-tokens="'.$row['id'].'">'.$row['branch_name'].'</option>';
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
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css">
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
		/*.iti { width: 90%; }*/
		/*.iti { width: 100%; }
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
		}*/
	</style>
</head>
<?php
	
?>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content  mt-5">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 pb-2 d-flex justify-content-between">
  							<h1 class="h3"> <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $BRANCHID))?> BRANCH </h1>
  							<?php if($_SESSION['user_role'] == 'Admin'):?>
  								<button class="btn btn-outline-primary" type="button"  data-toggle="modal" data-target="#modalInvestor"><i class="bi bi-person"></i> Add Investor</button>
  							<?php endif;?>
  						</div>
      				</div>
      			</div>
      			<div class="container-fluid">
      				<div class="row">
      					<div class="col-md-12 mb-5">
      						<a href="" class="gridView text-secondary btn btn-secondary" style="font-size: 2em;"><i class="bi bi-grid-3x3-gap text-white"></i></a>
      						<a href="" class="listView text-secondary btn btn-secondary" style="font-size: 2em;"><i class="bi bi-list text-white"></i></a>
      					</div>
      				</div>
      			</div>
      			<div class="container-fluid border-top pt-3 gridViewDiv">
      				<div class="row">
  						<?php
		      				$query = $connect->prepare("SELECT * FROM investors WHERE parent_id = ? ");
							$query->execute(array($_SESSION['parent_id']));
							if ($query->rowCount() > 0) {
								foreach ($query->fetchAll() as $rows) {
									// SELECT `id`, `photo`, `parent_id`, `title`, `firstname`, `lastname`, `working_status`, `id_type`, `id_number`, `gender`, `investor_country`, `email`, `phone`, `address`, `date_added`
									extract($rows);
									$_SESSION['parent_id'] = $parent_id;
									if ($photo == "") {
										$photo 	= 'dist/img/user2-160x160.jpg';
									}else{
										$photo 	= 'investors/investorsfiles/'.$photo;
									}
									$investor_id 	= $id;
									$parent_id 	= $parent_id;
									$user_role = "Investor";
								?>
			      			<div class="col-md-4">
	      						<div class="card card-widget widget-user">
					              	<div class="widget-user-header bg-info">
					                	<h3 class="widget-user-username"><?php echo $firstname; ?> <?php echo $lastname; ?></h3>
					                	<h5 class="widget-user-desc"><?php echo $user_role?>, <small><?php echo getCountryName($connect, $investor_country); ?></small></h5>
					             	</div>
					              	<div class="widget-user-image">
					                	<img class="img-circle elevation-2" src="<?php echo $photo?>" alt="<?php echo $photo?>">
					              	</div>
						            <div class="card-footer">
						                <div class="row">
						                  <div class="col-sm-6 border-right">
						                    <div class="description-block">
						                      <h5 class="description-header"><small><em><?php echo strtolower($email)?></em> </small></h5>
						                    </div>

						                  </div>
						                  <div class="col-sm-6">
						                    <div class="description-block">
						                      <h5 class="description-header"><?php echo $phone?></h5>
						                    </div>
						                  </div>
						                  <div class="col-sm-12">
						                    <div class="description-block">
						                      <h5 class="description-header"><?php echo $address?><h5>
						                    </div>
						                  </div>
						                  <?php if($_SESSION['user_role'] == 'Admin'){?>
						                  <div class="col-md-12 d-flex justify-content-between">
						                  		<a href="members/editAdmin?investor_id=<?php echo base64_encode($investor_id)?>" class="editAdmin text-primary" data-id="<?php echo $investor_id?>"><i class="bi bi-pencil-square"></i></a>
												<a href="" class="deleteAdmin text-danger" data-id="<?php echo $investor_id?>"><i class="bi bi-trash"></i></a>
						                  	</div>
						                  	<?php }else{}?>
						                </div>
						            </div>
					            </div>
					        </div>
						<?php } }else{
								echo "<h4 class='text-center'>No Investors have been Added</h4>";
						}?>
      				</div>
      			</div>
      			<div class="container-fluid listViewDiv" style="display: none;">
      				<div class="row">
      					<div class="col-md-12">
      						
      						<div class="card card-warning card-outline mb-5">
      							<div class="card-body box-profile">
      								
      								<div class="table table-responsive">
			      						<table id="adminsTable" class="cell-border" style="width:100%">
									        <thead>
									            <tr>
									            	<th>Photo</th>
									                <th>Names</th>
									                <th>Gender</th>
									                <th>Phone</th>
									                <th>Email</th>
									                <th>Address</th>
									                <th>Country</th>
									                <?php if($_SESSION['user_role'] == 'Admin'):?>
									                <th>Edit</th>
									                <th>Delete</th>
									                <?php else:?>
									                <?php endif;?>
									            </tr>
									        </thead>
									        <tbody class="text-dark">
									        	<?php
									        		$query = $connect->prepare("SELECT * FROM investors WHERE parent_id = ? ");
													$query->execute(array($_SESSION['parent_id']));
													if ($query->rowCount() > 0) {
														foreach ($query->fetchAll() as $row) {
															$_SESSION['parent_id'] = $row['parent_id'];
															if ($row['photo'] == "") {
																$photo 	= 'dist/img/user2-160x160.jpg';
															}else{
																$photo 	= 'investors/investorsfiles/'.$row['photo'];
															}
															$staff_id 	= $row['id'];
															$parent_id 	= $row['parent_id'];
														?>
															<tr>
																<td><img src="<?php echo $photo?>" class="img-fluid img-rounded" width="60" height="60"> </td>
																<td><?php echo $row['firstname']?> <?php echo $row['lastname']?></td>
																<td><?php echo $row['gender']?></td>
																<td><?php echo $row['phone']?></td>
																<td><?php echo $row['email']?> </td>
																<td><?php echo $row['address']?></td>
																<td><?php echo getCountryName($connect, $row['investor_country'])?></td>
																<?php if($_SESSION['user_role'] == 'Admin'):?>
																<td>
																	<a href="members/editAdmin?investor_id=<?php echo base64_encode($investor_id)?>" class="editAdmin text-primary" data-id="<?php echo $investor_id?>"><i class="bi bi-pencil-square"></i></a>
																</td>
																<td>
																	<a href="" class="deleteAdmin text-danger" data-id="<?php echo $investor_id?>"><i class="bi bi-trash"></i></a>
																</td>
																<?php else:?>
									                			<?php endif;?>
															</tr>
													<?php		
														}
														
													}else{
														
													}
									        	?>
									     	</tbody>
									    </table>
									</div>
      							</div>
      						</div>
      					</div>
      				</div>
      			</div>
      			
				<!-- Editing Modal -->
				<div class="modal fade" id="modalInvestor">
					<div class="modal-dialog modal-lg">
						<div class="modal-content bg-warning">
							<form class="" method="post" id="investorsForm" enctype="multipart/form-data">
								<div class="modal-header">
									<h4 class="modal-title">Investor Form</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="form-group col-md-12 mb-3">
											<label for="form">Staff Photo</label>
											<div class="">
												<button class="btn btn-secondary mb-2" type="button" id="selectImage">Select Image <i class="bi bi-file-person"></i></button>
												<br>
												<input type="file" name="photo" id="photo" class="form-control" onchange="preview_admin_image(event)" style="display: none;">
												<img src="<?php echo $photo?>" width="150" alt="profile" id="output_image">
												<input type="hidden" name="edit_photo" id="edit_photo">
											</div>
											<em>Add a clear face of the admin</em>
										</div>
										<input type="hidden" name="id" id="id">
										<input type="hidden" name="parent_id" id="parent_id" value="<?php echo($_SESSION['parent_id'])?>">
										<div class="form-group col-md-6">
											<label for="form">Title</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-people"></i></span>
												<select class="form-control" name="title" id="title" required>
													<option value=""></option>
													<option value="Mr.">Mr.</option>
													<option value="Miss.">Miss.</option>
													<option value="Mrs.">Mrs.</option>
													<option value="Dr.">Dr.</option>
													<option value="Prof.">Prof.</option>
													<option value="Rev.">Rev.</option>
												</select>
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="form">Firstname</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-people"></i></span>
												<input type="text" name="firstname" id="firstname" class="form-control" required>
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="form">Lastname</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-people"></i></span>
												<input type="text" name="lastname" id="lastname" class="form-control" required>
											</div>
										</div>
										<div class="form-groups col-md-6">
											<label>Working Status</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
												<select id="working_status" name="working_status" class="form-control" required>
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
										<div class="form-group col-md-12">
											<label for="form">ID Type And Number</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-people"></i></span>
												<select class="form-control" name="id_type" id="id_type" required>
													<option value=""></option>
													<option value="NRC">NRC</option>
													<option value="Passport">Passport</option>
													<option value="Driving Licence">Driving Licence</option>
												</select>
												<input type="text" name="id_number" id="id_number" class="form-control" required placeholder="ID number">
											</div>
										</div>
										
										<div class="form-group col-md-6 mb-3">
											<label>Gender</label>
											<select class="form-control"  name="gender" id="gender" required>
												<option value="Male">Male</option>
												<option value="Female">Female</option>
											</select>
										</div>
										<div class="form-group col-md-6 mb-3">
											<label>Country</label>
											<div class="">
												<select class="form-control"  name="investor_country" id="investor_country" required>
													<?php echo $countries ?>
												</select>
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="form">Email</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-at"></i></span>
												<input type="text" name="email" id="email" class="form-control">
											</div>
										</div>
										<!-- <div class="form-group col-md-6">
											<label for="form">Phone</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-phone"></i></span>
												<input type="text" name="phone" id="phone" class="form-control" required>
											</div>
										</div> -->
										<div class="form-group col-md-6 mb-3">											
											<label for="form">Phone</label>
											<div class="input-group mb-3">
												<input type="tel" id="phone" name="phone" class="form-control" onkeyup="complePhone(this.value)">
												<input type="text" name="borrower_phone" id="borrower_phone" class="form-control" readonly>
											</div>
											<p id="result"></p>
											<p id="error4" style="color: red; display: none;">Enter Your Valid Mobile Number</p>
										</div>
										<div class="form-group col-md-12 mb-3">
											<label>Address</label>
											<textarea class="form-control" rows="5" cols="5" name="address" id="address" required></textarea>
										</div>
									</div>
								</div>
								<div class="modal-footer justify-content-between">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button class="btn btn-secondary w-50 " type="submit" id="investorBtn">Submit</button>
										<button class="btn btn-secondary w-50 " type="submit" id="adminUpdateBtn" style="display: none;">Update</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- End of edit modal -->
      		</section>
		</div>
		<aside class="control-sidebar control-sidebar-dark"></aside>
	</div>
	<?php include("../footer_links.php")?>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	<script src="plugins/select2/js/select2.full.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js"></script>
	<script src="plugins/toastr/toastr.min.js"></script>
	<script>
		$(document).ready( function () {
		    $('#adminsTable').DataTable();
		    $("#branchesTable").DataTable();
		    // select
		    // $('.select2').select2();
		    
		    //datepicker
		    $("#open_date").datepicker({

				format: 'yyyy-mm-dd'
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
			});

			$(document).on("click",".editAdmin", function(e){
				e.preventDefault();
				$("#modalInvestor").modal("show");
				var investor_id = $(this).data("id");
				// alert(investor_id);
				$.ajax({
					url:"investors/editInvestor",
					method:"post",
					data:{investor_id:investor_id},
					dataType:"JSON",
					success:function(data){
						$('#id').val(data.id);
						$('#edit_photo').val(data.photo);
						$('#parent_id').val(data.parent_id);
						$('#title').val(data.title);
						$('#firstname').val(data.firstname);
						$('#lastname').val(data.lastname);
						$('#working_status').val(data.working_status);
						$('#id_type').val(data.id_type);
						$('#id_number').val(data.id_number);
						$('#gender').val(data.gender);
						$('#investor_country').val(data.investor_country);
						$('#email').val(data.email);
						$('#borrower_phone').val(data.phone);
						$('#address').val(data.address);
					}
				})

			})

			$(document).on("click",".deleteAdmin", function(e){
				e.preventDefault();
				// $("#modalInvestor").modal("show");
				var investor_id_delete = $(this).data("id");
				// alert(investor_id_delete);
				if (!confirm("You wish to remove this investor? It cannot be undone")) {
					return false;
				}else{
					$.ajax({
						url:"investors/editInvestor",
						method:"post",
						data:{investor_id_delete:investor_id_delete},
						success:function(data){
							errorNow(data);
							setTimeout(function(){
								location.reload();
							}, 2000);
							
						}
					})
				}

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
		
	//================================================= LOCALSTORAGE ==========================================


	var investor_country = document.getElementById('investor_country');
	investor_country.onchange = function () {
		localStorage['investor_country'] = this.value;
	}
	document.addEventListener('DOMContentLoaded', function () {
	 	var investor_country = document.getElementById('investor_country');
	 	if (localStorage['investor_country']) { 
	     	investor_country.value = localStorage['investor_country'];
	 	}
	 	investor_country.onchange = function () {
	      	localStorage['investor_country'] = this.value;
	  	}
	});
	
	// ================================================================== DISPLAY BRANCHES ===========================


</script>
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
	

	// ============================== ADD ADMINS ==========================================

	$(function(){
		$("#investorsForm").submit(function(e){
			e.preventDefault();
			// alert("Hello");
			var saveLoan = document.getElementById('saveLoan');
			var investorsForm = document.getElementById('investorsForm');
			var data = new FormData(investorsForm);
			var url = 'investors/submitInvestor';
			$.ajax({
				url:url+'?<?php echo time()?>',
				method:"post",
				data:data,
				cache : false,
				processData: false,
				contentType: false,
				beforeSend:function(){
					$("#investorBtn").html("<i class='fa fa-spinner fa-spin'></i>");
				},
				success:function(data){
					if (data === 'done') {
						successNow("Investor Sumitted");
						setTimeout(function(){
							location.reload();
						}, 2000);
						$("#investorBtn").html("Submit");
					}else if(data === 'update'){
						successNow("Investor Details Updated");
						setTimeout(function(){
							location.reload();
						}, 2000);
					}else{
						errorNow(data);
						$("#investorBtn").html("Submit");
						return false;
					}
				}
			})
		})
	})

	// ======================== display admins ------------

	// manageStaff();
	$.extend( true, $.fn.dataTable.defaults, {
	    "searching": true,
	    "ordering": false
	} );

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
</body>
</html>