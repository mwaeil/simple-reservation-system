<?php
/**
 * Created by PhpStorm.
 * User: Moldedcraft 1
 * Date: 2/23/2021
 * Time: 8:50 PM
 */

include './header.php';

$units = get_unit($_GET['id']);

?>


    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1><?= $units[0]['name'];?></h1>
                <p><a class="btn btn-primary btn-lg btn-success" href="reservation.php?id=<?=$_GET['id']?>" role="button">Reserve Now &raquo;</a></p>
            </div>
            <div class="col-md-4">
                <img src="img/<?= $units[0]['img'] ?>" alt="<?= $units[0]['name']; ?>" class="img-thumbnail img-responsive">
            </div>
        </div>
      </div>
    </div>

    <div class="container">
    <div class="row">
        <div class="col-md-4">
            <h4><?= $units[0]['description'];?></h4>
        </div>
    </div>

<?php

include './footer.php';

?>