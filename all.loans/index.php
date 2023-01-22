<?php 
  require ("includes/db.php");
  if (!isset($_COOKIE['ManagementApp']) && !isset($_SESSION['email'])) {?>
      <script>
          window.location = '../signout';
      </script>
  <?php
    }else {
        // header("location:https://loans.chumasolutions.com");
    }
  $option = '';
  $query = $connect->prepare("SELECT * FROM currencies");
  $query->execute();
  foreach ($query->fetchAll() as $row) {
    $option .= '<option value="'.$row['code'].'">'.$row['code'].'</option>';
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("links.php") ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <?php include ("nav_side.php"); ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid mt-4">
        <div class="row mb-2 mt-5">
          <div class="col-sm-6">
            <h4 class="m-0"><?php echo ucwords(getOrganisationName($connect, $_SESSION['parent_id']))?></h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="./" id="timeRemaining">Home</a></li>
              <li class="breadcrumb-item active"><?php echo ucwords(getOrganisationName($connect, $_SESSION['parent_id']))?> </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <?php if (!isset($_COOKIE['SelectedBranch'])):?>
      <!-- When user just logs in, they should create a branch -->
      <div class="container">
              <div class="row">
                <div class="col-md-12">
                  <div class="card card-warning card-outline mb-5">
                    <div class="card-header">
                      <h4 class="card-title">Added Branches</h4>
                    </div>
                    <div class="card-body box-profile">
                      
                      <div class="table table-responsive mb-5 mt-5">
                        <table id="branchesTable" class="cell-border" style="width:100%">
                          <thead>
                              <tr>
                                <th>Branch Name</th>
                                  <th>Location</th>
                                  <th>Landline</th>
                                  <th>Mobile</th>
                                  <th>Login</th>
                                 <?php if($_SESSION['user_role'] == 'Admin'):?> 
                                    <th>Actions</th>
                                <?php endif;?>
                              </tr>
                          </thead>
                          <tbody id="fetchBranches" class="text-dark">

                          </tbody>
                      </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <!-- end of brannch -->
    <?php else:?>
    <section class="content">
      
      <div class="container-fluid">
        <?php if($_SESSION['user_role'] == 'Admin'):?>
        <div class="row">
          <div class="col-lg-12 mb-4">
            <h1 class="h3 text-center"><?php echo getStaffMemberNames($connect, $_SESSION['user_id'], $_SESSION['parent_id'])?> <small><?php echo $_SESSION['user_role']?></small></h1>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo countBorrowers ($connect, $_SESSION['parent_id'], $BRANCHID)?></h3>

                <p>Registered Clients</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="borrowers/all-borrowers" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo countIncomeSources($connect, $_SESSION['parent_id'])?></h3>

                <p>Added Incomes</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="loans/collected_loans" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo countAllMembers($connect, $_SESSION['parent_id'])?></h3>

                <p>Staff Members</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="members/staff-members" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo countAllLoans($connect, $_SESSION['parent_id'])?></h3>

                <p>All Loans</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="loans/view-loan-applications" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>
        <?php else:?>
          <div class="row">
          <div class="col-lg-12 mb-4">
            <h1 class="h3 text-center"><?php echo getStaffMemberNames($connect, $_SESSION['user_id'], $_SESSION['parent_id'])?> <small><?php echo $_SESSION['user_role']?></small></h1>
          </div>
          <div class="col-lg-6 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo countBorrowers ($connect, $_SESSION['parent_id'], $BRANCHID)?></h3>

                <p>Registered Clients</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="borrowers/all-borrowers" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-6 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo countAllLoans($connect, $_SESSION['parent_id'])?></h3>

                <p>All Loans</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="loans/view_loans" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        </section>
        <?php endif;?>

          
          <?php endif;?>
        </div>
      </div>
    </section>
  </div>

  <aside class="control-sidebar control-sidebar-dark">

  </aside>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<?php include("footer_links.php")?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
  // var input = document.getElementById('currency');
  // input.onchange = function () {
  //   localStorage['currency_main'] = this.value;
    
  // }
  // document.addEventListener('DOMContentLoaded', function () {
  //    var input = document.getElementById('currency');
  //    if (localStorage['currency_main']) { 
  //        input.value = localStorage['currency_main'];
  //    }
  //    input.onchange = function () {
  //         localStorage['currency_main'] = this.value;
  //     }
  // });

  $(document).on("click", ".setCookies", function(e){
    e.preventDefault();
    var cvalue = $(this).data('id');
    var cname = "SelectedBrach";
    setCookie(cname, cvalue);
  })
  function setCookie(cname, cvalue) {
    event.preventDefault();
    const d = new Date();
    d.setTime(d.getTime() + (30*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

   // ================ sumit orgdata=========
    $(document).on("submit", "#orgForm", function(e){
      e.preventDefault();
      var data = document.getElementById('orgForm');
      var formData = new FormData(data);
      $.ajax({
        url:"members/submitOrgData",
        method:'POST',
        data: formData,
        cache : false,
        processData: false,
        contentType: false,
        beforeSend:function(){
          $("#submit").html('<i class="fa fa-spinner fa-spin"></i>');
        },
        success:function(data){
          if (data === 'done') {
            successNow("Organization Information Posted");
            setTimeout(function(){
              location.reload();
            }, 1500);
          }else if (data === 'updated') {
            successNow("Organization Information Updated");
            setTimeout(function(){
              location.reload();
            }, 1500);
          }else{
            errorNow(data);
          }
        }
      })
    })

    $(document).on("click", ".editData", function(e){
      e.preventDefault();
      var organisation_id = $(this).data("id");
      $.ajax({
        url:"members/edit",
        method:"post",
        data:{organisation_id:organisation_id},
        dataType:"JSON",
        success:function(data){
          $("#org_logo").val(data.org_logo);
          $("#organisation_name").val(data.organisation_name);
          $("#admin_email").val(data.admin_email);
          $("#hq_phone").val(data.hq_phone);
          $("#hq_address").val(data.hq_address);
          $("#ID").val(data.id);
        }
      })
    })
    // sortable -------
    $(function(){
      // Make the dashboard widgets sortable Using jquery UI
      $('.connectedSortable').sortable({
        placeholder: 'sort-highlight',
        connectWith: '.connectedSortable',
        handle: '.card-header, .nav-tabs',
        forcePlaceholderSize: true,
        zIndex: 999999
      })
      $('.connectedSortable .card-header').css('cursor', 'move')

      // jQuery UI sortable for the todo list
      $('.todo-list').sortable({
        placeholder: 'sort-highlight',
        handle: '.handle',
        forcePlaceholderSize: true,
        zIndex: 999999
      })

      // ###################### CREATE TASK ############
    })

    function clearForm(){
        document.getElementById('task').value = '';
        $("#task_id").val("");
    }
    

    // ========= ALERTS ============
     function successNow(msg){
      toastr.success(msg);
          toastr.options.progressBar = true;
          toastr.options.positionClass = "toast-top-center";
          toastr.options.showDuration = 1000;
      }

    function errorNow(msg){
    toastr.error(msg);
        toastr.options.progressBar = true;
        toastr.options.positionClass = "toast-top-center";
        toastr.options.showDuration = 1000;
    }

</script>
</body>
</html>
