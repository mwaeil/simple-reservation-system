<?php
/**
 * Created by PhpStorm.
 * User: Moldedcraft 1
 * Date: 2/23/2021
 * Time: 8:50 PM
 */

include './header.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = $_POST;

    $dateFrom = new DateTime($postData['date_from'] . ' ' . $postData['time_from']);
    $dateTo = new DateTime($postData['date_to'] . ' ' . $postData['time_to']);
    if($dateFrom >= $dateTo){
        $errors[] = 'Date To should be greater than Date From.';
    }

    $interval = $dateFrom->diff($dateTo);

    if($interval->h < 6 && $interval->d <= 0) {
        $errors[] = 'You can only reserve for a minimum of 6 hour.';
    }

    if(get_reservation_overlap($postData['unit_id'], $dateFrom->format('Y-m-d H:i:s'), $dateTo->format('Y-m-d H:i:s'))) {
        $errors[] = 'Selected Date and Time is not available at the moment. Please choose other date and time.';
    }

    if(strlen($postData['mobile']) <= 10) {
        $errors[] = 'Please enter a valid mobile no.';
    }



    if(count($errors) <= 0) {
        $id = stmtInsert('reservation', array(
            'first_name' => $postData['first_name'],
            'last_name' => $postData['last_name'],
            'email' => $postData['email'],
            'unit_id' => $postData['unit_id'],
            'mobile' => $postData['mobile'],
            'date_from' => $dateFrom->format('Y-m-d H:i:s'),
            'date_to' => $dateTo->format('Y-m-d H:i:s'),
            'status' => 'Submitted',
        ));

        header("Location: /reservation_details.php?id=$id");
        die();
    }
}

$units = get_unit($_GET['id']);

?>
    <br>
    <br>
    <div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="text-center">
                <img class="img-thumbnail" src="img/dp.jpg" alt="" width="72" height="72">
                <h1>Reservation form</h1>
                <p class="lead">Please fill up the form and submit your reservation.</p>
            </div>
            <?php if(count($errors) > 0) :?>
                <div class="panel panel-danger" style="margin: 0">
                    <div class="panel-heading">
                        <?=implode('<br>', $errors)?>
                    </div>
                </div>
            <?php endif?>

            <h4 class="mb-3">Personal information</h4>
            <form method="POST" >
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName">First name</label>
                        <input type="text" class="form-control" name="first_name" placeholder="" value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : '' ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">Last name</label>
                        <input type="text" class="form-control" name="last_name" placeholder="" value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : '' ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email">Email </label>
                        <input type="email" class="form-control" name="email" required placeholder="you@example.com" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">Mobile No.</label>
                        <input minlength="11" type="text" maxlength="13" class="form-control numeric" name="mobile" placeholder="0912xxxxxxx" value="<?php echo isset($_POST['mobile']) ? $_POST['mobile'] : '' ?>" required>
                    </div>
                </div>

                <hr class="mb-4">

                <h4 class="mb-3">Reservation</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cc-name">Unit</label>
                        <input type="text" class="form-control" readonly required value="<?php echo isset($_POST['name']) ? $_POST['name'] : $units[0]['name'] ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cc-name">From</label>
                        <input type="date" min="<?=date('Y-m-d', strtotime("+1 day"))?>" class="form-control" name="date_from" required value="<?php echo isset($_POST['date_from']) ? $_POST['date_from'] : '' ?>">
                        <input type="time" class="form-control" name="time_from" required value="<?php echo isset($_POST['time_from']) ? $_POST['time_from'] : '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="cc-number">To</label>
                        <input type="date" min="<?=date('Y-m-d', strtotime("+1 day"))?>" class="form-control" name="date_to" required value="<?php echo isset($_POST['date_to']) ? $_POST['date_to'] : '' ?>">
                        <input type="time" class="form-control" name="time_to" required value="<?php echo isset($_POST['time_to']) ? $_POST['time_to'] : '' ?>">
                    </div>
                </div>
                <hr class="mb-4">
                <input type="hidden" name="unit_id" value="<?=$units[0]['id']?>">
                <button class="btn btn-primary btn-lg btn-block" type="submit">Submit Reservation</button>
            </form>
        </div>
        <div class="col-md-4 ">
            <div class="jumbotron">
            <div class="container">
                <img src="img/<?= $units[0]['img'] ?>" alt="<?= $units[0]['name']; ?>" class="img-thumbnail img-responsive" width="200">
                <h3><?= $units[0]['name'];?></h3>
                <h6><?= $units[0]['description'];?></h6>
            </div>
            </div>
        </div>
    </div>
    </div>

<?php

include './footer.php';

?>