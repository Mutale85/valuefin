<?php 
    include("../../../includes/db.php");
    if (isset($_POST['staff_id'])) {
        $parent_id = $_SESSION['parent_id'];
		$staff_id   = $_POST['staff_id'];
        $staff_role = $_POST['staff_role'];
		$output = "";
		if($staff_role == 'Loan Officer'){
			$query = $connect->prepare("SELECT * FROM loan_officers WHERE staff_id = ? AND parent_id = ? ");
			$query->execute(array($staff_id, $parent_id));
			$row = $query->fetch();
            if ($row) {
			    $output = json_encode($row);
		    }
		
            echo $output;
		}else if($staff_role == 'Admin'){
			$query = $connect->prepare("SELECT * FROM officers_loan WHERE staff_id = ? AND parent_id = ? ");
			$query->execute(array($staff_id, $parent_id));
			$row = $query->fetch();
            if ($row) {
			    $output = json_encode($row);
		    }
		    echo $output;
        }else{
            $query = $connect->prepare("SELECT * FROM admins WHERE id = ? AND parent_id = ? ");
			$query->execute(array($staff_id, $parent_id));
			$row = $query->fetch();
            if ($row) {
			    $output = json_encode($row);
		    }
		    echo $output;
        }
		
	}
?>