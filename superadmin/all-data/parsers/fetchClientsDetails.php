<?php 
    include("../../../includes/db.php");
    if (isset($_POST['client_id'])) {
        $parent_id = $_SESSION['parent_id'];
		$borrower_id = $_POST['client_id'];
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
					<a href="<?php echo $borrower_id?>" class="view_files" id="<?php echo getBorrowerFullNamesByCardId($connect, $borrower_id) ?>">Click to view NRC</a>
				</div>
				
			</div>
		</div>
	<?php    

	}
?>