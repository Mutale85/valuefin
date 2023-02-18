<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
    if (isset($_GET['phonenumber']) && isset($_GET['username'])) {
        $phonenumber = $_GET['phonenumber'];
        $username   = $_GET['username'];
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Send SMS to borrowers</title>
	<?php include("../addon_header.php");?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light p-3">     					
			<div class="container-fluid">
                <div class="row ">
                    <?php 
                        $query = $connect->prepare("SELECT * FROM sms WHERE parent_id = ? ");
                        $query->execute(array($_SESSION['parent_id']));
                        $count = $query->rowCount();
                        if ($count > 0) {
                            $row = $query->fetch();
                            $all = 500;
                            $remaining = "Remaining SMS: ". ($all - $count);
                            
                        }else{
                            $remaining = "Remaining SMS: 500 ";
                        }
                    ?>
                    <div class="col-md-12 d-flex justify-content-between mt-5 border-bottom border-primary">
                        <h4><?php echo $remaining?></h4>
                    </div>
                </div>
            </div>
      			<!-- borrower form -->
            <div class="container-fluid pt-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-5">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo ucwords($username)?></h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php 
                                        $phonenumber = base64_decode($phonenumber);
                                        $query = $connect->prepare("SELECT * FROM `sms` WHERE `receiver` = ? ");
                                        $query->execute([$phonenumber]);
                                        $count = $query->rowCount();
                                        // if ($count > 0) {
                                        foreach ($query->fetchAll() as $row) {
                                            extract($row);
                                    ?>

                                            <div class="col-md-4">
                                                <div class="card mb-4 border border-primary shadow text-dark">
                                                    <div class="card-body">
                                                        <p> <?php echo $message?><p>
                                                    </div>
                                                    <div class="card-footer">
                                                        <em><i class="bi bi-clock-history"></i> <?php echo time_ago_check($date_sent)?></em>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                        //echo $output;
                                        // }else{
                                        //     echo "<p class='text-center'>You have not sent any SMS to ".$username." </p>";
                                            
                                        // }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
	</div>
	<?php include("../addon_footer.php")?>
	<script>
		$(document).ready( function () {
		    $('#myTable').DataTable();
		});

	</script>
</body>
</html>