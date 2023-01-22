<?php

	include("../includes/db.php");
	if (isset($_POST['username'])) {
		$username = Clean(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));
		$p_word = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS));
		
		
		if ($username === "") {
			echo "username is empty";
			exit();
		}

		if ($p_word === "") {
			echo "password is empty";
			exit();
		}
		
		$query = $connect->prepare("SELECT * FROM admins WHERE username = ? ");
		$query->execute(array($username));
		if ($query->rowCount() > 0) {
			foreach ($query->fetchAll() as $row) {
				extract($row);
				if($activate === '1'){
					if (password_verify($p_word, $password)) {
						$_SESSION['username'] 	= $username;
						$_SESSION['email'] 		= $email;
					    $_SESSION['user_id'] 	= $id;
					    $_SESSION['firstname'] 	= $firstname;
					    $_SESSION['lastname'] 	= $lastname;
					    $_SESSION['user_role'] 	= $user_role;
					    $_SESSION['parent_id'] 	= $parent_id;

					    setcookie("ValueFinLogin", base64_encode($_SESSION['email']. password_hash($_SESSION['email'], PASSWORD_DEFAULT)), time()+60*60*24*30, '/');
						setcookie("ValueFinLoginAccount", $user_role, time()+60*60*24*30, '/');
					    				    
					    echo "Redirecting you in 1 Second";

					}else{
						echo "Incorrect login credentials";
						exit();
					}
				}else{
					echo "User is not activated";
					exit();
				}
			}
		}else{
			echo 'User not found';
			exit();
		}

	}
?>