<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 

?>
<!DOCTYPE html>
<html>
<head>
	<title>Loan Types</title>
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
			<section class="content bg-light mt-5">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 pb-2 d-flex justify-content-between border-bottom">
  							<h1 class="h4">Add Loan Type</h1>
  							<!-- We create a table of all borrowers and we tick to pick the numbers -->
  							<button class="btn btn-warning" type="btn" data-toggle="modal" data-target="#modalLoan">Add Loan Type</button>
  						</div>
      				</div>
      			</div>
      			<!-- borrower form -->
      			<div class="container-fluid pt-3">
      				<div class="row">
      					
      					<div class="col-md-12">
      						<h4 class="mb-4"><strong>Loan Types</strong></h4>
      						
      						<div class="card card-primary card-outline mb-5">
      							<div class="card-body box-profile">
      								<!-- <div id="fetchLoanTypes"></div> -->
      								<div class="table table-responsive">
      									<table id="loanTypes" class="cell-border" style="width:100%">
									        <thead>
									            <tr>
									            	<th>#</th>
									                <th>Loan Type</th>
									                <?php if($_SESSION['user_role'] == 'Admin'):?>
									                <th>Actions</th>
									                <?php else:?>
									                <?php endif;?>
									                <th>Description</th>
									            </tr>
									        </thead>
									        <tbody>
									        	<?php
									        		$parent_id = preg_replace("#[^0-9]#", "", $_SESSION['parent_id']);
													$query = $connect->prepare("SELECT * FROM loan_type WHERE parent_id = ?");
													$query->execute(array( $parent_id));
													$numRows = $query->rowCount();
													$i = 1;
													if ($numRows > 0 ) {
														$loanData = array();
														$i = 1;
														foreach ($query->fetchAll() as $row) {?>
															<tr>
																<td><?php echo $i++?></td>
																<td><b><?php echo $row['type_name']?></b></td>
																<td>
																	<?php echo ucfirst($row['description'])?>
																</td>
																<td>
																	<a href="" class="editLoanType text-primary" data-id="<?php echo $row['id']?>"><i class="bi bi-pencil-square"></i></a>
																	<a href="" class="deleteLoanType text-danger" data-id="<?php echo $row['id']?>"><i class="bi bi-trash"></i></a>
																</td>
															</tr>
													<?php
														}
													}
									        	?>
									        
									     	</tbody>
									    </table>
      								</div>
      							</div>
      						</div>
      					</div>
      				</div>
      			</div>
      			
				<!-- Editing Modal -->
				<div class="modal fade" id="modalLoan">
					<div class="modal-dialog modal-lg">
						<div class="modal-content bg-secondary">
							<div class="modal-header">
								<h4 class="modal-title">Loan Type</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body text-secondary">
								<form action="" id="manage-loan-type">
									<div class="card">
										<div class="card-header">
											Loan Type Form
									  	</div>
										<div class="card-body">
											<input type="hidden" name="id">
											<div class="form-group">
												<label class="control-label">Type</label>
												<input type="text" name="type_name"  id="type_name"  class="form-control" required="required">
												<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
												<input type="hidden" name="id" id="id">
											</div>
											<div class="form-group">
												<label class="control-label">Description</label>
												<textarea name="description" id="description" cols="30" rows="6" class="form-control" placeholder="Description" required="required"></textarea>
											</div>
										</div>
										<div class="card-footer">
											<div class="row">
												<div class="col-md-12">
													<button class="btn btn-sm btn-primary col-sm-3 offset-md-3" id="addbtn" type="submit" onclick="saveLoanType()"> Save</button>
													<button class="btn btn-sm btn-default col-sm-3" type="button" data-target="modal" onclick="_reset()"> Cancel</button>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- End of edit modal -->
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
		    $('#loanTypes').DataTable();
		    // select
		    $('.select2').select2();
		});

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

		saveLoanType = function(){
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'loans/addLoanType';
			var branchForm = document.getElementById('manage-loan-type');
			xhr.open("POST", url, true);
			var type_name = document.getElementById('type_name').value;
			if (type_name == "") {
				errorNow("Loan type is required");
				return false;
			}
			var data = new FormData(branchForm);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					if (xhr.responseText === 'done') {
						successNow(type_name + ' added to the database');
						$("#manage-loan-type")[0].reset();
						document.getElementById("addbtn").innerHTML = 'Submit';
						// location.reload();
						// $("#modalLoan").modal("show");

					}else if(xhr.responseText === 'Updated'){
						successNow(type_name + ' Updated');

					}else{

						errorNow(xhr.responseText);
						document.getElementById("addbtn").innerHTML = 'Submit';
						return false;
					}
					document.getElementById("addbtn").innerHTML = 'Submit';
					// load();
				}
				
			}
			xhr.send(data);
			document.getElementById("addbtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}
		function _reset(){
			$('[name="id"]').val('');
			$('#manage-loan-type')[0].reset();
			$("#modalLoan").modal("hide");
			
			location.reload();
		}
		$(document).ready( function () {
		
		    $(document).on("click", ".editLoanType", function(e){
		    	e.preventDefault();
		    	var id = $(this).data('id');
		    	$("#modalLoan").modal("show");
		    	$.ajax({
		    		url: 'loans/editLoanType',
		    		method:'post',
		    		data:'editor_id='+id+'&loggedinID=<?php echo $_SESSION['parent_id']?>',
		    		dataType:"JSON",
		    		success:function(data){
		    			$("#type_name").val(data.type_name);
		    			$("#description").val(data.description);
		    			$("#id").val(data.id);
		    		}
		    	})
		    });
		    $(document).on("click", ".deleteLoanType", function(e){
		    	e.preventDefault();
		    	var id = $(this).data('id');
		    	if(confirm("Confirm deleting loan type")){
			    	$.ajax({
			    		url: 'loans/editLoanType',
			    		method:'post',
			    		data:'delete_id='+id+'&loggedParentId=<?php echo $_SESSION['parent_id']?>',
			    		
			    		success:function(data){
			    			if(data === 'done'){
			    				successNow("Loan Type Deleted");
			    				location.reload();
			    			}else{
			    				errorNow("Error Deleting Loan");
			    			}
			    		}
			    	})
			    }else{
			    	return false;
			    }
		    })
		});

	</script>
</body>
</html>