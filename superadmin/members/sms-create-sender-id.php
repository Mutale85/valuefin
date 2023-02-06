<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php");  
	

?>
<!DOCTYPE html>
<html>
<head>
	<title>SMS Create Sender ID</title>
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
      						$query = $connect->prepare("SELECT * FROM sms_settings WHERE parent_id = ?");
							$query->execute(array($_SESSION['parent_id']));
							if ($query->rowCount() > 0) {
								$row = $query->fetch();
								if ($row) {
									extract($row);
									
									$query_two = $connect->prepare("SELECT * FROM sms_prices WHERE Prefix = ?");
      								$query_two->execute(array($prefix));
      								if ($query_two->rowCount() > 0) {
      									$roq = $query_two->fetch();
      									if ($roq) {
      										extract($roq);
      										$remaining = "Trial SMS: ". (ceil(1 / $Price) - 1);
      										// We check how many have been sent
      									}
      								}else{
      									$remaining = 'You have no SMS';
      								}
								}
							}else{
								$remaining = ' 1000 SMS';
							}
      					?>
      					<div class="col-md-12 d-flex justify-content-between mt-5 border-bottom border-primary">
      						<h4><?php echo $remaining?></h4>
      						<h4><?php echo  getSenderID($connect, $_SESSION['parent_id'])?></h4>
      					</div>
      				</div>
      			</div>
      			<!-- borrower form -->
      			<div class="container-fluid pt-3">
      				<div class="row">
      					<div class="col-md-6">
      						<div class="card mb-5">
      							<div class="card-header">
      								<h4 class="card-title">Create Sender ID</h4>
      							</div>
      							<div class="card-body">
	      							<form method="post" id="senderidForm">
	      								<div class="form-group">
	      									<label>Enter Name</label>
	      									<input type="text" name="sender_id" id="sender_id" class="form-control" placeholder="Name, no digits" required>
	      									<em>This is the name that will appear as sender of the SMS</em>
	      								</div>
	      								<div class="form-group">
	      									<label>Country Name</label>
	      									<select class="form-control" name="country_name" id="country_name" required>
	      										<option value=""> Select One</option>
	      										<?php
	      											$query = $connect->prepare("SELECT * FROM countries");
	      											$query->execute();
	      											foreach ($query->fetchAll() as $row) {
	      												extract($row);
	      												echo '<option value="'.$country_name.'" data-code="'.$dial_code.'"> '.$country_name.' </option>';
	      											}
	      										?>

	      									</select>
	      								</div>
	      								<div class="form-group">
	      									<label>Country Code</label>
	      									<input type="text" name="prefix" id="prefix" class="form-control" required readonly>
	      									<input type="hidden" name="ID" id="ID">
	      								</div>
	      								<button type="submit" class="btn btn-primary" id="createBTN">Create</button>
	      							</form>
	      						</div>
      						</div>
      					</div>
      					<div class="col-md-6">
      						<div class="card mb-5">
      							<div class="card-header">
      								<h4 class="card-title"> Sender ID</h4>
      							</div>
      							<div class="card-body">
      								<div class="table table-responsive">
      									<table class="table table" id="senderTable">
      										<thead>
      											<tr>
      												<th>Sende ID</th>
      												<th>Country</th>
      												<th>Control</th>
      											</tr>
      										</thead>
      										<tbody class="text-dark">
      											<?php
			      								$query = $connect->prepare("SELECT * FROM sms_settings WHERE parent_id = ?");
												$query->execute(array($_SESSION['parent_id']));
												if ($query->rowCount() > 0) {
													$row = $query->fetch();
													if ($row) {
														extract($row);
												?>
													<tr>
														<td><?php echo $sender_id?></td>
														<td><?php echo $country_name ?></td>
														<td><a href="<?php echo $id?>" class="editSenderId"><i class="bi bi-pencil-square"></i></a> <a href="<?php echo $id?>" class="deleteSenderId"><i class="bi bi-trash"></i></a> </td>
													</tr>
												<?php
													}
												}else{
													echo " Create Sender ID";
												}
			      							?>
      										</tbody>
      									</table>
      								</div>
	      							
	      						</div>
      						</div>
      					</div>

      					<!-- <div class="col-md-12">
      						<form method="post" enctype="multipart/form-data" id="csvForm">
							   <div class="form-group">  
							    	<label>Select CSV File:</label>
							    	<input type="file" name="file"  id="file" class="form-control">
							   </div>
							    <input type="submit" name="submit" value="Import" class="btn btn-info" />
							</form>
      					</div> -->
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
		    $('#myTable, #senderTable').DataTable();

		    $("#country_name").change(function(){
		    	if ($(this).val() == "") {

		    	}else{
				  	var code = $(this).find(':selected').attr('data-code');;
				  	$("#prefix").val(code);
				  	// alert(code);
				  }
			});
		});
		
		$(function(){
			$("#senderidForm").submit(function(e){
				e.preventDefault();
				let myForm = document.getElementById('senderidForm');
				let formData = new FormData(myForm);

				$.ajax({
					url:"members/createSenderId",
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
						if (response === 'done') {
							successNow('Sender ID Added');
							setTimeout(function(){
								location.reload();
							}, 2000);
						}else if(response === 'updated'){
							successNow('Sender ID updated');
							setTimeout(function(){
								location.reload();
							}, 2000);
						}else{
							errorNow(response);
						}
					}
				})
			});

			// $("#csvForm").submit(function(e){
			// 	e.preventDefault();
			// 	let myForm = document.getElementById('csvForm');
			// 	let formData = new FormData(myForm);

			// 	$.ajax({
			// 		url:"members/submitCSV",
			// 		method:"post",
			// 		data:formData,
			// 		cache:false,
			// 		processData:false,
			// 		contentType:false,
			// 		beforeSend:function(){
			// 			$("#fa-sending").removeClass("fa-send");
			// 			$("#fa-sending").addClass("fa-spinner fa-spin");
			// 		},
			// 		success:function(response){
			// 			if (response === 'done') {
			// 				successNow('CSV ID Added');
			// 				setTimeout(function(){
			// 					location.reload();
			// 				}, 2000);
			// 			}else if(response === 'updated'){
			// 				successNow('CSV ID updated');
			// 				setTimeout(function(){
			// 					location.reload();
			// 				}, 2000);
			// 			}else{
			// 				errorNow(response);
			// 			}
			// 		}
			// 	})
			// });

			// ###### EDIT
			$(document).on("click",  ".editSenderId", function(e){
				e.preventDefault();
				var editSenderId = $(this).attr("href");
				$.ajax({
					url:"members/edit",
					method:"post",
					data:{editSenderId:editSenderId},
					dataType:"JSON",
					success:function(data){
						$("#ID").val(data.id);
						$("#sender_id").val(data.sender_id);
						$("#country_name").val(data.country_name);
						$("#prefix").val(data.prefix);
					}
				})
			});

			$(document).on("click",  ".deleteSenderId", function(e){
				e.preventDefault();
				var deleteSenderId = $(this).attr("href");
				if (!confirm("Confirm you wish to delete sender ID, you wont be able to send SMS?")) {
					return false;
				}else{
					$.ajax({
						url:"members/edit",
						method:"post",
						data:{deleteSenderId:deleteSenderId},
						
						success:function(data){
							if (data === 'done') {

								successNow("Sender ID removed");
								setTimeout(function(){
									location.reload();
								}, 2000);
							}else{
								errorNow(data);
							}
						}
					})
				}
			})
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