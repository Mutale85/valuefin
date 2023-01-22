<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 
  	if (isset($GET['applicant-id'])) {
		$ID = $GET['applicant-id'];
	}else{
		$ID = '';
	}
	function getcCountryName($connect, $id){
		$output = "";
		$query = $connect->prepare("SELECT * FROM currencies WHERE id = ?");
		$query->execute(array($id));
		$row = $query->fetch();
		if ($row) {
			extract($row);
			$output = $country;
		}
		return $output;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Borrower's Profile - <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?></title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<style type="text/css">
		@media print {

		  	html, body {
		    	height:100%; 
			    margin: 0 !important; 
			    padding: 0 !important;
			    overflow: hidden;
			    /*height: 100%;*/
		    	overflow: visible;
		  	}
		  	.pagebreak { page-break-before: always; }
		  	@page {
			  	margin-top: 2cm;
			}
			#sig-canvas {
				border: 2px dotted black;
				border-radius: 15px;
				cursor: crosshair;
				padding: 1em;
			}
		}
		
	</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<div class="content-wrappers">
			<section class="content mt-5">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<?php
								$branch_id =  base64_decode($_COOKIE['SelectedBranch']);
								$applicant_id = base64_decode($_GET['applicant-id']);
								$query = $connect->prepare("SELECT * FROM borrowers_details WHERE id = ? AND branch_id = ? AND parent_id = ?");
								$query->execute(array($applicant_id, $branch_id,  $_SESSION['parent_id']));
								$row = $query->fetch();
								if ($row) {
									extract($row);
									
							?>
      						<div class="bg-warnings">
      							
      							<div class="card card-primary">
					              	<div class="card-body box-profile">
					              		<div class="d-flex justify-content-between p-3 rounded" style="background-color: #01164f; color: #ddd;">
						                	<div class="text-left">
						                 		<img src="fileuploads/<?php echo $borrower_photo ?>" id="output_image2" class="profile-user-img img-fluid img-circle" alt="pic" style="width: 120px; height: 120px;">
						                 		<h3 class="profile-username text-left"><?php echo $borrower_title ?> <?php echo $borrower_firstname?> <?php echo $borrower_lastname?> </h3>
						                 		<p><?php echo $borrower_phone ?>, <?php echo $borrower_city ?></p>
						                	</div>
						                	<div class="text-right">
						                		<img src="../images/ChumaLogo2.jpeg" class="profile-user-img img-fluid img-circle" alt="Chuma" style="width: 120px; height: 120px;">
						                		<h3 class="profile-username text-right">Chuma Solutions Limited</h3>
						                		<p>FLAT 3, CHIYOLI ROAD, ROMA, LUSAKA</p>
						                		<p>+260 971 256 920 | +260 977 654 619</p>
						                	</div>
						                </div>
					                	<ul class="list-group list-group-unbordered mb-3">
					                		<li class="list-group-item">
					                    		<b>City</b> <span class="float-right" id="gender_span"><?php echo $borrower_city ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b>Gender</b> <span class="float-right" id="gender_span"><?php echo $borrower_gender ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b>DoB</b> <span class="float-right" id="dateofbirth_span"><?php echo $borrower_dateofbirth ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b>ID</b> <span class="float-right" id="ID_span"><?php echo $borrower_ID ?></span>
					                  		</li>
					                  		
					                  		<li class="list-group-item">
					                    		<b>Home Address</b> <span class="float-right" id="address_span"><?php echo $borrower_address ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b>Country</b> <span class="float-right" id="country_span"><?php echo getcCountryName($connect, $borrower_country) ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b>Phone No.</b> <span class="float-right" id="phone_span"><?php echo $borrower_phone ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b>Email</b> <span class="float-right" id="email_span"><?php echo $borrower_email ?></span>
					                  		</li>
					                  		
					                	</ul>
					                	<div class="mt-4 mb-4"></div>
					                	<?php if ($borrower_working_status == 'Business'): ?>
					                	<h4 class="text-secondary"><span id="working_span">Business Details</span></h4>

					                	<ul class="list-group list-group-unbordered mb-3">
					                  		<li class="list-group-item">
					                    		<b id="general_1">Business Type:</b> <span class="float-right" id="general_span_1"><?php echo $borrower_business_type ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="general_2">Business Name</b> <span class="float-right" id="general_span_2"><?php echo $borrower_business ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="general_3">Business Address</b> <span class="float-right" id="general_span_3"><?php echo $borrower_business_address ?></span>
					                  		</li>
					                	</ul>
					                	<?php else:?>
					                	<h4 class="text-secondary"><span id="working_span">Employee Details</span></h4>

					                	<ul class="list-group list-group-unbordered mb-3">
					                  		<li class="list-group-item">
					                    		<b id="general_1">Employer</b> <span class="float-right" id="general_span_1"><?php echo $borrower_employer_name ?></span>
					                  		</li>
					                  		<li class="list-group-item ">
					                    		<b id="general_2">Employer's Phone</b> <span class="float-right" id="general_span_2"><?php echo $borrower_employer_phone ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="general_3">Employer's Address</b> <span class="float-right" id="general_span_3"><?php echo $borrower_employer_address ?></span>
					                  		</li>
					                	</ul>

					                	<?php endif;?>

					                	<div class="mt-4 mb-4"></div>
					                	<h4 class="text-secondary"><span id="bank_span">Bank Details</span></h4>
					                	<ul class="list-group list-group-unbordered mb-3">
					                  		<li class="list-group-item">
					                    		<b id="">Bank Name</b> <span class="float-right" id="bank_name_span"><?php echo $borrower_bank_name ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">ACC No.</b> <span class="float-right" id="account_number_span"><?php echo $borrower_account_number ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">Branch</b> <span class="float-right" id="branch_name_span"><?php echo $borrower_branch_name ?></span>
					                  		</li>
					                	</ul>

					                	<div class="mt-4 mb-4"></div>
					                	<h4 class="text-secondary"><span id="next_of_kin_span">Next of Kin</span></h4>
					                	<ul class="list-group list-group-unbordered  mb-3">
					                  		<li class="list-group-item">
					                    		<b id="">Full Names</b> <span class="float-right" id="next_of_kin_fullnames_span"><?php echo $next_of_kin_fullnames ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">NRC</b> <span class="float-right" id="next_of_kin_nrc_span"><?php echo $next_of_kin_nrc ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">Relationship</b> <span class="float-right" id="next_of_kin_relationship_span"><?php echo $next_of_kin_relationship ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">Phonenumber</b> <span class="float-right" id="next_of_kin_phone_span"><?php echo $next_of_kin_phone ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">Physical Address</b> <span class="float-right" id="next_of_kin_address_span"><?php echo $next_of_kin_address ?></span>
					                  		</li>
					                	</ul>
					                	<div class="pagebreak"></div>
					                	<!-- show Guarantor if available -->
					                	<?php
					                		$branch_id 		= base64_decode($_COOKIE['SelectedBranch']);
											$applicant_id 	= base64_decode($_GET['applicant-id']);
					                		$sql = $connect->prepare("SELECT * FROM guarantors WHERE borrower_id = ? AND branch_id = ? AND parent_id = ? ");
					                		$sql->execute(array($applicant_id, $branch_id, $_SESSION['parent_id']));
					                		if($sql->rowCount() > 0):
					                		$rows = $sql->fetch();
					                		extract($rows);

					                	?>
					                	<h4 class="text-secondary"><span id="next_of_kin_span">Guarantors</span></h4>
					                	<ul class="list-group list-group-unbordered tex-decoration-none  mb-3">
					                  		<li class="list-group-item">
					                    		<b id="">Full Names</b> <span class="float-right" id="next_of_kin_fullnames_span"><?php echo $firstname ?> <?php echo $lastname ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">Phonenumber</b> <span class="float-right" id="next_of_kin_nrc_span"><?php echo $phone ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">Address </b> <span class="float-right" id="next_of_kin_relationship_span"><?php echo $address ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">ID Number</b> <span class="float-right" id="next_of_kin_phone_span"><?php echo $identity_number ?></span>
					                  		</li>
					                  		<li class="list-group-item">
					                    		<b id="">Working Status</b> <span class="float-right" id="next_of_kin_address_span"><?php echo $working_status ?></span>
					                  		</li>
					                	</ul>
					                	<?php else:?>
					                		<h4 class="text-secondary"><span id="next_of_kin_span">Guarantors</span></h4>
					                	<?php endif;?>

					                	<div id="sig-canvass" class="d-flex justify-content-between mt-5 mb-4">
					                		<div class="coy_sign">
						                		<h4 class="text-center">Chuma Solutions</h4><br>
						                		<table class="table table-borderless">
						                			<tr>
						                				<th>Sign</th><td>__________________________</td>
						                			</tr>
						                			<tr>
						                				<th>Name</th><td>__________________________</td>
						                			</tr>
						                			<tr>
						                				<th>Date</th><td>__________________________</td>
						                			</tr>
						                		</table>
						                	</div>
						                	<div class="client_sign">
					                			<h4 class="text-center">Client's Signature</h4><br>
					                			<table class="table table-borderless">
						                			<tr>
						                				<th>Sign</th><td>__________________________</td>
						                			</tr>
						                			<tr>
						                				<th>Name</th><td>__________________________</td>
						                			</tr>
						                			<tr>
						                				<th>Date</th><td>__________________________</td>
						                			</tr>
						                		</table>
					                		</div>
					                	</div>
					                	<!-- <div class="d-flex. justify-content-between">
					                		<a href=""></a>
					                	</div> -->
					              	</div>
				            	</div>
      						</div>

      					<?php }?>
      					</div>
      				</div>
      			</div>
      		</section>
      	</div>
    </div>
</body>
	<script>
		window.addEventListener("load", window.print());
	</script>
</html>

