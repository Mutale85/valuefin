<base href="http://localhost/kukula/k/borrowers">
<?php 
  	require ("../../includes/db.php");
  	if (!isset($_COOKIE['userLoggedin']) && !isset($_SESSION['email'])) {?>
    	<script>
      		window.location = '../';
    	</script>
	<?php
  	}