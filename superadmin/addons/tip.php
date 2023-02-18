<base href="http://localhost/valuefin.co/superadmin/">
<?php 
    if(!isset($_SESSION['user_role'])){
        header("location:../signout");
        // echo "Not logged in";
    }

    if (!isset($_COOKIE['SelectedBranch'])){?>
    	<script>
    		alert("You Need to Select A Branch Before Using The System");
    		setTimeout(function(){
    			window.location = 'members/branches';
    		}, 1500);
      		
    	</script>
	<?php
  	} 
?>