<?php
	include('../../../includes/db.php');
	if (isset($_POST['branch_id'])) {
		$branch_id = preg_replace("#[^0-9]#", "", $_POST['branch_id']);
		$output = "";
		$query = $connect->prepare("SELECT * FROM branches WHERE id = ? ");
		$query->execute(array($branch_id));
		$row = $query->fetch();
		if ($row) {
			$output = json_encode($row);
		}
		echo $output;
	}

	if (isset($_POST['delete_branch_id'])) {
		$branch_id = $_POST['delete_branch_id'];
		$de = $connect->prepare("DELETE FROM branches WHERE id = ?");
		if ($de->execute(array($branch_id))) {
			$de = $connect->prepare("DELETE FROM `allowed_branches` WHERE branch_id = ?");
			$de->execute(array($branch_id));

			$de = $connect->prepare("DELETE FROM `borrowers` WHERE branch_id = ?");
			$de->execute(array($branch_id));

			$de = $connect->prepare("DELETE FROM `borrower_files` WHERE branch_id = ?");
			$de->execute(array($branch_id));

			$de = $connect->prepare("DELETE FROM `collaterals` WHERE branch_id = ?");
			$de->execute(array($branch_id));

			$de = $connect->prepare("DELETE FROM `emailSettingForm` WHERE branch_id = ?");
			$de->execute(array($branch_id));

			$de = $connect->prepare("DELETE FROM `expenses` WHERE branch_id = ?");
			$de->execute(array($branch_id));

			$de = $connect->prepare("DELETE FROM `group_borrowers` WHERE branch_id = ?");
			$de->execute(array($branch_id));

			$de = $connect->prepare("DELETE FROM `group_borrower_members` WHERE branch_id = ?");
			$de->execute(array($branch_id));

			$de = $connect->prepare("DELETE FROM `group_loan_officer` WHERE branch_id = ?");
			$de->execute(array($branch_id));

			$de = $connect->prepare("DELETE FROM `guarantors` WHERE branch_id = ?");
			$de->execute(array($branch_id));

			$de = $connect->prepare("DELETE FROM `guarantor_files` WHERE branch_id = ?");
			$de->execute(array($branch_id));

			$de = $connect->prepare("DELETE FROM `income_table` WHERE branch_id = ?");
			$de->execute(array($branch_id));

			$de = $connect->prepare("DELETE FROM `loan_payments` WHERE branch_id = ?");
			$de->execute(array($branch_id));

			$de = $connect->prepare("DELETE FROM `sent_emails` WHERE branch_id = ?");
			$de->execute(array($branch_id));

			$de = $connect->prepare("DELETE FROM `sms` WHERE branch_id = ?");
			$de->execute(array($branch_id));
			echo 'done';
		}
	}

	if (isset($_POST['delete_admin_id'])) {
		$staff_id = $_POST['delete_admin_id'];
		$del = $connect->prepare("DELETE FROM admins WHERE id = ?");
		$del->execute(array($staff_id));

		$del = $connect->prepare("DELETE FROM allowed_branches WHERE  staff_id = ? AND parent_id = ? ");
		$del->execute(array($staff_id, $_SESSION['parent_id']));

		echo 'done';
	}

	if (isset($_POST['position_id'])) {
		$ID = $_POST['position_id'];
		$output = "";
		$query = $connect->prepare("SELECT * FROM positions WHERE id = ? AND parent_id = ? ");
		$query->execute(array($ID, $_SESSION['parent_id']));
		$row = $query->fetch();
		if ($row) {
			$output = json_encode($row);
		}
		echo $output;
	}

	if (isset($_POST['position_delete_id'])) {
		$id = $_POST['position_delete_id'];
		$del = $connect->prepare("DELETE FROM positions WHERE id = ? AND parent_id = ?");
		$del->execute(array($id, $_SESSION['parent_id']));
		echo 'done';
	}
	
	if (isset($_POST['organisation_id'])) {
		$ID = $_POST['organisation_id'];
		$output = "";
		$query = $connect->prepare("SELECT * FROM organisations WHERE id = ? AND parent_id = ? ");
		$query->execute(array($ID, $_SESSION['parent_id']));
		$row = $query->fetch();
		if ($row) {
			$output = json_encode($row);
		}
		echo $output;
	}

	if (isset($_POST['allow_access_id'])) {
		$allow_access_id = $_POST['allow_access_id'];
		$update = $connect->prepare("UPDATE admins SET activate = '1' WHERE id = ? AND parent_id = ?");
		if($update->execute(array($allow_access_id, $_SESSION['parent_id']))){
			echo "done";
		}else{
			echo "Failed to Give Access";
		}
	}

	if (isset($_POST['deny_access_id'])) {
		$deny_access_id = $_POST['deny_access_id'];
		$update = $connect->prepare("UPDATE admins SET activate = '0' WHERE id = ? AND parent_id = ?");
		if($update->execute(array($deny_access_id, $_SESSION['parent_id']))){
			echo "done";
		}else{
			echo "Failed to Give Access";
		}
	}

	if (isset($_POST['editSenderId'])) {
		$ID = $_POST['editSenderId'];
		$output = "";
		$query = $connect->prepare("SELECT * FROM sms_settings WHERE id = ? AND parent_id = ? ");
		$query->execute(array($ID, $_SESSION['parent_id']));
		$row = $query->fetch();
		if ($row) {
			$output = json_encode($row);
		}
		echo $output;
	}

	if (isset($_POST['deleteSenderId'])) {
		$id = $_POST['deleteSenderId'];
		$del = $connect->prepare("DELETE FROM sms_settings WHERE id = ? AND parent_id = ?");
		$del->execute(array($id, $_SESSION['parent_id']));
		echo 'done';
	}

	if (isset($_POST['staff_info_id'])) {
		$output = "";
		$staff_info_id = $_POST['staff_info_id'];
		$parent_id = $_SESSION['parent_id'];
		$query = $connect->prepare("SELECT * FROM `staff_members_addons` WHERE staff_id = ? AND parent_id = ? ");
		$query->execute(array($staff_info_id, $_SESSION['parent_id']));
		$row = $query->fetch();
		if ($row) {
			$output = json_encode($row);
		}
		echo $output;
		
	}
	