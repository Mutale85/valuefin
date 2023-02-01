<?php
	include('../../../includes/db.php');
	if (!empty($_POST['edit_id'])) {
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
        $borrower_dateofbirth       = date("Y-m-d", strtotime($borrower_dateofbirth));
        // Business Details
        $borrower_address 			= filter_input(INPUT_POST, 'borrower_address', FILTER_SANITIZE_SPECIAL_CHARS);
        $borrower_shop_number 		= filter_input(INPUT_POST, 'borrower_shop_number', FILTER_SANITIZE_SPECIAL_CHARS);
        $borrower_products 			= filter_input(INPUT_POST, 'borrower_products', FILTER_SANITIZE_SPECIAL_CHARS);
        // Files 
        
        $borrower_nrc_front         = $_FILES['borrower_nrc_front']['name'];
        $borrower_nrc_back          = $_FILES['borrower_nrc_back']['name'];
        
        $query = $connect->prepare("SELECT * FROM borrowers_details WHERE id = ?  ");
		$query->execute([$edit_id]);
		$row = $query->fetch();
        if($row){
            $bphoto     = $row['borrower_photo'];
            $b_nrc_front = $row['borrower_nrc_front'];
            $b_nrc_back  = $row['borrower_nrc_back'];
        }

        if($_FILES['borrower_photo']['name'] == ""){
            $borrower_photo   = $bphoto;
        }else{
            $borrower_photo 	        = $_FILES['borrower_photo']['name'];
            $destination1 = '../uploads/'.basename($borrower_photo);
            $filename1    = $_FILES["borrower_photo"]["tmp_name"];
            move_uploaded_file($filename1, $destination1);
        }

        if($_FILES['borrower_nrc_front']['name'] == ""){
            $borrower_nrc_front = $b_nrc_front;
        }else{
            $borrower_nrc_front = $_FILES['borrower_nrc_front']['name'];
            $filename    = $_FILES["borrower_nrc_front"]["tmp_name"];
            $destination = '../uploads/'.basename($borrower_nrc_front);
            move_uploaded_file($filename, $destination);
        }

        if($_FILES['borrower_nrc_back']['name'] == ""){
            $borrower_nrc_back = $b_nrc_back;
        }else{
            $borrower_nrc_back = $_FILES['borrower_nrc_back']['name'];
            $filename    = $_FILES["borrower_nrc_back"]["tmp_name"];
            $destination = '../uploads/'.basename($borrower_nrc_back);
            move_uploaded_file($filename, $destination);
        }

        //next of kin details 
        $next_of_kin_fullnames 		= filter_input(INPUT_POST, 'next_of_kin_fullnames', FILTER_SANITIZE_SPECIAL_CHARS);
        $next_of_kin_nrc 			= filter_input(INPUT_POST, 'next_of_kin_nrc', FILTER_SANITIZE_SPECIAL_CHARS);
        $next_of_kin_phone 			= filter_input(INPUT_POST, 'next_of_kin_phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $next_of_kin_relationship 	= filter_input(INPUT_POST, 'next_of_kin_relationship', FILTER_SANITIZE_SPECIAL_CHARS);
        $next_of_kin_address 		= filter_input(INPUT_POST, 'next_of_kin_address', FILTER_SANITIZE_SPECIAL_CHARS); 

        $sql = $connect->prepare("UPDATE `borrowers_details` SET `branch_id` = ?, `parent_id` = ?, `loan_officer_id` = ?, `borrower_photo` = ?, `borrower_title` = ?, `borrower_firstname` = ?, `borrower_lastname` = ?, `borrower_gender` = ?, `borrower_id` = ?, `borrower_nrc_front` = ?, `borrower_nrc_back` = ?, `borrower_address` = ?, `borrower_email` = ?, `borrower_phone` = ?, `borrower_dateofbirth` = ? WHERE id = ?");
        $ex = $sql->execute([$branch_id, $parent_id, $loan_officers_id, $borrower_photo, $borrower_title, $borrower_firstname, $borrower_lastname, $borrower_gender, $borrower_id, $borrower_nrc_front, $borrower_nrc_back, $borrower_address, $borrower_email, $borrower_phone, $borrower_dateofbirth, $edit_id]);
        
        $sql_business = $connect->prepare("UPDATE `borrowers_business_details` SET `branch_id` = ?, `parent_id` = ?, `loan_officers_id` = ?, `borrower_business` = ?, `borrower_shop_number` = ?, `borrower_products` = ? WHERE borrower_id = ?");
        $ed = $sql_business->execute([$branch_id, $parent_id, $loan_officers_id, $borrower_business, $borrower_shop_number, $borrower_products, $borrower_id]);
        
        $sql_next_of_kin = $connect->prepare("UPDATE `borrower_next_of_kin_details` SET `branch_id` = ?, `parent_id` = ?, `loan_officers_id` = ?, `next_of_kin_fullnames` = ?, `next_of_kin_nrc` = ?, `next_of_kin_phone` = ?, `next_of_kin_relationship` = ?, `next_of_kin_address` = ? WHERE borrower_id = ?");
        $q = $sql_next_of_kin->execute([$branch_id, $parent_id, $loan_officers_id,  $next_of_kin_fullnames, $next_of_kin_nrc, $next_of_kin_phone, $next_of_kin_relationship, $next_of_kin_address, $borrower_id]);
        
        if($q){
            echo "Client's details Updated";
        }
	}
?>