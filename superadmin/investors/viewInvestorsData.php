<?php
	include '../../includes/db.php';
	
	extract($_POST);
	if (!empty($investor_id)) {
		$query = $connect->prepare("SELECT * FROM investors WHERE id = ?");
		$query->execute(array(base64_decode($investor_id)));
		$row = $query->fetch();
		extract($row);
        if ($photo == "") {
            $photo 	= 'dist/img/user2-160x160.jpg';
        }else{
            $photo 	= 'investors/uploads/'.$photo;
        }
?>
    <div class="text-center">
		<img src="<?php echo $photo?>"  class="profile-user-img img-fluid img-circle" alt="pic" style="width: 120px; height: 120px;">
	</div>
	<h3 class="profile-username text-center"><span></span> <?php echo $title?> <?php echo $firstname ?> <?php echo $lastname ?> </h3>
	<p class="text-muted text-center"><span id="city"></span></p>
	<ul class="list-group list-group-unbordered mb-3">
		<li class="list-group-item">
			<b>Gender</b> <a class="float-right" ><?php echo $gender?></a>
		</li>
		<li class="list-group-item">
			<b>ID Type & No. </b> <a class="float-right" ><?php echo $id_type?> | <?php echo $id_number?></a>
		</li>
		<li class="list-group-item">
			<b>Phonenumber</b> <a class="float-right"><?php echo $phone ?></a>
		</li>
        <li class="list-group-item">
			<b>Email</b> <a class="float-right"><?php echo $email?></a>
		</li>
		
		<li class="list-group-item">
			<b> Address</b> <a class="float-right"><?php echo $address?>, <?php echo getCountryName($connect, $investor_country) ?></a>
		</li>
		<li class="list-group-item">
			<b>Amount </b> <a class="float-right">ZMW <?php echo $amount?></a>
		</li>
		<li class="list-group-item">
			<b>Equity </b> <a class="float-right"> <?php echo $equity?> <i class="bi bi-percent"></i></a>
		</li>
		
	</ul>
<?php

	}
	
	
	
?>