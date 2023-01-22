<?php
	function getUserIpAddr(){
	    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	        //ip from share internet
	        $ip = $_SERVER['HTTP_CLIENT_IP'];
	    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	        //ip pass from proxy
	        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }else{
	        $ip = $_SERVER['REMOTE_ADDR'];
	    }
	    return $ip;
	}

	function time_ago_check($time){
		// date_default_timezone_set("Africa/Lusaka");
		$time_ago 	= strtotime($time);
		$current_time = time();
		$time_difference = $current_time - $time_ago;
		$seconds = $time_difference;
		//lets make tround thes into actual time.
		$minutes 	= round($seconds / 60);
		$hours		= round($seconds / 3600);
		$days 		= round($seconds / 86400);
		$weeks   	= round($seconds / 604800); // 7*24*60*60;  
		$months  	= round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
		$years   	= round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60

		if ($seconds <= 60) {
			return "$seconds Seconds Ago";
		}else if ($minutes <= 60) {

			if ($minutes == 1) {
				return "1 minute Ago";
			}else{
				return "$minutes minutes ago";
			}
			
		}else if ($hours <= 24) {
			if ($hours == 1) {
				return "1 hour ago";
			}else{
				return "$hours hrs ago";
			}
		}else if ($days <= 7) {
			if ($days == 1) {
				return "1 day ago";
			}else{
				return "$days days ago";
			}
		}else if ($weeks < 7) {
			if ($weeks == 1) {
			
				return "1 week ago";
			}else{
				return "$weeks Weeks ago";
			}
		}else if ($months <= 12) {
			if ($months == 1) {
				return "1 month ago";
			}else{
				return "$months Months ago";
			}
		}else {
			if ($years == 1) {
				return "One year ago";
			}else{
				return "$years years ago";
			}
		}
	}


	function getBorrowerFullNames($connect, $user_id) {
		$query = $connect->prepare("SELECT * FROM borrowers WHERE id = ? AND parent_id = ? ");
		$query->execute(array($user_id, $_SESSION['parent_id']));
		$output = "";
		$row = $query->fetch();
		if ($row) {
			$output = $row['borrower_firstname'] . ' ' .$row['borrower_lastname'];
		}
		
		return $output;
	}

	function getBorrowerFullNamesByCardId($connect, $borrower_ID) {
		$query = $connect->prepare("SELECT * FROM borrowers WHERE borrower_ID = ? AND parent_id = ? ");
		$query->execute(array($borrower_ID, $_SESSION['parent_id']));
		$output = "";
		$row = $query->fetch();
		if($row){
			$output = $row['borrower_firstname'] . ' ' .$row['borrower_lastname'];
			return $output;
		}
	}

	function getBorrowerGroupNamesByCardId($connect, $group_id) {
		$query = $connect->prepare("SELECT * FROM group_borrowers WHERE group_id = ? AND parent_id = ? ");
		$query->execute(array($group_id, $_SESSION['parent_id']));
		$output = "";
		$row = $query->fetch();
		if($row){
			$output = $row['group_name'];
			return $output;
		}
	}

	function getBorrowerFullNamesByPhone($connect, $phone) {
		$query = $connect->prepare("SELECT * FROM borrowers_details WHERE borrower_phone = ? AND parent_id = ? ");
		$query->execute(array($phone, $_SESSION['parent_id']));
		$output = "";
		$row = $query->fetch();
		if($row){
			$output = $row['borrower_firstname'] . ' ' .$row['borrower_lastname'];
			return $output;
		}
	}

	function getBorrowerFullNamesByEmail($connect, $email) {
		$query = $connect->prepare("SELECT * FROM borrowers_details WHERE borrower_email = ? AND parent_id = ? ");
		$query->execute(array($email, $_SESSION['parent_id']));
		$output = "";
		$row = $query->fetch();
		if($row){
			$output = $row['borrower_firstname'] . ' ' .$row['borrower_lastname'];
			return $output;
		}
	}

	function InvestorFullNamesByEmail($connect, $email) {
		$query = $connect->prepare("SELECT * FROM investors WHERE email = ? AND parent_id = ? ");
		$query->execute(array($email, $_SESSION['parent_id']));
		$output = "";
		$row = $query->fetch();
		if($row){
			$output = $row['firstname'] . ' ' .$row['lastname'];
			return $output;
		}
	}


	function getStaffMemberNames($connect, $user_id, $parent_id) {
		$query = $connect->prepare("SELECT * FROM admins WHERE id = ? AND parent_id = ? ");
		$query->execute(array($user_id, $parent_id));
		$output = "";
		$row = $query->fetch();
		if($row){
			$output = $row['firstname']. ' '. $row['lastname'];
		}
		return $output;
	}

	function getStaffMemberNamesByEmail($connect, $email, $parent_id) {
		$query = $connect->prepare("SELECT * FROM admins WHERE email = ? AND parent_id = ? ");
		$query->execute(array($email, $parent_id));
		$output = "";
		$row = $query->fetch();
		if($row){
			$output = $row['firstname']. ' '. $row['lastname'];
		}
		return $output;
	}

	function getStaffMemberRole($connect, $staff_id, $parent_id) {
		$query = $connect->prepare("SELECT * FROM admins WHERE id = ? AND parent_id = ? ");
		$query->execute(array($staff_id, $parent_id));
		$output = "";
		$row = $query->fetch();
		if ($row) {
			$output = $row['position'];
		}
		
		return $output;
	}

	function getStaffMemberGender($connect, $user_id, $parent_id) {
		$query = $connect->prepare("SELECT * FROM admins WHERE id = ? AND parent_id = ? ");
		$query->execute(array($user_id, $parent_id));
		$output = "";
		$row = $query->fetch();
		if ($row) {
			$output = $row['gender'];
		}
		
		return $output;
	}

	function getStaffMemberPhoto($connect, $user_id, $parent_id) {
		$query = $connect->prepare("SELECT * FROM admins WHERE id = ? AND parent_id = ? ");
		$query->execute(array($user_id, $parent_id));
		$output = "";
		$row = $query->fetch();
		if($row){
			$output = $row['photo'];
		}
		return $output;
	}

	function getStaffMemberImage($connect, $user_id, $parent_id) {
		$query = $connect->prepare("SELECT * FROM admins WHERE id = ? AND parent_id = ? ");
		$query->execute(array($user_id, $parent_id));
		$image_src = "";
		$row = $query->fetch();
		if($row){
			extract($row);
			if ($photo == "") {
				$image_src = '../assets/images/client-image.jpg';
			}else{
				$image_src = 'members/adminphotos/'. $photo;
			}
			
		}
		return $image_src;
	}


	function getStaffMemberAddress($connect, $user_id, $parent_id) {
		$query = $connect->prepare("SELECT * FROM admins WHERE id = ? AND parent_id = ? ");
		$query->execute(array($user_id, $parent_id));
		$output = "";
		$row = $query->fetch();
		if($row){
			$output = $row['address'];
		}
		return $output;
	}

	function getStaffMemberEmail($connect, $user_id, $parent_id) {
		$query = $connect->prepare("SELECT * FROM admins WHERE id = ? AND parent_id = ? ");
		$query->execute(array($user_id, $parent_id));
		$output = "";
		$row = $query->fetch();
		if($row){
			$output = $row['email'];
		}
		return $output;
	}

	function getStaffMemberPhone($connect, $user_id, $parent_id) {
		$query = $connect->prepare("SELECT * FROM admins WHERE id = ? AND parent_id = ? ");
		$query->execute(array($user_id, $parent_id));
		$output = "";
		$row = $query->fetch();
		if($row){
			$output = $row['phone'];
		}
		return $output;
	}

	function borrowerPicture($connect, $user_id) {
		$query = $connect->prepare("SELECT * FROM borrowers WHERE id = ? ");
		$query->execute(array($user_id));
		$output = "";
		$row = $query->fetch();
		$img = $row['borrower_borrower_photo'];
		if ($img != "") {
			$output = 'fileuploads/'.$img;
		}else{
			$output = 'dist/img/avatar3.png';
		}
		return $output;
	}

	function userAge($time){
	// date_default_timezone_set("Africa/Lusaka");
		$time_ago 	= strtotime($time);
		$current_time = time();
		$time_difference = $current_time - $time_ago;
		$seconds = $time_difference;
		$minutes 	= round($seconds / 60);
		$hours		= round($seconds / 3600);
		$days 		= round($seconds / 86400);
		$weeks   	= round($seconds / 604800); // 7*24*60*60;  
		$months  	= round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
		$years   	= round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60

		if ($seconds <= 60) {
			return "$seconds Seconds Ago";
		}else if ($minutes <= 60) {

			if ($minutes == 1) {
				return "1 minute Ago";
			}else{
				return "$minutes minutes ago";
			}
			
		}else if ($hours <= 24) {
			if ($hours == 1) {
				return "1 hour ago";
			}else{
				return "$hours hrs ago";
			}
		}else if ($days <= 7) {
			if ($days == 1) {
				return "1 day ago";
			}else{
				return "$days days ago";
			}
		}else if ($weeks < 7) {
			if ($weeks == 1) {
			
				return "1 week ago";
			}else{
				return "$weeks Weeks ago";
			}
		}else if ($months <= 12) {
			if ($months == 1) {
				return "1 month ago";
			}else{
				return "$months Months ago";
			}
		}else {
			if ($years == 1) {
				return "One year ago";
			}else{
				return "$years years";
			}
		}
	}

	function getCountryName($connect, $id) {
		$query = $connect->prepare("SELECT * FROM currencies WHERE id = ? ");
		$query->execute(array($id));
		$output = "";
		$row = $query->fetch();
		if($row){
			$output = $row['country'];
		}
		return $output;
	}

	function borrowerFiles($connect, $user_id) {
		$query = $connect->prepare("SELECT * FROM borrowers WHERE id = ? ");
		$query->execute(array($user_id));
		$output = "";
		$row = $query->fetch();
		if($row){
			if ($row['borrower_borrower_files'] != "") {
			?>
				<li class="list-group-item">
	      			<b>Documents:</b>
			<?php
				$file = explode(", ", $row['borrower_borrower_files']);
				foreach ($file as $doc) {?>
					
	              	<a href="fileuploads/<?php echo $doc?>" target="_blank" class="float-right" title="download <?php echo $doc?>">
	              		<?php echo $doc?> </i>	
	              	</a>
	              	<br>
			<?php
				}
			?>
				</li>
			<?php
			}
		}
	}

	function borrowerAddedFiles($connect, $user_id) {
		$query = $connect->prepare("SELECT * FROM borrowers WHERE id = ? ");
		$query->execute(array($user_id));
		$output = "";
		$row = $query->fetch();
		if ($row) {
		
			if ($row['borrower_borrower_files'] != "") {

				$file = explode(", ", $row['borrower_borrower_files']);
				foreach ($file as $docs) {
					$output .= $docs.'<br>';
				}
			}
		}
		return $output;
	}

	function guarantorAddedFiles($connect, $guarantor_id, $parent_id, $branch_id) {
		$output = "";
		$query = $connect->prepare("SELECT * FROM guarantor_files WHERE guarantor_id = ? AND parent_id = ? AND branch_id = ? ");
		$query->execute(array($guarantor_id, $parent_id, $branch_id));
		foreach ($query->fetchAll() as $row) {
			extract($row);
			if ($file_name != "") {
				$output .= '<p>'. $file_name .' <a href="fileuploads/'.$file_name.'" target="_blank"><i class="bi bi-file"></i> View Document</a></p>'; 
			}else{
				$output .= '';
			}
		}

		return $output;
	}

	function getLoanOfficer($connect, $user_id) {
		$query = $connect->prepare("SELECT * FROM borrowers WHERE id = ? ");
		$query->execute(array($user_id));
		$output = "";
		$row = $query->fetch();
		if($row){
			if ($row['loan_officers'] != "") {
			?>
				<li class="list-group-item">
	      			<b>Loan officers:</b>
			<?php
				$officer = explode(", ", $row['loan_officers']);
				foreach ($officer as $id) {?>
					
	              	<a href="<?php echo $id?>"  class="float-right">
	              		<?php echo getStaffMemberNames($connect, $id, $_SESSION['parent_id'])?></i>	
	              	</a>
	              	<br>     	
			<?php
				}
			?>
				</li>
			<?php
			}
		}
	}


	function borrowerDetails($connect, $id) {
		$query = $connect->prepare("SELECT * FROM borrowers WHERE id = ? ");
		$query->execute(array($id));
		$count = $query->rowCount();
		$res = $query->fetchAll();

		foreach ($res as $row) {?>
			<tr>
				<td>
					<a href="borrowers/view_borrower_details?borrower_id=<?php echo $row['borrower_ID']?>" class="btn btn-xs btn-outline-primary">Loans</a>
				</td>
				<td><?php echo getBorrowerFullNames($connect, $row['id'])?> </td>
				<td><?php echo $row['borrower_business']?></td>
				<td><?php echo $row['borrower_ID']?></td>
				<td><?php echo $row['borrower_phone']?></td>
				<td><?php echo $row['borrower_email']?></td>
				<td>0</td> 
				 <td>0</td> 
				<td></td>
				<td>
					<a href="borrowers/view_borrower_details?borrower_id=<?php echo $row['borrower_ID']?>" class="btn btn-xs btn-outline-secondary editUser" id="<?php echo $row['id']?>"><i class="bi bi-person"></i>
					</a>
				</td>
			</tr>
		<?php
		}							        		
	}

	function passwordGenerate() {
	    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	    $password = array(); 
	    $alphabet_Length = strlen($alphabet) - 1;
	    for ($i = 0; $i < 9; $i++) {
	        $new = rand(0, $alphabet_Length);
	        $password[] = $alphabet[$new];
	    }
	    return implode($password); //turn the array into a string
	}

	function allowedBranches($connect, $staff_id, $parent_id){
		$output = '';
		$query = $connect->prepare("SELECT * FROM allowed_branches WHERE staff_id = ? AND parent_id = ?");
		$query->execute(array($staff_id, $parent_id));
		if ($query->rowCount() > 0) {
			// $output = '';
			foreach ($query->fetchAll() as $row) {
				$output .= '<li class="nav-item"><a href="javascript:void(0)" class="nav-link"> '.branchName($connect, $parent_id, $row['branch_id']).'</a></li>';
			}
		}else{
			// $output .= '';
		}
		return $output;
	}

	function branchName($connect, $parent_id, $branch_id){
		$output = '';
		$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ?  AND id = ?");
		$sql->execute(array($parent_id, $branch_id));
		if ($sql->rowCount() > 0) {
			foreach ($sql->fetchAll() as $row) {
				$output .= $row['branch_name'];
			}
		}
		return $output;
	}


	function groupBorrowersbranchName($connect, $parent_id, $branch_id){
		$output = '';
		$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ?  AND id = ?");
		$sql->execute(array($parent_id, $branch_id));
		if ($sql->rowCount() > 0) {
			foreach ($sql->fetchAll() as $row) {
				$output .= $row['branch_name'];
			}
		}
		return $output;
	}


	function adminBranches($connect){
		$sql = $connect->prepare("SELECT * FROM allowed_branches WHERE staff_id = ? AND parent_id = ?");
		$sql->execute(array($_SESSION['user_id'], $_SESSION['parent_id']));
		if($sql->rowCount() > 0){
			foreach($sql->fetchAll() as $rows){
			  	echo "<p class='fs-5'>".ucwords(base64_decode($_COOKIE['allowed_branches'.$rows['branch_id']]))."</p>";
			}
		}
	}

	function adminBranchesID($connect){
		$output = '';
		$sql = $connect->prepare("SELECT * FROM allowed_branches WHERE staff_id = ? AND parent_id = ?");
		$sql->execute(array($_SESSION['user_id'], $_SESSION['parent_id']));
		if($sql->rowCount() > 0){
			foreach($sql->fetchAll() as $rows){
			  	$output .= $rows['branch_id'];
			}
		}
		return $output;
	}

	function getBranchName($connect, $parent_id, $branch_id){
		$output = '';
		$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ?  AND id = ?");
		$sql->execute(array($parent_id, $branch_id));
		$row = $sql->fetch();
		if($row){
			$output = $row['branch_name'];
		}
		return $output;
	}



	function groupBorrowersLoanOfficers($connect, $g_id, $branch_id, $group_unique_id, $parent_id) {
		// $query = $connect->prepare("SELECT * FROM group_borrowers WHERE id = ? ");

		$query = $connect->prepare("SELECT * FROM group_borrowers WHERE id = ? AND branch_id = ? AND group_id = ? AND parent_id = ? ");
		$query->execute(array($g_id, $branch_id, $group_unique_id, $parent_id));
		$output = "";
		$row = $query->fetch();
		if ($row['loan_officers_id'] != "") {
			$lf = explode(',', $row['loan_officers_id']);
			$output = "<ol>";
			foreach ($lf as $loan_officer_id) {
               	$output .= '<li>'. getStaffMemberNames($connect, $loan_officer_id, $parent_id).'</li>';	
			}
			$output . "<ol>";
		}
		return $output;
	}

	function permisions ($connect, $branch_id, $parent_id, $staff_id){
		$query = $connect->prepare("SELECT * FROM allowed_branches WHERE branch_id = ? AND parent_id = ? AND staff_id = ?");
		$query->execute(array($branch_id, $parent_id, $staff_id));
		if ($query->rowCount() > 0) {
			# allowed to see branch information
			echo "<p class='fs-5 mb-4'>Allowed To View : " . ucwords(getBranchName($connect, $parent_id, $branch_id)). "<p>";
		}else{
			#not allowed
			// exit();
		}
	}

	function loanType($connect, $loan_id, $parent_id) {
		$query = $connect->prepare("SELECT * FROM loan_type WHERE id = ? AND parent_id = ?");
		$query->execute(array($loan_id, $parent_id));
		$output = '';
		foreach ($query->fetchAll() as $row) {
		 	$output .= $row['type_name'];
		}
		return $output; 
	}

	function loanStatus($connect, $BRANCHID, $parent_id, $status) {
		$output = '';
		$sql = $connect->prepare("SELECT * FROM `loans_table` WHERE branch_id = ? AND parent_id = ? AND loan_status = ? ");
        $sql->execute(array($BRANCHID, $parent_id, $status));
        $output = $sql->rowCount();
        return $output;
	}


	function countSMS($connect, $receiver, $parent_id){
		$output = '';
		$query = $connect->prepare("SELECT *, COUNT(id) AS total FROM sms WHERE receiver = ? AND parent_id = ?");
		$query->execute(array($receiver, $parent_id));
		$row = $query->fetch();
		if ($row) {
			$output = $row['total'];
		}
		return $output;
	}

	function countEmails($connect, $receiver, $parent_id){
		$output = '';
		$query = $connect->prepare("SELECT *, COUNT(id) AS total FROM sent_emails WHERE receiver = ? AND parent_id = ?");
		$query->execute(array($receiver, $parent_id));
		$row = $query->fetch();
		if ($row) {
			$output = $row['total'];
		}
		return $output;
	}

	function currentLoan($connect, $borrower_id){
		$output = '';
		$query = $connect->prepare("SELECT * FROM `loans_table` WHERE borrower_id = ? ");
		$query->execute(array($borrower_id));
		$output = $query->rowCount();
		return $output;
	}

