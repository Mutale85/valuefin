<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php"); 
?>
<!DOCTYPE html>
<html>
<head>
	<title>SMS Investor</title>
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
					<?php 
						$query = $connect->prepare("SELECT * FROM sms WHERE parent_id = ?");
						$query->execute(array($_SESSION['parent_id']));
						$count = $query->rowCount();
						$remaining = 500 - $count;
					?>
					<div class="col-md-12 mb-5 d-flex justify-content-between mt-5 border-bottom border-primary">
						<h4>REMAINING SMS: <b><?php echo $remaining?></b></h4>
					</div>
				
					<div class="col-md-12">     						
						<div class="card card-warning-outline">
							<div class="card-header">
								<h4 class="card-title">Borrowers</h4>
							</div>
							
							<?php 
								$query = $connect->prepare("SELECT * FROM investors WHERE parent_id = ?");
								$query->execute(array($_SESSION['parent_id']));
							?>
							<form method="post" id="createMessageForm" enctype="multpart/form-data">
								<div class="card-body box-profile">
									<div class="table table-responsive">
										<table class="cell-table" id="myTable">  
											
											<thead>
												<tr>
													<th><input type="checkbox" name="check_all" id="check_all"></th>
													<th>Names</th>
													<th>Phonenumber</th>
													<th>Sent SMS</th>
												</tr>
											</thead>  
											<tbody class="text-dark">  
											<?php  

												foreach ($query as $row) {

												?>
												<tr id="<?php echo $row['id']?>">
													<td data-column="Send SMS"><input type="checkbox" name="checked_user[]" class="checkSingle" id="checked_user" value="<?php echo $row['phone']?>"></td>
													<td data-column="Firstname"><?php echo $row['firstname'] ?></td>
													<td data-column="Mobile" class="text-danger"><?php echo $row['phone']?></td>
													<td><a href="SMSFIles/view-sent-sms?phonenumber=<?php echo base64_encode($row['phone'])?>&username=<?php echo $row['firstname'] ?>">View <?php echo countSMS($connect, $row['phone'], $_SESSION['parent_id'])?> SMS</a></td>
													
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
													<th>Phonenumber</th>
													<th>Sent SMS</th>
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
									<div class="form-group">
										<label>Message</label><br>
										<textarea name="sms" id="sms" placeholder="Write your Message" class="form-control" style="resize: none;"></textarea>
										<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
										<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID?>">
									</div>
									<button class="btn btn-primary" type="button" id="sendBtn">Send SMS </button> 
								</div>
							</form>
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


		$("#sendBtn").click(function(){
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
					url:"SMSFIles/sendMessage",
					method:"post",
					data:formData,
					cache:false,
					processData:false,
					contentType:false,
					beforeSend:function(){
						$("#sendBtn").html('Sending...');
					},
					success:function(response){
						successNow(response);
						setTimeout(function(){
							location.reload();
						}, 2000);
						
						
					}
				})
			}else{
				errorNow("Please tick at-least one Investor");
				return false;
			}
		});
	});

	</script>
</body>
</html>