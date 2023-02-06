<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Email Staff</title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
	
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
      					
      					<div class="col-md-12 d-flex justify-content-between mt-5 border-bottom border-primary">
      						<h4>Email Staff Members</h4>
      					</div>
      				</div>
      			</div>
      			<!-- borrower form -->
      			<div class="container-fluid pt-3">
      				<div class="row">
      					<div class="col-md-12">     						
      						<div class="card card-primary">
      							<div class="card-header">
      								<h4 class="card-title">Staff Members</h4>
      							</div>
      							
  								<?php 
  									$query = $connect->prepare("SELECT * FROM admins WHERE parent_id = ? ");
  									$query->execute(array($_SESSION['parent_id']));
  								?>
  								<form method="post" class="sendEmailForm" id="sendEmailForm" enctype="multpart/form-data">
      								<div class="card-body box-profile">
										<div class="table table-responsive">
											<table class="cell-table" id="myTable">  
											  
												<thead>
													<tr>
														<th><input type="checkbox" name="check_all" id="check_all"></th>
														<th>Firstname</th>
														<th>Lastname</th>
														<th>Email</th>
														<th>Sent Email</th>
														<th>Edit</th>
													</tr>
												</thead>  
												<tbody class="text-dark">  
												<?php  

													foreach ($query as $row) {
														extract($row)
														
													?>
													<tr id="<?php echo $row['id']?>">
														<td><input type="checkbox" name="reciever_email[]" class="checkSingle" data-username="<?php echo $firstname ?>" id="reciever_email" value="<?php echo $email?>">
															<input type="hidden" name="staff_name[]" id="staff_name" value="<?php echo $firstname ?>">
														</td>
														<td><?php echo $firstname ?></td>
														<td><?php echo $lastname?></td>
														<td><?php echo $email?></td>
														<td><a href="SMSFIles/sentEmails?user_email=<?php echo $email?>&username=<?php echo $firstname ?>">View <?php echo countEmails($connect, $email, $_SESSION['parent_id'])?> Email</a></td>
														<td data-column="Edit">
															<a href="members/staff-member-edit?staff_id=<?php echo base64_encode($id)?>"><i class="bi bi-pencil-square" aria-hidden="true"></i></a>
													</tr>
												<?php	
													}
												?>
												</tbody>
												<tfoot>
													<tr>
														<th>
															<span id="counting"></span>
														</th>
														<th>Firstname</th>
														<th>Lastname</th>
														<th>Email</th>
														<th>Sent Email</th>
														<th>Edit</th>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>
									
									<div class="card-header bg-secondary">
										<h4 class="card-title">Create New Message</h4>
									</div>
									<div class="card-body">
										<div class="sms-result"></div>
										<div class="form-group mb-3">
											<label>Subject</label>
											<input type="text" name="subject" id="subject" class="form-control" required>
										</div>
										<div class="form-group mb-3">
											<label>Message</label><br>
											<textarea name="message" id="message" placeholder="Write your Message" class="form-control" style="resize: none;"></textarea>
											<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
											<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID?>">
										</div>
										<div class="form-group mb-3">
											<a href="" class="attach"><i class="bi bi-paperclip" aria-hidden="true"></i> Attach File</a>
											<input type="file" name="attachment" id="attachment" style="display: none;">
											<div class="results"></div>
										</div>
										<button class="btn btn-primary" type="button" id="emailBtn">Send Email <i class="fa fa-send" id="fa-sending"></i></button> 
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
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	<script src="plugins/select2/js/select2.full.min.js"></script>
	<script src="plugins/toastr/toastr.min.js"></script>
	
	<script>
		$(document).ready( function () {
		    $('#myTable').DataTable();
		    $('#ServerTable').DataTable();
		});
		
		$(function(){
			
		
		$("#check_all").change(function(){
		    if(this.checked){
		      	$(".checkSingle").each(function(){
		        	this.checked=true;
		        	$("#counting").text($('#reciever_email:checked').length);
		      	})             
		    }else{
		      	$(".checkSingle").each(function(){
		        	this.checked=false;
		        	$("#counting").text($('#reciever_email:checked').length);
		      	})              
		    }
 		});

  		$(".checkSingle").click(function () {
    		if ($(this).is(":checked")){
      			var isAllChecked = 0;
      			$(".checkSingle").each(function(){
        			if(!this.checked)
           			isAllChecked = 1;
      			})              
      			if(isAllChecked == 0){ $("#check_all").prop("checked", true); }     
    		}else {
      			$("#check_all").prop("checked", false);
    		}
  		});



		$(".checkSingle").click(function(){
			$("#counting").text($('#reciever_email:checked').length);
		});

		$(".attach").click(function(event){
			event.preventDefault();
			$("#attachment").click();
		});
		$("#attachment").change(function(){
			$(".results").html($(this).val().split('\\').pop());
		})

		$("#emailBtn").click(function(){
			if ($(".checkSingle").is(":checked")) {
				var message = document.getElementById("message");

				if (message.value === "") {
					errorNow("Write your message");
					message.focus();
					return false;
				}
				let myForm = document.getElementById('sendEmailForm');
				let formData = new FormData(myForm);

				$.ajax({
					url:"SMSFIles/send-email-to-staff",
					method:"post",
					data:formData,
					cache:false,
					processData:false,
					contentType:false,
					beforeSend:function(){
						$("#fa-sending").removeClass("fa-send");
						$("#fa-sending").addClass("fa-spinner fa-spin");
					},
					success:function(response){
						successNow(response);
						if (response === 'done') {
							setTimeout(function(){
								location.reload();
							}, 2000);
						}else{
							errorNow(response);
							setTimeout(function(){
								location.reload();
							}, 2000);
						}
						$("#fa-sending").addClass("fa-send");
						$("#fa-sending").removeClass("fa-spinner fa-spin");
					}
				})
			}else{
				errorNow("Please tick at-least one borrower");
				return false;
			}
		});
	});

	$(document).on("submit", "#emailSettingForm", function(e){
		e.preventDefault();
		let myForm = document.getElementById('emailSettingForm');
		let formData = new FormData(myForm);
		$.ajax({
			url:"SMSFIles/emailSettings",
			method:"post",
			data:formData,
			cache:false,
			processData:false,
			contentType:false,
			beforeSend:function(){

				$("#createBTN").html("<i class='fa-spinner fa-spin'></i>");
			},
			success:function(response){
				if(response === 'done'){
					successNow("SMTP Saved");
					setTimeout(function(){
						location.reload();
					}, 2000);
				}else if(response === 'Server Updated'){
					successNow("SMTP Server Updated");
					setTimeout(function(){
						location.reload();
					}, 2000);
				}else{
					errorNow(response);
					setTimeout(function(){
						location.reload();
					}, 2000);
				}
				
				
				$("#createBTN").html("Submit");
			}
		})
	});

	$(document).on("click", ".editServer", function(e){
		e.preventDefault();
		var serverId = $(this).attr("href");
		$.ajax({
			url:"SMSFIles/editEmailServer",
			method:"post",
			data: {serverId:serverId},
			dataType:"JSON",
			success:function(data){
				$("#smtp_server").val(data.smtp_server);
				$("#smtp_port").val(data.smtp_port);
				$("#sender_email").val(data.sender_email);
				$("#sender_password").val(data.sender_password);
				$("#ID").val(data.id);
			}
		})
		// alert(serverId);
	})

	$(document).on("click", ".deleteServer", function(e){
		e.preventDefault();
		var deleteserverId = $(this).attr("href");
		if(confirm("Confirm you wish to deleted email settings?")){
			$.ajax({
				url:"SMSFIles/editEmailServer",
				method:"post",
				data: {deleteserverId:deleteserverId},
				
				success:function(data){
					if (data === 'deleted') {
						successNow("SMTP Removed");
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
		

	function errorNow(msge){
  		toastr.error(msge)
  		toastr.options.progressBar = true;
  		toastr.options.positionClass = "toast-top-center";
  	}

  	function successNow(msge){
  		toastr.success(msge)
  		toastr.options.progressBar = true;
  		toastr.options.positionClass = "toast-top-center";
  	}
    	
	</script>
</body>
</html>