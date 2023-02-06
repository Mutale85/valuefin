<?php
	include '../includes/db.php';
	$parent_id = $_SESSION['parent_id'];
	extract($_POST);
	$prefix = preg_replace("#[^0-9]#", "", $prefix);
	if (!empty($ID)) {
		# update
		$sql = $connect->prepare("UPDATE `sms_settings` SET  `sender_id` = ?, `country_name` = ?, `prefix` = ? WHERE id = ? and parent_id = ?");
		$ex = $sql->execute(array($sender_id, $country_name, $prefix, $ID, $parent_id));
		if ($ex) {
			echo "updated";
		}
	}else{
		#insert
		
		if (strlen($sender_id) > 12 || strlen($sender_id) < 3) {
			echo "Name cannot exceed 12 characters or be less that 4 characters";
			exit();
		}
		if (strlen($sender_id) > 12 || strlen($sender_id) < 3) {
			echo "Name cannot exceed 12 characters or be less that 4 characters";
			exit();
		}
		$query = $connect->prepare("SELECT * FROM sms_settings WHERE parent_id = ?");
		$query->execute(array($parent_id));
		if ($query->rowCount()> 0) {
			echo "Only One Sender ID is Allowed";
			exit();
		}

		$sql = $connect->prepare("INSERT INTO `sms_settings`( `parent_id`, `sender_id`, `country_name`, `prefix`) VALUES (?, ?, ?, ?)");
		$ex = $sql->execute(array($parent_id, $sender_id, $country_name, $prefix));
		if ($ex) {
			echo "done";
		}
	}
?>