// ========== RECEIPT FUNCTIONS ================
	function getBorrowerAddress($connect, $borrower_id, $parent_id){
		$query = $connect->prepare("SELECT * FROM borrowers_details WHERE id = ? AND parent_id = ? ");
		$query->execute(array($borrower_id, $parent_id));
		$output = "";
		$row = $query->fetch();
		if($row){
			if ($row['borrower_email'] == "") {
				$email = '';
			}else{
				$email = $row['borrower_email'];
			}

			$output = '
				<address>
	                <strong>'.$row['borrower_firstname'].' '. $row['borrower_lastname'].'</strong><br>
	                '.nl2br($row['borrower_address']).'<br>
	                Phone: '.$row['borrower_phone'].'<br>
	                Email: '.$email.'
	              </address>
			';
		}
		return $output;
	}

	function createReceiptNumber($connect, $loan_number, $parent_id){
		$output = '';
		$query = $connect->prepare("SELECT * FROM `loan_payments` WHERE loan_number = ? AND parent_id = ? ");
		$query->execute(array($loan_number, $parent_id));
		$row = $query->fetch();
		if ($row) {
			$output = '
				<b>Item #'.rand(105, 15000).'</b><br>
                <br>
                <b>Last Payment:</b> '.date("d/m/Y", strtotime($row['paid_date'])).'<br>
                <b>Loan Number:</b> '.$row['loan_number'].'
			';
		}
		return $output;
	}

	function checkPaymentDate($connect, $loan_number, $parent_id) {
		$output = '';
		$query = $connect->prepare("SELECT * FROM `loan_schedules` WHERE loan_id = ? AND parent_id = ? ");
		$query->execute(array($loan_number, $parent_id));
		$row = $query->fetch();
		if($query->rowCount() > 0){
			if ($row) {
				$output = '
					<p class="lead text-fade">Next Date: '.date("d/m/Y", strtotime($row['date_due'])).'</p>
				';
			}
		}else{
			$output = '<p class="lead">Last Due Date: Soon </p>';
		}
		return $output;
	}

	function getTotalPaid($connect, $payment_id, $loan_number, $parent_id){
		$output = '';
		$sql = $connect->prepare("SELECT *, SUM(amount) AS total_paid FROM `loan_payments` WHERE id = ? AND loan_number = ? AND parent_id = ? ");
		$sql->execute(array($payment_id, $loan_number, $parent_id));
		if ($sql->rowCount() > 0) {
			$rows = $sql->fetch();
			if ($rows) {
				extract($rows);
				$output = $total_paid;
			}
		}else{
			$output = 0.00;
		}
		return $output;
	}

	function getTotalPaidForStatement($connect, $borrower_id, $loan_number, $parent_id){
		$output = '';
		$sql = $connect->prepare("SELECT *, SUM(amount) AS total_paid FROM `loan_payments` WHERE borrower_id = ? AND loan_number = ? AND parent_id = ? ");
		$sql->execute(array($borrower_id, $loan_number, $parent_id));
		if ($sql->rowCount() > 0) {
			$rows = $sql->fetch();
			if ($rows) {
				extract($rows);
				$output = $total_paid;
			}
		}else{
			$output = 0.00;
		}
		return $output;
	}

	function getTotalBalanceForStatement($connect, $borrower_id, $loan_number, $parent_id){
		$output = '';
		$sql = $connect->prepare("SELECT balance FROM `loan_payments` WHERE borrower_id = ? AND loan_number = ? AND parent_id = ? ORDER BY id DESC ");
		$sql->execute(array($borrower_id, $loan_number, $parent_id));
		if ($sql->rowCount() > 0) {
			$rows = $sql->fetch();
			if ($rows) {
				extract($rows);
				$output = $balance;
			}
		}else{
			$output = 0.00;
		}
		return $output;
	}

	function getTotalPayablePrinciple($connect, $parent_id, $loan_number){
		$output = '';
		$query = $connect->prepare("SELECT * FROM loans_table WHERE parent_id = ? AND loan_number = ? AND loan_status = 'Released' ");
		$query->execute(array($parent_id, $loan_number));
		$row = $query->fetch();
		if ($query->rowCount() > 0) {
			if ($row) {
				$output = $row['total_payable_amount'];
			}
		}else{
			$output = 0.00;
		}
		return $output;
	}

	function getCurrency2($connect, $parent_id, $loan_number){
		$output = '';
		$query = $connect->prepare("SELECT * FROM loans WHERE  parent_id = ? AND loan_number = ? AND loan_status = 'Released' ");
		$query->execute(array($parent_id, $loan_number));
		$row = $query->fetch();
		if ($query->rowCount() > 0) {
			if ($row) {
				$output = $row['currency'];
			}
		}else{
			$output = "";
		}
		return $output;
	}
