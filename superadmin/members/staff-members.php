<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
	$option = $countries = '';
	$query = $connect->prepare("SELECT * FROM currencies");
	$query->execute();
	foreach ($query->fetchAll() as $row) {
		$option .= '<option value="'.$row['code'].'">'.$row['code'].'</option>';
		$countries .= '<option value="'.$row['id'].'">'.$row['country'].'</option>';
	}
	$branch_options = "";
	$sql = $connect->prepare("SELECT * FROM branches WHERE member_id = ?");
	$sql->execute(array($_SESSION['parent_id']));
	foreach ($sql->fetchAll() as $row) {
		$branch_options .= '<option value="'.$row['id'].'">'.$row['branch_name'].'</option>';
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>All Personnel</title>
	<?php include("../addon_header.php");?>
	<style>
		img.img-rounded {
			border-radius: 50%;
		}
	</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light">
			<div class="container-fluid border-top pt-3 gridViewDiv">
				<div class="row">
					<div class="col-md-12 mb-4">
						<a href="members/add-staff-members" class="btn btn-primary">Add new staff</a>
					</div>
					<?php
						$parent_id = $_SESSION['parent_id'];
						$query = $connect->prepare("SELECT * FROM admins WHERE parent_id = ? ");
						$query->execute([$parent_id]);
						if ($query->rowCount() > 0) {
							foreach ($query->fetchAll() as $rows) {
								extract($rows);
								$photo 	= 'dist/img/user2-160x160.jpg';
								
								$staff_id 	= $id;
								if ($user_role == 'superAdmin') {
									if($activate == '0'){
										$access = '<a href="'. $staff_id.'" class="nav-link allow_access ">Access  <span class="float-right badge bg-success"> Allow Access</span></a>';
									}elseif($activate == '1'){
										$access = '<a href="'. $staff_id.'" class="nav-link deny_access">Access  <span class="float-right badge bg-danger"> Deny Access</span></a>';
									}
								}
								
								if ($user_role == 'superAdmin') {
									$btn = '<li class="nav-item"><a href="members/staff-member-edit?staff_id='.base64_encode($staff_id).'" class="nav-link staff-member-edit" data-id="'.$id.'">Edit <span class="float-right badge bg-primary">Edit  <i class="bi bi-pencil-square"></i> </span></a></li>';
									$title = 'Super Admin';
								}else{
									$btn = '<li class="nav-item">
										<a href="members/staff-member-edit?staff_id='.base64_encode($staff_id).'" class="nav-link staff-member-edit" data-id="'.$id.'"> Edit <span class="float-right badge bg-primary">Edit <i class="bi bi-pencil-square"></i> </span></a></li>

										<li><a href="" class="nav-link deleteAdmin" data-id="'.$staff_id.'"> Remove  <span class="float-right badge bg-danger"> Remove <i class="bi bi-trash"></i> </span></a></li>
									';
									$title = $user_role;
								}
							?>
						
							<div class="col-md-4">
								<div class="card card-widget widget-user-2">
									<div class="widget-user-header bg-warning">
										<div class="widget-user-image">
											<img class="img-circle elevation-2" src="<?php echo $photo?>" alt="<?php echo $photo?>" style="width:70px; height:70px; border-radius: 50%">
										</div>
										<h3 class="mr-4 widget-user-username"><?php echo $firstname; ?> <?php echo $lastname; ?></h3>
										<h5 class="mr-4 widget-user-desc"><?php echo $title?></h5>
									</div>
									<div class="card-footer p-0">

										<ul class="nav flex-column">
											<?php echo allowedBranches($connect, $staff_id, $parent_id)?>
											<li class="nav-item">
												<a href="javascript:void(0)" class="nav-link">
													Phone <span class="float-right badge bg-secondary"><?php echo $phonenumber?></span>
												</a>
											</li>
											<li class="nav-item">
												<a href="javascript:void(0)" class="nav-link">
													Email <span class="float-right badge bg-primary"><?php echo strtolower($email)?></span>
												</a>
											</li>
											
											<?php if ($user_role == 'superAdmin'):?>
											<?php else:?>
												<?php echo $access ?>
											<?php endif;?>

											<?php if ($user_role != 'superAdmin'):?>
											<?php else:?>
												<?php echo $btn?>
											<?php endif;?>

											<li class="nav-item">
												<a href="<?php echo $user_role?>" class="nav-link callForm" id="<?php echo $staff_id?>">
													View More Data <span class="float-right badge bg-success"><i class="bi bi-person-plus"></i> More Info</span>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
					<?php } }?>
				</div>
			</div>
			<!-- Modal -->
			<div class="modal fade" id="moreInfoModal">
				<div class="modal-dialog modal-xl">
					<div class="modal-content">
						<form method="post" id="moreInfoForm" enctype="multipart/form-data">
							<div class="modal-header">
								<h4 class="modal-title">Add More Info <i class="bi bi-person-plus"></i></h4>
								<button type="button" class="close" data-dismiss="modal" onclick="clearForm()" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">  									
								<div id="moreData"></div>
							</div>
							<div class="modal-footer justify-content-between">
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- End of Modal -->
		</section>
	</div>
	<?php include("../addon_footer.php")?>
	
	<script>
		function clearForm(){
			$("#moreInfoForm")[0].reset();
		}
		$(document).ready( function () {
		
			$(document).on('click',  ".callForm", function(e){
				e.preventDefault();
				var staff_role = $(this).attr("href");
				var staff_id = $(this).attr("id");
				$("#moreInfoModal").modal("show");
				$.ajax({
					url:"members/parsers/getmoreData",
					method:"post",
					data:{staff_id:staff_id, staff_role:staff_role},
					success:function(data){
						$("#moreData").html(data);
					}
				})
			})

			// submit the form
			// $("#moreInfoForm").submit(function(e){
			// 	e.preventDefault();
			// 	var moreInfoForm = document.getElementById('moreInfoForm');
			// 	var data = new FormData(moreInfoForm);
			// 	var url = 'members/members-info-submit';
			// 	$.ajax({
			// 		url:url+'?<?php echo time()?>',
			// 		method:"post",
			// 		data:data,
			// 		cache : false,
			// 		processData: false,
			// 		contentType: false,
			// 		beforeSend:function(){
			// 			$("#allBtn").html("<i class='fa fa-spinner fa-spin'></i>");
			// 			$("#allBtn").attr("disabled", "disabled");
			// 		},
			// 		success:function(data){
			// 			successNow(data);
			// 			$("#allBtn").html("Save Changes");
			// 			$("#allBtn").removeAttr("disabled");
			// 		}
			// 	})
			// })


		});

	$(document).on("click", ".deleteAdmin", function(e){
		e.preventDefault();
		var delete_admin_id = $(this).data("id");
		if(confirm("Confirm you wish to remove staff member / employee")){
			$.ajax({
				url:"members/parsers/edit",
				method:"post",
				data:{delete_admin_id:delete_admin_id},
				
				success:function(data){
					if (data === 'done') {
						successNow("Staff Deleted");
						setTimeout(function(){
							location.reload();
						}, 2000);
					}else{
						errorNow(data);
					}
				}
			})
		}else{
			return false;
		}
	})

	$(document).on("click", ".allow_access", function(e){
		e.preventDefault();
		var allow_access_id = $(this).attr("href");
		if(confirm("Confirm giving permisions to access the system?")){
			$.ajax({
				url:"members/parsers/edit",
				method:"post",
				data:{allow_access_id:allow_access_id},
				beforeSend:function(){

				},
				success:function(data){
					if (data === 'done') {
						successNow("Staff Permitted to log in to the system");
						setTimeout(function(){
							location.reload();
						}, 2000);
					}else{
						errorNow(data);
					}
				}
			})
		}else{
			return false;
		}
	})

	$(document).on("click", ".deny_access", function(e){
		e.preventDefault();
		var deny_access_id = $(this).attr("href");
		if(confirm("Confirm revoking permisions to access the system?")){
			$.ajax({
				url:"members/parsers/edit",
				method:"post",
				data:{deny_access_id:deny_access_id},
				beforeSend:function(){

				},
				success:function(data){
					if (data === 'done') {
						successNow("Staff has been banned from having access to the system !");
						setTimeout(function(){
							location.reload();
						}, 2000);
					}else{
						errorNow(data);
					}
				}
			})
		}else{
			return false;
		}
	})

</script>
</body>
</html>