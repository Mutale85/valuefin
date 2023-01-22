<?php
	include 'includes/db.php';
	setcookie("ValueFinLogin", "", time() - 36000, "/");
	setcookie("ValueFinLoginAccount", "", time() - 36000, "/");
	// $update = $connect->prepare("UPDATE `login_table` SET logout_time = NOW() WHERE id = ? AND parent_id = ?");
	// $update->execute(array($_SESSION['lastID'], $_SESSION['parent_id']));
	unset($_SESSION['email']);
	unset($_SESSION['user_id']);
	unset($_SESSION['parent_id']);
	unset($_SESSION['username']);
	unset($_SESSION['user_role']);
	unset($_SESSION['firstname']);
	unset($_SESSION['lastname']);
	session_unset();
	session_destroy();
	header("location:./");
?>