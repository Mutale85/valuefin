<?php
	include("../../../includes/db.php");

	if (isset($_POST['old_password'])) {
		$old_password 	 = $_POST['old_password'];
		$new_password 	 = $_POST['new_password'];
		$retype_password = $_POST['retype_password'];
		$username 	= $_SESSION['username'];
		$query = $connect->prepare("SELECT * FROM admins WHERE username = ? ");
		$query->execute(array($username));
		if ($query->rowCount() > 0) {
			foreach ($query->fetchAll() as $row) {
				
				if (password_verify($old_password, $row['password'])) {
					if ($new_password == $retype_password) {
						$password = password_hash($new_password, PASSWORD_DEFAULT);
						$pass_w = base64_encode($new_password);
						$update = $connect->prepare("UPDATE admins SET password = ?, pass_w = ? WHERE id = ? AND username = ?");
						$ex = $update->execute(array($password, $pass_w, $_SESSION['user_id'], $_SESSION['username']));
						if ($ex) {
							echo "Password changed successfully - Next time when logging in, use the new password";
						}
					}else{
						echo "The new passwords don't match";
						exit();
					}
				}else{
					echo "incorrect old password";
					exit();
				}
				
			}
		}
	}
?>