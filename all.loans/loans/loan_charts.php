<?php 
    require ("../includes/db.php");
    require ("../includes/tip.php"); 
        
?>
<!DOCTYPE html>
<html>
<head>
    <title>Loan Charts</title>
    <?php include("../links.php") ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include ("../nav_side.php"); ?>

        <div class="content-wrapper mt-5">
            <section class="content mt-5">
                <div class="container-fluid mt-5 mb-5">
                    <div class="row mt-5">
                        <div class="col-md-12 mt-4 pb-2 d-flex justify-content-between border-bottom">
                            <h1 class="h4"><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $BRANCHID))?> Loan Charts</h1>
                            
                        </div>
                    </div>
                </div>
               <?php
                    $sql = $connect->prepare("SELECT * FROM `loans` WHERE branch_id = ? AND parent_id = ? AND loan_status = 'Rejected' ");
                    $sql->execute(array($BRANCHID, $_SESSION['parent_id']));
                    $Rejected = $sql->rowCount();
                    // echo $Rejected;

                    $sql = $connect->prepare("SELECT * FROM `loans` WHERE branch_id = ? AND parent_id = ? AND loan_status = 'Released' ");
                    $sql->execute(array($BRANCHID, $_SESSION['parent_id']));
                    $Released = $sql->rowCount();
                    // echo $Released;

                    $sql = $connect->prepare("SELECT * FROM `loans` WHERE branch_id = ? AND parent_id = ? AND loan_status = 'For_Approval' ");
                    $sql->execute(array($BRANCHID, $_SESSION['parent_id']));
                    $For_Approval = $sql->rowCount();
                    // echo $For_Approval;

                    $sql = $connect->prepare("SELECT * FROM `loans` WHERE branch_id = ? AND parent_id = ? AND loan_status = 'Approved' ");
                    $sql->execute(array($BRANCHID, $_SESSION['parent_id']));
                    $Approved = $sql->rowCount();
                    // echo $Approved;

                    $sql = $connect->prepare("SELECT * FROM `loans` WHERE branch_id = ? AND parent_id = ? AND loan_status = 'Completed' ");
                    $sql->execute(array($BRANCHID, $_SESSION['parent_id']));
                    $Completed = $sql->rowCount();
                    // echo $Completed;
                    $data = $Rejected.','.$Released.','.$For_Approval.','.$Completed; 

                    // $months = 12;
                    // $i = 1;
                    // $q = 1;
                    // $month_from_date =  $collected_payments = $paid_out_loans ="";
                    // for($x = 1; $x <= $months; $x++ ){
                    //     $month_from_date .= '\''.date("F", strtotime("+".$i++." month", strtotime("-1 month"))).'\', ';
                    //     $date_as_digit = date("n", strtotime("+".$q++ ."month", strtotime("-1 month")));
                    //     #========================== COLLECTED PAYMENTS FROM CLIENTS ==========
                    //     $query = $connect->prepare("SELECT * FROM `loan_payments` WHERE MONTH(`paid_date`) = ? AND branch_id = ? AND parent_id = ?  ");
                    //     $query->execute(array($date_as_digit, $BRANCHID, $_SESSION['parent_id']));
                    //     $numbers = $query->rowCount();
                    //     $collected_payments .= $numbers.', ';

                    //     // =================== PAYMENTS MADE TO CLIENTS =======
                    //     $sql = $connect->prepare("SELECT * FROM `loans` WHERE MONTH(release_date) = ? AND branch_id = ? AND parent_id = ? AND loan_status = 'Released' ");
                    //     $sql->execute(array($date_as_digit, $BRANCHID, $_SESSION['parent_id']));
                    //     $paid_out_loans .= $sql->rowCount().', ';


                    // }
                    // $collected_payments = rtrim($collected_payments, ', ');
                    // $paid_out_loans = rtrim( $paid_out_loans, ', ');
                    
                   
                    // $thisMonth = '\''.date("F").'\'';
                    // $fullMonths = $thisMonth.', '.$month_from_date;
                    // $fullMonths = rtrim($month_from_date, ', ');

                    // $sql = $connect->prepare("SELECT *, SUM(income_amount) AS total_amount FROM `income_table` WHERE branch_id = ? AND parent_id = ? AND income_date > (NOW() - INTERVAL 30 DAY) ");
                    $sql = $connect->prepare("SELECT * FROM `loans` WHERE release_date > (NOW() - INTERVAL 90 DAY) AND branch_id = ? AND parent_id = ? AND loan_status = 'Released' ");
                    $sql->execute(array($BRANCHID, $_SESSION['parent_id']));
                    $result = $sql->fetchAll();
                    $labels = $uptime = $downtime = '';
                        $sum = 0;
                        foreach ($result as $row) {
                            extract($row);
                            $labels .= '\''.date("M-d", strtotime($release_date,  strtotime("+1 day")) ).'\''.',';
                            $uptime .= $total_payable_amount+ $sum.','; 
                        }
                        $income_expenses_lable = rtrim($labels, ',');
                        
                        $incomes = rtrim($uptime, ',');

                    // $query = $connect->prepare("SELECT *, SUM(expense_amount) AS total_amount FROM `expenses` WHERE branch_id = ? AND parent_id = ? AND expense_date > (NOW() - INTERVAL 30 DAY) ");
                    $query = $connect->prepare("SELECT * FROM `loan_payments` WHERE paid_date > (NOW() - INTERVAL 90 DAY) AND branch_id = ? AND parent_id = ?  ");
                    $query->execute(array($BRANCHID, $_SESSION['parent_id']));
                    $count = $query->rowCount();
                    $results = $query->fetchAll();
                    if ($count > 0) {
                        $sum = 0;
                        foreach ($results as $row) {
                            extract($row);
                            $downtime .= $amount + $sum.',';
                            $labels .= '\''.date("M-d", strtotime($paid_date,  strtotime("+1 day")) ).'\''.',';
                        }

                        $downtimes_expenses = rtrim($downtime, ',');
                    }else{
                        $downtimes_expenses = $count;
                    }

                    $income_expenses_lable = rtrim($labels, ',');

                
                ?>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <h4>ALL PROCESSED LOANS</h4>
                            <div class="card card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">Loan Applications</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="donutChart" style="min-height: 400px; height: 400px; max-height: 400px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-5 mt-5">
                            <!-- BAR CHART -->
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Issued and Collection Last 90 Days</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart">
                                        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
    <?php include("../footer_links.php")?>
    <script src="plugins/chart.js/Chart.min.js"></script>
    
    <script>
         //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d');

    var donutData        = {
        labels: [
          'Rejected',
          'Paid Out',
          'Pending Approval',
          'Completed',
        ],
        datasets: [{
            data: [<?php echo $data?>],
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#3c8dbc'],
        }]
    }
    var donutOptions     = {
        maintainAspectRatio : false,
        responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: donutData,
        options: donutOptions
    })

