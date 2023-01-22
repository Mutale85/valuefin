<?php
require ("../includes/db.php");
if (isset($_POST['the_group_id'])) {
	$groupID  = preg_replace("#[^0-9]#", "", trim($_POST['the_group_id']));
    $branchID = preg_replace("#[^0-9]#", "", trim($_POST['the_branch_id']));
    $the_main_id = preg_replace("#[^0-9]#", "", trim($_POST['the_main_id']));
    $parent_id = $_SESSION['parent_id'];
    LoanOfficers($connect, $the_main_id, $branchID, $groupID, $parent_id);

}
// SELECT group_borrowers.*, allowed_branches.* FROM group_borrowers JOIN allowed_branches ON allowed_branches.branch_id = group_borrowers.branch_id WHERE allowed_branches.staff_id

function LoanOfficers($connect, $g_id, $branch_id, $group_unique_id, $parent_id) {
		// $query = $connect->prepare("SELECT * FROM group_borrowers WHERE id = ? ");

	$query = $connect->prepare("SELECT * FROM group_borrowers WHERE id = ? AND branch_id = ? AND group_id = ? AND parent_id = ? ");
	$query->execute(array($g_id, $branch_id, $group_unique_id, $parent_id));
	$output = "";
	$row = $query->fetch();
	if ($row['loan_officers_id'] != "") {
		$lf = explode(',', $row['loan_officers_id']);
		
		foreach ($lf as $loan_officer_id) {?>
			<div class="custom-control custom-checkbox">
              <input class="custom-control-input" type="checkbox" id="officerCheckbox<?php echo $loan_officer_id?>" name="loan_officer_id[]" value="<?php echo $loan_officer_id?>" >
              <label for="officerCheckbox<?php echo $loan_officer_id?>" class="custom-control-label"><?php echo getStaffMemberNames($connect, $loan_officer_id, $_SESSION['parent_id'])?></label>
          </div>
		<?php
           	
		}
	
	}
}

// if (isset($_POST['delete_id'])) {
// 	$delete_id  = preg_replace("#[^0-9]#", "", $_POST['delete_id']);
// 	$loggedParentId = preg_replace("#[^0-9]#", "", $_POST['loggedParentId']);
// 	$borrower_id_number = $_POST['borrower_id_number'];
// 	$branch_id = $_POST['branch_id'];
// 	$query = $connect->prepare("DELETE FROM borrowers WHERE id = ? AND parent_id = ? AND branch_id = ? ");
// 	$ex = $query->execute(array($delete_id, $loggedParentId, $branch_id));

// 	$query = $connect->prepare("DELETE FROM loan_offciers WHERE borrower_id = ? AND parent_id = ? AND borrower_id_number = ? ");
// 	$ex = $query->execute(array($delete_id, $loggedParentId, $borrower_id_number));

// 	$query = $connect->prepare("DELETE FROM borrower_files WHERE borrower_id = ? AND parent_id = ? AND borrower_id_number = ? ");
// 	$ex = $query->execute(array($delete_id, $loggedParentId, $borrower_id_number));
// 	if($ex){
// 		echo "done";
// 	}else{
// 		echo 'error';
// 		exit();
// 	}
// }

if (isset($_POST['delete_id'])) {
	$delete_id  = preg_replace("#[^0-9]#", "", $_POST['delete_id']);
	$loggedParentId = preg_replace("#[^0-9]#", "", $_POST['loggedParentId']);
	$borrower_id_number = $_POST['borrower_id_number'];
	$branch_id = $_POST['branch_id'];
	$query = $connect->prepare("UPDATE borrowers_details SET display = '0' WHERE id = ? AND parent_id = ? AND branch_id = ? ");
	$ex = $query->execute(array($delete_id, $loggedParentId, $branch_id));

	if($ex){
		echo "Borrower Details Sent to Trash Box";
	}else{
		echo 'error';
		exit();
	}
}

if (isset($_POST['guarantors_id'])) {
	$guarantors_id  = preg_replace("#[^0-9]#", "", $_POST['guarantors_id']);
	$parentID = preg_replace("#[^0-9]#", "", $_POST['parentID']);
	$branch_id = $_POST['branch_id'];
	$query = $connect->prepare("DELETE FROM guarantors WHERE id = ? AND parent_id = ? AND branch_id = ? ");
	$ex = $query->execute(array($guarantors_id, $parentID, $branch_id));

	$query = $connect->prepare("DELETE FROM guarantor_files WHERE guarantor_id = ? AND parent_id = ? AND branch_id = ? ");
	$ex = $query->execute(array($guarantors_id, $parentID, $branch_id));
	if($ex){
		echo "done";
	}else{
		echo 'error';
		exit();
	}
}


?>