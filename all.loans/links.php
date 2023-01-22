<?php
    if (!isset($_COOKIE['userLoggedin']) && !isset($_SESSION['email'])) {?>
        <script>
          window.location = 'https://chumasolutions.com'
        </script>
      <?php 
      }

    
	if (isset($_COOKIE['SelectedBranch'])) {
		$BRANCHID = $_COOKIE['SelectedBranch'];
	}else{
		$BRANCHID = "";
		// header("location:index");
	}

	if ((time() - $_SESSION['last_login_timestamp']) > 960) {

			if (isset($_POST['submit'])) {
				$email = $_POST['email'];
				$password = $_POST['password'];

				$query = $connect->prepare("SELECT * FROM admins WHERE email = ? ");
				$query->execute(array($email));
				if ($query->rowCount() > 0) {
					foreach ($query->fetchAll() as $row) {
						if($row['activate'] == 1){
							if (password_verify($password, $row['password'])) {
								
							    $_SESSION['last_login_timestamp'] = time();
							    // echo "Redirecting you in 1 Second";
							    header("location:".$_SERVER['REQUEST_URI']);
								
							}else{
								echo "Incorrect password or email";
								// exit();
							}
						}else{
							echo "Please Activate Your Account";
							// exit();
						}
					}
				}else{
					echo 'User not found';
					// exit();
				}

			}
		?>
		<style>
			.sessionDiv {
				width: 100%;
				margin: 10em auto;
			}
			.sessionForm {
				border:2px dashed tomato;
				padding: 4em;
				margin:2em auto;
			}
			.sessionForm input {
				padding: 8px;
			}
		</style>
		<div class="sessionDiv">

	    	<form method="post" class="sessionForm">
	    		<h4>Login</h4><br>
	    		<input type="email" name="email" id="email" placeholder="Email" required>
	    		<input type="password" name="password" id="password" placeholder="Password" required>
	    		<input type="submit" name="submit" value="Start Session">
	    	</form>
	    </div>
    	<script>
    		alert("Your Session Has Expired after 3 minutes of Inactivity");
    		// window.location = 'https://localhost/chumasolutions.com/signout';
    	</script>
	<?php
    	exit();
  	} else {
    	$_SESSION['last_login_timestamp'] = time();
    }

?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($BRANCHID)))?></title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<!-- iCheck -->
<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<!-- JQVMap -->
<link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="dist/css/adminlte.min.css">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
<!-- Daterange picker -->
<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
<!-- summernote -->
<link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
<link rel="icon" type="text/css" href="../images/ChumaLogo1.jpeg">
<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="intl.17/build/css/intlTelInput.css">
<link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- Bootstrap 4 -->
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<?php $BRANCHID = base64_decode($BRANCHID);?>