// =============== end of recept function =========================
	function getIDNumberForGroupLeader($connect, $group_leader_id, $parent_id){
		$output = '';
		$query = $connect->prepare("SELECT * FROM borrowers WHERE id = ? AND parent_id = ? ");
		$query->execute(array($group_leader_id, $parent_id));
		$row = $query->fetch();
		if ($query->rowCount() > 0) {
			if ($row) {
				$output = $row['borrower_ID'];
			}
		}else{
			$output = "";
		}
		return $output;
	}

	function countLoans($connect, $borrower_id, $parent_id) {
		$output = '';
		$query = $connect->prepare("SELECT * FROM `loans_table` WHERE borrower_id = ? AND parent_id = ?");
		$query->execute(array($borrower_id, $parent_id));
		$output = $query->rowCount();
		return $output;
	}
#============================= ORGARNISATION INFORMATION =================================================
	function getOrganisationName($connect, $parent_id) {
		$output = '';
		$query = $connect->prepare("SELECT * FROM organisations WHERE parent_id = ? ");
		$query->execute(array($parent_id));
		if($query->rowCount() > 0){
			$row = $query->fetch();
			if ($row) {
				$output = $row['organisation_name'];
			}
		}else{
			$output = "<a href='members/settings'>Organisation Name</a>";
		}

		return $output;
	}

	function getOrganisationLogo($connect, $parent_id) {
		$output = '';
		$query = $connect->prepare("SELECT * FROM organisations WHERE parent_id = ? ");
		$query->execute(array($parent_id));
		if($query->rowCount() > 0){
			$row = $query->fetch();
			if ($row) {
				$output = '
					<img src="https://loans.chumasolutions.com/members/adminphotos/'.$row['org_logo'].'" alt="'.$row['org_logo'].'" class="img-fluid img-responsive" width="130">
				';
			}
		}else{
			$output = "<a href='members/settings'>Organisation Logo</a>";
		}

		return $output;
	}

	function getOrganisationHeaderDetails($connect, $parent_id) {
		$output = '';
		$query = $connect->prepare("SELECT * FROM organisations WHERE parent_id = ? ");
		$query->execute(array($parent_id));
		if($query->rowCount() > 0){
			$row = $query->fetch();
			if ($row) {
				$output = '
					<img src="https://loans.chumasolutions.com/members/adminphotos/'.$row['org_logo'].'" alt="'.$row['org_logo'].'" class="img-fluid img-responsive" width="130">
                    <address>
                        <strong>'.$row['organisation_name'] .'</strong><br>
                        '.nl2br($row['hq_address']) .'<br>
                        
                    </address>
				';
			}
		}else{
			$output = "<a href='members/settings'>Organisation Name</a>";
		}

		return $output;
	}

	function getOrganisationAddressDetailsForPDF($connect, $parent_id) {
		$output = '';
		$query = $connect->prepare("SELECT * FROM organisations WHERE parent_id = ? ");
		$query->execute(array($parent_id));
		if($query->rowCount() > 0){
			$row = $query->fetch();
			if ($row) {
				$output = '
                    <address>
                        '.nl2br($row['hq_address']) .'<br>
                    </address>
				';
			}
		}else{
			$output = "<a href='members/settings'>Organisation Name</a>";
		}

		return $output;
	}

	function getOrganisationLogoDetailsForPDF($connect, $parent_id) {
		$output = '';
		$query = $connect->prepare("SELECT * FROM organisations WHERE parent_id = ? ");
		$query->execute(array($parent_id));
		if($query->rowCount() > 0){
			$row = $query->fetch();
			if ($row) {
				$output = '
                    https://loans.chumasolutions.com/members/adminphotos/'.$row['org_logo'].'
				';
			}
		}else{
			$output = "https://weblister.co/images/icon_new.png";
		}

		return $output;
	}

	function getOrganisationFooterDetails($connect, $parent_id) {
		$output = '';
		$query = $connect->prepare("SELECT * FROM organisations WHERE parent_id = ? ");
		$query->execute(array($parent_id));
		if($query->rowCount() > 0){
			$row = $query->fetch();
			if ($row) {
				$output = '
					<div class="mailFooter" style="text-align:center; padding:28px;margin-bottom:20px;">
							<p style="font-size:14px;">Mobile: '.$row['hq_phone'].'</p>
							<p style="font-size:14px;">Address: '.$row['hq_address'].'</p>
							<h2 class="title3" style="color:green">'.ucwords($row['organisation_name']).'</h2>
							&copy; '.ucwords($row['organisation_name']).'
						</div>;
				';
			}
		}else{
			$output = "<a href='members/settings'>Organisation Name</a>";
		}

		return $output;
	}

