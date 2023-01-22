<?php 
  	require ("../addons/db.php");
	  require ("../addons/tip.php");
  	$country = $phone = '';
	$query = $connect->prepare("SELECT * FROM currencies");
	$query->execute();
	foreach ($query->fetchAll() as $row) {
	    $country .= '<option value="'.$row['id'].'">'.$row['country'].'</option>';
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>View Borrowers of <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?></title>
	<?php include("../addon_header.php");?>
</head>

<body class="layout-fixed">
	<!-- Navbar -->
	<?php include("../addon_top_min_nav.php")?>
  	<!-- /.navbar -->

  	<!-- Main Sidebar Container -->
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0">Dashboard</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="./">Home</a></li>
						<li class="breadcrumb-item active">Dashboard v1</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<!-- /.content-header -->

		<!-- Main content -->
		<section class="content">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 border-bottom pb-2 mb-5 ">
      						<div class="d-flex justify-content-between">
      						<h1 class="h3"><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?> Borrowers</h1>
      					</div>
      				</div>
      			</div>
      			<div class="container-fluid mb-5">
      				<div class="row">
      					<div class="col-md-12">
      						<?php 
      							$query 	= $connect->prepare("SELECT * FROM borrowers_details WHERE branch_id = ? AND parent_id = ?  ");
      							$query->execute([$BRANCHID, $_SESSION['parent_id']]);
      							$count 	= $query->rowCount();
      							$res 	= $query->fetchAll();
      						?>
      						<div class="table table-responsive">
	      						<table id="myTable" class="cell-border" style="width:100%">
							        <thead>
							            <tr>
							            	<th>Photo</th>
							            	<th>Details</th>
							                <th>Status</th>
							                <th>Guarantor</th>
							                <th>Loans</th>
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
							        				
							     // 0974848034
							        	?>
							        				<tr>
							        					<td>
							        						<a href="borrowers/see-borrower-details?applicant-id=<?php echo base64_encode($row['id'])?>" class="btn btn-sm btn-outline-primary"><img src="fileuploads/<?php echo $row['borrower_photo']?>" id="output_image2" class=" img-fluid img-circle" alt="pic" style="width: 100px; height: 100px;"></a>
							        					</td>
							        					
							        					<td>
							        						<table class="table table-bordered">
                                                              	<tr>
							        								<th>Client No:</th>
							        								<th><?php echo $row['id']?></th>
							        							</tr>
							        							<tr>
							        								<th>Full Names</th>
							        								<th><?php echo $row['borrower_firstname']?> <?php echo $row['borrower_lastname']?></th>
							        							</tr>
							        							<tr>
							        								<th>Identity Number</th>
							        								<th><?php echo $row['borrower_ID']?></th>
							        							</tr>
							        							<tr>
							        								<th>Address</th>
							        								<td><?php echo $row['borrower_address']?></td>
							        							</tr>
							        							<tr>
							        								<th>Contact #</th>
							        								<td><?php echo $row['borrower_phone']?></td>
							        							</tr>
							        							<tr>
							        								<th>Email</th>
							        								<td><?php echo $em;?></td>
							        							</tr>
							        							<tr>
							        								<th>Work Status</th>
							        								<td><?php echo preg_replace("#[^a-zA-Z]#", " ", ucwords($row['borrower_working_status']))?></td>
							        							</tr>
							        							<tr>
										                  			<th>More Info</th>
										                  			<td><a href="borrowers/see-borrower-details?applicant-id=<?php echo base64_encode($row['id'])?>" target="_blank"><i class="bi bi-arrow-right"></i> View</a></td>
										                  		</tr>
							        						</table>
							        					</td>
							        					<td>--</td>
							        					<td>
							        						<?php
										                		$branch_id 		= $row['branch_id'];
																$applicant_id 	= $row['id'];
										                		$sql = $connect->prepare("SELECT * FROM guarantors WHERE borrower_id = ? AND branch_id = ? AND parent_id = ? ");
										                		$sql->execute(array($applicant_id, $branch_id, $_SESSION['parent_id']));
										                		if($sql->rowCount() > 0):
										                		$rows = $sql->fetch();
										                		extract($rows);

										                	?>
										                	<table class="table table-bordered">
										                  		<tr>
										                    		<th>Full Names</th> <td><?php echo $firstname ?> <?php echo $lastname ?></td>
										                  		</tr>
										                  		<tr>
										                    		<th>Phone </th> <td><?php echo $phone ?></td>
										                  		</tr>
										                  		<tr>
										                    		<th>Address </th> <td><?php echo $address ?></td>
										                  		</tr>
										                  		<tr>
										                    		<th>ID Number</th> <td><?php echo $identity_number ?></td>
										                  		</tr>
										                  		<tr>
										                    		<th>Working Status</th> <td><?php echo $working_status ?></td>
										                  		</tr>
										                  		<tr>
										                  			<th>More Info</th>
										                  			<td><a href="borrowers/guarantor-details?guarantor_id=<?php echo $id?>&borrower_id=<?php echo $applicant_id?>" target="_blank"><i class="bi bi-arrow-right"></i> View</a></td>
										                  		</tr>
										                	</table>
										                	<?php else:?>
										                		<h5 class="text-secondary"><span id="next_of_kin_span">No Guarantor</span></h5>
										                	<?php endif;?>
							        					</td>
							        					<td><?php echo currentLoan($connect, $row['id'])?></td> 
							        					<td>
							        						<!-- Example split danger button -->
							        						<div class="list-group">
								        						<a class="text-dark list-group-item list-group-item-action list-group-item-primary" href="borrowers/see-borrower-details?applicant-id=<?php echo base64_encode($row['id'])?>"><i class="bi bi-printer"></i> View & Print</a>
																<a class="text-dark list-group-item list-group-item-action list-group-item-success" href="borrowers/loan-application?applicant_id=<?php echo base64_encode($row['id'])?>&branch_id=<?php echo base64_encode($row['branch_id'])?>&parent_id=<?php echo base64_encode($row['parent_id'])?>" target="_blank" >Issue New Loan</a>
																<a class="text-dark list-group-item list-group-item-action list-group-item-secondary" id="addCollateral" href="<?php echo $row['id']?>">Add Collateral</a>
																<a class="text-dark list-group-item list-group-item-action list-group-item-info" id="addGuarantor" href="<?php echo $row['id']?>">Add Guarantor</a>
																<hr class="dropdown-divider">
																<a class="text-dark list-group-item list-group-item-action list-group-item-warning" href="borrowers/borrower-details-edit?applicant-id=<?php echo base64_encode($row['id'])?>" target="_blank" ><i class="bi bi-pencil-square"></i> Edit Details</a>
																<a class="text-dark list-group-item list-group-item-action list-group-item-danger removeBorrower" href="#" id="<?php echo $row['borrower_ID']?>" data-id="<?php echo $row['id']?>" data-branch_id="<?php echo $branch_id?>"><i class="bi bi-trash"></i> Remove Details</a>
															</div>
															
							        					</td>
							        				</tr>
							        				<!-- =========ADD GUARANTOR========= -->

													<div class="container">
														<div class="modal fade" id="gurantorModal_<?php echo $row['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
															<div class="modal-dialog modal-lg">
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title">
																			Add Guarantor for <?php echo $row['borrower_firstname'] ?> <?php echo $row['borrower_lastname']?>
																				
																		</h4>
																	</div>
																	<form class="" method="post" id="guarantorForm_<?php echo $row['id']?>" enctype="multipart/form-data">
																		<input type="hidden" name="guarantor_id" id="guarantor_id" value="">
									      								<input type="hidden" name="borrower_id" id="borrower_id" value="<?php echo $row['id']?>">
									      								<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $row['branch_id'] ?>">
									      								<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $row['parent_id']?>">
												  						<div class="modal-body">		
																			<div class="row">
																				<div class="form-group col-12 mb-3">
																					<label for="form">Guarantor Photo</label>
																					<div class=" border p-3">
																						<button class="btn btn-warning mb-3" type="button" id="selectGuarantorImage_<?php echo $row['id']?>">Select Image <i class="bi bi-file-person"></i></button><br>
																						<input type="file" name="photo_image" id="photo_image_<?php echo $row['id']?>" class="form-control"  style="display: none;" onchange="preview_guarantor_image__<?php echo $row['id']?>(event)" accept="image/png, image/jpg, image/jpeg">
																						<img src="dist/img/avatar2.png" id="guarantor_image_<?php echo $row['id']?>" class="shadow-sm img-fluid img-responsive" alt="pic" width="140">
																					</div>
																					<em>Add a clear face</em>
																					
												      							</div>
																				<div class="form-group col-6">
																					<label for="form">Firstname</label>
																					<div class="input-group mb-3">
																						<span class="input-group-text"><i class="bi bi-person"></i></span>
																						<input type="text" aria-label="First name" name="firstname" id="firstname" class="form-control">
																					</div>
																				</div>
																				<div class="form-group col-6">
																					<label for="form">Lastname</label>
																					<div class="input-group mb-3">
																						<span class="input-group-text"><i class="bi bi-person"></i></span>
																						<input type="text" aria-label="Last name" name="lastname" id="lastname" class="form-control">
																					</div>
																				</div>
																				<div class="form-group col-6">
																					<label>Gender</label>
																					<div class="input-group mb-3">
																						<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
																						<select id="gender" name="gender" class="form-control" required>
																							<option value="">Select</option>
																							<option value="Male">Male</option>
																							<option value="Female">Female</option>
																						</select>
																					</div>
																				</div>
																				<div class="form-group col-6">
																					<label for="form">Date of Birth</label>
																					<div class="input-group mb-3">
																						<span class="input-group-text"><i class="bi bi-calendar"></i></span>
																						<input type="date" name="dateofbirth" id="dateofbirth" class="form-control">
																					</div>
																				</div>
																				<div class="form-group col-6">
																					<label for="form">Email</label>
																					<div class="input-group mb-3">
																						<span class="input-group-text"><i class="bi bi-at"></i></span>
																						<input type="email" name="email" id="email" class="form-control">
																					</div>
																				</div>
																				<div class="form-group col-6 mb-3">
																					<label for="form">Phone</label>
																					<div class="input-group  mb-2">
																						<span class="input-group-text"><i class="bi bi-phone"></i></span>
																						<input type="text"  name="phone" id="phone" class="form-control">
																					</div>
																				</div>
																				<div class="form-group col-6 mb-3">
																					<label for="form">NRC or Passport</label>
																					<div class="input-group mb-1">
																						<span class="input-group-text"><i class="bi bi-file-person"></i></span>
																						<input type="text" name="identity_number" id="identity_number" class="form-control" required>
																					</div>
																				</div>
																				<div class="form-group col-6">
																					<label>Working Status</label>
																					<div class="input-group mb-3">
																						<span class="input-group-text"><i class="bi bi-person-plus"></i></span>
																						<select id="working_status" name="working_status" class="form-control">
																							<option value=""></option>
																							<option value="Employee">Employee</option>
																							<option value="Business Person">Business Person</option>
																						</select>
																					</div>
																				</div>
																				
																				<div class="form-group col-6">
																					<label for="form">Country</label>
																					<div class="input-group mb-3">
																						<span class="input-group-text"><i class="bi bi-geo"></i></span>
																						<select name="country" id="country" class="form-control">
																							<option value=""></option>
																							<?php echo $country;?>
																						</select>
																					</div>
																				</div>
																				<div class="form-group col-6">
																					<label for="form">City</label>
																					<div class="input-group mb-3">
																						<span class="input-group-text"><i class="bi bi-geo"></i></span>
																						<input type="text" name="city" id="city" class="form-control">
																					</div>
																				</div>
																				<div class="form-group col-6">
																					<label for="form">Home Address</label>
																					<div class="input-group mb-3">
																						<span class="input-group-text"><i class="bi bi-geo"></i></span>
																						<textarea type="text" name="address" id="address" class="form-control" rows="2"> </textarea>
																					</div>
																				</div>
																				
																				<div class="form-group col-6 mb-3">
																					<label for="form">Guarantor Files</label><br>
																					<div class="">
																						<button class="btn btn-warning" type="button" id="selectGuarantorFiles_<?php echo $row['id']?>">Select Files <i class="bi bi-files"></i></button>
																						<input type="file" name="guarantor_files[]" id="guarantor_files_<?php echo $row['id']?>" class="form-control" style="display: none;" multiple onchange="javascript:updateGuarantorList_<?php echo $row['id']?>()">

																						<div id="guarantor_fileList_<?php echo $row['id']?>"></div>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="modal-footer d-flex justify-content-between">
																			<button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
																			<button class="btn btn-primary"  type="submit" id="addBtn" onclick="addGuarantorClick_<?php echo $row['id']?>(event)">Save Changes</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
													</div>

													<!-- END OF GURANTOR -->
													<!-- =========ADD COLLATERAL========= -->

													<div class="container">
														<div class="row">
															<div class="col-md-12">
																<div class="modal fade" id="collateralModal_<?php echo $row['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
																	<div class="modal-dialog modal-lg">
																		<div class="modal-content">
																			<div class="modal-header">
																				<h4 class="modal-title">Collateral for <?php echo $row['borrower_firstname'] ?> <?php echo $row['borrower_lastname']?></h4>
																			</div>
																			<form class="" method="post" id="collateralForm_<?php echo $row['id']?>" enctype="multipart/form-data">
																				<input type="hidden" name="collateral_id" id="collateral_id">
																				<input type="hidden" name="borrower_id" id="borrower_id" value="<?php echo $row['id']?>">
																				<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $row['branch_id'] ?>">
																				<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $row['parent_id']?>">
																				
																				<div class="modal-body mb-5">
																					<div class="row">
																						<div class="form-group col-md-6">
																							<label for="collateral_type">Type</label>
																							
																							<select class="form-control" name="collateral_type" id="collateral_type">
																								<option value="Automobiles">
																								Automobiles
																								</option>
																								<option value="Electronic Items">
																								Electronic Items
																								</option>
																								<option value="Insurance policies">
																								Insurance policies
																								</option>
																								<option value="Investments">
																								Investments
																								</option>
																								<option value="Machinery and equipment">
																								Machinery and equipment
																								</option>
																								<option value="Real estate">
																								Real estate
																								</option>
																								<option value="Valuables and collectibles">
																								Valuables and collectibles
																								</option>
																							</select>
																							
																						</div>
																						<div class="form-group col-md-6">
																							<label for="product_name">Product name</label>
																							<div class="input-group mb-3">
																								<span class="input-group-text"><i class="bi bi-wallet"></i></span>
																								<input type="text"  name="product_name" id="product_name" class="form-control">
																							</div>
																						</div>
																						
																						<div class="form-group col-md-6">
																							<label for="form">Register Date</label>
																							<div class="input-group mb-3">
																								<span class="input-group-text"><i class="bi bi-calendar"></i></span>
																								<input type="date" name="register_date" id="register_date" class="form-control">
																							</div>
																						</div>
																						
																						<div class="form-group col-md-6">
																							<label for="product_value">Value</label>
																							<div class="input-group mb-3">
																								<span class="input-group-text"><?php echo getCurrency($connect, $_SESSION['parent_id']) ?></span>
																								<input type="number" step="any" name="product_value" id="product_value" class="form-control" placeholder="Amount">
																								<input type="hidden" name="currency" value="<?php echo getCurrency($connect, $_SESSION['parent_id']) ?>">
																							</div>
																						</div>
																						<div class="col-md-12 bg-secondary p-2 mt-5 mb-5 shadow-md">
																							<!-- <div class="border-bottom border-primary mb-3 mt-3"></div> -->
																							<h4 >Collateral Status</h4>
																						</div>
																						<div class="form-group col-md-6">
																							<label for="status">Where is the Collateral ?</label>
																							<select class="form-control" name="product_location" name="product_location">
																								<option value="Deposited into Branch">Deposited into Branch</option>
																								<option value="Collateral with Borrower">Collateral with Borrower</option>
																								<option value="Returned to Borrower">Returned to Borrower</option>
																								<option value="Repossession Initiated">Repossession Initiated</option>
																								<option value="Repossesed">Repossesed</option>
																								<option value="Under Auction">Under Auction</option>
																								<option value="Sold">Sold</option>
																								<option value="Lost">Lost</option>
																							</select>
																						</div>
																						
																						<div class="form-groups col-md-6">
																							<label for="form">When?</label>
																							<div class="input-group mb-3">
																								<span class="input-group-text"><i class="bi bi-calendar"></i></span>
																								<input type="text" name="action_date" id="action_date" class="form-control">
																							</div>
																						</div>
																						<div class="form-group col-md-12">
																							<label>Address</label>
																							<input type="text" name="address" id="address" class="form-control">
																							<em>If collateral is with the borrower, add address where it is located</em>
																						</div>
																						<div class="col-md-12 bg-secondary p-2 mt-5 mb-5 shadow-md">
																							<!-- <div class="border-bottom border-primary mb-3 mt-3"></div> -->
																							<h4> Product Details</h4>
																						</div>
																						<div class="form-group col-md-6">
																							<label>Serial #</label>
																							<input type="text" name="serial_number" id="serial_number" class="form-control">
																						</div>
																						<div class="form-group col-md-6">
																							<label>Model name </label>
																							<input type="text" name="model_name" id="model_name" class="form-control">
																						</div>
																						<div class="form-group col-md-6">
																							<label>Model #</label>
																							<input type="text" name="model_number" id="model_number" class="form-control">
																						</div>
																						<div class="form-group col-md-6">
																							<label>Product Color</label>
																							<input type="text" name="color" id="color" class="form-control">
																						</div>
																						<div class="form-group col-md-6">
																							<label>Manufature Date</label>
																							<input type="text" name="manufature_date" id="manufature_date" class="form-control">
																						</div>
																						<div class="form-group col-md-6">
																							<label>Product condition</label>
																							<select class="form-control" name="product_condition" name="product_condition">
																								<option value=""></option>
																								<option value="Excellent">Excellent</option>
																								<option value="Good">Good</option>
																								<option value="Fair">Fair</option>
																								<option value="Damaged">Damaged</option>
																							</select>
																						</div>
																						<div class="form-group col-md-4">
																							<label>Product Description</label>
																							<textarea class="form-control" rows="4" name="description" id="description"></textarea>
																						</div>
																						
																						<div class="form-group col-md-4 mb-3">
																							<label for="form">Collateral Photo</label>
																							<div class=" border p-3">
																								<button class="btn btn-warning mb-3" type="button" id="selectCollateralImage_<?php echo $row['id']?>">Select Image <i class="bi bi-file-person"></i></button><br>
																								<input type="file" name="photo" id="photo_<?php echo $row['id']?>" class="form-control"  style="display: none;" onchange="preview_image_<?php echo $row['id']?>(event)">
																								<img src="dist/img/avatar2.png" id="collateral_image_<?php echo $row['id']?>" class="shadow-sm img-fluid img-responsive" alt="pic" width="140">
																							</div>
																							<em>Add a clear photo of the product</em>
																						</div>
																						<div class="form-group col-md-4 mb-3">
																							<label for="form">Collateral Files</label>
																							<div class="border p-3">
																								<button class="btn btn-warning" type="button" id="selectFiles_<?php echo $row['id']?>">Select Files <i class="bi bi-files"></i></button>
																								<input type="file" name="files[]" id="collater_files_<?php echo $row['id']?>" class="form-control" style="display: none;" multiple onchange="javascript:updateList<?php echo $row['id']?>()">

																								<div id="fileList_<?php echo $row['id']?>"></div>
																							</div>
																							<em>Add reciepts, and any documents to support ownership</em>
																						</div>
																						<div class="col-md-12 bg-secondary p-2 mt-5 mb-5 shadow-md">
																							<!-- <div class="border-bottom border-primary mb-3 mt-3"></div> -->
																							<h4 >For Vehicles only </h4>
																						</div>
																						<div class="form-group col-md-4">
																							<label>Registration Number</label>
																							<input type="text" name="vehicle_reg_number" id="vehicle_reg_number" class="form-control" placeholder="Reg Number">
																						</div>
																						<div class="form-group col-md-4">
																							<label>Millage</label>
																							<input type="text" name="millage" id="millage" class="form-control" placeholder="Millage">
																						</div>
																						<div class="form-group col-md-4">
																							<label>Engine No.</label>
																							<input type="text" name="vehicle_engine_num" id="vehicle_engine_num" class="form-control" placeholder="Engine No.">
																						</div>

																					</div>
																				</div>
																				<div class="modal-footer d-flex justify-content-between ">
																					<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
																					<button class="btn btn-primary" type="submit" id="cBtn" onclick="addCollateralFuntion_<?php echo $row['id']?>(event)">Submit</button>
																				</div>
																			</form>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<!-- END OF COLLATERAL -->
													<script>
														$(function (){
															$(document).on("click", "#addGuarantor", function(e){
																e.preventDefault();
																var id = $(this).attr("href");
																$("#gurantorModal_"+id).modal("show");
															})

															$(document).on("click", "#addCollateral", function(e){
																e.preventDefault();
																var id = $(this).attr("href");
																$("#collateralModal_"+id).modal("show");
															})
													    })

													   // images ------ 
													   	
														var selectCollateralImage = document.getElementById('selectCollateralImage_<?php echo $row['id']?>');
												  		var fileInput = document.getElementById('photo_<?php echo $row['id']?>');
												  		selectCollateralImage.addEventListener("click", (e) => {
												  			$('#photo_<?php echo $row['id']?>').click();
												  		});

														function preview_image_<?php echo $row['id']?>(event) {
															var reader = new FileReader();
															reader.onload = function(){
																var output = document.getElementById('collateral_image_<?php echo $row['id']?>');
																output.src = reader.result;
															}
															reader.readAsDataURL(event.target.files[0]);
														}

														document.getElementById('selectFiles_<?php echo $row['id']?>').addEventListener("click", (e)=> {
															document.getElementById('collater_files_<?php echo $row['id']?>').click();
														})

													    updateList<?php echo $row['id']?> = function() {
															var input = document.getElementById('collater_files_<?php echo $row['id']?>');
															var output = document.getElementById('fileList_<?php echo $row['id']?>');

															output.innerHTML = '<ul>';
															for (var i = 0; i < input.files.length; ++i) {
															output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
															}
															output.innerHTML += '</ul>';
														}
														addCollateralFuntion_<?php echo $row['id']?> = function() {
															event.preventDefault();
															var xhr = new XMLHttpRequest();
															var url = 'borrowers/submitCollateral?<?php echo time()?>';
															var collateralForm = document.getElementById('collateralForm_<?php echo $row['id']?>');
															var collateral_type = document.getElementById('collateral_type');
															var product_value = document.getElementById('product_value');
															var product_name = document.getElementById('product_name');
															xhr.open("POST", url, true);
															var data = new FormData(collateralForm);
															// if (collateral_type.value === "") {
															// 	errorNow("Collateral type is required");
															// 	collateral_type.focus();
															// 	return false;
															// }
															// if (product_name.value === "") {
															// 	errorNow("Product name is required");
															// 	product_name.focus();
															// 	return false;
															// }
															// if (product_value.value === "") {
															// 	errorNow("Product Value is required");
															// 	product_value.focus();
															// 	return false;
															// }
															xhr.onreadystatechange = function(){
																if (xhr.readyState == 4 && xhr.status == 200) {
																	
																	errorNow(xhr.responseText);
																	$("#collateralForm_<?php echo $row['id']?>")[0].reset();
																	document.getElementById("cBtn").innerHTML = 'Submit';
																}
															}
															xhr.send(data);
															document.getElementById("cBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
														}

														// ============================ GUARANTORS =============================== 

														var selectImage = document.getElementById('selectGuarantorImage_<?php echo $row['id']?>');
												  		var fileInput = document.getElementById('photo_image');
												  		selectImage.addEventListener("click", (e) => {
												  			$('#photo_image_<?php echo $row['id']?>').click();
												  		});

														function preview_guarantor_image__<?php echo $row['id']?>(event) {
															var reader = new FileReader();
															reader.onload = function(){
																var output = document.getElementById('guarantor_image_<?php echo $row['id']?>');
																output.src = reader.result;
															}
															reader.readAsDataURL(event.target.files[0]);
														}

														document.getElementById('selectGuarantorFiles_<?php echo $row['id']?>').addEventListener("click", (e)=> {
															document.getElementById('guarantor_files_<?php echo $row['id']?>').click();
														})

													    updateGuarantorList_<?php echo $row['id']?> = function() {
															var input = document.getElementById('guarantor_files_<?php echo $row['id']?>');
															var output = document.getElementById('guarantor_fileList_<?php echo $row['id']?>');

															output.innerHTML = '<ul>';
															for (var i = 0; i < input.files.length; ++i) {
															output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
															}
															output.innerHTML += '</ul>';
														}
														
														addGuarantorClick_<?php echo $row['id']?> = function() {
															event.preventDefault();
															var xhr = new XMLHttpRequest();
															var url = 'borrowers/submitGuarantor';
															var guarantorForm = document.getElementById('guarantorForm_<?php echo $row['id']?>');
															var firstname = document.getElementById('firstname');
															var identity_number = document.getElementById('identity_number');
															// if (firstname.value === "") {
															// 	errorNow("Firstname is required");
															// 	firstname.focus();
															// 	return false;
															// }

															// if (identity_number.value === "") {
															// 	errorNow("Enter NRC ro ID Number");
															// 	ID.focus();
															// 	return false;
															// }

															xhr.open("POST", url, true);
															var data = new FormData(guarantorForm);
															xhr.onreadystatechange = function(){
																if (xhr.readyState == 4 && xhr.status == 200) {
																	if (xhr.responseText === 'done') {
																		successNow(firstname.value + ' added to the database');
																		
																		setTimeout(function(){
																			location.reload();
																		}, 1000)

																	}else{
																		// alert(xhr.responseText);
																		errorNow(xhr.responseText);
																		// $("#guarantorForm")[0].reset();
																		document.getElementById("addBtn").innerHTML = 'Submit';
																		return false;
																	}
																	
																}
															}
															xhr.send(data);
															document.getElementById("addBtn").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
														}
													</script>
							        	<?php

							        			}
							        		}else{
							        			
							   
							        		}
							        	?>

							     	</tbody>
							    </table>
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
		    $('.select2').select2();
		} );
	
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
		
		
		 $(document).on("click", ".removeBorrower", function(e){
	    	e.preventDefault();
	    	var borrower_id_number = $(this).attr('id');
	    	var branch_id = $(this).data('branch_id');
	    	var delete_id = $(this).data('id');
	    	var loggedParentId = '<?php echo $_SESSION['parent_id']?>';
	    	if(confirm("Confirm removing borrower? All loan information will also be archived")){
		    	$.ajax({
		    		url: 'borrowers/action',
		    		method:'post',
		    		
		    		data: {borrower_id_number:borrower_id_number, branch_id:branch_id, delete_id:delete_id, loggedParentId:loggedParentId},
		    		
		    		success:function(data){
		    			successNow(data);
		    			// $("#tbody").load(location.href + " #tbody");
		    			location.reload();
		    		}
		    	})
		    }else{
		    	return false;
		    }
	    })
	</script>
	
</body>
</html>