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
	<title>Companye Details</title>
	<?php include("../addon_header.php");?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light">
			<div class="container-fluid border-top pt-3">
				<div class="row">
					<div class="col-md-6">
						<div class="card card-info">
						<div class="card-header">
							<h4 class="card-title">Organization Form</h4>
							<div class="card-tools">
							<button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
							<button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
								<i class="fas fa-times"></i>
							</button>
							</div>
						</div>
						<div class="card-body">
							<form method="post" id="orgForm" enctype="multipart/form-data">
							<div class="form-group">
								<label>Logo</label>
								<input type="file" name="org_logo" name="org_logo" class="form-controls" accept="image/png, image/jpg, image/jpeg">
								<!-- <input type="" name=""> -->
							</div>
							<div class="form-group">
								<label>Organization Name</label>
								<input type="text" name="organisation_name" id="organisation_name" class="form-control">
								<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
								<input type="hidden" name="ID" id="ID" value="">
							</div>
							<div class="form-group">
								<label>HQ Email</label>
								<input type="text" name="admin_email" id="admin_email" class="form-control" value="<?php echo $_SESSION['email']?>" required>
								<!-- <input type="text" name="admin_password" id="admin_password" class="form-control" value="<?php echo $_SESSION['password']?>"> -->
							</div>
							<div class="form-group">
								<label>HQ Phone</label>
								<input type="text" name="hq_phone" id="hq_phone" class="form-control" value="" required>
							</div>
							<div class="form-group">
								<label>HQ Address</label>
								<textarea name="hq_address" id="hq_address" class="form-control" rows="2" required></textarea>
							</div>
							<button class="btn btn-info shadow" type="submit" id="submit">Submit</button>
							</form>
						</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card card-info">
						<div class="card-header">
							<h4 class="card-title">Organization</h4>
							<div class="card-tools">
							<button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
							<i class="fas fa-minus"></i>
							</button>
							<button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
							<i class="fas fa-times"></i>
							</button>
							</div>
						</div>
						<div class="card-body">
							<?php
							$query = $connect->prepare("SELECT * FROM `organisations` WHERE parent_id = ? ");
							$query->execute(array($_SESSION['parent_id']));
							if ($query->rowCount() > 0) {
								$row = $query->fetch();
								if ($row) {?>
								<img src="members/uploads/<?php echo $row['org_logo']?>" alt="<?php echo $row['org_logo']?>" class="img-fluid img-responsive" width="80">
								<address>
									<strong><?php echo $row['organisation_name'] ?></strong><br>
									<?php echo nl2br($row['hq_address']) ?><br>
									
									Phone: <?php echo $row['hq_phone']?><br>
									Email: <?php echo $row['admin_email']?>
									</address>
										<a href="" class="editData" data-id="<?php echo $row['id']?>">Edit</a>
							<?php }
							}else{
								echo '<h4>Add Organization\'s Data</h4>';
							}
							?>
						</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<?php include("../addon_footer.php")?>
	<script>
		$(document).on("submit", "#orgForm", function(e){
		      e.preventDefault();
		      var data = document.getElementById('orgForm');
		      var formData = new FormData(data);
		      $.ajax({
		        url:"members/parsers/submitOrgData",
		        method:'POST',
		        data: formData,
		        cache : false,
		        processData: false,
		        contentType: false,
		        beforeSend:function(){
		          	$("#submit").html('<i class="fa fa-spinner fa-spin"></i>');
		        },
		        success:function(data){
		          if (data === 'done') {
		            successNow("Organization Information Posted");
		            setTimeout(function(){
		              location.reload();
		            }, 1500);
		          }else if (data === 'updated') {
		            successNow("Organization Information Updated");
		            setTimeout(function(){
		              location.reload();
		            }, 1500);
		          }else{
		            errorNow(data);
		          }
		        }
		      })
		})

    	$(document).on("click", ".editData", function(e){
      		e.preventDefault();
      		var organisation_id = $(this).data("id");
	      	$.ajax({
		        url:"members/parsers/edit",
		        method:"post",
		        data:{organisation_id:organisation_id},
		        dataType:"JSON",
	        	success:function(data){
					$("#org_logo").val(data.org_logo);
					$("#organisation_name").val(data.organisation_name);
					$("#admin_email").val(data.admin_email);
					$("#hq_phone").val(data.hq_phone);
					$("#hq_address").val(data.hq_address);
					$("#ID").val(data.id);
	        	}
      		})
    	})
    </script>
</body>
</html>