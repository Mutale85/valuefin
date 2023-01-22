<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php");  
	
	$option = '';
	$query = $connect->prepare("SELECT * FROM admins WHERE parent_id = ?");
	$query->execute(array($_SESSION['parent_id']));
	// we should check the user branch and show admins who belong to the branches

	foreach ($query->fetchAll() as $row) {
	    $option .= '<option value="'.$row['id'].'">'.$row['firstname'] .' '. $row['lastname'].'</option>';
	}

	$branch_options = "";
	$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ? ");
	$sql->execute(array($_SESSION['parent_id']));
	foreach ($sql->fetchAll() as $row) {
		$branch_options .= '<option value="'.$row['id'].'">'.$row['branch_name'].'</option>';
	}


	$country = '';
	$query = $connect->prepare("SELECT * FROM currencies");
	$query->execute();
	foreach ($query->fetchAll() as $row) {
	    $country .= '<option value="'.$row['id'].'">'.$row['country'].'</option>';
	}

	// we chech the branches where the admin belongs to. then check other members who belong to the same branches

?>
<!DOCTYPE html>
<html>
<head>
	<title>View Borrowers of <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?></title>
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

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content bg-light">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 border-bottom pb-2 ">
      						<div class="d-flex justify-content-between">
      						<h1 class="h3"><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?> Borrowers</h1>
      						<button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#modal-primary"><i class="bi-person-plus"></i> Add New Borrower</button>
      						</div>
      					</div>

      				</div>
      			</div>
      			<!-- borrower form -->
      			<div class="container-fluid">
      				<div class="row">
      					<div class="col-md-12">
      						<?php 
      							$query 	= $connect->prepare("SELECT * FROM borrowers WHERE parent_id = ? AND branch_id = ? ");
      							$query->execute(array($_SESSION['parent_id'], $BRANCHID));
      							$count 	= $query->rowCount();
      							$res 	= $query->fetchAll();
      							// We will display 
      						?>
      						<div class="table table-responsive">
	      						<table id="myTable" class="cell-border" style="width:100%">
							        <thead>
							            <tr>
							            	<th>Serial #</th>
							            	<th>View</th>
							            	<th>Fullnames</th>
							                <th>Contact</th>
							                <th>Business</th>
							                <th>Current Loan</th>
							                <th>Branch</th>
							                <th>Action</th>
							            </tr>
							        </thead>
							        <tbody>
							        	<?php 
							        		$i = 1;
							        		if ($count > 0) {
							        			foreach ($res as $row) {
							        				// here now, the admin will only see those who belong to his branches
							        				$branch_id = $row['branch_id'];
							        				if ($row['borrower_business'] == "") {
							        					$bs = "N/A";
							        				}else{
							        					$bs = $row['borrower_business'];
							        				}
							        				if ($row['borrower_email'] == "") {
							        					$em = "N/A";
							        				}else{
							        					$em = $row['borrower_email'];
							        				}
							      //   				$sql = $connect->prepare("SELECT * FROM allowed_branches WHERE branch_id = ? AND parent_id = ? AND staff_id = ? ");
													// $sql->execute(array($branch_id, $_SESSION['parent_id'], $_SESSION['user_id']));
													// if($sql->rowCount() > 0){
													// 	$rows = $sql->fetch();
													// 	if ($rows) {
															
													// 	}

							        	?>
							        				<tr>
							        					<td><?php echo $i++?></td>
							        					<td>
							        						<a href="borrowers/view_borrower_details?borrower_id=<?php echo $row['borrower_ID']?>" class="btn btn-sm btn-outline-primary">More Details</a>
							        					</td>
							        					<td>
							        						<p><strong><?php echo getBorrowerFullNames($connect, $row['id'])?></strong></p>
							        						<p><span class="text-secondary">ID: </span> <?php echo $row['borrower_ID']?></p>
							        					</td>
							        					<td>
							        						
							        						<p><span class="text-secondary">Address: </span> <?php echo $row['borrower_address']?></p>
							        						<p><span class="text-secondary">Contact # : </span> <?php echo $row['borrower_phone']?></p>
							        						<p><span class="text-secondary">Email: </span> <?php echo $em;?></p>
							        						<p><span class="text-secondary">Work: </span> <?php echo preg_replace("#[^a-zA-Z]#", " ", ucwords($row['borrower_working_status']))?></p>
							        					</td>
							        					<td><?php echo ucwords($bs)?></td>
							        					<td> 0 </td> 
							        					<td><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $row['branch_id']))?></td>
							        					<td>
							        						<a href="borrowers/edit_borrower_details?borrower_id=<?php echo $row['borrower_ID']?>" class="btn btn-outline-secondary editUser" id="<?php echo $row['id']?>"><i class="bi bi-pencil-square"></i>
							        						</a>
							        						<a href="borrowers/view_borrower_details?borrower_id=<?php echo $row['borrower_ID']?>" class="btn btn-outline-secondary text-danger removeBorrower" id="<?php echo $row['borrower_ID']?>" data-id="<?php echo $row['id']?>" data-branch_id="<?php echo $branch_id?>"><i class="fa fa-trash text-danger"></i></a>
							        					</td>
							        				</tr>
							        	<?php
							      //   				}else{
														
													// }
							        			}
							        		}else{
							        			
							   
							        		}
							        	?>

							     	</tbody>
							     	<tfoot>
							            <tr>
							            	<th>Serial #</th>
							            	<th>View</th>
							            	<th>Fullnames</th>
							                <th>Contact</th>
							                <th>Business</th>
							                <th>Current Loan</th>
							                <th>Branch</th>
							                <th>Action</th>
							            </tr>
							        </tfoot>
							    </table>
							 </div>
      					</div>
      				</div>
      			</div>
      			<!-- Modal Form -->
      			<div class="modal fade" id="modal-primary">
					<div class="modal-dialog modal-lg">
						<div class="modal-content bg-secondary">
							<form class="" method="post" id="borrowerForm" enctype="multipart/form-data">
								<div class="modal-header">
									<h4 class="modal-title"><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?> Borrower Information</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
							
								<div class="modal-body">
	      						
	      							<div class="form-group mb-3">
	      								<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID ?>">
	      								<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
	      							</div>
									<div class="form-group">
										<label for="form">Firstname</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-person"></i></span>
											<input type="text" aria-label="First name" name="borrower_firstname" id="borrower_firstname" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="form">Lastname</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-person"></i></span>
											<input type="text" aria-label="Last name" name="borrower_lastname" id="borrower_lastname" class="form-control">
										</div>
									</div>
									<p class="text-white">OR</p>
									<div class="form-group">
										<label for="form">Business Name</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-briefcase"></i></span>
											<input type="text" aria-label="Last name" name="borrower_business" id="borrower_business" class="form-control">
										</div>
									</div>

									<div class="border-bottom pb-2 mb-4"></div>
									<div class="form-group">
										<label>Gender</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
											<select id="borrower_gender" name="borrower_gender" class="form-control">
												<option value="">Select</option>
												<option value="male">Male</option>
												<option value="female">Female</option>
											</select>
										</div>
									</div>
									<div class="form-group mb-3">
										<label for="form">ID No:</label>
										<div class="input-group mb-1">
											<span class="input-group-text"><i class="bi bi-file-person"></i></span>
											<input type="text" aria-label="Last name" name="borrower_ID" id="borrower_ID" class="form-control">
										</div>
										<em>This can be NRC, PASSPORT or the document you deam fit</em>
									</div>
									<div class="form-group">
										<label for="form">Country</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-geo"></i></span>
											<select name="borrower_country" id="borrower_country" class="form-control">
												<option value=""></option>
												<?php echo $country;?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="form">City</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-geo"></i></span>
											<input type="text" name="borrower_city" id="borrower_city" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="form">Home Address</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-geo"></i></span>
											<textarea type="text" name="borrower_address" id="borrower_address" class="form-control" rows="5"> </textarea>
										</div>
									</div>
									<div class="form-group">
										<label for="form">Email</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-at"></i></span>
											<input type="email" name="borrower_email" id="borrower_email" class="form-control">
										</div>
									</div>
									<div class="form-group mb-3">
										<label for="form">Phone</label>
										<div class="input-group  mb-2">
											<span class="input-group-text"><i class="bi bi-phone"></i></span>
											<input type="text"  name="borrower_phone" id="borrower_phone" class="form-control">
										</div>
										<em>Add country code without the symbol to be able to send SMSs</em>
									</div>
									<div class="form-group">
										<label for="form">Date of Birth</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-calendar"></i></span>
											<input type="text" aria-label="dateofbirth" name="borrower_dateofbirth" id="borrower_dateofbirth" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label>Working Status</label>
										<div class="input-group mb-3">
											<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
											<select id="borrower_working_status" name="borrower_working_status" class="form-control">
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
									<div class="form-group mb-3">
										<label for="form">Borrower Photo</label>
										<div class=" border p-3">
											<button class="btn btn-warning mb-3" type="button" id="selectImage">Select Image <i class="bi bi-file-person"></i></button><br>
											<input type="file" name="borrower_borrower_photo" id="borrower_borrower_photo" class="form-control"  style="display: none;" onchange="preview_image(event)">
											<img src="dist/img/avatar2.png" id="output_image" class="shadow-sm img-fluid img-responsive" alt="pic" width="140">
										</div>
										<em>Add a clear face of the applicant</em>
									</div>
									<div class="form-group mb-3">
										<label for="form">Borrower Files</label>
										<div class="border p-3">
											<button class="btn btn-warning" type="button" id="selectFiles">Select Files <i class="bi bi-files"></i></button>
											<input type="file" name="borrower_borrower_files[]" id="borrower_borrower_files" class="form-control" style="display: none;" multiple onchange="javascript:updateList()">

											<div id="fileList"></div>
										</div>
									</div>

									<div class="form-group mb-5">
										<!-- if the borrowe is assinge the  -->
										<label>Loan Officers</label>
										<div id="showloadOfficers"></div>
									</div>
									
								</div>
								<div class="modal-footer">
									<button class="btn btn-outline-light" type="button" onclick="_reset()"> Cancel</button>
									<button class="btn btn-outline-warning w-30" type="submit" id="borrowerBtn" onclick="addBorrower(event)">Submit</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- <button type="button" class="btn btn-danger toastrDefaultError">Toast</button> -->
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
	
		$(function(){
			$("#borrower_dateofbirth").datepicker({

				format: 'yyyy/mm/dd'
			});
		})
	</script>
	<script>

		// =========================== find loan officers -==============
		function _reset() {
			$('#borrowerForm')[0].reset();
			$("#modal-primary").modal("hide");
			location.reload();
		}

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
		fetchLoanOfficers('<?php echo $BRANCHID;?>');

	  	var input = document.getElementById('borrower_country');
	  	input.onchange = function () {
	    	localStorage['borrower_country'] = this.value;
	    	alert(this.value);
	  	}
	  	document.addEventListener('DOMContentLoaded', function () {
	     	var input = document.getElementById('borrower_country');
	     	if (localStorage['borrower_country']) { 
	         	input.value = localStorage['borrower_country'];
	     	}
	     	input.onchange = function () {
	          	localStorage['borrower_country'] = this.value;
	      	}
	  	});
	   
	   // images ------ 
	   	
		var selectImage = document.getElementById('selectImage');
  		var fileInput = document.getElementById('borrower_borrower_photo');
  		selectImage.addEventListener("click", (e) => {
  			$('#borrower_borrower_photo').click();
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
			document.getElementById('borrower_borrower_files').click();
		})

	    updateList = function() {
			var input = document.getElementById('borrower_borrower_files');
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
			var url = 'borrowers/submitBorrower';
			var borrowerForm = document.getElementById('borrowerForm');
			var borrower_firstname = document.getElementById('borrower_firstname').value;
			xhr.open("POST", url, true);
			var data = new FormData(borrowerForm);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					if (xhr.responseText === 'done') {
						successNow(borrower_firstname + ' added to the database');
						// alert("Borrower Submited");
						// window.location = "view_borrowers";
						// $("#borrowerForm")[0].reset();
						document.getElementById("borrowerBtn").innerHTML = 'Submit';

					}else{
						// alert(xhr.responseText);
						errorNow(xhr.responseText);
						// $("#borrowerForm")[0].reset();
						document.getElementById("borrowerBtn").innerHTML = 'Submit';
						return false;
					}
					
				}
			}
			xhr.send(data);
			document.getElementById("borrowerBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}

		// $(function(){
		// 	$(document).on("click", ".editUser", function(e){
		// 		e.preventDefault();
		// 		var user_id = $(this).attr("id");
		// 		alert(user_id);
		// 	})
		// })

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
	    	var borrower_id_number = $(this).attr('id');
	    	var branch_id = $(this).data('branch_id');
	    	var delete_id = $(this).data('id');
	    	var loggedParentId = '<?php echo $_SESSION['parent_id']?>';
	    	if(confirm("Confirm removing borrower? All loan information will also be removed")){
		    	$.ajax({
		    		url: 'borrowers/action',
		    		method:'post',
		    		
		    		data: {borrower_id_number:borrower_id_number, branch_id:branch_id, delete_id:delete_id, loggedParentId:loggedParentId},
		    		
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