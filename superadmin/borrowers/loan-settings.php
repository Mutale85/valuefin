<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");

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
	<?php include("../addon_header.php");?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content">     					
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
									<div class="form-group">
										<label class="control-label">Type</label>
										<input type="text" name="type_name"  id="type_name"  class="form-control" required="required">
										<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
										<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID?>">
										<input type="hidden" name="id" id="id">
									</div>
									<div class="form-group">
										<label class="control-label">Interest Rate</label>
										<input type="number" name="interest_rate"  id="interest_rate"  class="form-control" step="any" required="required">
									</div>
									<div class="form-group">
										<label class="control-label">Period</label>
										<select name="period" id="period" class="form-control"  required="required">
											<option disabled selected>Choose</option>
											<option value="Per Day"> Per Day</option>
											<option value="Per Week"> Per Week</option>
											<option value="Per Month"> Per Month</option>
										</select>
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

	<?php include("../addon_footer.php")?>
	<script>
		
		saveLoanType = function(){
			event.preventDefault();
			var xhr = new XMLHttpRequest();
			var url = 'borrowers/loans/addLoanType';
			var branchForm = document.getElementById('manage-loan-type');
			xhr.open("POST", url, true);
			var type_name = document.getElementById('type_name').value;
			if (type_name == "") {
				errorToast("Loan type is required");
				return false;
			}
			var data = new FormData(branchForm);
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200) {
					
					errorToast(xhr.responseText);
					document.getElementById("addbtn").innerHTML = 'Submit';
			    	displayLoansTypes();
			    	// _reset();
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
		    	// $("#modalLoan").modal("show");
		    	$.ajax({
		    		url: 'borrowers/loans/editLoanType',
		    		method:'post',
		    		data:'editor_id='+id+'&loggedinID=<?php echo $_SESSION['parent_id']?>',
		    		dataType:"JSON",
		    		success:function(data){
		    			$("#type_name").val(data.type_name);
		    			$("#interest_rate").val(data.interest_rate);
		    			$("#period").val(data.period);
		    			$("#id").val(data.id);
		    		}
		    	})
		    });
		    $(document).on("click", ".deleteLoanType", function(e){
		    	e.preventDefault();
		    	var id = $(this).data('id');
		    	if(confirm("Confirm deleting loan type")){
			    	$.ajax({
			    		url: 'borrowers/loans/editLoanType',
			    		method:'post',
			    		data:'delete_id='+id+'&loggedParentId=<?php echo $_SESSION['parent_id']?>',
			    		
			    		success:function(data){
			    			successToast(data);
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
	    		url: 'borrowers/loans/actionsLoan',
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