<?php
ob_start();

// Start or resume the session
if (session_status() == PHP_SESSION_NONE) {
  session_name("GYM_session_no_one_knows");
  session_start();
}
require_once('connect.php');
require_once('model_db.php');

// Check if email and password are set in the POST request
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $modelDb = new modelDb($pdo);
    $user = $modelDb->getUser($email);

    if ($user) {
        $passwordHash = $user['password'];
        $salt = $user['salt'];
        $user_id = $user['id'];

        if (check_password($password, $salt, $passwordHash)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user'] = $user;
            header("Location: account.php");
        }
        else {
          $error_msg = 'Incorrect login or password. Please verify your credentials and attempt to log in again.';
          include 'signin.php';
          exit();
        }
    } else {
      $error_msg = 'Incorrect login or password. Please verify your credentials and attempt to log in again.';
      include 'signin.php';
      exit();
    }
}
else {
  $error_msg = 'Not valid input';
      include 'signin.php';
}

/**
 * Checks if the provided password matches the stored hashed password.
 *
 * @param string $password        The provided password.
 * @param string $salt            The salt used during password hashing.
 * @param string $hashed_password The stored hashed password.
 *
 * @return bool Returns true if the password matches, false otherwise.
 */
function check_password($password, $salt, $hashed_password) {
    $saltedPass = $password . $salt;
    return password_verify($saltedPass, $hashed_password);
}
