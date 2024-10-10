<?php

// Include the database connection script
require_once 'connect.php';

/**
 * Class CreateUser that encapsulates the functionality to create a user in a database,
 * including password hashing with a generated salt.
 */
class CreateUser
{
  /**
     * @var PDO Private property to store the PDO instance.
     */
    private $pdo; 

    /**
     * Constructor to initialize the class with a PDO instance.
     *
     * @param PDO $pdo A PDO instance for database connection.
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

        /**
     * Method to create a new user in the database.
     *
     * @param string $email    The email of the user.
     * @param string $password The password of the user.
     *
     * @return bool Returns true if the user creation is successful, false otherwise.
     */
    function create_User($email, $password)
    {
        // Generate a unique salt for the user
        $salt = $this->generate_salt();

        // Hash the password using the generated salt
        $hashedPassword = $this->hash_password($password, $salt);

        // SQL query to insert user data into the 'user' table
        $sql = "INSERT INTO user (email, password, salt) VALUES (:email, :password, :salt)";

        // Prepare the SQL statement
        $stmt = $this->pdo->prepare($sql);

        // Bind values to parameters
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":password", $hashedPassword);
        $stmt->bindValue(":salt", $salt);

        // Execute the SQL statement and return the result
        return $stmt->execute();
    }

    /**
     * Private method to generate a random salt.
     *
     * @return string Returns a random salt.
     */
    private function generate_salt() {
        return bin2hex(random_bytes(16));
    }

    /**
     * Private method to hash the password using PBKDF2.
     *
     * @param string $password The password to be hashed.
     * @param string $salt     The salt to be used in the hashing process.
     *
     * @return false|string Returns the hashed password.
     */
    private function hash_password($password, $salt) {
        // Combine the password and salt
        $salted_password = $password . $salt;

        // Hash the salted password using the default password hashing algorithm (PASSWORD_DEFAULT)
        $hashed_password = password_hash($salted_password, PASSWORD_DEFAULT);

        // Return the hashed password
        return $hashed_password;
    }
}
?>
