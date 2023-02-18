<?php 
  require ("../includes/db.php");
  require ("addons/tip.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("addon_header.php")?>
  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <?php include("addon_top_min_nav.php")?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include("addon_side_nav.php")?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">

    </section>
    <section class="content bg-light p-3">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box shadow bgs-info">

              <div class="inner">
                <h3>
                  
                  <?php
                    $today = date("Y-m-d"); 
                    
                    echo countNumberofLoanPaymentsToday($connect, $BRANCHID, $parent_id, $today)
                  ?>
                </h3>

                <p>Todays Collection</p>
              </div>
              <div class="icon">
                <i class="bi bi-piggy-bank"></i>
              </div>
              <a href="loans/daily-collections?branch_id=<?php echo base64_encode($BRANCHID)?>&parent_id=<?php echo base64_decode($_SESSION['parent_id'])?>" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <!-- small Pending loans box -->
            <div class="small-box shadow bgs-success">
              <div class="inner">
                <h3><?php echo countNumberofLoanApplications($connect, $BRANCHID, $parent_id)?></h3>

                <p>Loan Applications</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="loans/view-loan-applications?branch_id=<?php echo base64_encode($BRANCHID)?>&parent_id=<?php echo base64_decode($_SESSION['parent_id'])?>" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box shadow bgs-warning">
              <div class="inner">
                <h3><?php echo countNumberOfClients($connect, $BRANCHID, $parent_id)?></h3>

                <p>Branch Clients</p>
              </div>
              <div class="icon">
                <i class="bi bi-people"></i>
              </div>
              <a href="borrowers/view-borrowers?branch_id=<?php echo base64_encode($BRANCHID)?>&parent_id=<?php echo base64_decode($_SESSION['parent_id'])?>" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box shadow bgs-danger">
              <div class="inner">
                <h3><?php echo countNumberOfLoansAprovedAndIssued($connect, $BRANCHID, $parent_id)?></h3>

                <p>Issued Loans</p>
              </div>
              <div class="icon">
                <i class="bi bi-wallet2"></i>
              </div>
              <a href="reports/issued-loans?branch_id=<?php echo base64_encode($BRANCHID)?>&parent_id=<?php echo base64_decode($_SESSION['parent_id'])?>" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

        </div>
        
        <div class="row">
          
          <section class="col-lg-7 connectedSortable">
            
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Disbursed Fund
                </h3>
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                      <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Margins</a>
                    </li>
                    <!-- <li class="nav-item">
                      <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                    </li> -->
                  </ul>
                </div>
              </div>
              <div class="card-body">
                <div class="tab-content p-0">
                  
                    <div class="chart tab-pane active" id="revenue-chart">
                        <div id="chart-bar"></div>
                   </div>
                    <div class="chart tab-pane" id="sales-chart" >
                    
                      <div id="chart-donut" style="height:200px;"></div>
                  </div>
                </div>
              </div>
            </div>

          </section>

          <section class="col-lg-5 connectedSortable">

            <div class="card bg-gradient-light">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Received Funds
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart" id="chart-all-branches" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></div>
              </div>
              
              <div class="card-footer bg-transparent">

              </div>
            </div>


          </section>
          
        </div>
        
      </div>
    </section>
  </div>
  <?php 
    $query = $connect->prepare("SELECT DATE(paid_date) AS date_collected, SUM(amount) AS total_collected FROM loan_payments WHERE branch_id = ? AND parent_id = ? GROUP BY DATE(paid_date) ");
    $query->execute([$get_branch, $parent_id]);
    $data = [];
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $data[] = [
        'x' => $row['date_collected'],
        'y' => $row['total_collected']
      ];
    }
    $data_json = json_encode($data);
  ?>
  <?php include("addon_footer.php")?>
  <script>
     var options = {
          series: [{
          name: 'Net Profit',
          data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
        }, {
          name: 'Revenue',
          data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
        }, {
          name: 'Free Cash Flow',
          data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
        }],
          chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
        },
        yaxis: {
          title: {
            text: 'ZMW (thousands)'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "ZMW " + val
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart-bar"), options);
        chart.render();
      

        var options = {
          series: [44, 55, 41],
          chart: {
          type: 'donut',
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chart-donut"), options);
        chart.render();

        // incomes from all branches

        var options = {
          series: [44, 55, 67, 83],
          chart: {
          height: 350,
          type: 'radialBar',
        },
        plotOptions: {
          radialBar: {
            dataLabels: {
              name: {
                fontSize: '22px',
              },
              value: {
                fontSize: '16px',
              },
              total: {
                show: true,
                label: 'Total',
                formatter: function (w) {
                  // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                  return 249
                }
              }
            }
          }
        },
        labels: ['Apples', 'Oranges', 'Bananas', 'Berries'],
        };

        var chart = new ApexCharts(document.querySelector("#chart-all-branches"), options);
        chart.render();
      
  </script>
</body>
</html>
