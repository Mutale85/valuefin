<?php
	include '../includes/db.php';
	
    if (isset($_POST['loan_type_id'])) {
    	$ouput = '';
    	$loan_type_id = preg_replace("#[^0-9]#", "", $_POST['loan_type_id']);
        $sql = $connect->prepare("SELECT * FROM loan_plans WHERE loan_type = ? AND parent_id = ? ");
        $sql->execute(array($loan_type_id, $_SESSION['parent_id']));
        if ($sql->rowCount() > 0) {
	        foreach ($sql->fetchAll() as $row) {
	            
	            $ouput .=  '<option value="'.$row['id'].'">'.$row['months'].' Months / [ '.$row['interest_percentage'].'% ] / [ '.$row['penalty_rate'].'% ]</option>';
	        }

        }else{
        	$ouput.= '<option value="">No Loan Plan Found</option>';
        }
        echo $ouput;
    }


     if (isset($_POST['loan_parent_id'])) {
        $loan_parent_id = preg_replace("#[^0-9]#", "", base64_decode($_POST['loan_parent_id']));
        $sql = $connect->prepare("SELECT * FROM loan_type WHERE parent_id = ? ");
        $sql->execute(array($loan_parent_id));
        if ($sql->rowCount() > 0) {
            $ouput = '<option value="">Select loan type</option>';
            foreach ($sql->fetchAll() as $row) {
                
                $ouput .=  '<option value="'.$row['id'].'" data-rate="'.$row['interest_rate'].'" data-period="'.$row['period'].'">'.ucwords($row['type_name']).' - [ '.$row['interest_rate'].' % ] / [ '.$row['period'].' ]</option>';
            }

        }else{
            $ouput.= '<option value="">No Loan Type Found</option>';
        }
        echo $ouput;
    }

    if (isset($_POST['borrower_card_id_get_loan_number'])) {
        echo preg_replace("#[^0-9]#", "_", $_POST['borrower_card_id_get_loan_number']);
    }
    
?>