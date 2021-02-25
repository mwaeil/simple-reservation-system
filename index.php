<?php
/**
 * Created by PhpStorm.
 * User: Moldedcraft 1
 * Date: 2/23/2021
 * Time: 8:50 PM
 */

include './header.php';

seed();

$units = get_unit();

?>



    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
        <div class="container">
            <h2>Welcome to <?=$PROJECT_NAME?></h2>
            <p>I am Angelic Guingab Pascua. This is a simple project submitted in partial fulfillment for the requirement of PHP Subject in ACLC College Taguig.</p>
            <p><a class="btn btn-primary btn-lg" href="https://www.facebook.com/angelic.g.pascua/" target="_blank" role="button">Learn more about me &raquo;</a></p>
        </div>
    </div>

    <div class="container">
    <div class="row">
        <?php foreach($units as $unit): ?>
            <div class="col-md-4">
                <h2><?=$unit['name']?></h2>
                <img src="img/<?= $unit['img'] ?>" alt="<?= $unit['name']; ?>" class="img-thumbnail img-responsive " style="width: 100px; height: 100px">
                <br>
                <br>
                <p><a class="btn btn-default" href="details.php?id=<?=$unit['id']?>" role="button">View details &raquo;</a></p>
            </div>
        <?php endforeach;?>
    </div>


<?php

include './footer.php';

?>