#============================= END OF ORGARNISATION INFORMATION =================================================


	function getFundedClient($connect, $id_number){
		$output = '';
		$query = $connect->prepare("SELECT * FROM clients_in_need WHERE id_number = ? ");
		$query->execute(array($id_number));
		$row = $query->fetch();
		if ($row) {
			$output = $row['firstname'] . ' '. $row['lastname'];
		}
		return $output;
	}
	// ========= FRONT PAGEs ========================
	function countBorrowers ($connect, $parent_id, $branch_id) {
		$output = '';
		$query 	= $connect->prepare("SELECT * FROM borrowers_details WHERE parent_id = ? AND branch_id = ? AND display = '1' ");
		$query->execute(array($parent_id, $branch_id));
		$output = $query->rowCount();
		return $output;
	}

	// function countIncomeSources($connect, $parent_id){
	// 	$query = $connect->prepare("SELECT * FROM income_table WHERE parent_id = ? ");
	// 	$query->execute(array($_SESSION['parent_id']));
	// 	$output = $query->rowCount();
	// 	return $output;
	// }
	function countIncomeSources($connect, $parent_id){
		$query = $connect->prepare("SELECT * FROM collected_funds WHERE parent_id = ? ");
		$query->execute(array($_SESSION['parent_id']));
		$output = $query->rowCount();
		return $output;
	}


	function countAllLoans($connect, $parent_id){
		$query = $connect->prepare("SELECT * FROM loans_table WHERE parent_id = ?  ");
		$query->execute(array($parent_id));
		$output = $query->rowCount();
		return $output;
	}

	function countAllMembers($connect, $parent_id){
		$query = $connect->prepare("SELECT * FROM admins WHERE parent_id = ?  ");
		$query->execute(array($_SESSION['parent_id']));
		$output = $query->rowCount();
		return $output;
	}

