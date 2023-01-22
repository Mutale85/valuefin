<base href="http://localhost/valuefin.co/superadmin/">
<?php 
    if(!isset($_SESSION['user_role'])){
        header("location:../signout");
        // echo "Not logged in";
    }
?>