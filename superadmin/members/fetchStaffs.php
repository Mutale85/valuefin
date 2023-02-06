<?php
	include('../includes/db.php');
	if (isset($_POST['parent_id'])) {
		$parent_id = preg_replace("#[^0-9]#", "", $_POST['parent_id']);
		$query = $connect->prepare("SELECT * FROM admins WHERE parent_id = ? ");
		$query->execute(array($parent_id));
		if ($query->rowCount() > 0) {
			foreach ($query->fetchAll() as $row) {
				$_SESSION['parent_id'] = $row['parent_id'];
				if ($row['photo'] == "") {
					$photo 	= 'http://localhost/kukula/k/dist/img/user2-160x160.jpg';
				}else{
					$photo 	= 'http://localhost/kukula/k/members/adminphotos/'.$row['photo'];
				}
				$staff_id 	= $row['id'];
				$parent_id 	= $row['parent_id'];
			?>
				<tr>
					<td><img src="<?php echo $photo?>" class="img-fluid img-rounded" width="60" height="60"> </td>
					<td><?php echo $row['firstname']?></td>
					<td><?php echo $row['lastname']?></td>
					<td><?php echo $row['gender']?></td>
					<td><?php echo $row['phone']?></td>
					<td><?php echo $row['email']?> </td>
					<td><?php echo $row['address']?></td>
					<td><?php echo getCountryName($connect, $row['country'])?></td>
					<td><?php echo $row['user_role']?></td>
					<td><?php echo allowedBranches($connect, $staff_id, $parent_id)?></td>
					<td>
						<a href="members/editAdmin?staff_id=<?php echo base64_encode($staff_id)?>" class="editAdmin text-primary" data-id="<?php echo $row['id']?>"><i class="bi bi-pencil-square"></i></a>
						<a href="" class="deleteAdmin text-danger" data-id="<?php echo $row['id']?>"><i class="bi bi-trash"></i></a>
					</td>
				</tr>
		<?php		
			}
			
		}else{
			
		}
	}
	$connect = null;
?>
