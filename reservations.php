<?php
// Set the session name and start the session if not already started
session_name("GYM_session_no_one_knows");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Redirect to signin.php if user_id is not set in the session
if (!isset($_SESSION['user_id'])){

  header('Location: signin.php');

}

// Logout logic: unset session and destroy session data
if (isset($_GET['exit']) && $_GET['exit']) {
  session_unset();

  session_destroy();

  header('Location: index.php');
  exit();
}
require 'model_db.php';
require 'connect.php';

// Display error message if set in the session
if (isset($_SESSION['error_msg']))
  $error_msg = $_SESSION['error_msg'];

$modelDb = new modelDB($pdo);
$user_id = $_SESSION['user_id'];

// Generate and set CSRF token if not already set
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Handle form submissions when CSRF token is valid
if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
  if (isset($_POST['date']) && isset($_POST['time']) && isset($_POST['trainer']) && isset($_GET['save']) && $_GET['save'] == 1) {
    try {
      $dateTime = new DateTime($_POST['date']);
      $dateTime = new DateTime($_POST['time']);
      $modelDb->createReservation($user_id ,$_POST['date'], $_POST['time'], $_POST['trainer']);
  } catch (Exception $e) {
      $_SESSION['error_msg'] = "Invalid input";
      header('Location: reservations.php?create=1');
  }
    
  }
  if (isset($_POST['new_date']) || isset($_POST['new_time']) || isset($_POST['new_trainer']) && isset($_GET['save']) && $_GET['save'] == 2) {
    try {
      $dateTime = new DateTime($_POST['new_date']);
      $dateTime = new DateTime($_POST['new_time']);
      $modelDb->updateReservation($_POST['new_date'], $_POST['new_time'], $_POST['new_trainer'], $_POST['reservation_id']);
  } catch (Exception $e) {
      $_SESSION['error_msg'] = "Invalid input";
      header('Location: reservations.php?edit=1');
  }
  }
unset($_SESSION['csrf_token']); 
}

if (isset($_GET['delete']) && $_GET['delete'] == 1 && isset($_SESSION['reservation_id'])){
  $reservation = $modelDb->getReservationById($_SESSION['reservation_id']);
  if ($reservation['user_id'] === $_SESSION['user_id']) {
    $modelDb->deleteReservation($_SESSION['reservation_id']);
    unset($_SESSION['reservation_id']);
  }
}

$itemsPerPage = 3;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $itemsPerPage;
$data = $modelDb->getReservations($itemsPerPage, $offset, $user_id);
$totalRecords = $modelDb->getCountOfRecords('reservation', $user_id);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Account</title>
  <base href="/~vakhodan/">
  <link rel="stylesheet" href="./css/normalize.css">
  <link rel="stylesheet" href="./css/styles.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
</head>
<body>
<header class="header">
  <div class="container flex header-container">
    <a class="header-logo header-logo-link" href="index.php">
      <img class="header-logo header-logo-image" src="./img/logo.svg"  alt="Logo">
    </a>
    <nav class="header-nav uppercase">
      <ul class="header-list list-reset flex">
        <li class="header-list-item">
          <a class="header-link" href="account.php">
            my account
          </a>
        </li>
        <li class="header-list-item">
          <a class="header-link" href="reservations.php">
            my reservations
          </a>
      </ul>
    </nav>
    <div class="header-btns flex">
      <a href="reservations.php?exit=true" class="button-link header-btn uppercase">Sign out</a>
    </div>
  </div>