//==================================== BAR CHART ===================
    Chart.Legend.prototype.afterFit = function() {
            this.height = this.height + 50;
        };

        var areaChartData = {
          labels  : [<?php echo $income_expenses_lable?>],
          datasets: [
            {
                label               : 'Issued ',
                backgroundColor     : '#5b9291',
                borderColor         : 'rgba(60,141,188,0.8)',
                pointRadius          : true,
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data                : [<?php echo $incomes?>] // data will come from loan_payments,
            },
            {
                label               : 'Collected',
                backgroundColor     : '#790201',
                borderColor         : 'rgba(255, 0, 0, 1)',
                pointRadius         : true,
                pointColor          : 'rgba(255, 0, 0, 1)',
                pointStrokeColor    : '#fbff00',
                pointHighlightFill  : '#fbff00',
                pointHighlightStroke: 'rgba(255, 0, 0, 1)',
                data                : [<?php echo $downtimes_expenses?>] // data will come  with status released
            },
          ]
        }

        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        var temp1 = areaChartData.datasets[1]
            barChartData.datasets[0] = temp1
            barChartData.datasets[1] = temp0

        var barChartOptions = {
            responsive              : true,
            maintainAspectRatio     : false,
            datasetFill             : false,
            scales: {
                xAxes: [{
                    gridLines: {
                        display:false
                    }
                }],
                yAxes: [{
                    gridLines: {
                        display:false
                    }   
                }]
            }
        }

        new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        })

    </script>
</body>
</html>