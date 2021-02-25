<?php
/**
 * Created by PhpStorm.
 * User: Moldedcraft 1
 * Date: 2/23/2021
 * Time: 8:50 PM
 */

include './header.php';

$r = get_reservation($_GET['id']);

?>

<style>
    @media print {
        img{
            width: 20%;
        }
        .hide-on-print{
            display: none;
        }
    }
</style>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
                <div class="panel panel-default" id="form">
                    <div class="panel-heading">
                        <h2>Reservation No: RS<?=str_pad($r['id'], 5, '0', STR_PAD_LEFT)?></h2>
                    </div>
                    <table class="table">
                        <tbody>
                        <tr>
                            <td colspan="2">
                                <div class="panel panel-success " style="margin: 0">
                                    <div class="panel-heading">
                                        Thank you for booking a reservation. We will confirm your booking vial <b>CALL</b> or <b>SMS</b> within 12 hours.
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Full Name</b></td>
                            <td><?=$r['first_name'] . ' ' . $r['last_name']?></td>
                        </tr>
                        <tr>
                            <td><b>Email</b></td>
                            <td><?=$r['email']?></td>
                        </tr>
                        <tr>
                            <td><b>Unit</b></td>
                            <td><?=$r['unit_name']?></td>
                        </tr>
                        <tr>
                            <td><b>From</b></td>
                            <td><?=$r['date_from']?></td>
                        </tr>
                        <tr>
                            <td><b>To</b></td>
                            <td><?=$r['date_to']?></td>
                        </tr>
                        <tr>
                            <td><b>Status</b></td>
                            <td><?=$r['status']?></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <img src="img/<?= $r['unit_img'] ?>" class="img-thumbnail img-responsive">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="hide-on-print" id="btn-container">
                    <button class="btn btn-default" onclick="window.print()">Print Reservation</button>
                    <a href="index.php" class="btn btn-primary">Back to Home</a>
                </div>
            </div>
        </div>
      </div>
    </div>

    <div class="container">
    <div class="row">
    </div>

<?php

include './footer.php';

?>
