<?php 
  	require ("../includes/db.php");
  	require ("../includes/tip.php"); 

  	
	$loan_type = "";
	$parent_id = preg_replace("#[^0-9]#", "", $_SESSION['parent_id']);
	$query = $connect->prepare("SELECT * FROM loan_type WHERE parent_id = ?");
	$query->execute(array( $parent_id));
	$numRows = $query->rowCount();
	foreach ($query->fetchAll() as $row){
		$loan_type .= '<option value="'.$row['id'].'">'.$row['type_name'].'</option>';
	}
 	$option = '';
  	$query = $connect->prepare("SELECT * FROM currencies");
  	$query->execute();
  	foreach ($query->fetchAll() as $row) {
    	$option .= '<option value="'.$row['code'].'">'.$row['code'].'</option>';
  	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Loan Settings</title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<div class="content-header">
		      	<div class="container-fluid mt-4">
		        	<div class="row mb-2 mt-5">
		          		<div class="col-sm-6">
		            		<h4 class="m-0"><?php echo ucwords(getOrganisationName($connect, $_SESSION['parent_id']))?></h4>
		          		</div>
		          		<div class="col-sm-6">
		            		<ol class="breadcrumb float-sm-right">
		              			<li class="breadcrumb-item"><a href="./" id="timeRemaining">Home</a></li>
		              			<li class="breadcrumb-item active"><?php echo ucwords(getOrganisationName($connect, $_SESSION['parent_id']))?> </li>
		            		</ol>
		          		</div>
		        	</div>
		      	</div>
		    </div>
			<section class="content mt-5">      			
      					
      			<div class="container-fluid pt-3">
      				<h4 class="mb-3 ">Create Loans Types</h4>
      				<div class="row">
      					<div class="col-md-4">
      						<div class="card card-warning card-outline mb-5">
      							<form action="" id="manage-loan-type">
								
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
											<div class="col-md-12 text-center">
												<button class="btn btn-primary" id="addbtn" type="submit" onclick="saveLoanType()"> Save</button>
												
											</div>
										</div>
									</div>
									
								</form>
							</div>
      					</div>
      					<div class="col-md-8">
      						     						
      						<div class="card card-warning card-outline mb-5">
      							<div class="card-body box-profile">
      								<div id="fetchLoanTypes"></div>
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
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script src="plugins/toastr/toastr.min.js"></script>
	<script>
		

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
					
					errorNow(xhr.responseText);
					document.getElementById("addbtn").innerHTML = 'Submit';
			    	displayLoansTypes();
			    	_reset();
				}
				
			}
			xhr.send(data);
			document.getElementById("addbtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		}
		function _reset(){
			$('[name="id"]').val('');
			$('#manage-loan-type')[0].reset();
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
			    			successNow(data);
			    			displayLoansTypes();	
			    		}
			    	})
			    }else{
			    	return false;
			    }
		    })
		});

		function displayLoansTypes(){
			var all_branches = "all_branches";
			var parent_id = "<?php echo $_SESSION['parent_id']?>";
			$.ajax({
	    		url: 'loans/actionsLoan',
	    		method:'post',
	    		data:'all_branches='+all_branches+'&loanTypesParentId=<?php echo $_SESSION['parent_id']?>',
	    		
	    		success:function(data){
	    			$("#fetchLoanTypes").html(data);
	    		}
	    	})
		}
		displayLoansTypes();

	</script>
</body>
</html>