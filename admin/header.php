<?php
/**
 * Created by PhpStorm.
 * User: Moldedcraft 1
 * Date: 2/23/2021
 * Time: 9:21 PM
 */
session_start();
$isLoggedIn = isset($_SESSION['isLoggedIn']);

include '../db/connect.php';


$reservations = [];
if($isLoggedIn) {
    $reservations = get_reservation();
}

?>




<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?=$PROJECT_NAME?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        body {
            padding-top: 50px;
            padding-bottom: 20px;
        }
    </style>
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="icon" href="../img/dp.jpg">

    <script src="../js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top hide-on-print" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><?=$PROJECT_NAME?></a>
        </div>
        <?php if($isLoggedIn): ?>
        <div id="navbar" class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" method="POST">
            <button type="submit" name="logout" class="btn btn-danger">Logout</button>
          </form>
        </div>
        <?php endif;?>
    </div>
</nav>
