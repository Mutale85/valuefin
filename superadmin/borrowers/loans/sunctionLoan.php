<?php
	include "../../../includes/db.php";
    if(isset($_POST['borrower_id'])){
        $borrower_id    = $_POST['borrower_id'];
        $loan_id        = $_POST['loan_id'];
        $comment        = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);
        $branch_id      = $_POST['branch_id'];
        $parent_id      = $_POST['parent_id'];
        $amount         = $_POST['amount'];
        $loan_status    = $_POST['loan_status'];
        if($loan_status == 'Approve'){
            $update = $connect->prepare("UPDATE loan_applications SET status = 'approved' WHERE id = ? AND applicant_id = ? ");
            $update->execute([$loan_id, $borrower_id]);
            $sql = $connect->prepare("INSERT INTO approvedLoans(`branch_id`, `parent_id`, `loan_id`, `borrower_id`, `comment`, `amount`) VALUES(?, ?, ?, ?, ?, ?) ");
            $sql->execute([$branch_id, $parent_id, $loan_id, $borrower_id, $comment, $amount]);
            echo "Loan ". $loan_status;
        }else{
            $update = $connect->prepare("UPDATE loan_applications SET status = 'rejected' WHERE id = ? AND applicant_id = ? ");
            $update->execute([$loan_id, $borrower_id]);
            $sql = $connect->prepare("INSERT INTO rejectedLoans(`branch_id`, `parent_id`, `loan_id`, `borrower_id`, `comment`, `amount`) VALUES(?, ?, ?, ?, ?, ?) ");
            $sql->execute([$branch_id, $parent_id, $loan_id, $borrower_id, $comment, $amount]);
            echo "Loan " .$loan_status;
        }
        // sms customer about the approved loan.
    }

?>