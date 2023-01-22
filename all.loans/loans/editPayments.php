<?php
	include '../includes/db.php';
	if (isset($_POST['payment_id'])) {
		$payment_id = preg_replace("#[^0-9]#", "", $_POST['payment_id']);
		$output = "";
		$query = $connect->prepare("SELECT * FROM loan_payments WHERE id = ? ");
		$query->execute(array($payment_id));
		$row = $query->fetch();
		if ($row) {
			$output = json_encode($row);
		}
		echo $output;	
	}
?>