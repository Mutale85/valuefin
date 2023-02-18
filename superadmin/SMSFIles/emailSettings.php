<?php
	include '../includes/db.php';

	if (isset($_POST['serverId'])) {
		$sql = $connect->prepare("SELECT * FROM emailSettingForm WHERE id = ? AND parent_id = ? ");
		$sql->execute(array($_POST['serverId'], $_SESSION['parent_id']));
		foreach($sql->fetchAll() as $row){
			echo json_decode($row);
		}
	}


	extract($_POST);
	if (!empty($ID)) {
		$update = $connect->prepare("UPDATE `emailSettingForm` SET `sender_name` = ?, `smtp_server` = ?, `smtp_port` = ?, `sender_email` = ?, `sender_password` = ? WHERE id = ? AND parent_id = ? ");
		$ex = $update->execute(array($sender_name, $smtp_server, $smtp_port, $sender_email, $sender_password, $ID, $parent_id));
		if ($ex) {
			echo "Email Server Updated";
		}	
	}elseif (empty($ID)) {
		$sql = $connect->prepare("INSERT INTO `emailSettingForm`(`sender_name`, `smtp_server`, `smtp_port`, `sender_email`, `sender_password`, `parent_id`, `branch_id`) VALUES (?, ?, ?, ?, ?, ?, ?) ");
		$ex = $sql->execute(array($sender_name, $smtp_server, $smtp_port, $sender_email, $sender_password, $parent_id, $branch_id));
		if ($ex) {
			echo "Email Server Set Successfully";
		}
	}

?>