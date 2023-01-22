<?php
	include '../includes/db.php';
	extract($_POST);

	if (!empty($info_id)) {
		# Update
		$update = $connect->prepare("UPDATE staff_members_addons SET `man_number` = ?, `bank_name` = ?, `account_number` = ?, `gender` = ?, `country` = ?, `city` = ? WHERE id = ? AND staff_id = ? AND parent_id = ? ");
		$ex = $update->execute(array($man_number, $bank_name, $account_number, $gender, $country, $city, $info_id, $staff_id, $parent_id));
		if ($ex) {
			echo "Data Updated successfully";
		}
	}else{
		#insert 
		$query = $connect->prepare("SELECT * FROM staff_members_addons WHERE staff_id = ? AND parent_id = ? ");
		$query->execute(array($staff_id, $parent_id));
		if ($query->rowCount() > 0) {
			echo "You already added more information form ". getStaffMemberNames($connect, $staff_id, $parent_id);
			exit();
		}
		$sql = $connect->prepare("INSERT INTO `staff_members_addons`(`staff_id`, `branch_id`, `parent_id`, `man_number`, `bank_name`, `account_number`, `gender`, `country`, `city`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$ex = $sql->execute(array($staff_id, $branch_id, $parent_id, $man_number, $bank_name, $account_number, $gender, $country, $city));
		if ($ex) {
			echo "Data Posted successfully";
		}
	}
?>