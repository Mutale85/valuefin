<?php
    include('../../../includes/db.php');
    include("../../../includes/conf.php"); 
    if(isset($_POST['borrower_id'])){
        $borrower_id = filter_input(INPUT_POST, 'borrower_id', FILTER_SANITIZE_SPECIAL_CHARS);
        extract($_POST);
        $paid_date = date("Y-m-d", strtotime($date_added));
        $balance = $loan_balance_amount;
        $sql = $connect->prepare("INSERT INTO `loan_payments`(`borrower_id`, `branch_id`, `parent_id`, `loan_number`, `currency`, `amount`, `balance`, `paid_date`, `payment_method`, `collected_by`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ? )");
        $sql->execute([$borrower_id, $branch_id, $parent_id, trim($loan_id), $currency, $amount, $balance, $paid_date, $payment_method, $collected_by]);

        
        //send an SMS
        $to = getClientsPhone($connect, $borrower_id);
        // $to = '+260976330092';
        $api_key = API;
        $sender_id = SENDER;
        
        if($balance == 0){
            $message = 'Thank you so much. Your Valuefin loan is fully paid. You are eligible for another one';
            echo getBorrowerFullNamesByCardId($connect, $borrower_id) ." has paid off the whole loan";
        }else{
            $message = 'You have made a loan payment to ValueFin -  Balance: '. $currency. ' '. $balance;
            echo getBorrowerFullNamesByCardId($connect, $borrower_id) ." has made a partial payment on the loan ";
        }
        
        echo SMSNOW($to, $message, $api_key, $sender_id);
        $response = 'message_successful';
        $sql = $connect->prepare("INSERT INTO `sms`(`receiver`, `sender_id`, `parent_id`, `branch_id`, `message`, `responseText`) VALUES (?, ?, ?, ?, ?, ?) ");
		$sql->execute(array($to, $sender_id, $parent_id, $branch_id, $message, $response));
    }   
?>