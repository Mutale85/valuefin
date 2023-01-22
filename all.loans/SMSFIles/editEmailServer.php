<?php
	include '../includes/db.php';

	if (isset($_POST['serverId'])) {
		$sql = $connect->prepare("SELECT * FROM emailSettingForm WHERE id = ? AND parent_id = ? ");
		$sql->execute(array($_POST['serverId'], $_SESSION['parent_id']));
		$row = $sql->fetch();
		if ($row) {
			$data = json_encode($row);
		}
		echo $data;
	}

	if (isset($_POST['deleteserverId'])) {
		$sql = $connect->prepare("DELETE FROM emailSettingForm WHERE id = ? AND parent_id = ? ");
		$ex = $sql->execute(array($_POST['deleteserverId'], $_SESSION['parent_id']));
		if ($ex) {
			echo 'deleted';
		}
	}