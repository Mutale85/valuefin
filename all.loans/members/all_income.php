<?php 
  	require ("../../includes/db.php");
  	require ("../../includes/tip.php"); 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Income and Expenses</title>
	<?php include("../links.php") ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css">
	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php include ("../nav_side.php"); ?>
		<div class="content-wrapper">
			<section class="content mt-5">
      			<div class="container-fluid mt-5 mb-5">
      				<div class="row mt-5">
      					<div class="col-md-12 mt-4 pb-2 d-flex justify-content-between border-bottom pb-3">
  							<h4> ALL BRANCHES </h4>
  						</div>
      				</div>
      			</div>
      			<!-- Start of Income Table -->
      			<div class="container-fluid">
      				<div class="row">
      					<div class="col-md-12">
      						<div class="card card-secondary">
      							<div class="card-header">
      								<h4 class="card-title">Income Table </h4>
      								<div class="card-tools">
				                  		<button type="button" class="btn btn-tool" data-card-widget="collapse">
				                    		<i class="fas fa-minus"></i>
				                  		</button>
				                  		<button type="button" class="btn btn-tool" data-card-widget="remove">
				                    		<i class="fas fa-times"></i>
				                  		</button>
				                	</div>
      							</div>
      							<div class="card-body">
		      						<div class="table table-responsive">
		      							<table class="cell-table table-sm" id="incomeTable" style="width: 100%">
		      								<thead>
		      									<tr>
		      										<th>#</th>
		      										<th>Expense Name</th>
		      										<th>Amount</th>
		      										<th>Date</th>
		      										<th>Receipt or Invoice</th>
		      										<th>Loan Number:</th>
		      										<th>Edit</th>
		      										<th>Remove</th>
		      									</tr>
		      								</thead>
		      								<tbody class="text-dark">
		      									<?php
		      										$number = 1;
		      										$query = $connect->prepare("SELECT * FROM income_table WHERE  parent_id = ? ORDER BY income_date ");
		      										$query->execute(array( $_SESSION['parent_id']));
		      										if ($query->rowCount() > 0) {
		      											foreach ($query->fetchAll() as $row) {
		      												extract($row);
		      												if ($receipt_no_1 == "" AND $receipt_no_2 == "") {
		      													$receipt = "N/A"; 
		      												}elseif ($receipt_no_1 == "" AND $receipt_no_2 != "") {
		      													$receipt = '
		      															<a href="expenses/files/'.$receipt_no_2.'">'.$receipt_no_2.'</a> <i class="bi bi-trash deleteIncomeFile text-danger" id="'.$id.'" data-row="receipt_no_2"></i>;
		      														';
		      												}elseif ($receipt_no_1 != "" AND $receipt_no_2 == "") {
		      													$receipt = '
		      															<a href="expenses/files/'.$receipt_no_1.'">'.$receipt_no_1.'</a> <i class="bi bi-trash deleteIncomeFile text-danger" id="'.$id.'" data-row="receipt_no_1"></i>
		      														';
		      												}elseif ($receipt_no_1 != "" AND $receipt_no_2 != "") {
		      													$receipt = '
		      															<a href="expenses/files/'.$receipt_no_1.'">'.$receipt_no_1.'</a> <i class="bi bi-trash deleteIncomeFile text-danger" id="'.$id.'" data-row="receipt_no_1"></i><br>
		      															<a href="expenses/files/'.$receipt_no_2.'">'.$receipt_no_2.'</a> <i class="bi bi-trash deleteIncomeFile text-danger" id="'.$id.'" data-row="receipt_no_2"></i>;
		      														';
		      												}
		      												if ($income_loan_linked_to == "") {
		      													$lnumber = "N/A ";
		      												}else{
		      													$lnumber = $income_loan_linked_to;
		      												}

		      									?>
		      										<tr>
		      											<td><?php echo $number++ ?></td>
		      											<td><?php echo $income_name ?></td>
		      											<td><small><?php echo $currency ?></small> <?php echo number_format($income_amount,2) ?></td>
		      											<td><?php echo $income_date ?></td>
		      											<td><?php echo $receipt ?></td>
		      											<td><?php echo $lnumber ?></td>
		      											<td><a href="" data-id="<?php echo $id?>" class="editIncome"><i class="bi bi-pencil-square"></i></a> </td>
		      											<td>
		      												<a href="" data-id="<?php echo $id?>" class="deleteIncome"><i class="bi bi-trash text-danger"></i></a>
		      											</td>
		      										</tr>
		      									<?php			
		      											}
		      										}else{

		      										}

		      									?>
		      								</tbody>
		      								<?php
		      								$query = $connect->prepare("SELECT *, SUM(income_amount) AS total_incomes FROM income_table WHERE  parent_id = ?  ");
		      								$query->execute(array( $_SESSION['parent_id']));
		      								$rows = $query->fetch();
		      								if ($rows) {
		      									$total_incomes = $rows['total_incomes'];
		      									$currency = $rows['currency'];	
		      								}
		      								?>
		      								<tfoot>
		      									<tr>
		      										<th></th>
		      										<th>Total Income</th>
		      										<th><small><?php echo $currency?></small> <?php echo number_format($total_incomes,2)?></th>
		      										<th></th>
		      										<th></th>
		      										<th></th>
		      										<th></th>
		      										<th></th>
		      									</tr>
		      								</tfoot>
		      							</table>
		      						</div>
		      					</div>
		      				</div>
      					</div>
      				</div>
      			</div>
      			<!-- Start of Expenses Table -->
      			<div class="container-fluid">
      				<div class="row">
      					<div class="col-md-12">
      						<div class="card card-warning">
      							<div class="card-header">
      								<h4 class="card-title">Expenses Table</h4>
      								<div class="card-tools">
				                  		<button type="button" class="btn btn-tool" data-card-widget="collapse">
				                    		<i class="fas fa-minus"></i>
				                  		</button>
				                  		<button type="button" class="btn btn-tool" data-card-widget="remove">
				                    		<i class="fas fa-times"></i>
				                  		</button>
				                	</div>
      							</div>
      							<div class="card-body">
		      						<div class="table table-responsive">
		      							<table class="cell-table table-sm" id="expensesTable" style="width: 100%">
		      								<thead>
		      									<tr>
		      										<th>#</th>
		      										<th>Expense Name</th>
		      										<th>Amount</th>
		      										<th>Date</th>
		      										<th>Receipt or Invoice</th>
		      										<th>Loan Number:</th>
		      										<th>Edit</th>
		      										<th>Remove</th>
		      									</tr>
		      								</thead>
		      								<tbody class="text-dark">
		      									<?php
		      										$number = 1;
		      										$query = $connect->prepare("SELECT * FROM expenses WHERE  parent_id = ? ORDER BY expense_date ");
		      										$query->execute(array( $_SESSION['parent_id']));
		      										if ($query->rowCount() > 0) {
		      											foreach ($query->fetchAll() as $row) {
		      												extract($row);
		      												if ($receipt_no_1 == "" AND $receipt_no_2 == "") {
		      													$receipt = "N/A"; 
		      												}elseif ($receipt_no_1 == "" AND $receipt_no_2 != "") {
		      													$receipt = '
		      															<a href="expenses/files/'.$receipt_no_2.'">'.$receipt_no_2.'</a> <i class="bi bi-trash deleteFile text-danger" id="'.$id.'" data-row="receipt_no_2"></i>;
		      														';
		      												}elseif ($receipt_no_1 != "" AND $receipt_no_2 == "") {
		      													$receipt = '
		      															<a href="expenses/files/'.$receipt_no_1.'">'.$receipt_no_1.'</a> <i class="bi bi-trash deleteFile text-danger" id="'.$id.'" data-row="receipt_no_1"></i>
		      														';
		      												}elseif ($receipt_no_1 != "" AND $receipt_no_2 != "") {
		      													$receipt = '
		      															<a href="expenses/files/'.$receipt_no_1.'">'.$receipt_no_1.'</a> <i class="bi bi-trash deleteFile text-danger" id="'.$id.'" data-row="receipt_no_1"></i><br>
		      															<a href="expenses/files/'.$receipt_no_2.'">'.$receipt_no_2.'</a> <i class="bi bi-trash deleteFile text-danger" id="'.$id.'" data-row="receipt_no_2"></i>;
		      														';
		      												}
		      												if ($expense_loan_linked_to == "") {
		      													$lnumber = "N/A ";
		      												}else{
		      													$lnumber = $expense_loan_linked_to;
		      												}

		      									?>
		      										<tr>
		      											<td><?php echo $number++ ?></td>
		      											<td><?php echo $expense_name ?></td>
		      											<td><small><?php echo $currency ?></small> <?php echo number_format($expense_amount, 2) ?></td>
		      											<td><?php echo $expense_date ?></td>
		      											<td><?php echo $receipt ?></td>
		      											<td><?php echo $lnumber ?></td>
		      											<td>
		      												<a href="" data-id="<?php echo $id?>" class="editExpense"><i class="bi bi-pencil-square"></i></a> 
		      											</td>
		      											<td>
		      												<a href="" data-id="<?php echo $id?>" class="deleteExpense"><i class="bi bi-trash text-danger"></i></a> 
		      											</td>
		      										</tr>
		      									<?php			
		      											}
		      										}else{

		      										}

		      									?>
		      								</tbody>
		      								<?php
		      								$query = $connect->prepare("SELECT *, SUM(expense_amount) AS total_expenses FROM expenses WHERE  parent_id = ? ORDER BY expense_date ");
		      								$query->execute(array( $_SESSION['parent_id']));
		      								$rows = $query->fetch();
		      								if ($rows) {
		      									$total_expenses = $rows['total_expenses'];
		      									$currency = $rows['currency'];	
		      								}
		      								?>
		      								<tfoot>
		      									<tr>
		      										<th></th>
		      										<th>Total Expenses</th>
		      										<th><small><?php echo $currency?></small> <?php echo number_format($total_expenses, 2)?></th>
		      										<th></th>
		      										<th></th>
		      										<th></th>
		      										<th></th>
		      										<th></th>
		      									</tr>
		      								</tfoot>
		      							</table>
		      						</div>
		      					</div>
		      				</div>
      					</div>
      				</div>
      			</div>
      			<!-- End of Expenses Table -->
      			
      			<div class="container-fluid">
      				<div class="row">
      					<div class="col-md-6 mt-5 mb-5">
      						<!-- BAR CHART -->
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Total Loans Issued</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                	<div class="table table-responsive">
                                		<table id="loansTable" class="cell-table table-sm" style="width:100%">
      										<thead>
      											<th>Loan#</th>
      											<th>Borrower</th>
      											<th>Apply Date</th>
      											<th>Principal</th>
      										</thead>
      										<tbody class="text-dark">
			      								<?php
			      									// 
			      									
			      									$query = $connect->prepare("SELECT *,  SUM(principle_amount) AS Issued FROM `loans` WHERE  parent_id = ? AND loan_status = 'Released' ");
													$query->execute(array(  $_SESSION['parent_id']));

													if ($query->rowCount() > 0) {

														foreach ($query->fetchAll() as $row) {
															extract($row);

															$sql = $connect->prepare("SELECT * FROM `loanStatus` WHERE loan_id = ? AND branch_id = ? AND parent_id = ? ");
															$sql->execute(array($id, $branch_id, $parent_id));
															if ($sql->rowCount() > 0) {
																$rows = $sql->fetch();
																if ($rows) {
																	extract($rows);
																	$action_date = $action_date;
																}
															}else{
																$action_date = '<span class="text-warning">Pending</span>';
															}

														?>

														<tr>

															<td><a href="loans/view_loan_details?loan_number=<?php echo $loan_number?>&borrower_id=<?php echo $borrower_id?>" class="text-primary"><?php echo $loan_number ?></a></td>
															<td>
																<a href="loans/view_loan_details?loan_number=<?php echo $loan_number?>&borrower_id=<?php echo $borrower_id?>" class="text-primary">
																	<?php

																		if(!getBorrowerFullNamesByCardId($connect, $borrower_id)){
																			echo getBorrowerGroupNamesByCardId($connect, $loan_number) .' Group';
																		}else{
																			echo getBorrowerFullNamesByCardId($connect, $borrower_id);
																		}
																	?>
																</a>
															</td>
															<td class="text-primary"><?php echo date("Y-m-d", strtotime($date_added)) ?></td>
															<td><small><?php echo $currency ?></small> <?php echo number_format($principle_amount, 2)?></td>
														</tr>

													<?php
														}
													}
			      								?>
			      							</tbody>
      									
		                                	<div class="mt-5">
			                                    <?php

			                                    	$sql = $connect->prepare("SELECT *,  SUM(principle_amount) AS Issued FROM `loans` WHERE  parent_id = ? AND loan_status = 'Released' ");
			                    					$sql->execute(array($_SESSION['parent_id']));
			                    					$row = $sql->fetch();
			                    					if ($row) {
			                    						extract($row);
			                    						echo '<tr class="text-dark"><th>Total Funds Issued Out</th><th></th><th></th> <th>'. $currency.' '. $Issued.'</th></tr>';
			                    					}
			                                    ?>
			                                </div>
			                            </table>
                                	</div>
                                </div>
                            </div>
      					</div>
      					<div class="col-md-6 mt-5 mb-5">
      					<!-- LINE CHART -->
				            <div class="card card-info">
				              	<div class="card-header">
				                	<h3 class="card-title">Total Loan Collections</h3>

				                	<div class="card-tools">
				                  		<button type="button" class="btn btn-tool" data-card-widget="collapse">
				                    		<i class="fas fa-minus"></i>
				                  		</button>
				                  		<button type="button" class="btn btn-tool" data-card-widget="remove">
				                    		<i class="fas fa-times"></i>
				                  		</button>
				                	</div>
				              	</div>
				              	<div class="card-body">
				              		<div class="table table-responsive">
	                     				<table class="cell-table table table-sm" id="paymentsTable" width="100%">
	                     					<thead>
	                     						<th>Collection Date</th>
	                     						<th>Collected By</th>
	                     						<th>Payment Method</th>
	                     						<th>Amount</th>
	                     						<th>View Receipt</th>
	                     					</thead>
	                     					<tbody class="text-dark">
			                     			<?php
			                     				$sql = $connect->prepare("SELECT * FROM `loan_payments` WHERE parent_id = ? ");
			                     				$sql->execute(array($_SESSION['parent_id']));
			                     				if ($sql->rowCount() > 0) {

			                     					foreach ($sql->fetchAll() as $rows) {
			                     						extract($rows);
			                     						?>
			                     						<tr>
				                     						<td><?php echo $paid_date ?></td>
				                     						<td><?php echo getStaffMemberNames($connect, $collected_by, $parent_id)?></td>
				                     						<td><?php echo $payment_method ?></td>
				                     						<td><small><?php echo $currency ?></small> <?php echo number_format($amount, 2)?></td>
				                     						<td>
				                     							<a href="loans/payment_receitp?loan_number=<?php echo $loan_number?>&branch_id=<?php echo $BRANCHID?>&parent_id=<?php echo $parent_id?>&borrower_id=<?php echo $borrower_id?>" class="pdf"> <i class="bi bi-printer"></i> View</a>
				                     						</td>
				                     					</tr>
			                     					<?php
			                     					}	
				                     			}else{?>
				                     				
				                     		<?php
				                     			}
			                     			?>
			                     			</tbody>
	                     					<div class="text-warning mt-5">
	                     						<?php
			                                    	$sql = $connect->prepare("SELECT *,  SUM(amount) AS collected  FROM `loan_payments` WHERE  parent_id = ?  ");
			                    					$sql->execute(array($_SESSION['parent_id']));
			                    					$row = $sql->fetch();
			                    					if ($row) {
			                    						extract($row);
			                    						echo '<tr class="text-dark"><th>Total Funds Collected</th><th></th><th></th> <th>'. $currency.' '. $collected.'</th><th></th></tr>';
			                    					}
			                                    ?>
	                     					</div>
	                     				</table>
	                     			</div>

				                	
				              	</div>
				            </div>
				        </div>
      				</div>
      			</div>
      			<!-- Modal Form -->
      			<div class="modal fade" id="modalExpenses">
					<div class="modal-dialog modal-lg">
						<div class="modal-content bg-warning">
							<form class="" method="post" id="income_expenseForm" enctype="multipart/form-data">
								<div class="modal-header">
									<h4 class="modal-title">INCOME / EXPENSES FORM</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="form-group col-md-12">
											<label>Select Income or Expense</label>
											<select class="form-control" name="income_or_expense" id="income_or_expense" onchange="check(this.value)" required>
												<option value=""></option>
												<option value="Income">Income</option>
												<option value="Expense">Expense</option>
											</select>
										</div>
										<script>
											function check(args) {
												if (args == "") {
													document.getElementById('labelname').innerHTML = "";
												}else{
													document.getElementById('labelname').innerHTML = args;
												}
											}
										</script>
										<div class="form-group col-md-6">
											<label>Date</label>
											<div class="input-group">
												<span class="input-group-text"><i class="bi bi-calendar"></i></span>
												<input type="text" name="date" id="date" class="form-control" autocomplete="off">
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for=""><span id="labelname"></span> Name</label>
											<div class="input-group">
												<span class="input-group-text"><i class="bi bi-wallet"></i></span>
												<input type="text" name="name" id="name" class="form-control">
											</div>
										</div>
										<div class="form-group col-md-6">
											<label>Amount</label>
											<div class="input-group">
												<span class="input-group-text" id="currency_"><i class="bi bi-wallet"></i></span>
												<input type="number" name="amount" id="amount" step="any" class="form-control">
											</div>
											<input type="hidden" name="currency" id="currency">
										</div>
										<div class="form-group col-md-6">
											<label>Invoice/Receipt #1</label>
											<div class="input-group">
												<span class="input-group-text"><i class="bi bi-file-pdf"></i></span>
												<input type="file" name="receipt_no_1" id="receipt_no_1" class="form-control">
												<input type="hidden" name="receipt_no_1_edit" id="receipt_no_1_edit" class="form-control">
											</div>
										</div>
										<div class="form-group col-md-6">
											<label>Invoice/Receipt #2</label>
											<div class="input-group">
												<span class="input-group-text"><i class="bi bi-file-pdf"></i></span>
												<input type="file" name="receipt_no_2" id="receipt_no_2" class="form-control">
												<input type="hidden" name="receipt_no_2_edit" id="receipt_no_2_edit" class="form-control">
											</div>
										</div>
										<div class="form-group col-md-6">
											<?php 
												$sql = $connect->prepare("SELECT * FROM `loans` WHERE  parent_id = ? ");
												$sql->execute(array( $_SESSION['parent_id']));

											?>
											<label>Loan name and ID.</label>
											
											<select class="form-control" name="loan_linked_to" id="loan_linked_to">
												<option value=""></option>
												<?php
													if ($sql->rowCount() > 0) {
														foreach ($sql->fetchAll() as $row) {
															extract($row);
															?>
														<option value="<?php echo $loan_number?>"><?php echo getBorrowerFullNamesByCardId($connect, $borrower_id) ?>  ( Loan ID: <?php echo $loan_number?>) </option>
												<?php			
														}
													}else{
														echo '<option>No Loan Found in the Branch</option>';
													}
												?>
											</select>
											
											<em><b>IF EXPENSE IS LINKED TO A LOAN, SELECT THE LOAN, ELSE SKIP.</b></em>
										</div>
									</div>
								</div>
								<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID?>">
								<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
								<input type="hidden" name="ID" id="ID">
								<div class="modal-footer justify-content-between">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button class="btn btn-secondary w-50 " type="submit" id="expenseBtn">Submit</button>
								</div>
							</form>
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
	<script src="plugins/chart.js/Chart.min.js"></script>
	<script>
		$("#date").datepicker({

			format: 'yyyy-mm-dd',
			autoclose:true,
			startDate: '-3d',
			defaultViewDate: 'today',
		});
		$('.select2').select2();
		 $("#expensesTable").DataTable();
		 $("#incomeTable").DataTable();
		 $('#loansTable, #paymentsTable').DataTable();

		//========== GET CURRENCY
		document.addEventListener('DOMContentLoaded', function () {
		 	var currency_ = document.getElementById('currency_');
		 	var currency = document.getElementById('currency');
		 	if (localStorage['currency']) { 
		     	currency_.innerHTML = localStorage['currency'];
		     	currency.value = localStorage['currency'];
		 	}
		});

		// ============================== ADD ADMINS ==========================================

		$(function(){
			$("#income_expenseForm").submit(function(e){
				e.preventDefault();
				// alert("Hello");
				var name = document.getElementById('name');
				var amount = document.getElementById('amount');
				var income_expenseForm = document.getElementById('income_expenseForm');
				var income_or_expense = document.getElementById('income_or_expense').value;
				var data = new FormData(income_expenseForm);
				var url = 'expenses/submitExpense';
				$.ajax({
					url:url+'?<?php echo time()?>',
					method:"post",
					data:data,
					cache : false,
					processData: false,
					contentType: false,
					beforeSend:function(){
						$("#expenseBtn").html("<i class='fa fa-spinner fa-spin'></i>");
					},
					success:function(data){
						if (data === 'done') {
							successNow(income_or_expense +" "+ name.value +' '+ amount.value + ' Added');
							setTimeout(function(){
								location.reload();
							}, 2000);
							$("#expenseBtn").html("Submit");
						}else if(data === 'update'){
							successNow(income_or_expense +" "+ name.value +' '+ amount.value + ' updated');
							setTimeout(function(){
								location.reload();
							}, 2000);
						}else{
							errorNow(data);
							$("#expenseBtn").html("Submit");
							return false;
						}
					}
				})
			})
	

		// ============= EDIT AND DELETE EXPENSE 
			$(document).on("click",".editExpense", function(e){
				e.preventDefault();
				$("#modalExpenses").modal("show");
				var expense_id = $(this).data("id");
				// alert(id);
				$.ajax({
					url:"expenses/editExpense",
					method:"post",
					data:{expense_id:expense_id},
					dataType:"JSON",
					success:function(data){
						$('#ID').val(data.id);
						$('#name').val(data.expense_name);
						$('#amount').val(data.expense_amount);
						$('#loan_linked_to').val(data.expense_loan_linked_to);
						$('#date').val(data.expense_date);
						$('#receipt_no_1_edit').val(data.receipt_no_1);
						$('#receipt_no_2_edit').val(data.receipt_no_2);
						$("#income_or_expense").val("Expense");
					}
				})
			})
		//=========== income ======== editIncome

			$(document).on("click",".editIncome", function(e){
				e.preventDefault();
				$("#modalExpenses").modal("show");
				var income_id = $(this).data("id");
				// alert(id);
				$.ajax({
					url:"expenses/editExpense",
					method:"post",
					data:{income_id:income_id},
					dataType:"JSON",
					success:function(data){
						$('#ID').val(data.id);
						$('#name').val(data.income_name);
						$('#amount').val(data.income_amount);
						$('#loan_linked_to').val(data.income_loan_linked_to);
						$('#date').val(data.income_date);
						$('#receipt_no_1_edit').val(data.receipt_no_1);
						$('#receipt_no_2_edit').val(data.receipt_no_2);
						$("#income_or_expense").val("Income");
					}
				})
			})

		//-===== delete file===========

			$(document).on("click", ".deleteFile", function(){
				var fileID = $(this).attr("id");
				var editableRow = $(this).data("row");
				if(confirm("Deleted files cannot be restored, Confirm delete")){
					$.ajax({
						url:"expenses/editExpense",
						method:"post",
						data:{fileID:fileID, editableRow:editableRow},
						success:function(data){
							successNow(data);
							setTimeout(function(){
								location.reload();
							}, 2000);
						}
					})
				}else{
					return false;
				}
			})

			$(document).on("click", ".deleteIncomeFile", function(){
				var incomefileID = $(this).attr("id");
				var editIncometableRow = $(this).data("row");
				if(confirm("Deleted files cannot be restored, Confirm delete")){
					$.ajax({
						url:"expenses/editExpense",
						method:"post",
						data:{incomefileID:incomefileID, editIncometableRow:editIncometableRow},
						success:function(data){
							successNow(data);
							setTimeout(function(){
								location.reload();
							}, 2000);
						}
					})
				}else{
					return false;
				}
			})
		
		//========== DELETE INCOME =====================

			$(document).on("click",".deleteIncome", function(e){
				e.preventDefault();
				var income_id_delete = $(this).data("id");
				// alert(investor_id_delete);
				if (!confirm("You wish to remove this Income? It cannot be undone")) {
					return false;
				}else{
					$.ajax({
						url:"expenses/editExpense",
						method:"post",
						data:{income_id_delete:income_id_delete},
						success:function(data){
							errorNow(data);
							setTimeout(function(){
								location.reload();
							}, 2000);
							
						}
					})
				}
			})

			//============ DELETE EXPENSE ========= deleteExpense
			$(document).on("click",".deleteExpense", function(e){
				e.preventDefault();
				var expense_id_delete = $(this).data("id");
				// alert(investor_id_delete);
				if (!confirm("You wish to remove this expense? It cannot be undone")) {
					return false;
				}else{
					$.ajax({
						url:"expenses/editExpense",
						method:"post",
						data:{expense_id_delete:expense_id_delete},
						success:function(data){
							errorNow(data);
							setTimeout(function(){
								location.reload();
							}, 2000);
							
						}
					})
				}
			})
		})

	// ================================= DISPLAYS ======================================
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

	</script>
</body>
</html>
