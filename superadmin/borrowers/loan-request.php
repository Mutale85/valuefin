<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
  	if(isset($_GET['client-id'])){
        $borrower_id = base64_decode($_GET['client-id']);
    }else{
        //return to main page
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Pending Loans at <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?></title>
	<?php include("../addon_header.php");?>
</head>

<body class="layout-fixed">
	<?php include("../addon_top_min_nav.php")?>
  	<?php include("../addon_side_nav.php")?>
	<div class="content-wrapper">
		<?php include("../addon_content_header.php")?>
        <section class="content bg-light p-2">
            <div class="container-fluid">
                <div class="row">
                    
                    <div class="col-md-6">
                        <?php 
                            $query = $connect->prepare("SELECT * FROM borrowers_details WHERE borrower_id = ?");
                            $query->execute([$borrower_id]);
                            $row = $query->fetch();
                            extract($row);
                        ?>
						<div class="bg-light p-1">
							<div class="card card-primary card-outline">
								<div class="card-header">
									<h4  class="card-title"><?php echo getBorrowerFullNamesByCardId($connect, $borrower_id) ?>'s Profile</h4>
								</div>
								<div class="card-body box-profile">
									<?php echo getClientsDetails($connect, $borrower_id)?>
									<div class="border-top border-dark mt-4 mb-4"></div>
									<h4 class="text-secondary"><span id="working">Business Details</span></h4>

									<?php echo getBusinessDetails($connect, $borrower_id)?>

									<div class="border-top border-dark mt-4 mb-4"></div>
									<h4 class="text-secondary"><span id="next_of_kin">Next of Kin</span></h4>
									<?php echo getNextofKinDetails($connect, $borrower_id)?>
									<div class="border-top border-dark mt-4 mb-4"></div>
									<h4 class="text-secondary"><span id="next_of_kin">Documents</span></h4>
                                    <a href="<?php echo $borrower_id?>" class="view_files">Click to view NRC</a>
								</div>
								
							</div>
						</div>
					</div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>
            
        </section>
        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo getBorrowerFullNamesByCardId($connect, $borrower_id) ?>'s NRC</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="nrcdocs"></div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
	</div>

	<?php include("../addon_footer.php")?>
	<script>
        $(document).on('click', '.view_files', function(e){
            e.preventDefault();
            $("#modal-default").modal("show");
            var borrower_id = $(this).attr('href');
            $.ajax({
                url:'borrowers/loans/viewNRC',
                method:'post',
                data:{borrower_id:borrower_id},
                success:function(data){
                    $("#nrcdocs").html(data);
                }
            })
        });
    </script>
</body>
</html>