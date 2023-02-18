<?php
	include '../../includes/db.php';
	extract($_POST);
	if (!empty($id)) {
		# update
		if ($_FILES['photo']['name']) {
			$photo 	= $_FILES['photo']['name'];
			$filename = $_FILES['photo']['tmp_name'];
			$destination = 'uploads/'.basename($photo);
			move_uploaded_file($filename, $destination);
		}else{
			$photo = $edit_photo;

		}
		$phonenumber = preg_replace("#[^0-9+]#", "", $phonenumber);
		$update = $connect->prepare("UPDATE `investors` SET  `photo` = ?, `title` = ?, `firstname` = ?, `lastname` = ?, `id_type` = ?, `id_number` = ?, `gender` = ?, `investor_country` = ?, `email` = ?, `phone` = ?, `address` = ?, `amount` = ?, `equity` = ? WHERE id = ? ");
		$ex = $update->execute(array($photo, $title, $firstname, $lastname, $id_type, $id_number, $gender, $investor_country, $email, $phonenumber, $address, $amount, $equity, $id));
		if ($ex) {
			echo "Investors's data updated";
		}
	}else{
		$photo 	= $_FILES['photo']['name'];
		$filename = $_FILES['photo']['tmp_name'];
		$destination = 'uploads/'.basename($photo);
		move_uploaded_file($filename, $destination);
		$phonenumber = preg_replace("#[^0-9+]#", "", $phonenumber);
		$sql = $connect->prepare("INSERT INTO `investors`(`photo`, `parent_id`, `title`, `firstname`, `lastname`, `id_type`, `id_number`, `gender`, `investor_country`, `email`, `phone`, `address`, `amount`, `equity`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
		$ex = $sql->execute(array($photo, $parent_id, $title, $firstname, $lastname, $id_type, $id_number, $gender, $investor_country, $email, $phonenumber, $address, $amount, $equity));

		if ($ex) {
			echo "Investor Added";
		}
	}
?>