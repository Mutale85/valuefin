<?php
	include('../../../includes/db.php');
	if (isset($_POST['borrower_firstname'])) {
        $branch_id					= filter_input(INPUT_POST, 'branch_id', FILTER_SANITIZE_SPECIAL_CHARS);
        $parent_id 					= preg_replace("#[^0-9]#", "", $_POST['parent_id']);
        $loan_officers_id			= preg_replace("#[^0-9]#", "", $_POST['loan_officers_id']);
        $borrower_title             = filter_input(INPUT_POST, 'borrower_title', FILTER_SANITIZE_SPECIAL_CHARS);
        $borrower_firstname 		= filter_input(INPUT_POST, 'borrower_firstname', FILTER_SANITIZE_SPECIAL_CHARS);
        $borrower_lastname 			= filter_input(INPUT_POST, 'borrower_lastname', FILTER_SANITIZE_SPECIAL_CHARS);
        $borrower_business 			= filter_input(INPUT_POST, 'borrower_business', FILTER_SANITIZE_SPECIAL_CHARS);
        $borrower_gender  			= filter_input(INPUT_POST, 'borrower_gender', FILTER_SANITIZE_SPECIAL_CHARS);
        $borrower_id 				= filter_input(INPUT_POST, 'borrower_nrc_number', FILTER_SANITIZE_SPECIAL_CHARS);
        $borrower_country 			= filter_input(INPUT_POST, 'borrower_country', FILTER_SANITIZE_SPECIAL_CHARS);
        $borrower_city 				= filter_input(INPUT_POST, 'borrower_city', FILTER_SANITIZE_SPECIAL_CHARS);
        $borrower_email 			= filter_var($_POST['borrower_email'], FILTER_SANITIZE_EMAIL);
        $borrower_phone 			= preg_replace("#[^0-9]#", "", $_POST['phone']);
        $borrower_dateofbirth 		= filter_input(INPUT_POST,'borrower_dateofbirth', FILTER_SANITIZE_SPECIAL_CHARS);
        $borrower_age 	            = filter_var($_POST['borrower_age'], FILTER_SANITIZE_SPECIAL_CHARS);
        $edit_id 		            = filter_input(INPUT_POST,'edit_id', FILTER_SANITIZE_SPECIAL_CHARS);
        
        // Business Details
        $borrower_address 			= filter_input(INPUT_POST, 'borrower_address', FILTER_SANITIZE_SPECIAL_CHARS);
        $borrower_shop_number 		= filter_input(INPUT_POST, 'borrower_shop_number', FILTER_SANITIZE_SPECIAL_CHARS);
        $borrower_products 			= filter_input(INPUT_POST, 'borrower_products', FILTER_SANITIZE_SPECIAL_CHARS);
        // Files 
        $borrower_photo 	        = $_FILES['borrower_photo']['name'];
        $borrower_nrc_front         = $_FILES['borrower_nrc_front']['name'];
        $borrower_nrc_back          = $_FILES['borrower_nrc_back']['name'];
        

        if($borrower_photo == ""){
            echo "You forgot to add the clients Picture";
            exit();
        }
        $destination1 = '../uploads/'.basename($borrower_photo);
        $filename1    = $_FILES["borrower_photo"]["tmp_name"];
        $destination2 = '../uploads/'.basename($borrower_nrc_front);
        $filename2    = $_FILES["borrower_nrc_front"]["tmp_name"];
        $destination3 = '../uploads/'.basename($borrower_nrc_back);
        $filename3    = $_FILES["borrower_nrc_back"]["tmp_name"];

        $path_parts = pathinfo($_FILES["borrower_photo"]["name"]);
        $ext = $path_parts['extension'];
        if ($ext != "gif") {
        	move_uploaded_file($filename1, $destination1);
        	move_uploaded_file($filename2, $destination2);
        	move_uploaded_file($filename3, $destination3);
        }

        //next of kin details 
        $next_of_kin_fullnames 		= filter_input(INPUT_POST, 'next_of_kin_fullnames', FILTER_SANITIZE_SPECIAL_CHARS);
        $next_of_kin_nrc 			= filter_input(INPUT_POST, 'next_of_kin_nrc', FILTER_SANITIZE_SPECIAL_CHARS);
        $next_of_kin_phone 			= filter_input(INPUT_POST, 'next_of_kin_phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $next_of_kin_relationship 	= filter_input(INPUT_POST, 'next_of_kin_relationship', FILTER_SANITIZE_SPECIAL_CHARS);
        $next_of_kin_address 		= filter_input(INPUT_POST, 'next_of_kin_address', FILTER_SANITIZE_SPECIAL_CHARS); 

		
		$query = $connect->prepare("SELECT * FROM borrowers_details WHERE borrower_id = ? AND branch_id = ? AND parent_id = ? ");
		$query->execute([$borrower_id, $branch_id, $parent_id]);
		if ($query->rowCount() > 0) {
			echo 'Client with NRC: '. $borrower_id. ' is already registered to this branch';
			exit();
		}
        
        
        $sql = $connect->prepare("INSERT INTO `borrowers_details`(`branch_id`, `parent_id`, `loan_officer_id`, `borrower_photo`, `borrower_title`, `borrower_firstname`, `borrower_lastname`, `borrower_gender`, `borrower_id`, `borrower_nrc_front`, `borrower_nrc_back`, `borrower_address`, `borrower_email`, `borrower_phone`, `borrower_dateofbirth`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $ex = $sql->execute([$branch_id, $parent_id, $loan_officers_id, $borrower_photo, $borrower_title, $borrower_firstname, $borrower_lastname, $borrower_gender, $borrower_id, $borrower_nrc_front, $borrower_nrc_back, $borrower_address, $borrower_email, $borrower_phone, $borrower_dateofbirth]);
        
        $sql_business = $connect->prepare("INSERT INTO `borrowers_business_details`(`branch_id`, `parent_id`, `loan_officers_id`, `borrower_id`, `borrower_business`, `borrower_shop_number`, `borrower_products`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $ed = $sql_business->execute([$branch_id, $parent_id, $loan_officers_id, $borrower_id, $borrower_business, $borrower_shop_number, $borrower_products]);
        
        $sql_next_of_kin = $connect->prepare("INSERT INTO `borrower_next_of_kin_details`(`branch_id`, `parent_id`, `loan_officers_id`, `borrower_id`, `next_of_kin_fullnames`, `next_of_kin_nrc`, `next_of_kin_phone`, `next_of_kin_relationship`, `next_of_kin_address`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $q = $sql_next_of_kin->execute([$branch_id, $parent_id, $loan_officers_id, $borrower_id, $next_of_kin_fullnames, $next_of_kin_nrc, $next_of_kin_phone, $next_of_kin_relationship, $next_of_kin_address]);
        
        if($ex){
            echo "Client submitted";
        }
        
	}
?>