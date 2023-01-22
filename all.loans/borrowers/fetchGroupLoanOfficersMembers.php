<?php 
  	require ("../includes/db.php");

    if (isset($_POST['the_group_id'])) {
        $groupID  = preg_replace("#[^0-9]#", "", trim($_POST['the_group_id']));
        $branchID = preg_replace("#[^0-9]#", "", trim($_POST['the_branch_id']));
        $the_main_id = preg_replace("#[^0-9]#", "", trim($_POST['the_main_id']));
        $query = $connect->prepare("SELECT * FROM group_borrowers WHERE id = ? AND branch_id = ? AND group_id = ? AND parent_id = ? ");
        $query->execute(array($the_main_id, $branchID, $groupID, $_SESSION['parent_id']));
        $output = "";
        $row = $query->fetch();
        if ($row['loan_officers_id'] != "") {
          $lf = explode(',', $row['loan_officers_id']);
          
          foreach ($lf as $loan_officer_id) {?>
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input" type="checkbox" id="officerCheckbox<?php echo $loan_officer_id?>" name="loan_officer_id[]" value="<?php echo $loan_officer_id?>" checked>
              <label for="officerCheckbox<?php echo $loan_officer_id?>" class="custom-control-label"><?php echo getStaffMemberNames($connect, $loan_officer_id, $_SESSION['parent_id'])?></label>
          </div>
        <?php      
          }
        }

        $sql = $connect->prepare("SELECT * FROM allowed_branches WHERE branch_id = ? AND parent_id = ? ");
        $sql->execute(array($branchID, $_SESSION['parent_id']));
        foreach ($sql->fetchAll() as $rows) {
          $staff_id = $rows['staff_id'];
        ?>
        <div class="custom-control custom-checkbox">
          <input class="custom-control-input" type="checkbox" id="customCheckbox<?php echo $staff_id?>" name="loan_officer_id[]" value="<?php echo $staff_id?>">
          <label for="customCheckbox<?php echo $staff_id?>" class="custom-control-label"><?php echo getStaffMemberNames($connect, $staff_id, $_SESSION['parent_id'])?> | Branch <?php echo $branchID?></label>
        </div>
     <?php    
      }
   
    }


    if (isset($_POST['branch_id'])) {
      $branch_id = preg_replace("#[^0-9]#", "", $_POST['branch_id']);
      $sql = $connect->prepare("SELECT * FROM allowed_branches WHERE branch_id = ? AND parent_id = ? ");
        $sql->execute(array($branch_id, $_SESSION['parent_id']));
        foreach ($sql->fetchAll() as $rows) {
            $staff_id = $rows['staff_id'];
        ?>
        <div class="custom-control custom-checkbox">
         <input class="custom-control-input" type="checkbox" id="customCheckbox<?php echo $staff_id?>" name="loan_officer_id[]" value="<?php echo $staff_id?>">
         <label for="customCheckbox<?php echo $staff_id?>" class="custom-control-label"><?php echo getStaffMemberNames($connect, $staff_id, $_SESSION['parent_id'])?> | Branch <?php echo $branch_id?></label>
        </div>
<?php
        }
    }

    if (isset($_POST['groupID'])) {
        $groupID = preg_replace("#[^0-9]#", "", $_POST['groupID']);
        $branchID = preg_replace("#[^0-9]#", "", $_POST['branchID']);
        $query = $connect->prepare("SELECT * FROM group_borrowers WHERE group_id = ? AND parent_id = ? ");
        $query->execute(array($groupID, $_SESSION['parent_id']));
        $output = "";
        $row = $query->fetch();
        $id = '';
        if ($row['borrowers_id'] != "") {
          $member = explode(", ", $row['borrowers_id']);
         
          foreach ($member as $borrower_id) {
            $output .= '<option value=""></option><option value="'.$borrower_id.'" selected>'.getBorrowerFullNames($connect, $borrower_id, $_SESSION['parent_id']).'</option>'; 
            $id = $borrower_id;

          }
        }
        $sql = $connect->prepare("SELECT  * FROM borrowers WHERE branch_id = ? AND parent_id = ?  ");
        $sql->execute(array($branchID, $_SESSION['parent_id']));
        foreach ($sql->fetchAll() as $rows) {
          // we get the branch ID, and try to find other people who belong to 
            $output .= '<option value="'.$rows['id'].'">'.ucwords(getBorrowerFullNames ($connect, $rows['id'], $rows['parent_id'])).'</option>';

        }
        echo $output;
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
    
?>
