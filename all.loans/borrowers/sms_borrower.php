<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php");  
	

?>
<!DOCTYPE html>
<html>
<head>
	<title>SMS Borrowers</title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
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
      					<?php 
      						$query = $connect->prepare("SELECT * FROM sms WHERE parent_id = ? ");
							$query->execute(array($_SESSION['parent_id']));
							$count = $query->rowCount();
							if ($count > 0) {
								$row = $query->fetch();
								$all = 1000;
								$remaining = "SMS: ". ($all - $count);
								
							}else{
								$remaining = "SMS: 1000 ";
							}
      					?>
      					<div class="col-md-12 d-flex justify-content-between mt-5 border-bottom border-primary">
      						<h4><?php echo $remaining?></h4>
      						<!-- <h4><a href="members/sms-create-sender-id" class="btn btn-primary">Create Sender ID</a></h4> -->
      					</div>
      				</div>
      			</div>
      			<!-- borrower form -->
      			<div class="container-fluid pt-3">
      				<div class="row">
      					<div class="col-md-12">     						
      						<div class="card card-primary">
      							<div class="card-header">
      								<h4 class="card-title">Clients</h4>
      							</div>
      							
  								<?php 
  									$query = $connect->prepare("SELECT * FROM borrowers_details WHERE branch_id = ? AND parent_id = ? AND display = '1' ");
  									$query->execute(array($BRANCHID, $_SESSION['parent_id']));
  								?>
  								<form method="post" class="createMessageForm" id="createMessageForm" enctype="multpart/form-data">
      								<div class="card-body box-profile">
										<div class="table table-responsive">
											<table class="table cell-table" id="myTable">  
											  
												<thead>
													<tr>
														<th><input type="checkbox" name="check_all" id="check_all" style="margin-left: -9px;"></th>
														<th>Firstname</th>
														<th>Lastname</th>
														<th>Mobile</th>
														<th>Sent SMS</th>
														<th>Edit</th>
													</tr>
												</thead>  
												<tbody class="text-dark">  
												<?php  

													foreach ($query as $row) {?>
													<tr id="<?php echo $row['id']?>">
														<td data-column="Send SMS"><input type="checkbox" name="checked_user[]" class="checkSingle" id="checked_user" value="<?php echo $row['borrower_phone']?>"></td>
														<td data-column="Firstname"><?php echo $row['borrower_firstname'] ?></td>
														<td data-column="Lastname"><?php echo $row['borrower_lastname']?></td>
														<td data-column="Mobile"><?php echo $row['borrower_phone']?></td>
														<td><a href="borrowers/sentSMS?user_phone=<?php echo $row['borrower_phone']?>&username=<?php echo $row['borrower_firstname'] ?>">View <?php echo countSMS($connect, $row['borrower_phone'], $_SESSION['parent_id'])?> SMS</a></td>
														<td data-column="Edit">
															<a href="borrowers/edit_borrower_details?borrower_id=<?php echo $row['borrower_ID']?>"><i class="bi bi-pencil-square" aria-hidden="true"></i></a>
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
														<th>Mobile</th>
														<th>Sent SMS</th>
														<th>Edit</th>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>
									
									<div class="card-header bg-warning">
										<h4 class="card-title">Create New Message</h4>
									</div>
									<div class="card-body">
										<div class="sms-result"></div>
										<div class="form-group">
											<label>Message</label><br>
											<textarea name="sms" id="sms" placeholder="Write your Message" class="form-control" style="resize: none;"></textarea>
											<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
											<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID?>">
										</div>
										<button class="btn btn-primary  shadow" type="button" id="sms-btn">Send SMS <i class="fa fa-send" id="fa-sending"></i></button> 
									</div>
								</form>
      						</div>
      					</div>
      				</div>
      			</div>
      			
				<!-- Editing Modal -->
				<div class="modal fade" id="modalEditNumber">
					<div class="modal-dialog modal-lg">
						<div class="modal-content bg-secondary">
							<div class="modal-header">
								<h4 class="modal-title">Edit Borrower Number</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">

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
	<script src="plugins/toastr/toastr.min.js"></script>
	<script>
		$(document).ready( function () {
		    $('#myTable').DataTable();
		});
		
		$(function(){
		
		$("#check_all").change(function(){
		    if(this.checked){
		      	$(".checkSingle").each(function(){
		        	this.checked=true;
		        	$("#counting").text($('#checked_user:checked').length);
		      	})             
		    }else{
		      	$(".checkSingle").each(function(){
		        	this.checked=false;
		        	$("#counting").text($('#checked_user:checked').length);
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
			$("#counting").text($('#checked_user:checked').length);
		});


		$("#sms-btn").click(function(){
			if ($(".checkSingle").is(":checked")) {
				var mobile = $("#mobile").val();
				var sms = document.getElementById("sms");

				if (sms.value === "") {
					errorNow("Write your message");
					sms.focus();
					return false;
				}
				let myForm = document.getElementById('createMessageForm');
				let formData = new FormData(myForm);

				$.ajax({
					url:"borrowers/send-sms-to-borrowers",
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
						setTimeout(function(){
							location.reload();
						}, 2000);
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