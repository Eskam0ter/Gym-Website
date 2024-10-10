<?php
ob_start();
/**
 * Set session name.
 * Start session if not already started.
 */
if (session_status() == PHP_SESSION_NONE) {
  session_name("GYM_session_no_one_knows");
  session_start();
}
/**
 * Redirect to signin.php if user is not logged in.
 */
if (!isset($_SESSION['user_id']))
    header('Location: signin.php');

/**
 * Generate and set CSRF token if not already set.
 */
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once 'connect.php';
require_once 'model_db.php';
global $pdo;


/**
 * Check if there is an error message in the session.
 */
if (isset($_SESSION['error_msg'])){
  $error_msg = $_SESSION['error_msg'];
  unset($_SESSION['error_msg']);
}


  /**
 * Model Database object.
 * @var modelDb
 */
$user_id = $_SESSION['user_id'];
$modelDb = new modelDb($pdo);

/**
 * Check if the CSRF token is present in the POST request and matches the session token.
 */
if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
  if (!empty($_FILES["avatar"]["name"])) {
    if ($_FILES["avatar"]["type"] == 'image/jpeg' || $_FILES["avatar"]["type"] == 'image/png'){ 
      if ($modelDb->editUserData($user_id,null, null, null, $_FILES['avatar']) === false) {
        $_SESSION['error_msg'] .= ' Something went wrong ';
        header('Location: account.php?edit=true');
      }
    }
    else {
      $_SESSION['error_msg'] .= ' Invalid image format ';
      header('Location: account.php?edit=true');
    }
  }
  if (isset($_POST['name']) && !empty($_POST['name'])) {
    if (strlen($_POST['name']) <= 50){
      $modelDb->editUserData($user_id, $_POST['name']);
    }
    else {
      $_SESSION['error_msg'] .= ' Name is too long ';
      header('Location: account.php?edit=true');
    }
  }
  if (isset($_POST['surname']) &&  !empty($_POST['surname'])) {
    if (strlen($_POST['surname']) <= 50)
      $modelDb->editUserData($user_id,null, $_POST['surname']);
    else {
      $_SESSION['error_msg'] .= ' Surname is too long ';
      header('Location: account.php?edit=true');
    }
  }
  if (isset($_POST['email']) && !empty($_POST['email'])) {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error_msg'] .= ' Invalid email ';
      header('Location: account.php?edit=true');
    }
    else if ($modelDb->getUser($_POST['email'])) {
      $_SESSION['error_msg'] .= ' User with this email already exists ';
      header('Location: account.php?edit=true');
    }
    else {
      $modelDb->editUserData($user_id,null, null, $_POST['email']);
    }
  }
  unset($_SESSION['csrf_token']);
}


/**
 * User ID from the session.
 * @var int
 */
$user = $modelDb->getUserById($user_id);


/**
 * Logout if exit parameter is set to true.
 */
if (isset($_GET['exit']) && $_GET['exit']) {

    session_unset();

    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
          $params["path"], $params["domain"],
          $params["secure"], $params["httponly"]
      );
  }

    session_destroy();

    header('Location: index.php');
    exit();
}
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
      <a href="account.php?exit=true" class="button-link header-btn uppercase">Sign out</a>
    </div>
  </div>
</header>

<main>
  <div class="container flex account-container">

      <?php if (isset($_GET['edit']) && $_GET['edit']) {
          echo '
<div class="flex">
<form action="account.php?save=true" method="post" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="';echo $_SESSION['csrf_token']; echo '">
    <div>    
    <img class="account-img" src=';
    echo './' . htmlspecialchars($user["avatar"]) . ' alt="Profile picture">
    </div>
          <ul class="account-list list-reset">
          <li class="list-item">
            <label for="avatar">Your avatar(JPEG, PNG):</label>
            <input id="avatar" type="file" name="avatar" accept="image/*">
          </li>
          <li class="list-item">
              <label for="name">Name:</label>
              <input class="sign-up-input" type="text" id="name" name="name">
          </li>
          <li class="list-item">
              <label for="surname">Surname:</label>
      <input class="sign-up-input" type="text" id="surname" name="surname">
          </li>
          <li class="list-item">
              <label for="email">Email:</label>
      <input class="sign-up-input" type="email" id="email" name="email">
          </li>
      </ul>
      <div class="red error" id="error">'; if (isset($error_msg)) echo $error_msg; echo '</div>
        <button type="submit" class="uppercase btn btn-reset header-btn">Save</button>
           
           <a href="account.php" class="">Cancel</a>
</form>
</div>
';

      }
      else {
          echo '    
    <img class="account-img" src=';
     echo './' . $user["avatar"] . ' alt="Profile picture">
<ul class="account-list list-reset">
          <li class="list-item">
              Name: '; if (isset($user['first_name']))echo htmlspecialchars($user['first_name']) ;
          echo '          </li>
          <li class="list-item">
              Surname: '; if (isset($user['last_name']))echo htmlspecialchars($user['last_name']);
          echo '
          </li>
          <li class="list-item">
              Email: '; if (isset($user['email']))echo htmlspecialchars($user['email']);
          echo '
          </li>
      </ul>
      <div><a href="account.php?edit=true" class="header-btn button-link uppercase">Edit</a></div>
          ';
      }

      ?>




  </div>
</main>
</body>
</html>