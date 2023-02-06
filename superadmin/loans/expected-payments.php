<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Today's expected payments</title>
	<?php include("../addon_header.php");?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light">
      				
			<div class="container-fluid pt-3">
				<div class="row">
					<div class="col-md-12">     						
						<div class="card card-primary card-outline">
							<div class="card-header">
								<h4 class="card-title"><?php echo date("j F, Y");?> - Today's Expected Payments</h4>
							</div>
							<?php
								
								$query = $connect->prepare("SELECT * FROM loan_applications WHERE branch_id = ? AND parent_id = ? AND repayment_start_date = ? ");
								$today = date("Y-m-d");
								$query->execute([$BRANCHID, $_SESSION['parent_id'], $today]);
							?>
							<form method="post" class="createMessageForm" id="createMessageForm" enctype="multpart/form-data">
								<div class="card-body box-profile">
									<div class="table table-responsive">
										<table class="table cell-table" id="myTable">  
											
											<thead>
												<tr>
													<th><input type="checkbox" name="check_all" id="check_all" style="margin-left: -8px;"></th>
													<th>Names</th>
													<th>Date</th>
													<th>Amount</th>
													<th>Phone</th>
												</tr>
											</thead>  
											<tbody class="text-dark">  
											<?php  

												foreach ($query as $row) {?>
												<tr id="<?php echo $row['id']?>">
													<td data-column="Send SMS"><input type="checkbox" name="checked_user[]" class="checkSingle" id="checked_user" value="<?php echo $row['borrower_phone']?>"></td>
													<td data-column="Firstname"><?php echo getBorrowerFullNamesByCardId($connect, $row['applicant_id']) ?> </td>
													<td data-column="Lastname"><?php echo date('j F, Y', strtotime($row['repayment_start_date'])) ?></td>
													<td data-column="Lastname"><?php echo $row['currency'] ?> <?php echo $row['total_loan_amount'] ?></td>
													<td data-column="Mobile"><?php echo getClientsPhone($connect, $row['applicant_id'])?></td>
													
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
													<th>Names</th>
													<th>Due Date</th>
													<th>Amount</th>
													<th>Phone</th>
													<!-- <th>Sent SMS</th> -->
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								
								<div class="card-header bg-warning">
									<h4 class="card-title">Create reminder SMS</h4>
								</div>
								<div class="card-body">
									<div class="sms-result"></div>
									<div class="form-group">
										<label>Message</label><br>
										<textarea name="sms" id="sms" placeholder="Write your Message" class="form-control" style="resize: none;">Hello, Your payment is for a loan you got from valuefin is due today.</textarea>
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
      			
      	</section>
	</div>
	<?php include("../addon_footer.php")?>
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
					url:"borrowers/send-sms-to-borrower",
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

	
    	
	</script>
</body>
</html>