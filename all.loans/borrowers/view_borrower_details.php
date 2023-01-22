<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php");  
	
	if (isset($_GET['borrower_id'])) {
		$borrower_id = preg_replace("#[^0-9]#", "/", $_GET['borrower_id']);
		//check if id is found.
		$query = $connect->prepare("SELECT * FROM borrowers WHERE borrower_ID = ?");
		$query->execute(array($borrower_id));
		$count = $query->rowCount();
		if ($count > 0) {
			$row = $query->fetch();
			$user_id = $row['id'];
			$working_status = preg_replace("#[^a-zA-Z]#", " ", ucwords($row['borrower_working_status']));
			$borrower_working_status = $row['borrower_working_status'];
			$borrower_firstname = $row['borrower_firstname'];
			$borrower_lastname = $row['borrower_lastname'];
			$borrower_email = $row['borrower_email'];
			$borrower_phone = $row['borrower_phone'];
			$borrower_dateofbirth    = $row['borrower_dateofbirth'];
			$borrower_address = $row['borrower_address'];
			$borrower_city = $row['borrower_city'];
			$borrower_country = $row['borrower_country'];
			$borrower_business = $row['borrower_business'];
			$borrower_gender  = $row['borrower_gender'];
			$date_added 	   = $row['date_added'];
			$borrower_borrower_files = $row['borrower_borrower_files'];
			$borrower_borrower_photo = $row['borrower_borrower_photo'];
			$loan_officers = $row['loan_officers'];
		}else{
			header("location:./");
		}
		
		$country = '';
		$query = $connect->prepare("SELECT * FROM currencies");
		$query->execute();
		foreach ($query->fetchAll() as $row) {
		    $country .= '<option value="'.$row['id'].'">'.$row['country'].'</option>';
		}

		$option = '';
		$query = $connect->prepare("SELECT * FROM admins");
		$query->execute();
		foreach ($query->fetchAll() as $row) {
		    $option .= '<option value="'.$row['id'].'">'.$row['firstname'].' '.$row['lastname'].'</option>';
		}

		$branch_options = "";
		$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ? ");
		$sql->execute(array($_SESSION['parent_id']));
		foreach ($sql->fetchAll() as $row) {
			$branch_options .= '<option value="'.$row['id'].'">'.$row['branch_name'].'</option>';
		}

		$branch_options = "";
		$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
		$sql->execute(array($_SESSION['parent_id']));
		foreach ($sql->fetchAll() as $row) {
			$branch_options .= '<option value="'.$row['id'].'">'.$row['branch_name'].'</option>';
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>View Borrowers</title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content bg-light">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 border-bottom">
      						<h1 class="h3"><?php echo ucwords(getBorrowerFullNames($connect, $user_id))?></h1>
      					</div>
      				</div>
      			</div>
      			<!-- borrower form -->
      			<div class="container-fluid">
      				<div class="row">
  						<div class="col-md-4">
  							<div class="card card-primary card-outline mb-5">
          						<div class="card-body box-profile">
					                <div class="text-center">
					                  	<img class="profile-user-img img-fluid img-circle" src="<?php echo borrowerPicture($connect, $user_id)?>" alt="User profile picture" style="width: 100px; height: 100px;">
					                </div>

					                <h3 class="profile-username text-center"><?php echo ucwords(getBorrowerFullNames($connect, $user_id))?></h3>

					                <p class="text-muted text-center"><?php echo $working_status?></p>

					                <ul class="list-group list-group-unbordered mb-3">
					                	<li class="list-group-item">
					                    	<b>C_ID: </b> <a href="<?php echo preg_replace("#[^0-9]#", "-", $borrower_id) ?>" class="float-right"><?php echo $borrower_id;?></a>
					                  	</li>
					                  	<li class="list-group-item">
					                    	<b>DOB: </b> <a href="<?php echo preg_replace("#[^0-9]#", "-", $borrower_id) ?>" class="float-right"><?php echo date("d/m/Y", strtotime($borrower_dateofbirth))?>,  <?php echo userAge($borrower_dateofbirth)?></a>
					                  	</li>
					                  	<li class="list-group-item">
					                    	<b>Email:</b> <a href="<?php echo preg_replace("#[^0-9]#", "-", $borrower_id) ?>" class="float-right"><?php echo $borrower_email?></a>
					                  	</li>
					                  	<li class="list-group-item">
					                    	<b>Phone:</b> <a href="<?php echo preg_replace("#[^0-9]#", "-", $borrower_id) ?>" class="float-right"><?php echo $borrower_phone?></a>
					                  	</li>
					                </ul>

					                <a href="create-new-loan?borrower_id=<?php echo $_GET['borrower_id']?>" class="btn btn-primary btn-block"><b>Issue Loan</b> </a>
					            </div>
        					</div>
  						</div>
  						<div class="col-md-8">
  							<div class="card card-primary card-outline mb-5">
  								<div class="card-body">
  									<ul class="list-group list-group-unbordered mb-3">
					                  	<li class="list-group-item">
					                    	<b>Address:</b> 
					                    	<a class="float-right"><?php echo $borrower_address?></a>
					                    	<br>
					                    	<b>City:</b> 
					                    	<a class="float-right"><?php echo $borrower_city?></a>
					                    	<br>
					                    	<b>Country:</b> 
					                    	<a class="float-right"><?php echo getCountryName($connect, $borrower_country) ?></a>
					                  	</li>
					                  	<?php echo getLoanOfficer($connect, $user_id)?>
					                  	<li class="list-group-item">
					                  		<b>Business:</b>
					                  		<a class="float-right"><?php echo $borrower_business?></a>
					                  	</li>
					                  	<li class="list-group-item">
					                  		<b>Added date:</b>
					                  		<a class="float-right"><?php echo $date_added ?></a>
					                  	</li>
					                  	
					                  	<?php echo borrowerFiles($connect, $user_id)?>
					                </ul>
					                <a href="borrowers/edit_borrower_details?borrower_id=<?php echo $borrower_id?>" class="btn btn-outline-primary btn-block" ><b>Edit Info</b></a>
  								</div>
  							</div>
  						</div>
      					<!-- </div> -->
      					<div class="col-md-12 mt-5 mb-5">
      						<div class="card card-warning card-outline">
      							<div class="card-header">
      								<h4><?php echo getBorrowerFullNamesByCardId($connect, $_GET['borrower_id'])?> Loans</h4>
      							</div>
      							<div class="card-body">
		      						<div class="table table-responsive">
			      						<table id="loansTable" class="cell-border table table-sm" style="width:100%">
											<thead>
												<th>Loan#</th>
												<th>From</th>
												<th>Maturity</th>
												<th>Principal</th>
												<th>Interest Rate</th>
												<th>Interest</th>
												<th>Processing Fees</th>
												<th>Due</th>
												<th>Paid</th>
												<th>Balance</th>
												<th>View Loan</th>
											</thead>
											<tbody>
												
											
		      								<?php
		      									// 
		      									$query = $connect->prepare("SELECT * FROM loans WHERE branch_id = ? AND parent_id = ? AND borrower_id = ? ");
												$query->execute(array($BRANCHID,  $_SESSION['parent_id'], $_GET['borrower_id']));
												$status = "";
												if ($query->rowCount() > 0) {
													$this_loan_id = $loan_number = "";
													foreach ($query->fetchAll() as $row) {
														extract($row);
														
														$l_status = preg_replace("#[^a-zA-Z]#", " ", $loan_status);
														if($l_status == 'Released') {
															$status = '<div class="text-success">'.$l_status.'</div>';
														}elseif ($l_status == 'Rejected') {
															$status = '<div class="text-danger">'.$l_status.'</div>';
														}elseif ($l_status == 'Completed') {
															$status = '<div class="text-secondary">'.$l_status.'</div>';
														}
														$sql = $connect->prepare("SELECT *, SUM(amount) AS total_paid FROM `loan_payments` WHERE loan_id = ? AND loan_number = ? AND borrower_id = ? ");
														$sql->execute(array($id, $loan_number, $borrower_id));
														if ($sql->rowCount() > 0) {
															$rows = $sql->fetch();
															if ($rows) {
																// extract($rows);
																$paid = $rows['total_paid'];
															}
														}else{
															$paid = 0.00;
														}
														// loanType($connect, $loan_id, $parent_id);
														
													?>
													<td>
														<a href="view_loan_details?loan_number=<?php echo $loan_number?>&borrower_id=<?php echo $_GET['borrower_id']?>" class="text-primary"><?php echo $loan_number ?></a>
													</td>
													<td>
														<?php echo date("d/m/Y", strtotime($release_date)); ?>	
													</td>
													<td> 
														<?php
														echo date("d/m/Y", strtotime("+".$loan_duration." ".$loan__period."", strtotime($release_date)));
														?>
													</td>
													<td><small><?php echo $currency ?></small> <?php echo number_format($principle_amount, 2)?></td>
													<td><?php echo $loan_interest ?>% </td>
													<td><small><?php echo $currency ?></small> <strong><?php echo number_format($total_interest_amount, 2) ?></strong></td>
													<td><small><?php echo $currency ?></small> <?php echo number_format($loan_processing_fee, 2) ?></td>
													<td><small><?php echo $currency ?></small> <strong><?php echo number_format($total_payable_amount, 2) ?></strong></td>
													<td><small><?php echo $currency ?></small> <?php echo $paid?></td>
													<td><small><?php echo $currency ?></small> <strong><?php echo number_format($total_payable_amount - $paid, 2) ?></strong></td>
													<td>
														<a href="view_loan_details?loan_number=<?php echo $loan_number?>&borrower_id=<?php echo $_GET['borrower_id']?>" class="text-primary"><i class="bi bi-arrow-right-square"></i></a>
													</td>
												<?php
													}
												}
		      								?>
		      								</tbody>
		      								<tfoot>
												<th>Loan#</th>
												<th>From</th>
												<th>Maturity</th>
												<th>Principal</th>
												<th>Interest Rate</th>
												<th>Interest</th>
												<th>Processing Fees</th>
												<th>Due</th>
												<th>Paid</th>
												<th>Balance</th>
												<th>View Loan</th>
											</tfoot>
										</table>
									 </div>
								</div>
      						</div>
      					</div>
      				</div>
      			</div>
      		</section>
		</div>
		<aside class="control-sidebar control-sidebar-dark"></aside>
	</div>
	<?php include("../footer_links.php")?>
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>
	<script src="plugins/toastr/toastr.min.js"></script>
	<script>
		$(document).ready( function () {
		    $('#loansTable').DataTable();
		    $("#paymentsTable").DataTable();
		    $('.select2').select2();
		
			$("#borrower_dateofbirth").datepicker({

				format: 'yyyy-mm-dd'
			});
			$('#datemask2').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })

			$(document).on("click", ".takeAction", function(e){
				e.preventDefault();
				var loan_id = $(this).data("loan_id");
				var borrower_id = $(this).data("borrower_id");

				$("#modal-primary").modal("show");
				$("#loan_id").val(loan_id);
				$("#borrower_id").val(borrower_id);
				
			})

			// submit 

			$("#loanActionForm").submit(function(e){
				e.preventDefault();
				var loanActionForm = document.getElementById('loanActionForm');
				var data = new FormData(loanActionForm);

				$.ajax({
					url:'actionsLoan?<?php echo time()?>',
					method:"post",
					data:data,
					cache : false,
    				processData: false,
    				contentType: false,
    				beforeSend:function(){
    					$("#submit").html('<i class="fa fa-spinner fa-spin"></i>');
    				},
					success:function(data){
						if (data === 'success') {
							successNow("Loan Actioned");
							$("#submit").html("Submit");
							setTimeout(function(){
								location.reload();
							}, 2000);
						}else{
							errorNow(data);
						}
						
					}
				})
			})
			// loanPaymentForm ------------

			$("#loanPaymentForm").submit(function(e){
				e.preventDefault();
				var loanPaymentForm = document.getElementById('loanPaymentForm');
				var data = new FormData(loanPaymentForm);

				$.ajax({
					url:'loanPayments?<?php echo time()?>',
					method:"post",
					data:data,
					cache : false,
    				processData: false,
    				contentType: false,
    				beforeSend:function(){
    					$("#addPay").html('<i class="fa fa-spinner fa-spin"></i>');
    				},
					success:function(data){
						if (data === 'success') {
							successNow("Payment Submitted");
							$("#addPay").html("Submit");
							setTimeout(function(){
								location.reload();
							}, 2000);
						}else if(data === 'updated'){
							successNow("Payment Update");
							setTimeout(function(){
								location.reload();
							}, 2000);
							
						}else{
							errorNow(data);
						}
						
					}
				})
			})
		})

		// ======= EDIT LOAN --------

		$(document).on("click", ".editPayment", function(e){
			e.preventDefault();
			var payment_id = $(this).data("id");
			$("#paymentModal").modal("show");
			$.ajax({
				url:"editPayments",
				method:"post",
				data:{payment_id:payment_id},
				dataType:"JSON",
				success:function(data){
					$("#edit_id").val(data.id);
					$("#payment_method").val(data.payment_method);
					$("#datemask2").val(data.paid_date);
					$("#comment").val(data.comment);
					$("#amount").val(data.amount);
				}
			})
		})

		$(document).on("click", ".deletePayment", function(e){
			e.preventDefault();
			var payment_id = $(this).data("id");
			$("#modal-danger").modal("show");
			$("#delete_id").val(payment_id);
		})

		$("#submitTodelete").click(function(e){
			var delete_id = $("#delete_id").val();
			$.ajax({
				url:'loanPayments?<?php echo time()?>',
				method:"post",
				data:{delete_id:delete_id},
				beforeSend:function(){
					$("#submitTodelete").html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success:function(data){
					if (data === 'success') {
						successNow("Payment Deleted");
						$("#submitTodelete").html("DELETE");
						setTimeout(function(){
							location.reload();
						}, 2000);
					}else{
						errorNow(data);
					}
					
				}
			})	
		})

		
		function errorNow(msge){
      		toastr.error(msge)
      		toastr.options.progressBar = true;
      		toastr.options.positionClass = "toast-top-center";
      		toastr.options.showDuration = 1000;
      	}

      	function successNow(msge){
      		toastr.success(msge)
      		toastr.options.progressBar = true;
      		toastr.options.positionClass = "toast-top-center";
      		toastr.options.showDuration = 1000;
      	}

   //    	function failed(msge) {
   //    		toastr["error"]("Sorry, not processing<br /><br /><button type='button' class='btn clear'>Close</button>', 'Failed");
   //    		toastr.options = {
			//   "closeButton": true,
			//   "debug": false,
			//   "newestOnTop": false,
			//   "progressBar": false,
			//   "positionClass": "toast-top-center",
			//   "preventDuplicates": false,
			//   "onclick": null,
			//   "showDuration": "300",
			//   "hideDuration": "1000",
			//   "timeOut": 0,
			//   "extendedTimeOut": 0,
			//   "showEasing": "swing",
			//   "hideEasing": "linear",
			//   "showMethod": "fadeIn",
			//   "hideMethod": "fadeOut",
			//   "tapToDismiss": false
			// }
   //    	}
   //    	function goodMsg(mgs) {
   //    		toastr["error"]("Sorry", mgs+" <br /><br /><button type='button' class='btn clear'>Close</button>", "Failed");
   //    		toastr.options = {
			//   "closeButton": true,
			//   "debug": false,
			//   "newestOnTop": false,
			//   "progressBar": false,
			//   "positionClass": "toast-top-center",
			//   "preventDuplicates": false,
			//   "onclick": null,
			//   "showDuration": "300",
			//   "hideDuration": "1000",
			//   "timeOut": 0,
			//   "extendedTimeOut": 0,
			//   "showEasing": "swing",
			//   "hideEasing": "linear",
			//   "showMethod": "fadeIn",
			//   "hideMethod": "fadeOut",
			//   "tapToDismiss": false
			// }
   //    	}
   //    		toastr.options = {
			// 	"closeButton": false,
			// 	"debug": false,
			// 	"newestOnTop": false,
			// 	"progressBar": false,
			// 	"positionClass": "toast-top-center",
			// 	"preventDuplicates": false,
			// 	"onclick": null,
			// 	"showDuration": "300",
			// 	"hideDuration": "1000",
			// 	"timeOut": "5000",
			// 	"extendedTimeOut": "1000",
			// 	"showEasing": "swing",
			// 	"hideEasing": "linear",
			// 	"showMethod": "fadeIn",
			// 	"hideMethod": "fadeOut"
			// }
	</script>
</body>
</html>