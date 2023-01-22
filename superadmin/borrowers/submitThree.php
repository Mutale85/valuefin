<?php
	include('../includes/db.php');

	if (!empty($_COOKIE['BORROWERID'])) {
		$applicant_id = $_COOKIE['BORROWERID'];
		$update = $connect->prepare("UPDATE borrowers_details SET `next_of_kin_fullnames`= ?,`next_of_kin_nrc`= ?,`next_of_kin_phone`= ?,`next_of_kin_relationship`= ?,`next_of_kin_address`= ? WHERE id = ? ");
		extract($_POST);
		
		$ex = $update->execute(array($next_of_kin_fullnames, $next_of_kin_nrc, $next_of_kin_phone, $next_of_kin_relationship, $next_of_kin_address, $applicant_id));
		if($ex){
			echo "Next of Kin Details Updated";
		}else{
			echo "Error uploading User";
			exit();
		}
	}elseif (!empty($_POST['applicant_id'])) {
		$applicant_id = $_POST['applicant_id'];
		$update = $connect->prepare("UPDATE borrowers_details SET `next_of_kin_fullnames`= ?,`next_of_kin_nrc`= ?,`next_of_kin_phone`= ?,`next_of_kin_relationship`= ?,`next_of_kin_address`= ? WHERE id = ? ");
		extract($_POST);
		
		$ex = $update->execute(array($next_of_kin_fullnames, $next_of_kin_nrc, $next_of_kin_phone, $next_of_kin_relationship, $next_of_kin_address, $applicant_id));
		if($ex){
			echo "Next of Kin Details Updated";
		}else{
			echo "Error uploading User";
			exit();
		}
	}
?>