</header>
<main>
    <div class="container">
        <h1 class="section-title uppercase reservations-title section-offset">reservations</h1>
        <?php if (isset($_GET['create']) && $_GET['create'] == 1) {
          echo '<form action="reservations.php?save=1" method="post">
                    <input type="hidden" name="csrf_token" value="';echo $_SESSION['csrf_token']; echo '">
                    <ul class="account-list list-reset">
                    <li class="list-item">
                      <label for="date">Date:</label>
                      <input class="sign-up-input" type="date" id="date" name="date" value="'; echo date("Y-m-d") . '" min="'; echo date ("Y-m-d") . '" required>
                    </li>
                    <li class="list-item">
                      <label for="time">Time:</label>
                      <input class="sign-up-input" type="time" id="time" name="time" value="'; echo date("H:i") . '" required>
                    </li>
                    <li class="list-item">
                        <label for="trainer">Trainer:</label>
                        <select class="sign-up-input" id="trainer" name="trainer" required>
                        <option value="1">Daniil V.</option>
                        <option value="2">Petr S.</option>
                        </select>
                    </li>
                </ul>
                <button type="submit" class="uppercase btn btn-reset header-btn">Save</button>
                <a href="reservations.php" class="">Cancel</a>
                </form>
      <div class="red error" id="error">'; if (isset($error_msg)) echo $error_msg; unset($_SESSION['error_msg']); echo '</div>
                ';
        }
        else if (isset($_GET['edit']) && $_GET['edit'] == 1 && !empty($_GET['reservation_id'])) {
          $_SESSION['reservation_id'] = $_GET['reservation_id'];
          echo '<form action="reservations.php?save=2" method="post">
                    <input type="hidden" name="csrf_token" value="';echo $_SESSION['csrf_token']; echo '">
                    <input type="hidden" name="reservation_id" value="';echo $_GET['reservation_id']; echo '">
                    <ul class="account-list list-reset">
                    <li class="list-item">
                      <label for="date">Date:</label>
                      <input class="sign-up-input" type="date" id="date" name="new_date" value="' . $_GET['reservation_date'] . '" min="'; echo date ("Y-m-d") . '" required>
                    </li>
                    <li class="list-item">
                      <label for="time">Time:</label>
                      <input class="sign-up-input" type="time" id="time" name="new_time" value="' . $_GET['reservation_time'] . '" required>
                    </li>
                    <li class="list-item">
                        <label for="trainer">Trainer:</label>
                        <select class="sign-up-input" id="trainer" name="new_trainer" required>
                        <option value="1"';echo ($_GET['trainer_id'] === '1') ? 'selected' : ''; echo '>Daniil V.</option>
                        <option value="2"';echo ($_GET['trainer_id'] === '2') ? 'selected' : ''; echo '>Petr S.</option>
                        </select>
                    </li>
                </ul>
                <button type="submit" class="uppercase btn btn-reset header-btn">Save</button>
                <a href="reservations.php?delete=1" class="uppercase red">delete</a>
                </form>
                <a href="reservations.php" class="uppercase">Cancel</a>';
        }
        else {
          echo '
        <div class="flex reservations-container">
        <div>
          <ol class="account-list">';
               foreach ($data as $item): 
                  $date = new DateTime($item['workout_date']);
                  $time = new DateTime($item['workout_time']);

                  echo '<li class="list-item">'; echo 'Reservation date: ' . $date->format('m.d.Y') . 
                  ' Reservation time: ' . 
                  $time->format('H:i') . ' Trainer: ';
                  switch ($item['trainer_id']) {
                    case '1': echo 'Daniil V.';
                    break;
                    case '2': echo 'Petr S.';
                    break;
                  }
                  echo '<a class="button-link header-btn uppercase" href="reservations.php?edit=1&reservation_id=' . $item['id'] . '&reservation_date='
                   . $item['workout_date'] . '&reservation_time=' . $item['workout_time'] . '&trainer_id=' . $item['trainer_id'] .
                   '" class="">Edit</a> </li>'; 
              endforeach; 
          echo '</ol>';
          $totalPages = ceil($totalRecords / $itemsPerPage);
          for ($i = 1; $i <= $totalPages; $i++) {
              echo "<a href='reservations.php?page=$i'>$i</a> - ";
          }
          echo '</div>
          <div>
          <a href="reservations.php?create=1" class="header-btn button-link uppercase">Create new</a>
          </div>';
        }
          ?>

        
        </div>
    </div>
</main>
</body>
</html>