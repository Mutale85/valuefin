<?php
	include('../includes/db.php');
	if (isset($_POST['member_id'])) {
		$member_id = preg_replace("#[^0-9]#", "", $_POST['member_id']);
		$query = $connect->prepare("SELECT * FROM branches WHERE member_id = ? ");
		$query->execute(array($member_id));
		if ($query->rowCount() > 0) {
			foreach ($query->fetchAll() as $row) {?>
				<tr>
					<td>
						<a href="<?php echo base64_encode($row['id'])?>" class="NavsetCookies" data-id="<?php echo base64_encode($row['id'])?>">Start Work: <?php echo $row['branch_name']?></a>
					</td>
					<td><?php echo $row['address']?>, <?php echo $row['city']?>, <?php echo getCountryName($connect, $row['country'])?></td>
					<td><?php echo $row['phone_landline']?></td>
					<td><?php echo $row['phone_mobile']?></td>
					<td>
						<a href="<?php echo base64_encode($row['id'])?>" class="NavsetCookies" data-id="<?php echo base64_encode($row['id'])?>">Start Working</a>
					</td>
					<?php if($_SESSION['user_role'] == 'Admin'):?>
					<td>
						<a href="branches/branche" class=" text-primary" data-id="<?php echo $row['id']?>"><i class="bi bi-eye"></i></a>
						
					</td>

					<?php else:?>
					<?php endif;?>
				</tr>
		<?php		
			}
			
		}else{
			echo '<a href="branches/branche" class=" text-primary" ><i class="bi bi-plus-circle"></i> Create a Branch</a>';
		}
	}
	$connect = null;
?>
