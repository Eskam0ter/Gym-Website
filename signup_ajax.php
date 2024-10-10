<?php
require_once("model_db.php");
require_once("connect.php");
ob_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Get data from the request body and validate it
    $error = validateData($pdo);

    $response = array("status" => "success", "message" => $error);
    echo json_encode($response);
} else {
    $response = array("status" => "error", "message" => "Invalid request type");
    echo json_encode($response);
}


/**
 * Validates the data received in the request.
 *
 * @param PDO $pdo A PDO instance for database connection.
 *
 * @return string The error message if validation fails, an empty string otherwise.
 */
function validateData($pdo) {
  $error = '';
  $modelDb = new modelDb($pdo); ;
   if ($modelDb->getUser($_POST['email'])) {
    $error = 'The user with this email already exists.';
  }
  return $error;
}