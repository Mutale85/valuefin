<?php 
	include("../includes/db.php");
	if (isset($_POST['delete_id'])) {
		$delete_id  = preg_replace("#[^0-9]#", "", $_POST['delete_id']);
		$loggedParentId = preg_replace("#[^0-9]#", "", $_POST['loggedParentId']);
		$query = $connect->prepare("DELETE FROM borrowers WHERE id = ? AND parent_id = ? ");
		$ex = $query->execute(array($delete_id, $loggedParentId));
		if($ex){
			echo "done";
		}else{
			echo 'error';
			exit();
		}
	}
?>