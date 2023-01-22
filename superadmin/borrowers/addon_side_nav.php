<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="./" class="brand-link">
      <img src="dist/img/ValueFinIcon.png" alt="ValueFinIcon Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">valueFin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="./" class="d-block"><?php echo $_SESSION['username']?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
                <li class="nav-header text-danger border-bottom"><b>CLIENTS</b></li>
                <li class="nav-item">
                    <a href="borrowers/" class="nav-link">
                    <i class="bi bi-plus-circle text-danger nav-icon"></i>
                    <p>Add Clients</p>
                    </a>
                </li>  
                <li class="nav-item">
                    <a href="borrowers/all-borrowers" class="nav-link">
                    <i class="bi bi-person-circle text-success nav-icon"></i>
                    <p>View Clients</p>
                    </a>
                </li>
                <li class="nav-item">
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
                </li>
                <li class="nav-item">
                    <a href="borrowers/sms_borrower" class="nav-link">
                    <i class="bi bi-reply-all nav-icon"></i>
                    <p>SMS Clients</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="borrowers/email_borrower" class="nav-link">
                    <i class="bi bi-mailbox nav-icon"></i>
                    <p>Email Clients</p>
                    </a>
                </li>
                <li class="nav-header text-danger border-bottom"><b>LOANS</b></li>   
                <li class="nav-item">
                    <a href="loans/view-loan-applications" class="nav-link">
                    <i class="bi bi-arrow-right-square nav-icon"></i>
                    <p> View Loans </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="loans/expected-payments" class="nav-link">
                    <i class="bi bi-piggy-bank nav-icon"></i>
                    <p>Expected Payments </p>
                    </a>
                </li> 
                <li class="nav-item">
                    <a href="loans/disbursed_loans" class="nav-link">
                    <i class="bi bi-binoculars nav-icon"></i>
                    <p>Disbursed Funds </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="loans/collected_loans" class="nav-link">
                    <i class="bi bi-bucket nav-icon"></i>
                    <p>Collected Funds </p>
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
                <!-- <i class="nav-icon bi bi-bank"></i> -->
                <i class="nav-icon bi bi-arrow-left-right"></i>
                <p>
                    Reports
                    <i class="fas fa-angle-left right"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="reports/issued-loans" class="nav-link">
                    <i class="bi bi-plus-circle text-primary nav-icon"></i>
                    <p>Issued Loans</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="reports/arrear-loans" class="nav-link">
                    <i class="bi bi-circle text-danger nav-icon"></i>
                    <p>Arrear Loans</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="reports/fully-paid-loans" class="nav-link">
                    <i class="bi bi-circle text-primary nav-icon"></i>
                    <p>Fully Repaid Loans</p>
                    </a>
                </li>
                </ul>
                </li>
                <li class="nav-item">
                    <a href="navtree" class="nav-link">
                    <i class="nav-icon bi bi-wallet"></i>
                    <p>
                        Admin Controls
                        <i class="fas fa-angle-left right"></i>
                    </p>
                    </a>
                    <ul class="nav nav-treeview">
                    <li class="nav-header text-danger border-bottom">Settings</li>
                    <li class="nav-item">
                        <a href="loans/loan-settings" class="nav-link">
                        <i class="bi bi-gear-wide-connected nav-icon"></i>
                        <p>Loan Settings</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="members/email-settings" class="nav-link text-danger">
                        <i class="bi bi-mailbox nav-icon"></i>
                        <p>Email Settings</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="members/sms-create-sender-id" class="nav-link text-danger">
                        <i class="bi bi-reply-all nav-icon"></i>
                        <p>SMS Settings</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="members/positions" class="nav-link text-danger">
                        <i class="bi bi-plus-square nav-icon"></i>
                        <p>Create Positions</p>
                        </a>
                    </li>
                    <li class="nav-header text-success border-bottom">Staff Care</li>
                    <li class="nav-item">
                        <a href="members/add-staff-members" class="nav-link  text-success">
                        <i class="bi bi-person-plus nav-icon"></i>
                        <p>Add Staff</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="members/staff-members" class="nav-link  text-success">
                        <i class="bi bi-arrow-right-square nav-icon"></i>
                        <p>View Staff</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="members/email-staff" class="nav-link  text-success">
                        <i class="bi bi-mailbox nav-icon"></i>
                        <p>Email Staff</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="members/sms-staff" class="nav-link text-success">
                        <i class="bi bi-reply-all nav-icon"></i>
                        <p>SMS Staff</p>
                        </a>
                    </li>

                    <li class="nav-header text-primary border-bottom">Management</li>
                    <li class="nav-item">
                        <a href="members/settings" class="nav-link">
                        <i class="bi bi-gear-wide nav-icon"></i>
                        <p>Company Settings</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="members/branches" class="nav-link">
                        <i class="bi bi-building nav-icon"></i>
                        <p>Branches</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="members/login_data" class="nav-link">
                        <i class="bi-clock-history nav-icon"></i>
                        <p>Logs</p>
                        </a>
                    </li>
                    </ul>
                </li>
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
                <!-- <li class="nav-item menu-open">
                <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="./index.html" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Dashboard v1</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./index2.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Dashboard v2</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./index3.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Dashboard v3</p>
                    </a>
                </li>
                </ul>
                </li> -->
            
            </ul>
        </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
