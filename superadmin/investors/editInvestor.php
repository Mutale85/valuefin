<?php
	include '../includes/db.php';
	
	extract($_POST);
	if (!empty($investor_id)) {
		$query = $connect->prepare("SELECT * FROM investors WHERE id = ?");
		$query->execute(array($investor_id));
		$row = $query->fetch();
		if ($row) {
			$data = json_encode($row);
		}
		echo $data;
	}
	
	if (isset($_POST['investor_id_delete'])) {

		$d = $connect->prepare("DELETE FROM investors WHERE id = ?");
		if($d->execute(array($_POST['investor_id_delete']))){
			echo "Investor Deleted";
		}
	}
	
?>