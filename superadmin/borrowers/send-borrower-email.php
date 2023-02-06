<?php 
  	require ("../../includes/db.php");
	require ("../addons/tip.php");
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
        <section class="content bg-light">     					
			<div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">     						
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4 class="card-title">Clients</h4>
                            </div>
                            
                            <?php 
                                $query = $connect->prepare("SELECT * FROM borrowers_details WHERE branch_id = ? AND parent_id = ?  AND borrower_email != '' ");
                                $query->execute(array($BRANCHID, $_SESSION['parent_id']));
                            ?>
                            <form method="post" class="sendEmailForm" id="sendEmailForm" enctype="multpart/form-data">
                                <div class="card-body box-profile">
                                    <div class="table table-responsive">
                                        <table class="cell-table " id="myTable">  
                                            
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" name="check_all" id="check_all"></th>
                                                    <th>Firstname</th>
                                                    <th>Lastname</th>
                                                    <th>Email</th>
                                                    <th>Sent Email</th>
                                                    <th>Edit</th>
                                                </tr>
                                            </thead>  
                                            <tbody class="text-dark">  
                                            <?php  

                                                foreach ($query as $row) {
                                                    if ($row['borrower_email'] != '') {
                                                        $borrower_email = $row['borrower_email'];
                                                    }else{
                                                        $borrower_email = 'no email';
                                                    }
                                                ?>
                                                <tr id="<?php echo $row['id']?>">
                                                    <td><input type="checkbox" name="reciever_email[]" class="checkSingle" data-username="<?php echo $row['borrower_firstname'] ?>" id="reciever_email" value="<?php echo $row['borrower_email']?>">
                                                        <input type="hidden" name="borrower_name" id="borrower_name" value="<?php echo $row['borrower_firstname'] ?>">
                                                    </td>
                                                    <td><?php echo $row['borrower_firstname'] ?></td>
                                                    <td><?php echo $row['borrower_lastname']?></td>
                                                    <td><?php echo $borrower_email?></td>
                                                    <td><a href="borrowers/sentEmails?user_email=<?php echo $row['borrower_email']?>&username=<?php echo $row['borrower_firstname'] ?>">View <?php echo countEmails($connect, $row['borrower_email'], $_SESSION['parent_id'])?> Email</a></td>
                                                    <td data-column="Edit">
                                                        <a href="borrowers/edit_borrower_details?borrower_id=<?php echo $row['borrower_ID']?>"><i class="bi bi-pencil-square" aria-hidden="true"></i></a>
                                                </tr>
                                            <?php	
                                                }
                                            ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>
                                                        <span id="counting"></span>
                                                    </th>
                                                    <th>Firstname</th>
                                                    <th>Lastname</th>
                                                    <th>Email</th>
                                                    <th>Sent Email</th>
                                                    <th>Edit</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="card-header bg-secondary">
                                    <h4 class="card-title">Create New Message</h4>
                                </div>
                                <div class="card-body">
                                    <div class="sms-result"></div>
                                    <div class="form-group mb-3">
                                        <label>Subject</label>
                                        <input type="text" name="subject" id="subject" class="form-control" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Message</label><br>
                                        <textarea name="message" id="message" placeholder="Write your Message" class="form-control" style="resize: none;"></textarea>
                                        <input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_SESSION['parent_id']?>">
                                        <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $BRANCHID?>">
                                    </div>
                                    <div class="form-group mb-3">
                                        <a href="" class="attach"><i class="bi bi-paperclip" aria-hidden="true"></i> Attach File</a>
                                        <input type="file" name="attachment" id="attachment" style="display: none;">
                                        <div class="results"></div>
                                    </div>
                                    <button class="btn btn-primary" type="button" id="emailBtn">Send Email <i class="fa fa-send" id="fa-sending"></i></button> 
                                </div>
                            </form>
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
		
		$(function(){
		
		$("#check_all").change(function(){
		    if(this.checked){
		      	$(".checkSingle").each(function(){
		        	this.checked=true;
		        	$("#counting").text($('#checked_user:checked').length);
		      	})             
		    }else{
		      	$(".checkSingle").each(function(){
		        	this.checked=false;
		        	$("#counting").text($('#checked_user:checked').length);
		      	})              
		    }
 		});

  		$(".checkSingle").click(function () {
    		if ($(this).is(":checked")){
      			var isAllChecked = 0;
      			$(".checkSingle").each(function(){
        			if(!this.checked)
           			isAllChecked = 1;
      			})              
      			if(isAllChecked == 0){ $("#check_all").prop("checked", true); }     
    		}else {
      			$("#check_all").prop("checked", false);
    		}
  		});



		$(".checkSingle").click(function(){
			$("#counting").text($('#checked_user:checked').length);
		});


		$("#smsBtn").click(function(){
			if ($(".checkSingle").is(":checked")) {
				var mobile = $("#mobile").val();
				var sms = document.getElementById("sms");

				if (sms.value === "") {
					errorNow("Write your message");
					sms.focus();
					return false;
				}
				let myForm = document.getElementById('createMessageForm');
				let formData = new FormData(myForm);

				$.ajax({
					url:"borrowers/processing/send-sms-to-borrowers",
					method:"post",
					data:formData,
					cache:false,
					processData:false,
					contentType:false,
					beforeSend:function(){
						$("#smsBtn").html("Sending...");
					},
					success:function(response){
						successNow(response);
						setTimeout(function(){
							location.reload();
						}, 2000);
						$("#smsBtn").html("Send SMS");
						
					}
				})
			}else{
				errorNow("Please tick at-least one borrower");
				return false;
			}
		});
	});

	function errorNow(msge){
  		toastr.error(msge)
  		toastr.options.progressBar = true;
  		toastr.options.positionClass = "toast-top-center";
  	}

  	function successNow(msge){
  		toastr.success(msge)
  		toastr.options.progressBar = true;
  		toastr.options.positionClass = "toast-top-center";
  	}
    	
	</script>
</body>
</html>