#========================= PROJECTS FUNCTIONS ===============================
	function calculateSpentBudget($connect, $project_id, $parent_id){
		$output = '';
		$query = $connect->prepare("SELECT SUM(spent_budget) AS total_budget FROM `projectMilestone` WHERE parent_id = ? AND projectID = ? ");
		$query->execute(array($parent_id, $project_id));
		$row = $query->fetch();
		if ($row) {
			$output = $row['total_budget'];
		}
		return $output;
	}

	function getOtherTeamMembers($connect, $parent_id) {
		$output = '
				<table class="cell-table">
		';
		$query = $connect->prepare("SELECT * FROM admins WHERE parent_id = ? ");
	  	$query->execute(array($parent_id));
	  	if ($query->rowCount() > 0) {
	  		foreach ($query->fetchAll() as $row) {
	  			extract($row);
	  			
	  			$query2 = $connect->prepare("SELECT * FROM projects_team_members WHERE team_member = ? AND parent_id = ? ");
				$query2->execute(array($id, $parent_id));
				
				if($query2->rowCount() > 0){
					$roq = $query2->fetch();
					if($roq){
						$member_id = $roq['team_member'];
						if ($id == $member_id) {
						 	$output .= '
						 		<tr>
									<th>
									
						 				<label><input type="checkbox" name="team_members[]" id="team_members" value="'.$member_id.'" checked> '.getStaffMemberNames($connect, $member_id, $parent_id) .'</label>  
						 			</th>
						 			<td><a href="'.$member_id.'" class="text-danger removeMember"><i class="bi bi-trash"></i></a></td>
								</tr>
						 	';
						} 
					}
				}else{
					$output .= '<tr>
									<th>
										<label><input type="checkbox" name="team_members[]" id="team_members" value="'.$id.'" > '.getStaffMemberNames($connect, $id, $parent_id) .'</label>
									</th>
									<td></td>
								</tr>
								';
				}
			}
	  		$output .= '</table>';
	  	}else{
	  		$output .= '</table>';
	  	 
	  	}
	  	return $output;
	}

