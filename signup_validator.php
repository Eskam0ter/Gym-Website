<?php
ob_start();
session_name("GYM_session_no_one_knows");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once 'create_user.php';
require_once 'model_db.php';
require_once 'connect.php';
global $pdo;




if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if (!validateEmail($email)) {
        $error_msg = 'Enter a valid email address';
        include 'signup.php';
    }
    else if (alreadyExists($email, $pdo)) {
        $error_msg = 'User with this email already exists';
        include 'signup.php';
    }
    else if (!isPasswordCorrect($password)){
        $error_msg = 'Password should be at least 8 characters';
        include 'signup.php';
    }
    else if (!doPasswordsMatch($password, $confirm_password)) {
        $error_msg = 'Passwords do not match';
        include 'signup.php';
    }
    else {
        $reg = new CreateUser($pdo);
        $modelDB = new modelDb($pdo);
        if ($reg->create_User($email,$password))
            $user = $modelDB->getUser($email);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user;
            header("Location: account.php");
    }
}

/**
 * Checks if passwords match.
 *
 * @param string $password          The password to check.
 * @param string $confirm_password  The confirmation password to compare.
 *
 * @return bool Returns true if passwords match, false otherwise.
 */
function doPasswordsMatch ($password, $confirm_password) {
    return $password === $confirm_password;
}

/**
 * Checks if the password is correct (at least 8 characters).
 *
 * @param string $password The password to check.
 *
 * @return bool Returns true if the password is correct, false otherwise.
 */
function isPasswordCorrect($passwrod)
{
    return $passwrod >= 8;
}

/**
 * Checks if the user with the given email already exists.
 *
 * @param string $email The email to check.
 * @param PDO    $pdo   A PDO instance for database connection.
 *
 * @return bool Returns true if the user with the given email already exists, false otherwise.
 */
function alreadyExists($email, $pdo)
{
    $modelDB = new modelDB($pdo);
    return $modelDB->userExists($email);
}

/**
 * Validates email format.
 *
 * @param string $email The email to validate.
 *
 * @return bool Returns true if the email format is valid, false otherwise.
 */
function validateEmail($email) {
    $filteredEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    if ($filteredEmail !== false) {
        return true; 
    } else {
        return false; 
    }
}
