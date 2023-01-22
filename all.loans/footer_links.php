
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>

<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<!-- <script src="plugins/daterangepicker/daterangepicker.js"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="dist/js/pages/dashboard.js"></script> -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<script src="plugins/toastr/toastr.min.js"></script>
<script src="intl.17/build/js/intlTelInput.js"></script>
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<script src="../js/timeme.min.js"></script>
<script type="text/javascript">
    // Initialize library and start tracking time
    TimeMe.initialize({
      currentPageName: "my-home-page", // current page
      // idleTimeoutInSeconds: 15, // seconds,

    });

    function logoutFunction(){
      console.log("You have been IDLE, So logging you out");
    }

    // ... Some time later ...

    // Retrieve time spent on current page
    let timeSpentOnPage = TimeMe.getTimeOnCurrentPageInSeconds();
    let durationInSeconds = 10;
    TimeMe.setIdleDurationInSeconds(durationInSeconds);
    TimeMe.callAfterTimeElapsedInSeconds(15, function(){
      console.log("The user has been actively using the page for 15 seconds! Let's prompt them with something.");
    });

    TimeMe.callWhenUserLeaves(function(){
      console.log("The user is not currently viewing the page!");
    }, 5);

    TimeMe.callWhenUserReturns(function(){
      console.log("The user has come back!");
    });
</script>

<script>
    $(document).on("click", ".NavsetCookies", function(e){
      e.preventDefault();
      var cvalue = $(this).data('id');
      var cname = "SelectedBranch";
      	setBranchCookie(cname, cvalue);
      	setTimeout(function(){
	        // location.reload();
          window.location = "./";
	      }, 1500);
      })
      function setBranchCookie(cname, cvalue) {
        event.preventDefault();
        successToast("Branch Selected");
        
        const d = new Date();
        d.setTime(d.getTime() + (30*24*60*60*1000));
        let expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
      }

    function successToast(msg){
      toastr.success(msg);
          toastr.options.progressBar = true;
          toastr.options.positionClass = "toast-top-center";
          toastr.options.showDuration = 1000;
      }

    function errorToast(msg){
    toastr.error(msg);
        toastr.options.progressBar = true;
        toastr.options.positionClass = "toast-top-center";
        toastr.options.showDuration = 1000;
    }

    $(function(){
      $('.select2').select2();
      $('#message').summernote({
          height: 200,
          toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen']],
          ],
          
        });
    })

    $(document).on("click", ".nav-link", function(e){
      var href = $(this).attr("href");
      if( href !== '#'){
        var cname = 'Activepage';
        var cvalue = href;
        SelectedHref(cname, cvalue);
      }
    })

    function SelectedHref(cname, cvalue) {
        // event.preventDefault();
      const d = new Date();
      d.setTime(d.getTime() + (1*24*60*60*1000));
      let expires = "expires="+ d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    // function AtiveHref() {
    //     // event.preventDefault();
    //   const d = new Date();
    //   d.setTime(d.getTime() + (1*24*60*60*1000));
    //   let expires = "expires="+ d.toUTCString();
    //   document.cookie = "pageActive" + "=" + 'active' + ";" + expires + ";path=/";
    // }

    // AtiveHref();

    // $(document).ready(function() {
    //   $(document).on("click", ".nav-item", function(e){
    //     var classname = $(this).attr("class");
    //     if( classname !== 'menu-open'){
    //       var cname = 'MenuOpen';
    //       var cvalue = classname;
    //       SelectedHref(cname, cvalue);
    //     }
    //   })
    // });

    // function da(){
    //   var page = '<?php if(!empty($_COOKIE['Activepage'])) {echo $_COOKIE['Activepage']; }else{echo '';}?>';
    //   $("a.nav-link").each(function(e){
    //     var href = $(this).attr("href");
    //     if(href === page){
    //       $(this).addClass("active");
    //     }
    //   })
    // }
    // da();

    

  </script>