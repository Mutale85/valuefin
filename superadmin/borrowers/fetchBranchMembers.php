<?php 
  	require ("../includes/db.php");
  	if (isset($_POST['branch_id'])) {
  		  $branch_id = preg_replace("#[^0-9]#", "", $_POST['branch_id']);
  		  $sql = $connect->prepare("SELECT * FROM allowed_branches WHERE branch_id = ? AND parent_id = ? ");
        $sql->execute(array($branch_id, $_SESSION['parent_id']));
        foreach ($sql->fetchAll() as $rows) {
            $staff_id = $rows['staff_id'];
        ?>
        <div class="custom-control custom-checkbox">
			   <input class="custom-control-input" type="checkbox" id="customCheckbox<?php echo $staff_id?>" name="loan_officer[]" value="<?php echo $staff_id?>">
			   <label for="customCheckbox<?php echo $staff_id?>" class="custom-control-label"><?php echo getStaffMemberNames($connect, $staff_id, $_SESSION['parent_id'])?></label>
		    </div>
<?php
        }
  	}


    if (isset($_POST['branch_id_select'])) {
        $branch_id = preg_replace("#[^0-9]#", "", $_POST['branch_id_select']);
        $sql = $connect->prepare("SELECT * FROM borrowers WHERE branch_id = ? AND parent_id = ? ");
        $sql->execute(array($branch_id, $_SESSION['parent_id']));
        foreach ($sql->fetchAll() as $row) {
            $branch_id = $row['branch_id'];
            echo '<option value=""></option><option value="'.$row['id'].'">'.getBorrowerFullNames($connect, $row['id']).'</option>';
        }
    }

    if (isset($_POST['groupID'])) {
        $groupID = preg_replace("#[^0-9]#", "", $_POST['groupID']);
        $sql = $connect->prepare("SELECT * FROM group_borrower_members WHERE group_unique_id = ? AND parent_id = ? ");
        $sql->execute(array($groupID, $_SESSION['parent_id']));
        foreach ($sql->fetchAll() as $row) {
            
            echo '<option value=""></option><option value="'.$row['id'].'" selected>'.ucwords($row['member_names']).'</option>';
        }
    }

    if (isset($_POST['fetchLeader'])) {
        $groupID = preg_replace("#[^0-9]#", "", $_POST['groupID']);
        $sql = $connect->prepare("SELECT * FROM group_borrowers WHERE group_id = ? AND parent_id = ? ");
        $sql->execute(array($groupID, $_SESSION['parent_id']));
        foreach ($sql->fetchAll() as $row) {
            $group_id = $row['group_id'];
            echo '<option value=""></option><option value="'.$row['id'].'" selected>'.ucwords(getBorrowerFullNames($connect, $row['group_leader_id'])).'</option>';
        }
    }

    if (isset($_POST['fetchLoanOfficers'])) {
        $groupID  = preg_replace("#[^0-9]#", "", trim($_POST['groupID']));
        $branchID = preg_replace("#[^0-9]#", "", trim($_POST['branchID']));
        $sql = $connect->prepare("SELECT * FROM group_loan_officer_id WHERE group_unique_id = ? AND branch_id = ? AND parent_id = ? ");
        $sql->execute(array($groupID, $branchID, $_SESSION['parent_id']));
        foreach ($sql->fetchAll() as $row) {
            $loan_officer_id = $row['loan_officer_id'];
          ?>
          <div class="custom-control custom-checkbox">
           <input class="custom-control-input" type="checkbox" id="officerCheckbox<?php echo $loan_officer_id?>" name="loan_officer_id[]" value="<?php echo $loan_officer_id?>" checked>
           <label for="officerCheckbox<?php echo $loan_officer_id?>" class="custom-control-label"><?php echo getStaffMemberNames($connect, $loan_officer_id, $_SESSION['parent_id'])?></label>
          </div>
  <?php
      }
    }

    
?>
