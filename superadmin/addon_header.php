<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>valueFin SuperAdmin | Dashboard</title>

<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<!-- iCheck -->
<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<!-- JQVMap -->
<!-- <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css"> -->
<!-- Theme style -->
<link rel="stylesheet" href="dist/css/adminlte.min.css">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
<!-- Daterange picker -->
<!-- <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css"> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<!-- summernote -->
<link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="../intl.17/build/css/intlTelInput.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />


<?php 
if (isset($_COOKIE['SelectedBranch'])) {
    $BRANCHID = $_COOKIE['SelectedBranch'];
}else{
    $BRANCHID = "";
    // header("location:index");
}
$BRANCHID = base64_decode($BRANCHID);
?>
<style>
    .select-style {
        width: 70px;
        padding: 0;
        margin: 0;
        display: inline-block;
        vertical-align: middle;
        background: url("http://grumbletum.com/places/arrowdown.gif") no-repeat 100% 30%;
    }
    .select-style select {
        width: 100%;
        padding: 0;
        margin: 0;
        background-color: transparent;
        background-image: none;
        border: none;
        box-shadow: none;
        -webkit-appearance: none;
            -moz-appearance: none;
                appearance: none;
    }
    .iti { width: 100%; }
    .intl-tel-input {
        background-color: black;
    }
    .intl-tel-input .selected-flag {
        z-index: 4;
        background-color: black;
    }
    .iti__selected-dial-code {
        color: red;
    }
    .intl-tel-input .country-list {
        z-index: 5;
        background-color: black;
    }
    .input-group .intl-tel-input .form-control {
        border-top-left-radius: 4px;
        border-top-right-radius: 0;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 0;
    }
</style>