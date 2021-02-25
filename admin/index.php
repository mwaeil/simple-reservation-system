<?php
/**
 * Created by PhpStorm.
 * User: Moldedcraft 1
 * Date: 2/25/2021
 * Time: 8:54 AM
 */

include './header.php';

$reservations = [];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = $_POST;

    if(isset($postData['username']) && isset($postData['password'])) {
        if ($postData['username'] === 'admin' && $postData['password'] === 'pascua') {
            $_SESSION['isLoggedIn'] = true;
            header("Refresh:0");
            die();
        } else {
            $errors[] = 'Invalid Username and Password';
        }
    } elseif(isset($postData['logout'])) {
        unset($_SESSION['isLoggedIn']);
        session_destroy();
        header("Refresh:0");
        die();
    }
}




if($isLoggedIn) {
    $reservations = get_reservation();
}

?>



<?php if(!$isLoggedIn): ?>
    <br>
    <br>
    <br>
    <br>
    <div class="container">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <h2 class="text-center">ADMIN</h2>
            <br>
            <?php if(count($errors) > 0) :?>
                <div class="panel panel-danger" style="margin: 0">
                    <div class="panel-heading">
                        <?=implode('<br>', $errors)?>
                    </div>
                </div>
            <?php endif?>
            <br>
            <form class="" role="form" method="post">
                <div class="form-group">
                    <input type="text" placeholder="Username" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Sign in</button>
            </form>
        </div>
    </div>
<?php else:?>
<div class="container">
    <div class="col-md-12">
        <br>
        <br>
        <h2>Reservations</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Reservation No.</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Unit</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($reservations as $reservation): ?>
            <tr>
                <td>RS<?=str_pad($reservation['id'], 5, '0', STR_PAD_LEFT)?></td>
                <td><?=$reservation['first_name'] . ' ' . $reservation['last_name']?></td>
                <td><?=$reservation['email']?></td>
                <td><?=$reservation['mobile']?></td>
                <td><?=$reservation['unit_name']?></td>
                <td><?=date('F j, Y h:i a', strtotime($reservation['date_from'])) . ' to ' . date('F j, Y h:i a', strtotime($reservation['date_to']))?></td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>
<?php endif;?>
