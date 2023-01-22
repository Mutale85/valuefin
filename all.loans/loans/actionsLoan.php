<?php
	include "../includes/db.php";
	if (isset($_POST['months'])) {
		$months = preg_replace("#[^0-9]#", "", $_POST['months']);
		$loan_type = preg_replace("#[^0-9]#", "", $_POST['loan_type']);
		$interest_percentage = preg_replace("#[^0-9]#", "", $_POST['interest_percentage']);
		$penalty_rate = preg_replace("#[^0-9]#", "", $_POST['penalty_rate']);
		$parent_id 	= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$id 		= preg_replace("#[^0-9]#", "", $_POST['id']);
		$date_added = date("Y-m-d");
		if ($id !== "") {
			# update
			$up = $connect->prepare("UPDATE loan_plans SET loan_type = ?,  months = ?, interest_percentage = ?, penalty_rate = ?  WHERE id = ? AND parent_id = ? ");
			$up->execute(array($loan_type, $months, $interest_percentage, $penalty_rate, $id, $parent_id));
			echo 'Updated';
		}else{
			$query = $connect->prepare("SELECT * FROM loan_plans WHERE loan_type = ? AND months = ? AND interest_percentage = ? AND parent_id = ?");
			$query->execute(array($loan_type, $months, $interest_percentage, $parent_id));
			if ($query->rowCount() > 0) {
				echo "You are trying to add the same data again";
				exit();
			}else{
				$in = $connect->prepare("INSERT INTO `loan_plans`(loan_type, `months`, `interest_percentage`, `penalty_rate`, `parent_id`) VALUES(?, ?, ?, ?, ?) ");
				$ex = $in->execute(array($loan_type, $months, $interest_percentage, $penalty_rate, $parent_id));
				if ($ex) {
					echo "done";
				}else{
					echo "Error processing the form";
					exit();
				}
			}
		}
	}

	if (isset($_POST['editor_id'])) {
		$editor_id  = preg_replace("#[^0-9]#", "", $_POST['editor_id']);
		$loggedinID = preg_replace("#[^0-9]#", "", $_POST['loggedinID']);
		$query = $connect->prepare("SELECT * FROM loan_plans WHERE id = ? AND parent_id = ? ");
		$query->execute(array($editor_id, $loggedinID));
		$row = $query->fetch();
		if ($row) {
			$data = json_encode($row);
		}
		echo $data;
	}

	if (isset($_POST['delete_id'])) {
		$delete_id  = preg_replace("#[^0-9]#", "", $_POST['delete_id']);
		$loggedParentId = preg_replace("#[^0-9]#", "", $_POST['loggedParentId']);
		$query = $connect->prepare("DELETE FROM loan_plans WHERE id = ? AND parent_id = ? ");
		$ex = $query->execute(array($delete_id, $loggedParentId));
		if($ex){
			echo "done";
		}else{
			echo 'error';
			exit();
		}
	}
	

	//======================= insert loan fees ==============================
	if (isset($_POST['fees_name'])) {
		$fees_name = filter_var($_POST['fees_name'], FILTER_SANITIZE_STRING);
		$parent_id = preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$branch_id = preg_replace("#[^0-9]#", "", $_POST['branch_id']);
		$fee_choice = filter_var(trim($_POST['fee_choice']), FILTER_SANITIZE_STRING);
		$loan_fees = filter_var($_POST['loan_fees'], FILTER_SANITIZE_STRING);
		$symbol = $_POST['symbol'];
		if ($fee_choice == 'Percentage') {
			$choice = 'percentage_based';
		}elseif ($fee_choice == 'Amount') {
			$choice = 'amount_based';
		}
		$sql = $connect->prepare("INSERT INTO `loan_fees`(`choice`, `loan_fees_name`, `loan_fees`, `symbol`, `parent_id`, `branch_id`) VALUES(?, ?, ?, ?, ?, ?)");
		$ex = $sql->execute(array($choice, $fees_name, $loan_fees, $symbol, $parent_id, $branch_id));
		if($ex){
			echo 'done';
		}
		
	}

	// ======================== UPDATE THE LOAN STATUS ========================================
	if (isset($_POST['loan_status'])) {
		
		extract($_POST);

		$release_date = date("Y-m-d");
		$month = date("F");
		$date_disbursed = date("Y-m-d");
		$sql  = $connect->prepare("UPDATE loans_table SET loan_status = ?, release_date = ?, actioned_by = ? WHERE borrower_id = ? AND branch_id = ? AND parent_id = ? AND loan_number = ? ");
		$q = $sql->execute(array($loan_status, $release_date, $_SESSION['user_id'], $borrower_id, $branch_id, $parent_id, $loan_number));
		if($loan_status == 'Released'){
			$sql = $connect->prepare("INSERT INTO `disbursed_funds`(`branch_id`, `parent_id`, `user_id`, `borrower_id`, `currency`, `amount`, `month`, `date_disbursed`) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
			$sql->execute(array($branch_id, $parent_id, $_SESSION['user_id'], $borrower_id, $currency, $amount, $month, $date_disbursed));
		}else{

		}
		if ($q) {
			echo "Loan Actioned Successfully";
		}
	}


	if (isset($_POST['all_branches'])) {
		$parent_id = $_POST['loanTypesParentId'];
		$parent_id = preg_replace("#[^0-9]#", "", $_SESSION['parent_id']);
		$query = $connect->prepare("SELECT * FROM loan_type WHERE parent_id = ?");
		$query->execute(array( $parent_id));
		$numRows = $query->rowCount();
		$i = 1;
		if ($numRows > 0 ) {
			$i = 1;
		?>
			<div class="table table-responsive">
				<table id="loanTypes" class="text-dark cell-border" style="width:100%">
			        <thead>
			            <tr>
			            	<th>#</th>
			                <th>Loan Type</th>
			                <th>Interest Rate</th>
			                <th>Period</th>
			                <th>Actions</th>
			            </tr>
			        </thead>
		        	<tbody>
		        	<?php
						foreach ($query->fetchAll() as $row) {?>
							<tr>
								<td><?php echo $i++?></td>
								<td><b><?php echo $row['type_name']?></b></td>
								<td><?php echo  $row['interest_rate']?> %</td>
								<td>
									<?php echo ucfirst($row['period'])?>
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
		    	<script>
		    		$(document).ready( function () {
					    $('#loanTypes').DataTable();
					});
		    	</script>
			</div>
<?php	}?>