<?php
	include '../includes/db.php';

	if (isset($_POST['editor_id'])) {
		$editor_id  = preg_replace("#[^0-9]#", "", $_POST['editor_id']);
		$loggedinID = preg_replace("#[^0-9]#", "", $_POST['loggedinID']);
		$query = $connect->prepare("SELECT * FROM loan_type WHERE id = ? AND parent_id = ? ");
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
		$query = $connect->prepare("DELETE FROM loan_type WHERE id = ? AND parent_id = ? ");
		$ex = $query->execute(array($delete_id, $loggedParentId));
		if($ex){
			echo "done";
		}else{
			echo 'error';
			exit();
		}
	}
	
?>