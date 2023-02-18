<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
  	
	$option = '';
	$query = $connect->prepare("SELECT * FROM admins WHERE id = ? AND parent_id = ?");
	$staff_id 	= base64_decode($_GET['staff_id']);
	$parent_id 	= $_SESSION['parent_id'];
	$query->execute(array($staff_id, $parent_id));
	$row = $query->fetch();
	if ($row) {
		$firstname 	= $row['firstname'];
		$lastname 	= $row['lastname'];
		$email 		= $row['email'];
		$phone 		= $row['phonenumber'];
		$user_role 	= $row['user_role'];
		$nrc_number = $row['nrc_number'];
		$nrc_copy 	= $row['nrc_copy'];
		
		// $photo = '../../dist/img/avatar.png';
		$profile_pic = $row['profile_pic'];
		

	}
	
	$branch_options = $all_branch_options ="";
	$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
	$sql->execute(array($parent_id));
	$results = $sql->fetchAll();
	

	$branches = array();
	foreach($results as $row) {
	    $branches[] = $row['id'];  
	}

	

	$query = $connect->prepare("SELECT * FROM allowed_branches WHERE staff_id = ? AND parent_id = ? ");
	$query->execute(array($staff_id, $parent_id));
	if ($query->rowCount() > 0) {
		foreach($query->fetchAll() as $row) {
	        $branch_options .= '
				<div class="form-check">
                  	<input class="form-check-input" type="checkbox"  name="branchID[]" id="branchID" value="'.$row['branch_id'].'" checked>
                  	<label class="form-check-label">'.branchName($connect, $parent_id, $row['branch_id']).'</label>
                </div>
			';
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Personnel Data Update</title>
	<?php include("../addon_header.php");?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light">
			<div class="container mb-5">
				<div class="row">
					<div class="col-md-12 mb-5">
						<div class="card card-primary">
							<div class="card-header">
								<h4 class="card-title"><?php echo $firstname?></h4>
							</div>
							<form class="" method="post" id="adminsForm" enctype="multipart/form-data">
								<div class="card-body">
									<div class="row">
										<div class="form-group col-md-12 mb-3">
											<label for="form">Staff Photo</label>
											<div class="">
												<button class="btn btn-outline-secondary mb-3" type="button" id="selectImage">Change Image <i class="bi bi-file-person"></i></button><br>
												<input type="file" name="photo" id="photo" class="form-control" onchange="preview_admin_image(event)" style="display: none;">
												<img src="members/uploads/<?php echo $profile_pic?>" width="150" alt="profile" id="output_image">
											</div>
											<em>Add a clear face</em>
											<input type="hidden" name="photo_hidden" id="photo_hidden" value="<?php echo $profile_pic?>">
											<input type="hidden" name="nrc_copy_hidden" id="nrc_copy_hidden" value="<?php echo $nrc_copy?>">
											<input type="hidden" name="staff_id" id="staff_id" value="<?php echo $staff_id?>">
											<input type="hidden" name="parent_id" id="parent_id" value="<?php echo($_SESSION['parent_id'])?>">
										</div>
										<div class="form-group col-md-4">
											<label for="form">Firstname</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-people"></i></span>
												<input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo $firstname?>">
											</div>
										</div>
										<div class="form-group col-md-4">
											<label for="form">Lastname</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-people"></i></span>
												<input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo $lastname?>">
											</div>
										</div>
										<div class="form-group col-md-4">
											<label for="form">Phone</label>
											<div class="input-group mb-3">
												<input type="text" name="phone" id="phone" class="form-control" value="<?php echo $phone?>" onkeyup="getPhone(this.value)">
												<input type="hidden" name="phonenumber" id="phonenumber" class="form-control" value="<?php echo $phone?>">
											</div>
											<p id="result"></p>
										</div>
										<div class="form-group col-md-4">
											<label for="form">NRC Number</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-file"></i></span>
												<input type="text" name="nrc_number" id="nrc_number" class="form-control" required>
											</div>
										</div>
										<div class="form-group col-md-4">
											<label for="form">NRC Copy</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-file-pdf"></i></span>
												<input type="file" name="nrc_copy" id="nrc_copy" class="form-control" required>
											</div>
										</div>
										
										<div class="form-group col-md-4 mb-3">
											<label>Staff Role</label>
											<select class="form-control" name="user_role" id="user_role" required>
												<option value="Loan Officer">Loan Officer</option>
												<option value="Admin">Admin</option>
												<option value="superAdmin">Super Admin</option>
											</select>
										</div>
			
										<div class="form-group col-md-12">
											<label for="form">Home Address</label>
											<div class="input-group mb-3">
												<span class="input-group-text"><i class="bi bi-map"></i></span>
												<textarea type="text" name="home_address" id="home_address" class="form-control" rows="4"></textarea>
											</div>
										</div>
										<div class="form-group col-md-12 mb-3">
											<label>Branch Permission</label>
											<table class="table table-borderless table-sm">
												<?php echo getAllowedBranches($connect, $parent_id, $staff_id);?>
											</table>
										</div>
									</div>
									
								</div>
								<div class="card-footer">
									<button class="btn btn-primary" type="submit" id="adminUpdateBtn" onclick="updateAdmins(event)">Update</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
		
	</div>
	<?php include("../addon_footer.php")?>
		<?php 
			if(isset($_GET['staff_id'])):
		?>
		<script>
				function editData(staff_id, staff_role){
					$.ajax({
						url:'members/parsers/editData',
						method:"post",
						data:{staff_id:staff_id, staff_role:staff_role},
						dataType:'Json',
						success:function(data){
							$("#user_role").val(data.user_role);
							$("#staff_permission").val(data.staff_permission);
						}
					})
				}
				editData('<?php echo $staff_id?>', '<?php echo $user_role?>')
		</script>
		<?php endif;?>
	<script>
		updateAdmins = function() {
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'members/parsers/submitedittedStaff';
			var adminsForm = document.getElementById('adminsForm');
			xhr.open("POST", url, true);
			var firstname = document.getElementById('firstname').value;
			var data = new FormData(adminsForm);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					var result = xhr.responseText;
					successToast(result);
					
				}
			}
			xhr.send(data);
			document.getElementById("adminBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}

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

	    function getPhone(phone_number){
	      	var number = iti.getNumber(intlTelInputUtils.numberFormat.E164);
	      	var isValid = iti.isValidNumber();
	      	result = document.querySelector("#result");
	      	phone = document.getElementById("phonenumber");
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


	</script>
</body>
</html>