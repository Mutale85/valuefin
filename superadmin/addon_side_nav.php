<div class="preloader flex-column justify-content-center align-items-center" style="background-color: #ffff !important; ">
	<img class="animation__shake" src="dist/img/ValueFinIcon.png" alt="logo" height="60" width="60">
</div>

  <!-- Navbar -->
<!-- <nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top">
    <ul class="navbar-nav">
      	<li class="nav-item">
        	<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      	</li>
      	<li class="nav-item d-none d-sm-inline-block">
        	<a href="./" class="nav-link">Home</a>
      	</li>
      	<li class="nav-item d-none d-sm-inline-block"></li>
    </ul>

    <ul class="navbar-nav ml-auto">
      	<li class="nav-item">
        	<a class="nav-link" data-widget="navbar-search" href="#" role="button">
              <span id="timeRemaining"></span>
        	</a>
            <div class="navbar-search-block">
                <span id="timeRemaining"></span>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link" href="../signout">
            <i class="fa fa-sign-out-alt"></i>
            </a>
            
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fa fa-plus"></i>
    
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul>
</nav> -->
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="./" class="brand-link">
        <img src="dist/img/ValueFinIcon.png" alt="ValueFin" class="brand-images img-circle elevation-3" style="opacity: 1; height:40px;width: 40px; border-radius: 50%; ">
        <span class="brand-text font-weight-light"><b>ValueFin</b></span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="./" class="d-block"><?php echo $_SESSION['username']?></a>
            </div>
        </div>
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" id="">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <?php
            if (isset($_COOKIE['SelectedBranch'])) {
                $SelectedBranch = $_COOKIE['SelectedBranch'];
                $branch_id = base64_decode($SelectedBranch);
            ?>
            <li class="nav-item menu-open">
              <a href="./" class="nav-link active">
                <i class="nav-icon bi bi-shop"></i>
                <p>
                  <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id))?>
                </p>
              </a>
            </li>
          <?php  
            }else{?>
              <li class="nav-item menu-open">
                <a href="branches/branch" class="nav-link active">
                  <i class="nav-icon bi bi-building"></i>
                  <p>
                    Set Up Branch
                  </p>
                </a>
              </li>
            <?php }?>
          
          <li class="nav-item">
            <a href="navtree" class="nav-link">
              <i class="nav-icon bi bi-shop-window"></i>
              <p>
                Branches
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <?php
                $sql = $connect->prepare("SELECT * FROM allowed_branches WHERE staff_id = ? AND parent_id = ? ");
                $sql->execute(array($_SESSION['user_id'], $_SESSION['parent_id']));
                if($sql->rowCount() > 0){
                foreach ($sql->fetchAll() as $row) {
                    $branch_id = $row['branch_id'];
                    if ($branch_id == $BRANCHID) {?>
                      <li class="nav-item">
                        <a href="" class="nav-link NavsetCookies" data-id="<?php echo base64_encode($branch_id)?>" id="<?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id))?>">
                          <i class="bi bi-shop nav-icon text-success"></i>
                          <p><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id))?></p>
                        </a>
                      </li>
               <?php       
                    }else{?>
                      <li class="nav-item">
                        <a href="" class="nav-link NavsetCookies" data-id="<?php echo base64_encode($branch_id)?>" id="<?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id))?>">
                          <i class="bi bi-shop-window nav-icon"></i>
                          <p><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id))?></p>
                        </a>
                      </li>
              <?php
                    }
                  }
                    
                }else{?>
                  <li class="nav-item">
                        <a href="branches/branch" type="button" class="nav-link" >
                          <i class="bi bi-shop-window nav-icon"></i>
                          Create Branch
                        </a>

                  </li>
                  
                <?php } ?>

            </ul>
          </li>
          <!-- Show No Nav Till Branch is Selected -->
          <?php if (isset($_COOKIE['SelectedBranch'])):?>
          <li class="nav-header border-top pb-2"><b>CLIENTS</b></li>
          <li class="nav-item">
            <a href="borrowers/" class="nav-link">
              <i class="bi bi-person-add nav-icon"></i>
              <p>Add Clients</p>
            </a>
          </li>  
          <li class="nav-item">
            <a href="borrowers/view-borrowers" class="nav-link">
              <i class="bi bi-person-circle text-success nav-icon"></i>
              <p>View Clients</p>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a href="borrowers/view-collaterals" class="nav-link">
              <i class="bi bi-archive text-warning nav-icon"></i>
              <p>View Collaterals</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="borrowers/view-guarantors" class="nav-link">
              <i class="bi bi-archive text-warning nav-icon"></i>
              <p>View Guarantors</p>
            </a>
          </li> -->
         
          <li class="nav-item">
            <a href="borrowers/borrowers-sms" class="nav-link">
              <i class="bi bi-reply-all nav-icon"></i>
              <p>SMS Clients</p>
            </a>
          </li>
          <!-- <li class="nav-item">
            <a href="borrowers/send-borrower-email" class="nav-link">
              <i class="bi bi-mailbox nav-icon"></i>
              <p>Email Clients</p>
            </a>
          </li> -->
          <li class="nav-header text-danger border-top pb-2"><b>LOANS</b></li>
          
             
          <li class="nav-item">
            <a href="borrowers/all-loan-applications?branch_id=<?php echo base64_encode($BRANCHID)?>&parent_id=<?php echo base64_decode($_SESSION['parent_id'])?>" class="nav-link">
              <i class="bi bi-wallet nav-icon"></i>
              <p> Loan Applications </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="borrowers/loan-settings" class="nav-link">
              <i class="bi bi-sliders nav-icon"></i>
              <p> Loan Settings</p>
            </a>
          </li>

          <li class="nav-header text-danger border-top pb-2"><b>LOAN PAYMENTS</b></li>
          <!-- <li class="nav-item">
            <a href="loans/disbursed-loans" class="nav-link">
              <i class="bi bi-wallet nav-icon"></i>
              <p>Disbursed Funds </p>
            </a>
          </li> -->
          <!-- <li class="nav-item">
            <a href="loans/expected-paymentsdaily-collections" class="nav-link">
              <i class="bi bi-bucket nav-icon"></i>
              <p>Expected Payments </p>
            </a>
          </li> -->
          <li class="nav-item">
            <a href="loans/daily-collections?branch_id=<?php echo base64_encode($BRANCHID)?>&parent_id=<?php echo base64_decode($_SESSION['parent_id'])?>" class="nav-link">
              <i class="bi bi-piggy-bank nav-icon"></i>
              <p>Daily Collections </p>
            </a>
          </li> 
          

          <li class="nav-item">
            <a href="loans/collected-loans?branch_id=<?php echo base64_encode($BRANCHID)?>&parent_id=<?php echo base64_decode($_SESSION['parent_id'])?>" class="nav-link">
              <i class="bi bi-bucket nav-icon"></i>
              <p>Payment Received </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="navtree" class="nav-link">
              <i class="nav-icon bi bi-bank"></i>
              <p>
                Investors
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="investors/add_investor" class="nav-link">
                  <i class="bi bi-plus-circle nav-icon"></i>
                  <p>Add & View Investor</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="investors/sms_investor" class="nav-link">
                  <i class="bi bi-circle nav-icon"></i>
                  <p>SMS Investors</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="investors/email_investor" class="nav-link">
                  <i class="bi bi-circle text-primary nav-icon"></i>
                  <p>Email Investors</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="navtree" class="nav-link">
              <i class="nav-icon bi bi-arrow-left-right"></i>
              <p>
                Reports
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="reports/issued-loans?branch_id=<?php echo base64_encode($BRANCHID)?>&parent_id=<?php echo base64_decode($_SESSION['parent_id'])?>" class="nav-link">
                  <i class="bi bi-wallet2 text-primary nav-icon"></i>
                  <p>Issued Loans</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="reports/arrear-loans?branch_id=<?php echo base64_encode($BRANCHID)?>&parent_id=<?php echo base64_decode($_SESSION['parent_id'])?>" class="nav-link">
                  <i class="bi bi-circle text-danger nav-icon"></i>
                  <p>Arrear Loans</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="reports/fully-paid-loans?branch_id=<?php echo base64_encode($BRANCHID)?>&parent_id=<?php echo base64_decode($_SESSION['parent_id'])?>" class="nav-link">
                  <i class="bi bi-circle text-primary nav-icon"></i>
                  <p>Fully Repaid Loans</p>
                </a>
              </li>
            </ul>
          </li>
              
          <?php if($_SESSION['user_role'] == 'superAdmin'):?>

            <li class="nav-item">
            <a href="navtree" class="nav-link">
              <i class="nav-icon bi bi-wallet"></i>
              <p>
                Admin Controls
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-header text-warning border-top pb-2">PERSONNEL DATA</li>
              <li class="nav-item">
                <a href="members/add-staff-members" class="nav-link  text-warning">
                  <i class="bi bi-person-plus nav-icon"></i>
                  <p>Add Personnel</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="members/staff-members" class="nav-link  text-warning">
                  <i class="bi bi-person-badge nav-icon"></i>
                  <p>View Personnel</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="members/email-staff" class="nav-link  text-warning">
                  <i class="bi bi-mailbox nav-icon"></i>
                  <p>Email Staff</p>
                </a>
              </li> -->
              <li class="nav-item">
                <a href="members/sms-staff" class="nav-link text-warning">
                  <i class="bi bi-reply-all nav-icon"></i>
                  <p>SMS Personnel</p>
                </a>
              </li>

              <li class="nav-header text-white border-top pb-2">MANAGEMENT</li>
              <li class="nav-item">
                <a href="members/settings" class="nav-link">
                  <i class="bi bi-gear-wide nav-icon"></i>
                  <p>Company Details</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="members/branches" class="nav-link">
                  <i class="bi bi-building nav-icon"></i>
                  <p>Branches</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="members/files-uploaded" class="nav-link">
                  <i class="bi bi-building nav-icon"></i>
                  <p>All Uploads</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="members/login_data" class="nav-link">
                  <i class="bi-clock-history nav-icon"></i>
                  <p>Logs</p>
                </a>
              </li>
              <li class="nav-header text-white border-top pb-2">CONSOLIDATED DATA</li>
              
              <li class="nav-item">
                <a href="all-data/all-clients" class="nav-link text-white">
                  <i class="bi bi-people nav-icon"></i>
                  <p>All Clients</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="all-data/sms-create-sender-id" class="nav-link text-white">
                  <i class="bi bi-wallet nav-icon"></i>
                  <p>Issued Loans</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="all-data/sms-create-sender-id" class="nav-link text-white">
                  <i class="bi bi-piggy-bank nav-icon"></i>
                  <p>Collected Loans</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="all-data/sms-create-sender-id" class="nav-link text-white">
                  <i class="bi bi-box nav-icon"></i>
                  <p>Arrear Loans</p>
                </a>
              </li> -->
              
              <!-- 
              <li class="nav-item">
                <a href="members/positions" class="nav-link text-white">
                  <i class="bi bi-plus-square nav-icon"></i>
                  <p>Create Positions</p>
                </a>
              </li>
            -->
            </ul>
          </li>
          
          <?php else:?>

          <?php endif;?>
        <?php else:?>

        <?php endif;?>
          <li class="nav-item">
            <a href="members/password-reset" class="nav-link">
              <i class="nav-icon bi bi-gear-wide-connected text-primary"></i>
              <p class="text">Reset Password</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../signout" class="nav-link">
              <i class="nav-icon fa fa-sign-out-alt text-danger"></i>
              <p class="text">Sign Out</p>
            </a>
          </li>
        </ul>
      </nav>
      <br><br><br>
    </div>
</aside>