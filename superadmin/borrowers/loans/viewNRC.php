<?php
	include '../../../includes/db.php';
	
    if (isset($_POST['borrower_id'])) {
        $query = $connect->prepare("SELECT * FROM borrowers_details WHERE borrower_id = ? ");
        $query->execute([$_POST['borrower_id']]);
        $row = $query->fetch();
        if($row){
            extract($row);
            
            echo '<img src="borrowers/uploads/'.$borrower_nrc_front.'" class="img-fluid" alt="'.$borrower_nrc_front.'" >';
            echo '<div class="border-top border-dark mt-4 mb-4"></div>';
            echo '<img src="borrowers/uploads/'.$borrower_nrc_back.'" class="img-fluid" alt="'.$borrower_nrc_front.'" >';
        }
    }
?>