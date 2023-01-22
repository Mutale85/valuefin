<?php
	include '../includes/db.php';
	extract($_POST);
	if (!empty($ID)) {
		$update = $connect->prepare("UPDATE `positions` SET `title`= ? WHERE id = ? AND parent_id = ? ");
		if($update->execute(array($job_title, $ID, $parent_id))){
			echo "updated";
		}
	}else{
		$sql = $connect->prepare("INSERT INTO `positions`(`title`, `parent_id`) VALUES (?, ?) ");
		
		if($sql->execute(array($job_title, $parent_id))){
			echo "done";
		}
	}
?>