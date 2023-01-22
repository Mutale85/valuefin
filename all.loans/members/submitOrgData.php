<?php
	include '../includes/db.php';
	extract($_POST);
	if (!empty($ID)) {
		
		$query = $connect->prepare("SELECT * FROM `organisations` WHERE parent_id = ? ");
		$query->execute(array($parent_id));
		$row = $query->fetch();
		if ($row) {
			$logo = $row['org_logo'];
		}

		if ($_FILES['org_logo']['name'] == "") {
			$org_logo = $logo;
		}else{
			$org_logo = $_FILES['org_logo']['name'];
			$filename = $_FILES['org_logo']['tmp_name'];
			$destination = 'adminphotos/'.$org_logo;
			move_uploaded_file($filename, $destination);
		}

		$update = $connect->prepare("UPDATE `organisations` SET `org_logo` = ?, `organisation_name`= ? , `admin_email`= ?,`hq_phone`= ?,`hq_address`= ? WHERE id = ? AND parent_id = ? ");
		$ex = $update->execute(array($org_logo, $organisation_name, $admin_email, $hq_phone, $hq_address, $ID, $parent_id));
		if ($ex) {
			echo 'updated';
		}
	}else{
		$date_added = date("Y-m-d");
		$query = $connect->prepare("SELECT * FROM `organisations` WHERE parent_id = ? ");
		$query->execute(array($parent_id));
		if ($query->rowCount() > 0) {
			echo $organisation_name . ' is already saved';
			exit();
		}
		$org_logo = $_FILES['org_logo']['name'];
		$filename = $_FILES['org_logo']['tmp_name'];
		$destination = 'adminphotos/'.$org_logo;
		move_uploaded_file($filename, $destination);
		$sql = $connect->prepare("INSERT INTO `organisations`(`org_logo`, `organisation_name`, `parent_id`, `admin_email`, `hq_phone`, `hq_address`, `date_added`) VALUES (?, ?, ?, ?, ?, ?, ?) ");
		$ex = $sql->execute(array($org_logo, $organisation_name, $parent_id, $admin_email, $hq_phone, $hq_address, $date_added));
		if ($ex) {
			echo 'done';
		}
	}
?>