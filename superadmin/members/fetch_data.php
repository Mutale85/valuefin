<?php
include('../includes/db.php');
if (isset($_SESSION['parent_id'])) {

// fetch records
	$parent_id = $_SESSION['parent_id'];
	$query = $connect->prepare("SELECT * FROM admins WHERE parent_id = ? ");
	$query->execute(array($parent_id));
	$numRows = $query->rowCount();
	if ( $numRows > 0) {
		$adminData = array();
		foreach ($query->fetchAll() as $row) {
			$_SESSION['parent_id'] = $row['parent_id'];
			if ($row['photo'] == "") {
				$photo 	= 'http://localhost/kukula/k/dist/img/user2-160x160.jpg';
			}else{
				$photo 	= 'http://localhost/kukula/k/members/adminphotos/'.$row['photo'];
			}
			$staff_id 	= $row['id'];
			$parent_id 	= $row['parent_id'];

			$adminRows = array();
			$adminRows[] = '<img src="'.$photo.'" alt="'.$photo.'" class="img-fluid img-rounded" width="60" height="60">';
			$adminRows[] = ucfirst($row['firstname'].' '.$row['lastname']);
			$adminRows[] = $row['gender'];		
			$adminRows[] = $row['phone'];	
			$adminRows[] = $row['email'];
			$adminRows[] = $row['address'];
			$adminRows[] = getCountryName($connect, $row['country']);
			$adminRows[] = $row['user_role'];
			$adminRows[] = allowedBranches($connect, $staff_id, $parent_id);			
			$adminRows[] = '<a href="members/editAdmin?staff_id='.base64_encode($staff_id).'" class="editAdmin text-primary" data-id="'.$row['id'].'"><i class="bi bi-pencil-square"></i></a>
			<a href="" class="deleteAdmin text-danger" data-id="'.$row['id'].'"><i class="bi bi-trash"></i></a> ';
			$adminData[] = $adminRows;
		}
		$output = array(
			"draw"				=>	intval($_SESSION['parent_id']),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$adminData
		);
		echo json_encode($output);
		
	}
}

?>
