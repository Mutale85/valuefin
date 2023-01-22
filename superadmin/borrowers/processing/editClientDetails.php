<?php
    include('../../../includes/db.php');
	if (isset($_POST['client_nrc'])) {
        $client_nrc = $_POST['client_nrc'];
        $query = $connect->prepare("SELECT * FROM borrowers_details WHERE borrower_id = ?");
        $query->execute([$client_nrc]);
        $row = $query->fetch();
        if($row){
            echo json_encode($row);
        }

    }
?>