<?php 
  	require ("../includes/db.php");
	require ("../includes/tip.php");
  	
	// $query = $connect->prepare("SELECT * FROM admins WHERE id = ? AND parent_id = ?");
	

?>
<!DOCTYPE html>
<html>
<head>
	<title>Reset Password</title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
	<style>
		/* Style all input fields */
		

		/* Style the container for inputs */
		.containers {
		  background-color: #f1f1f1;
		  padding: 20px;
		}

		/* The message box is shown when the user clicks on the password field */
		#password_message {
		  display:none;
		  background: #f1f1f1;
		  color: #000;
		  position: relative;
		  padding: 20px;
		  margin-top: 10px;
		  width: 100%;
		}

		#password_message p {
		  padding: 10px 35px;
		  font-size: 18px;
		}

		/* Add a green text color and a checkmark when the requirements are right */
		.valid {
		  color: green;
		}

		.valid:before {
		  position: relative;
		  left: -35px;
		  content: "✔";
		}

		/* Add a red text color and an "x" when the requirements are wrong */
		.invalid {
		  color: red;
		}

		.invalid:before {
		  position: relative;
		  left: -35px;
		  content: "✖";
		}
	</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content bg-light">
      			<div class="container mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4">
      						<div class="d-flex justify-content-between">
      							<h4>Reset Password</h4>
      						</div>
      					</div>
      				</div>
      			</div>
      			<div class="container mb-5">
      				<div class="row">
  						<div class="col-md-12 mb-5">
  							<div class="card card-primary">
  								<div class="card-header">
  									<h4 class="card-title">Reset Password</h4>
  								</div>
	  							<form class="" method="post" id="PasswordForm">
		  							<div class="card-body">
		  								<div class="row">
											
											<div class="form-group col-md-4">
												<label for="form">Old Password</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-star"></i></span>
													<input type="password" name="old_password" id="old_password" class="form-control" >
												</div>
											</div>
											<div class="form-group col-md-4">
												<label for="form">New Password</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-star"></i></span>
													<input type="password" name="new_password" id="new_password" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
												</div>
											</div>
											
											<div class="form-group col-md-4">
												<label for="form">Retype Password</label>
												<div class="input-group mb-3">
													<span class="input-group-text"><i class="bi bi-star"></i></span>
													<input type="password" name="retype_password" id="retype_password" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
												</div>
											</div>
											<div id="password_message">
												<h3>Password must contain the following:</h3>
												<p id="letter" class="invalid">A <b>lowercase</b> letter</p>
												<p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
												<p id="number" class="invalid">A <b>number</b></p>
												<p id="length" class="invalid">Minimum <b>8 characters</b></p>
											</div>
										</div>
										
									</div>
									<div class="card-footer">
										<button class="btn btn-primary" type="submit" id="adminUpdateBtn" onclick="updatePassword(event)">Change Password</button>
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
	<script src="plugins/toastr/toastr.min.js"></script>
	<script>
var myInput = document.getElementById("new_password");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

// When the user clicks on the password field, show the message box
myInput.onfocus = function() {
  document.getElementById("password_message").style.display = "block";
}

// When the user clicks outside of the password field, hide the message box
myInput.onblur = function() {
  document.getElementById("password_message").style.display = "none";
}

// When the user starts to type something inside the password field
myInput.onkeyup = function() {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) {  
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }
  
  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {  
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }
  
  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
var new_password = document.getElementById('new_password');
var retype_password = document.getElementById('retype_password');
retype_password.onblur = function(){
	if (new_password.value !== retype_password.value) {
		errorNow("Passwords don't match");
		return false;
	}
}
</script>
	<script>
		updatePassword = function() {
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'members/reset-password';
			var PasswordForm = document.getElementById('PasswordForm');
			xhr.open("POST", url, true);
			var old_password = document.getElementById('old_password');
			var new_password = document.getElementById('new_password').value;
			var retype_password = document.getElementById('retype_password').value;
			var data = new FormData(PasswordForm);
			if (old_password.value === "") {
				errorNow("Type in your current password");
				old_password.focus();
				return false;
			}
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					
					errorNow(xhr.responseText);
					document.getElementById("adminBtn").innerHTML = 'Update';
					return false;
					
				}
			}
			xhr.send(data);
			document.getElementById("adminBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}


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
	</script>
</body>
</html>