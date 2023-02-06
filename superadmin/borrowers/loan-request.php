<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
  	if(isset($_GET['client-id'])){
        $borrower_id = base64_decode($_GET['client-id']);
        $application_id = base64_decode($_GET['application_id']);
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
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
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
                        <div class="bg-light p-1">
							<div class="card card-primary card-outline">
								<div class="card-header">
									<h4  class="card-title"><?php echo getBorrowerFullNamesByCardId($connect, $borrower_id) ?>'s Application</h4>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
								<div class="card-body box-profile">
									<?php 
                                        $query = $connect->prepare("SELECT * FROM loan_applications WHERE id = ? AND applicant_id = ? ");
                                        $query->execute([$application_id, $borrower_id]);
                                        $row = $query->fetch();
                                        extract($row);
                                    ?>
									<h4 class="text-secondary"><span id="working">Alternative Contact Details</span></h4>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 35%;">Fullnames</th>
                                            <td style="width: 65%" align="right"><?php echo $alt_contact_names?></td>
                                        </tr>
                                        <tr>
                                            <th style="width: 35%;">Phone number</th>
                                            <td style="width: 65%" align="right"><?php echo $alt_contact_phone?></td>
                                        </tr>
                                        <tr>
                                            <th style="width: 35%;">Relationship</th>
                                            <td style="width: 65%" align="right"><?php echo $alt_contact_relationship?></td>
                                        </tr>
                                    </table>
                                    
									<div class="border-top border-dark mt-4 mb-4"></div>
                                    <h4 class="text-secondary"><span id="working">Loan Request Details</span></h4>
									<table class="table table-bordered">
                                        <tr>
                                            <th style="width: 35%;">Application Date</th>
                                            <td style="width: 65%" align="right"><?php echo date("j F, Y", strtotime($date_submitted))?></td>
                                        </tr>
                                        <tr>
                                            <th style="width: 35%;">Requested Amount</th>
                                            <td style="width: 65%" align="right"><?php echo $currency?> <?php echo $principle_amount?></td>
                                        </tr>
                                        <tr>
                                            <th style="width: 35%;">Interest Rate</th>
                                            <td style="width: 65%" align="right"> <?php echo $interest?>% / Month</td>
                                        </tr>
                                        <tr>
                                            <th style="width: 35%;">Gross Loan</th>
                                            <td style="width: 65%" align="right"><?php echo $currency?> <?php echo $total_loan_amount?></td>
                                        </tr>
                                        <tr>
                                            <th style="width: 35%;">Processing Fee</th>
                                            <td style="width: 65%" align="right"><?php echo $currency?> <?php echo $loan_processing_fee?></td>
                                        </tr>
                                        <tr>
                                            <th style="width: 35%;">Net Loan Amount</th>
                                            <td style="width: 65%" align="right"><?php echo $currency?> <?php echo $net_loan?></td>
                                        </tr>
                                        <tr>
                                            <th style="width: 35%;">Period</th>
                                            <td style="width: 65%" align="right"><?php echo $days?> Days | <?php echo $weeks?> Weeks</td>
                                        </tr>
                                    </table>
                                    <div class="border-top border-dark mt-4 mb-4"></div>
                                    <h4 class="text-secondary"><span id="working">Repayment Details</span></h4>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 35%;">Repayment Start Date</th>
                                            <td style="width: 65%" align="right"><?php echo date("j F, Y", strtotime($repayment_start_date))?> </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 35%;">Daily Amount</th>
                                            <td style="width: 65%" align="right"> <?php echo $currency?> <?php echo $repayment_amount_daily?> </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 35%;">Weekly Amount</th>
                                            <td style="width: 65%" align="right"> <?php echo $currency?> <?php echo $repayment_amount_weekly?> </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 35%;">Monthly Amount</th>
                                            <td style="width: 65%" align="right"> <?php echo $currency?> <?php echo $repayment_amount_month?> </td>
                                        </tr>
                                    </table>
                                    <div class="mt-4 mb-4">
                                        <?php if($status === 'pending'):?>
                                            <a href="<?php echo $borrower_id?>" id="<?php echo $id?>" data-amount="<?php echo $principle_amount?>" class="approveLoan btn btn-success">Approve</a>
                                            <a href="<?php echo $borrower_id?>" id="<?php echo $id?>" data-amount="<?php echo $principle_amount?>" class="rejectLoan btn btn-danger">Reject</a>
                                        <?php elseif($status === 'approved'):?>
                                            <button class="btn btn-success" type="button">Loan <?php echo ucwords($status)?></button>
                                        <?php else:?>
                                            <button class="btn btn-danger" type="button">Loan <?php echo ucwords($status)?></button>
                                        <?php endif;?>
                                    </div>
								</div>
								
							</div>
						</div>
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

        <!-- Approve Loan -->
        <div class="modal fade" id="approveModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"><span id="titleState"> Approve </span> loan for <?php echo getBorrowerFullNamesByCardId($connect, $borrower_id) ?></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="approveForm">
                        <div class="modal-body">
                            
                            <label>Add remarks <small class="text-danger">(required)</small></label>
                            <textarea name="comment" id="comment" rows="4" class="form-control" required></textarea>
                            <input type="hidden" name="borrower_id" id="borrower_id">
                            <input type="hidden" name="loan_id" id="loan_id">
                            <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID ?>">
                            <input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
                            <input type="hidden" name="amount" id="amount">
                            <input type="hidden" name="loan_status" id="loan_status">
                                <!-- <p>Loan wil be saved in the expenses</p> -->
                            
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="btnSubmit">Submit</button>
                        </div>
                    </form>
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

        $(document).on('click', '.approveLoan, .rejectLoan', function(e){
            e.preventDefault();
            $("#approveModal").modal("show");
            var borrower_id = $(this).attr('href');
            var loan_id = $(this).attr('id');
            var amount = $(this).data('amount');
            var text = $(this).text();
            document.getElementById('borrower_id').value = borrower_id;
            document.getElementById('loan_id').value = loan_id;
            document.getElementById('amount').value = amount;
            document.getElementById('titleState').innerText = text;
            document.getElementById('loan_status').value = text;
            if(text === 'Reject'){
                $("#btnSubmit").addClass('btn-danger');
                $("#btnSubmit").html(text + ' and close');
            }else{
                $("#btnSubmit").removeClass('btn-danger');
                $("#btnSubmit").html(text + ' and Pay');
            }
            
        })

        $("#approveForm").submit(function(e){
            e.preventDefault();
            var data = $(this).serialize();
            
            $.ajax({
                url:'borrowers/loans/sunctionLoan',
                method:'post',
                data:data,
                beforeSend:function(){
                    $("#btnSubmit").html('Processing...');
                },
                success:function(data){
                    successToast(data);
                    setTimeout(function(){
                        location.reload();
                    }, 3000);
                    $("#btnSubmit").html("Submit");
                }
            })
        })

    </script>
</body>
</html>