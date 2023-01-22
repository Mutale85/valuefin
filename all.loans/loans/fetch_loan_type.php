<?php
	include '../includes/db.php';
	if (isset($_SESSION['parent_id'])) {
		$parent_id = preg_replace("#[^0-9]#", "", $_SESSION['parent_id']);
		$query = $connect->prepare("SELECT * FROM loan_type WHERE parent_id = ?");
		$query->execute(array( $parent_id));
		$numRows = $query->rowCount();
		if ($numRows > 0 ) {
			$loanData = array();
			$i = 1;
			foreach ($query->fetchAll() as $row) {
				$loanRow = array();
				$loanRow[] = echo $i++;
				$loanRow[] = '<p><strong>'. $row['type_name']. '</strong></p><p>'.ucfirst($row['description']).'</p>';
				$loanRow[] = '<a href="" class="editLoanType text-primary" data-id="'.$row['id'].'"><i class="bi bi-pencil-square"></i></a>
							<a href="" class="deleteLoanType text-danger" data-id="'.$row['id'].'"><i class="bi bi-trash"></i></a> ';
				$adminData[] = $loanRow;
			}
			$output = array(
			"draw"				=>	intval($_SESSION['parent_id']),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$adminData
			);
			echo json_encode($output);
		}
	}
?>