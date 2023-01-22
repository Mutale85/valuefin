<?php
	require ("../includes/db.php");
		$branch_id = base64_decode($_COOKIE['SelectedBranch']);
		$parent_id = $_SESSION['parent_id'];
	if (isset($_POST['fileId'])) {
		$fileId = $_POST['fileId'];
		$delete = $connect->prepare("DELETE FROM borrowers_files WHERE id = ? AND parent_id = ? AND branch_id = ?");
		$ex = $delete->execute(array($fileId, $parent_id, $branch_id));
		if ($ex) {
			echo "File Removed Completely";
		}else{
			echo "";
		}
	}

	if (isset($_POST['CollateralFileId'])) {
		$CollateralFileId 	= $_POST['CollateralFileId'];
		$borrower_id 		= $_POST['borrower_id'];
		$delete = $connect->prepare("DELETE FROM collaterals_files WHERE id = ? AND borrower_id = ?");
		$ex = $delete->execute(array($CollateralFileId, $borrower_id));
		if ($ex) {
			echo "File Removed Completely";
		}else{
			echo "";
		}
	}

	if (isset($_POST['GuarantorFileId'])) {
		$GuarantorFileId 	= $_POST['GuarantorFileId'];
		$borrower_id 		= $_POST['borrower_id'];
		$delete = $connect->prepare("DELETE FROM guarantor_files WHERE id = ? AND borrower_id = ?");
		$ex = $delete->execute(array($GuarantorFileId, $borrower_id));
		if ($ex) {
			echo "File Removed Completely";
		}else{
			echo "";
		}
	}

	
?>