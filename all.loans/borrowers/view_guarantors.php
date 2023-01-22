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
	<title>View Borrowers</title>
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
			<section class="content ">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 border-bottom pb-2 ">
      						<div class="d-flex justify-content-between">
      						<h1 class="h3"><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $BRANCHID))?> Guarantors</h1>
      						<a href="borrowers/add_guarantors" class="btn btn-outline-warning" ><i class="bi-person-plus"></i> Add New Guarantor</a>
      						</div>
      					</div>

      				</div>
      			</div>
      			<!-- borrower form -->
      			<div class="container-fluid">
      				<div class="row">
      					<div class="col-md-12">

      						<?php 
      							$query 	= $connect->prepare("SELECT * FROM guarantors WHERE parent_id = ? AND branch_id = ? ");
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
							            	<th>Fullnames</th>
							                <th>Contact</th>
							                <th>Business</th>
							                <th>Gurantees</th>
							                <th>Branch</th>
							                <th>Action</th>
							            </tr>
							        </thead>
							        <tbody class="text-dark">
							        	<?php 
							        		$i = 1;
							        		if ($count > 0) {
							        			foreach ($res as $row) {
							        				// here now, the admin will only see those who belong to his branches
							        				$branch_id = $row['branch_id'];
							        				if ($row['business'] == "") {
							        					$bs = "N/A";
							        				}else{
							        					$bs = $row['business'];
							        				}
							        				if ($row['email'] == "") {
							        					$em = "N/A";
							        				}else{
							        					$em = $row['email'];
							        				}
							        				$sql = $connect->prepare("SELECT * FROM `loans` WHERE guarantor_id = ? AND branch_id = ? and parent_id = ? ");
							        				$sql->execute(array($BRANCHID, $row['id'], $_SESSION['parent_id']));
							        				if ($sql->rowCount() > 0) {
							        					$rows = $sql->fetch();
							        					if ($rows) {
							        						$borrower =  getBorrowerFullNamesByCardId($connect, $rows['borrower_id']).'<br>';
							        					}
							        					
							        				}

							        	?>
							        				<tr>
							        					<td><?php echo $i++?></td>
							        					
							        					<td>
							        						<p><strong><?php echo $row['firstname']?> <?php echo $row['lastname']?></strong></p>
							        						<p><span class="text-secondary">ID: </span> <?php echo $row['card_id']?></p>
							        					</td>
							        					<td>
							        						
							        						<p><span class="text-secondary">Address: </span> <?php echo $row['address']?></p>
							        						<p><span class="text-secondary">Contact # : </span> <?php echo $row['phone']?></p>
							        						<p><span class="text-secondary">Email: </span> <?php echo $em;?></p>
							        						<p><span class="text-secondary">Work: </span> <?php echo preg_replace("#[^a-zA-Z]#", " ", ucwords($row['working_status']))?></p>
							        					</td>
							        					<td><?php echo ucwords($bs)?></td>
							        					<td><?php echo getBorrowerFullNamesByCardId($connect, $row['borrower_id'])?></td> 
							        					<td><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $row['branch_id']))?></td>
							        					<td>
							        						<a href="borrowers/edit_guarantors_details?guarantors_id=<?php echo $row['card_id']?>" class="btn btn-outline-secondary editUser" id="<?php echo $row['id']?>"><i class="bi bi-pencil-square"></i>
							        						</a>
							        						<a href="borrowers/view_details?guarantors_id=<?php echo $row['card_id']?>" class="btn btn-outline-secondary text-danger removeGurantor" id="<?php echo $row['id']?>" data-id="<?php echo $row['id']?>" data-branch_id="<?php echo $branch_id?>"><i class="fa fa-trash text-danger"></i></a>
							        					</td>
							        				</tr>
							        	<?php
							        			}
							        		}else{
							        			
							   
							        		}
							        	?>

							     	</tbody>
							     	<tfoot>
							            <tr>
							            	<th>Serial #</th>
							            	<th>Fullnames</th>
							                <th>Contact</th>
							                <th>Business</th>
							                <th>Gurantees</th>
							                <th>Branch</th>
							                <th>Action</th>
							            </tr>
							        </tfoot>
							    </table>
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
		    $('.select2').select2();
		} );
	</script>
	<script>
		$(function(){
			$("#dateofbirth").datepicker({

				format: 'yyyy/mm/dd'
			});
		})
	</script>
	<script>



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
		
		 $(document).on("click", ".removeGurantor", function(e){
	    	e.preventDefault();
	    	var guarantors_id = $(this).attr('id');
	    	var branch_id = $(this).data('branch_id');
	    	var delete_id = $(this).data('id');
	    	var parentID = '<?php echo $_SESSION['parent_id']?>';
	    	if(confirm("Confirm removing borrower? All loan information will also be removed")){
		    	$.ajax({
		    		url: 'borrowers/action',
		    		method:'post',
		    		
		    		data: {guarantors_id:guarantors_id, branch_id:branch_id,  parentID:parentID},
		    		
		    		success:function(data){
		    			if(data === 'done'){
		    				successNow("Guarantor Removed from the system");
		    				setTimeout(function(){
								location.reload();
		    				}, 2000)
		    				
		    			}else{
		    				errorNow("Error Deleting Guarantor");
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