<?php
	include('../includes/db.php');
	if (isset($_FILES['file'])) {
		$BRANCHID 		= base64_decode($_COOKIE['SelectedBranch']);
		$file_name 		= $_FILES['file']['name'];
		$filename 		= $_FILES['file']['tmp_name'];
		$destination 	= 'uploads/'.basename($file_name);
		if(move_uploaded_file($filename, $destination)){
			$query = $connect->prepare("SELECT * FROM borrowers_files WHERE file_name = ? AND borrower_id = ?");
			$query->execute(array($file_name, $_SESSION['applicant_id']));
			if ($query->rowCount() > 0) {
				//skip
			}else{
				$sql = $connect->prepare("INSERT INTO `borrowers_files`(`borrower_id`, `parent_id`, `branch_id`, `file_name`) VALUES(?, ?, ?, ?) ");
				$sql->execute(array($_SESSION['applicant_id'], $_SESSION['parent_id'], $BRANCHID, $file_name));
			}
		}else{
			echo "Files failed to upload";
			exit();
		}
	}

	// if (isset($_FILES['file'])) {
	// 	$BRANCHID 		= base64_decode($_COOKIE['SelectedBranch']);
	// 	$file_name 		= $_FILES['file']['name'];
	// 	$filename 		= $_FILES['file']['tmp_name'];
	// 	$destination 	= 'uploads/'.basename($file_name);
	// 	if(move_uploaded_file($filename, $destination)){
	// 		$query = $connect->prepare("SELECT * FROM borrowers_files WHERE file_name = ? AND borrower_id = ?");
	// 		$query->execute(array($file_name, $_COOKIE['BORROWERID']));
	// 		if ($query->rowCount() > 0) {
	// 			//skip
	// 		}else{
	// 			$sql = $connect->prepare("INSERT INTO `borrowers_files`(`borrower_id`, `parent_id`, `branch_id`, `file_name`) VALUES(?, ?, ?, ?) ");
	// 			$sql->execute(array($_COOKIE['BORROWERID'], $_SESSION['parent_id'], $BRANCHID, $file_name));
	// 		}
	// 	}else{
	// 		echo "Files failed to upload";
	// 		exit();
	// 	}
	// }
?>