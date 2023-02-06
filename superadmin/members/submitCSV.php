<?php  
  include '../includes/db.php';
  $parent_id = $_SESSION['parent_id'];
  // if($_FILES['file']['name']){
  //   $filename = explode(".", $_FILES['file']['name']);
  //   if($filename[1] == 'csv'){
  //     $handle = fopen($_FILES['file']['tmp_name'], "r");
  //     while($data = fgetcsv($handle)){
  //         echo $Country      = $data[0];  
  //         $Country_name = $data[1];
  //         $Prefix       = $data[2];
  //         $Price        = $data[3];
         
  //         $query = $connect->prepare("INSERT INTO `sms_prices`(`Country`, `Country_name`, `Prefix`, `Price`) VALUES(?, ?, ?, ?) ");
  //         $query->execute(array($Country, $Country_name, $Prefix, $Price));
  //     }
  //     fclose($handle);
  //     echo "done";
  //   }
  // }

    $fileName = $_FILES["file"]["tmp_name"];
    
    if ($_FILES["file"]["size"] > 0) {
        
        $file = fopen($fileName, "r");
        
        while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
            
            $Country = "";
            if (isset($column[0])) {
                $Country =  $column[0];
            }
            $Country_name = "";
            if (isset($column[1])) {
                $Country_name = $column[1];
            }
            $Prefix = "";
            if (isset($column[2])) {
                $Prefix = $column[2];
            }
            $Price = "";
            if (isset($column[3])) {
               echo  $Price =  $column[3];
            }
            
            $query = $connect->prepare("INSERT INTO `sms_prices`(`Country`, `Country_name`, `Prefix`, `Price`) VALUES(?, ?, ?, ?) ");
            $query->execute(array($Country, $Country_name, $Prefix, $Price));
            
        }
        fclose($file);
        echo "done";
    }
