<base href="http://localhost/osabox.net/<?php echo $_COOKIE['ManagementApp'] ?>/">
<?php
	if (!isset($_COOKIE['userLoggedin']) && !isset($_SESSION['email'])) {?>
    	<script>
      		window.location = '../signout';
    	</script>
	<?php
  	
  }

  	if (!isset($_COOKIE['SelectedBranch'])){?>
    	<script>
    		alert("You Need to Select A Branch Before Using The System");
    		setTimeout(function(){
    			window.location = '../';
    		}, 1500);
      		
    	</script>
	<?php
  	} 
?>