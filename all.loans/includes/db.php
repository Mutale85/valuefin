<?php
    // session_set_cookie_params(
    //     604800,
    //     ini_get('session.cookie_path'),
    //     '.chumasolutions.com'
    // );
    // session_start();
    // $PASS = "Mutale@19@85";
	// $USER = "MutaleMulenga";
	// $dbname = "ChumaSolutions";
	// $connect = new PDO("mysql:host=localhost;dbname=$dbname;", $USER, $PASS);
	// $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// include 'functions.php';
	// ini_set("pcre.jit", "0");

    session_start();
    $PASS = "Mutale@19@85";
	$USER = "MutaleMulenga";
	$dbname = "valueFin";
	$connect = new PDO("mysql:host=localhost;dbname=$dbname;", $USER, $PASS);
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	include 'functions.php';
	ini_set("pcre.jit", "0");
?>