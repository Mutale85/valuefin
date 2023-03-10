<?php
	include('../../../includes/db.php');
	if (isset($_POST['member_id'])) {
		$member_id = preg_replace("#[^0-9]#", "", $_POST['member_id']);
		$query = $connect->prepare("SELECT * FROM branches WHERE member_id = ? ");
		$query->execute(array($member_id));
		if ($query->rowCount() > 0) {
			foreach ($query->fetchAll() as $row) {?>
				<tr>
					<td>
						<a href="<?php echo base64_encode($row['id'])?>" class=" NavsetCookies" data-id="<?php echo base64_encode($row['id'])?>"><?php echo $row['branch_name']?></a>
					</td>
					<td><?php echo $row['address']?>, <?php echo $row['city']?>, <?php echo getCountryName($connect, $row['country'])?></td>
					<td><?php echo $row['phone_mobile']?></td>
					<td>
						<a href="<?php echo base64_encode($row['id'])?>" class="NavsetCookies btn btn-primary btn-sm" data-id="<?php echo base64_encode($row['id'])?>">Start Working <i class="bi bi-arrow-right-circle"></i></a>
					</td>
					<td>
						<a href="" class="editBranch text-primary" data-id="<?php echo $row['id']?>"><i class="bi bi-pencil-square"></i></a>
						<a href="" class="deleteBranch text-danger" data-id="<?php echo $row['id']?>"><i class="bi bi-trash"></i></a>
					</td>
				</tr>
		<?php		
			}
			
		}else{
			echo "";
		}
	}
	$connect = null;
?>
