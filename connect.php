<?php
/**
 * Include the configuration file which contains database constants.
 */
require_once('config.php');

/**
 * Database connection using PDO.
 *
 * @var PDO|null
 */
$pdo = null;

// Try to establish a connection to the database using PDO
try {
        /**
     * Create a new PDO instance with the specified database connection parameters.
     */

    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);

        /**
     * Set PDO attributes to handle errors by throwing exceptions.
     */
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    /**
     * If an exception is caught during the connection attempt, display an error message and terminate the script.
     */
    die("Connection failed: " . $e->getMessage());
}
?>
