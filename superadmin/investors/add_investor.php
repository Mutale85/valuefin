<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php"); 
	
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
	<title>Investors </title>
	<?php include("../addon_header.php");?>
</head>
<?php
	
?>
<body class="layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light p-2">
      			
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12 mb-5">
						<button class="btn btn-outline-primary" type="button"  data-toggle="modal" data-target="#modalInvestor"><i class="bi bi-person"></i> Add Investor</button>
						
					</div>
				</div>
			</div>

			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6">
						<div class="card card-ouline-secondary">
							<div class="card-header">
								<h4 class="card-title">Investors Form</h4>
							</div>
							<div class="card-body">
								<form class="" method="post" id="investorsForm" enctype="multipart/form-data">
									<div class="row">
										<div class="form-group col-md-12 mb-3">
											<label for="form">Investor Photo</label>
											<div class="">
												<button class="btn btn-secondary mb-2" type="button" id="selectImage">Add Photo <i class="bi bi-file-person"></i></button>
												<br>
												<input type="file" name="photo" id="photo" class="form-control" onchange="preview_admin_image(event)" style="display: none;">
												<img src="dist/img/user2-160x160.jpg" width="100" alt="profile" id="output_image">
												<input type="hidden" name="edit_photo" id="edit_photo">
											</div>
											<em>Add a clear face</em>
										</div>
										<input type="hidden" name="id" id="id">
										<input type="hidden" name="parent_id" id="parent_id" value="<?php echo($_SESSION['parent_id'])?>">
										<div class="form-group col-md-4">
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
										
										<div class="form-group col-md-6 mb-3">											
											<label for="form">Phone</label>
											<div class="input-group mb-3">
												<input type="tel" id="investor_phone" name="investor_phone" class="form-control" onkeyup="getPhone(this.value)">
												<input type="hidden" name="phonenumber" id="phonenumber" class="form-control" readonly>
											</div>
											<p id="result"></p>
											<p id="error4" style="color: red; display: none;">Enter Your Valid Mobile Number</p>
										</div>
										<div class="form-group col-md-12 mb-3">
											<label>Address</label>
											<textarea class="form-control" rows="5" cols="5" name="address" id="address" required></textarea>
										</div>
										<div class="form-group col-md-6">
											<label for="form">Amount Invested</label>
											<div class="input-group mb-3">
												<span class="input-group-text">ZMW</span>
												<input type="number" step="any" name="amount" id="amount" class="form-control" required>
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="form">Equity</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-percent"></i></span>
												<input type="number" step="any" name="equity" id="equity" class="form-control">
											</div>
										</div>
									</div>
									<button class="btn btn-secondary " type="submit" id="investorBtn">Submit</button>
									<button class="btn btn-secondary " type="submit" id="adminUpdateBtn" style="display: none;">Update</button>
								</form>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						
						<div class="card card-warning card-outline mb-5">
							<div class="card-body box-profile">
								
								<div class="table table-responsive">
									<table id="investorsTable" class="cell-border" style="width:100%">
										<thead>
											<tr>
												<th>Photo</th>
												<th>Names</th>
												<th>Amount</th>
												<th>Equity</th>
												<th>Actions</th>
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
															$photo 	= 'investors/uploads/'.$row['photo'];
														}
														$investor_id 	= $row['id'];
														$parent_id 	= $row['parent_id'];
													?>
														<tr>
															<td><img src="<?php echo $photo?>" class="img-fluid img-rounded" width="60" height="60"> </td>
															<td><?php echo $row['firstname']?> <?php echo $row['lastname']?></td>
															<td><?php echo $row['amount']?></td>
															<td><?php echo $row['equity']?></td>
															<td>
																<div class="btn-group">
																	<a href="<?php echo base64_encode($investor_id)?>" class="moreData btn btn-secondary" data-username="<?php echo $row['firstname']?>"><i class="bi bi-box"></i></a>
																	<a href="<?php echo base64_encode($investor_id)?>" class="editAdmin btn btn-primary" data-id="<?php echo $investor_id?>"><i class="bi bi-pencil-square"></i></a>
																	<a href="" class="deleteAdmin btn btn-danger" data-id="<?php echo $investor_id?>"><i class="bi bi-trash"></i></a>
																</div>
															</td>
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

			<!-- Moredata Modal -->
			<div class="modal fade" id="investorModal">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="investor_name"></h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div id="investor_data"></div>
						</div>
					</div>
				</div>
			</div>
			
		</section>
	</div>

	<?php include("../addon_footer.php")?>
	<script>
		$(document).ready( function () {
		    $('#investorsTable').DataTable();
		    
			$(document).on("click",".editAdmin", function(e){
				e.preventDefault();
				var investor_id = $(this).data("id");
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
						$('#investor_phone').val(data.phone);
						$('#phonenumber').val(data.phone);
						$('#id_type').val(data.id_type);
						$('#id_number').val(data.id_number);
						$('#gender').val(data.gender);
						$('#investor_country').val(data.investor_country);
						$('#email').val(data.email);
						$('#address').val(data.address);
						$('#amount').val(data.amount);
						$('#equity').val(data.equity);
					}
				})

			})

			$(document).on("click", ".moreData", function(e){
				e.preventDefault();
				var investor_id = $(this).attr("href");
				var username = $(this).data('username');
				document.getElementById('investor_name').innerText = username + ' Information';
				$.ajax({
					url:"investors/viewInvestorsData",
					method:"post",
					data:{investor_id:investor_id},
					success:function(data){
						$("#investorModal").modal("show");
						$("#investor_data").html(data);
					}
				})
			})

			$(document).on("click",".deleteAdmin", function(e){
				e.preventDefault();
				
				var investor_id_delete = $(this).data("id");
				
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

	

		// Telephone number with country code
		var input = document.querySelector("#investor_phone");
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

		function getPhone(phone){
			var number = iti.getNumber(intlTelInputUtils.numberFormat.E164);
			var isValid = iti.isValidNumber();
			result = document.querySelector("#result");
			phonenumber = document.getElementById("phonenumber");
			if (phone == "") {
				result.textContent = "Add Your Number";
				return false;
			}
			if (isValid === true) {
				result.textContent = "Number: " + number + ", is valid";
				phonenumber.value = number;
			}else if(isValid === false){
				result.textContent = "Number: " + number + ", is invalid";
				phonenumber.value = number;
			}
		}

	</script>
</body>
</html>