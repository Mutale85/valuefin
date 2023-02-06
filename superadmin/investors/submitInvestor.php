<?php
	include '../includes/db.php';
	extract($_POST);
	if (!empty($id)) {
		# update
		if ($_FILES['photo']['name']) {
			$photo 	= $_FILES['photo']['name'];
			$filename = $_FILES['photo']['tmp_name'];
			$destination = 'investorsfiles/'.basename($photo);
			move_uploaded_file($filename, $destination);
		}else{
			$photo = $edit_photo;

		}
		$borrower_phone = preg_replace("#[^0-9]#", "", $borrower_phone);
		$update = $connect->prepare("UPDATE `investors` SET  `photo` = ?, `title` = ?, `firstname` = ?, `lastname` = ?, `working_status` = ?, `id_type` = ?, `id_number` = ?, `gender` = ?, `investor_country` = ?, `email` = ?, `phone` = ?, `address` = ? WHERE id = ? ");
		$ex = $update->execute(array($photo, $title, $firstname, $lastname, $working_status, $id_type, $id_number, $gender, $investor_country, $email, $borrower_phone, $address, $id));
		if ($ex) {
			echo "update";
		}
	}else{
		$photo 	= $_FILES['photo']['name'];
		$filename = $_FILES['photo']['tmp_name'];
		$destination = 'investorsfiles/'.basename($photo);
		move_uploaded_file($filename, $destination);
		$borrower_phone = preg_replace("#[^0-9]#", "", $borrower_phone);
		$sql = $connect->prepare("INSERT INTO `investors`(`photo`, `parent_id`, `title`, `firstname`, `lastname`, `working_status`, `id_type`, `id_number`, `gender`, `investor_country`, `email`, `phone`, `address`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
		$ex = $sql->execute(array($photo, $parent_id, $title, $firstname, $lastname, $working_status, $id_type, $id_number, $gender, $investor_country, $email, $borrower_phone, $address));

		if ($ex) {
			echo "done";
		}
	}
?>