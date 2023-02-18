<?php
    include("../../../includes/db.php");
    if(isset($_POST['staff_id'])){
        $staff_id = $_POST['staff_id'];
        $staff_role = $_POST['staff_role'];
        if($staff_role == 'admin'){
            $query = $connect->prepare("SELECT * FROM officers_admin WHERE staff_id = ? ");
            $query->execute([$staff_id]);
            $row = $query->fetch();
            if($row){
                extract($row);
    ?>
            <table class="table table-bordered">
                <tr>
                    <th>NRC</th>
                    <th>Home Address</th>
                    <th>View NRC</th>
                </tr>
                <tr>
                    <td><?php echo $nrc_number?></td>
                    <td><?php echo $home_address?></td>
                    <td><a href="members/uploads/<?php echo $nrc_copy?>" target="_blank"><?php echo $nrc_copy?></a> </td>
                </tr>
            </table>
    <?php
            }
        }else{
            $query = $connect->prepare("SELECT * FROM loan_officers WHERE staff_id = ? ");
            $query->execute([$staff_id]);
            $row = $query->fetch();
            if($row){
                extract($row);
    ?>
            <table class="table table-bordered">
                <tr>
                    <th>NRC</th>
                    <th>Home Address</th>
                    <th>View NRC</th>
                </tr>
                <tr>
                    <td><?php echo $nrc_number?></td>
                    <td><?php echo $home_address?></td>
                    <td><a href="members/uploads/<?php echo $nrc_copy?>" target="_blank"><?php echo $nrc_copy?></a> </td>
                </tr>
            </table>
    <?php
            }
        }
    }
?>