//================= SMS FUNCTIONS =====================

	function getSenderID($connect, $parent_id) {
		$output = '';
		$query = $connect->prepare("SELECT * FROM `sms_settings` WHERE parent_id = ?");
		$query->execute(array($parent_id));
		$row = $query->fetch();
		if($query->rowCount() > 0){
			if ($row) {
				extract($row);
				$output = $sender_id;
			}
		}else{
			$output = '<a href="members/sms-create-sender-id" class="btn btn-primary">Create Sender ID</a>';
		}
		return $output;
	}

	function getParentIBByBorroweresPhone($connect, $borrower_phone) {
		$output = '';
		$query = $connect->prepare("SELECT * FROM `borrowers`  WHERE borrower_phone = ?");
		$query->execute(array($borrower_phone));
		$row = $query->fetch();
		if ($row) {
			extract($row);
			$output = $parent_id;
		}
		return $output;;
	}

	function getCurrency($connect, $parent_id){
		$output = '';
		$query = $connect->prepare("SELECT * FROM `branches` WHERE member_id = ?");
		$query->execute(array($parent_id));
		$row = $query->fetch();
		if ($row) {
			extract($row);
			$output = $currency;
		}
		return $output;
	}



# ===================== PAYROLL ===============================
function getAllowedBranches($connect, $parent_id, $staff_id) {
	$output = '';
	$query = $connect->prepare("SELECT * FROM branches WHERE member_id = ? ");
  	$query->execute(array($parent_id));
  	if ($query->rowCount() > 0) {
  		foreach ($query->fetchAll() as $row) {
  			extract($row);
  			
  			$query2 = $connect->prepare("SELECT * FROM allowed_branches WHERE staff_id = ? AND branch_id = ? AND parent_id = ? ");
			$query2->execute(array($staff_id, $id, $parent_id));
			
			if($query2->rowCount() > 0){
				$roq = $query2->fetch();
				if($roq){
					$branch_id = $roq['branch_id'];
					if ($id == $branch_id) {
					 	$output .= '
					 		<tr>
								<th>
								
					 				<label><input type="checkbox" name="branchID[]" id="branchID" value="'.$roq['branch_id'].'" checked> '.branchName($connect, $parent_id, $roq['branch_id']) .'</label>  
					 			</th>
					 			<td><a href="'.$roq['branch_id'].'" class="text-danger removeMember"><i class="bi bi-trash"></i></a></td>
							</tr>
					 	';
					}else{
						
					} 
				}
			}else{
				$output .= '<tr>
								<th>
									<label><input type="checkbox" name="team_members[]" id="team_members" value="'.$id.'" > '.branchName($connect, $parent_id, $id) .'</label>
								</th>
								<td></td>
							</tr>
							';
			}
		}
  		
  	}else{
  		
  	 
  	}
  	return $output;
}


function getTotalAmountOwed($connect, $borrower_id, $loan_number){
	$query = $connect->prepare("SELECT * FROM loans_table WHERE borrower_id = ? AND loan_number = ? AND loan_status = 'Released'  ");
	$query->execute(array($borrower_id, $loan_number));

	$output = '';
	$row = $query->fetch();
	if ($row) {
		extract($row);
		$output = $total_payable_amount;
	}
	return $output;
}

function getTotalAmountPaid($connect, $borrower_id, $loan_number){
	$output = "";
	$sql = $connect->prepare("SELECT SUM(amount) AS total_paid FROM `loan_payments` WHERE borrower_id = ? AND loan_number = ? ");
	$sql->execute(array($borrower_id, $loan_number));

	$row = $sql->fetch();
	if ($row) {
		extract($row);
		$output = $total_paid;
	}
	return $output;
}

	
?>

