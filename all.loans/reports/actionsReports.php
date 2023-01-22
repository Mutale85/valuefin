<?php
	include "../includes/db.php";
	if (isset($_POST['remarks'])) {
		$remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
		$branch_id = preg_replace("#[^0-9]#", "", $_POST['branch_id']);
		$parent_id = preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$borrower_id = preg_replace("#[^0-9]#", "", $_POST['borrower_id']);
		$loan_number =  $_POST['loan_number'];
		$remarks_id = $_POST['remarks_id'];
		$date_added = date("Y-m-d");
		if ($remarks_id !== "") {
			# update
			$up = $connect->prepare("UPDATE reports_issued_loans SET remarks = ? WHERE id = ? AND loan_number = ? ");
			$up->execute(array($remarks, $remarks_id, $loan_number));
			echo 'Remarks Updated';
		}else{
			$date_added = date("Y-m-d");
			$sql = $connect->prepare("INSERT INTO `reports_issued_loans`(`branch_id`, `parent_id`, `loan_number`, `remarks`, `borrower_id`, `user_id`, `date_added`) VALUES(?, ?, ?, ?, ?, ?, ?) ");
			$ex = $sql->execute(array($branch_id, $parent_id, $loan_number, $remarks, $borrower_id, $_SESSION['user_id'], $date_added));
			if ($ex) {
				echo "Remarks recorded";
			}else{
				echo "Error processing";
				exit();
			}
			
		}
	}

	if (isset($_POST['remarksEditId'])) {
		$remarksEditId = $_POST['remarksEditId'];
		$query = $connect->prepare("SELECT * FROM reports_issued_loans WHERE id = ? AND parent_id = ? ");
		$query->execute(array($remarksEditId, $_SESSION['parent_id']));
		$row = $query->fetch();
		if ($row) {
			$data = json_encode($row);
		}
		echo $data;
	}

	if (isset($_POST['deleteEditId'])) {
		$id = $_POST['deleteEditId'];
		$borrower_id = $_POST['borrower_id'];
		// $delete = $connect->prepare("DELETE FROM reports_issued_loans WHERE id = ? AND borrower_id = ? ");
		$delete = $connect->prepare("UPDATE reports_issued_loans SET display = '0' WHERE id = ? AND borrower_id = ? ");
		if($delete->execute(array($id, $borrower_id))){
			echo "Remarks Deleted But Saved in the Archives for Reference";
		}else{
			echo "Failed to Save Data";
		}

	}
?>