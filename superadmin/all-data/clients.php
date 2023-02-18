<?php 
  	require ("../../includes/db.php");
	  require ("../addons/tip.php");
  	$country = $phone = '';
	$query = $connect->prepare("SELECT * FROM currencies");
	$query->execute();
	foreach ($query->fetchAll() as $row) {
	    $country .= '<option value="'.$row['id'].'">'.$row['country'].'</option>';
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>View Borrowers of <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], base64_decode($_COOKIE['SelectedBranch'])))?></title>
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
                    <div class="table-responsive">
                        <table class="table table-bordered" id="clientsTable">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Names</th>
									<th>NRC</th>
                                    <th>Branch</th>
                                    <th>Loan balance</th>
                                    <th>More Data</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $query = $connect->prepare("SELECT * FROM borrowers_details WHERE  parent_id = ?");
                                $query->execute([$_SESSION['parent_id']]);
                                foreach($query->fetchAll() as $row){
                                    extract($row); 
                                          
                            ?>
                                  <tr>
                                    <td><img src="<?php echo getClientsImage($connect, $borrower_id) ?>" alt="<?php echo $borrower_photo ?>" class="img-fluid img-responsive img-circle" style="width: 30px;height:30px;border: radius 50%;"></td>
									<td><?php echo $borrower_firstname?> <?php echo $borrower_lastname?></td>
                                    <td><?php echo $borrower_id?> </td>
                                    <td><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id))?>Branch</td>
                                    <td><?php echo checkifBorrowerAppliedForALoan($connect, $parent_id, $borrower_id)?></td>
                                    <td><a href="<?php echo $borrower_id?>" class="btn btn-secondary btn-sm more_details" id="<?php echo $borrower_firstname?>"><i class="bi bi-box"></i> More Details</a></td>
                            <?php
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Details Modal -->
            <div class="modal fade" id="clientsModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title"><span id="titleState"> </span></h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        
                        <div class="modal-body">
                            <div id="displayData"></div>
                        </div> 
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            
                        </div>
                    
                    </div>
                </div>
            </div>
            <!-- End of details Modal -->
            <!-- Document Modal -->
            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="docsOwner"></h4>
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
            <!-- End of documents modal -->
        </section>
	</div>

	<?php include("../addon_footer.php")?>
	<script>
        $("#clientsTable").DataTable();
        $(document).on('click', '.more_details', function(e){
            e.preventDefault();
            var client_id = $(this).attr("href");
            var clients_name = $(this).attr('id');
            document.getElementById('titleState').innerText = 'Details for '+clients_name;
            $.ajax({
                url:'all-data/parsers/fetchClientsDetails',
                method:'post',
                data:{client_id:client_id},
                success:function(data){
                    $("#clientsModal").modal("show");
                    $("#displayData").html(data);
                }
            })
        })

        $(document).on('click', '.view_files', function(e){
            e.preventDefault();
            $("#modal-default").modal("show");
            var borrower_id = $(this).attr('href');
            var clients_names = $(this).attr("id");
            document.getElementById('docsOwner').innerHTML = 'Docs for '+ clients_names;
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