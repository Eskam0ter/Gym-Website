<?php
require_once 'connect.php';
/**
 * Class modelDb to handle interactions with the database.
 */
class modelDb
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
     * Creates a new reservation in the database.
     *
     * @param int    $user_id    The ID of the user making the reservation.
     * @param string $date       The date of the reservation.
     * @param string $time       The time of the reservation.
     * @param int    $trainer_id The ID of the trainer for the reservation.
     */
    public function createReservation($user_id ,$date, $time, $trainer_id) {
      $sql = 'INSERT INTO reservation (user_id, trainer_id, workout_date, workout_time) VALUES (:user_id, :trainer_id, :workout_date, :workout_time);';
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':workout_date', $date);
      $stmt->bindParam(':workout_time', $time);
      $stmt->bindParam(':trainer_id', $trainer_id);
      $stmt->bindParam(':user_id', $user_id);
      $stmt->execute();
    }

    /**
     * Updates an existing reservation with new date, time, and trainer information.
     *
     * @param string $new_date        The new date for the reservation.
     * @param string $new_time        The new time for the reservation.
     * @param int    $new_trainer_id  The new trainer ID for the reservation.
     * @param int    $reservation_id  The ID of the reservation to update.
     */
    public function updateReservation($new_date, $new_time, $new_trainer_id, $reservation_id) {
      $sql = "UPDATE reservation
      SET workout_date = :newDate, workout_time = :newTime, trainer_id = :newTrainer
      WHERE id = :reservationId";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':newDate', $new_date);
      $stmt->bindParam(':newTime', $new_time);
      $stmt->bindParam(':newTrainer', $new_trainer_id);
      $stmt->bindParam('reservationId', $reservation_id);
      $stmt->execute();
    }

    /**
     * Deletes a reservation from the database.
     *
     * @param int $reservation_id The ID of the reservation to delete.
     */
    public function deleteReservation($reservation_id) {
      $sql = "DELETE FROM reservation WHERE id = :reservation_id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam('reservation_id', $reservation_id);
      $stmt->execute();
    }

    /**
     * Retrieves a specified number of reservations for a user with pagination.
     *
     * @param int $itemsPerPage The number of reservations per page.
     * @param int $offset       The offset for pagination.
     * @param int $user_id      The ID of the user to retrieve reservations for.
     *
     * @return array Returns an array of reservations.
     */
    public function getReservations($itemsPerPage, $offset, $user_id){
      $sql = "SELECT * FROM reservation WHERE user_id = :user_id ORDER BY workout_date, workout_time ASC LIMIT :itemsPerPage OFFSET :offset";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
      $stmt->bindParam(":itemsPerPage", $itemsPerPage, PDO::PARAM_INT);
      $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Retrieves information about a reservation based on the provided reservation ID.
     *
     * @param int $reservation_id The ID of the reservation to retrieve.
     *
     * @return array Returns an array of reservation information.
     */
    public function getReservationById($reservation_id) {
      $sql = "SELECT * FROM reservation WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(":id", $reservation_id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the count of records for a particular table (used for pagination).
     *
     * @param string $tableName The name of the table to count records from.
     * @param int    $user_id    The ID of the user associated with the records.
     *
     * @return int Returns the count of records.
     */
    public function getCountOfRecords($tableName, $user_id) {
      $sql = "SELECT COUNT(*) as result_records FROM $tableName WHERE user_id = :user_id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(":user_id", $user_id);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['result_records'];
    }

    /**
     * Checks if a user with a given email exists in the database.
     *
     * @param string $email The email to check.
     *
     * @return bool Returns true if the user exists, false otherwise.
     */
    public function userExists($email)
    {

        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user)
            return true;
        else
            return false; // Returns true if user exists, false otherwise
    }

    /**
     * Retrieves user information based on the provided email.
     *
     * @param string $email The email of the user.
     *
     * @return array|null Returns an array of user information if the user exists, null otherwise.
     */
    public function getUser($email) {
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    /**
     * Retrieves user information based on the provided user ID.
     *
     * @param int $id The ID of the user.
     *
     * @return array Returns an array of user information.
     */
    public function getUserById($id)
    {
        $sql = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
    /**
     * Changes the size of the uploaded image and saves it.
     *
     * @param int $user_id The user ID to associate with the image.
     * @param array $image The $_FILES array representing the uploaded image.
     *
     * @return string|false The path to the resized and saved image or false on failure.
     *
     * @throws Exception If there is an error in processing the image.
     */
    private function changeImageSize($user_id, $image) {
      $file_name = $user_id . 'avatar';
          $file_size = $image["size"];
          $file_tmp = $image["tmp_name"];
          $file_type = $image["type"];
          $allowed_types = array('image/jpeg', 'image/png');

          try {
            $imageType = @exif_imagetype($image['tmp_name']);
      
            if ($imageType === false) {

                throw new Exception('Failed getting image type');
            }

        } catch (Exception $e) {
            return false;
        }

          if (in_array($file_type, $allowed_types) === false) {
            $_SESSION['error_msg'] = 'Only allowed image types JPEG, PNG';
            return false;
          }

          $upload_path = 'img/' . $user_id . 'avatar';

          if ($file_size > 5242880) {
            $_SESSION['error_msg'] = 'File is too large. Maximum file size is 5 MB';
            return false;
          }

              move_uploaded_file($file_tmp, $upload_path);

              list($originalWidth, $originalHeight, $type) = getimagesize($upload_path);
            
              $newWidth = 250;
              $newHeight = 250;

              switch ($type) {
                  case IMAGETYPE_JPEG:
                      $originalImage = imagecreatefromjpeg($upload_path);
                      break;
                  case IMAGETYPE_PNG:
                      $originalImage = imagecreatefrompng($upload_path);
                      break;
                  default:
                    return false;
              }

              $newImage = imagecreatetruecolor($newWidth, $newHeight);
              imagecopyresampled($newImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

              imagejpeg($newImage, 'img/' . $file_name, 85);
              imagedestroy($originalImage);
              imagedestroy($newImage);
              return 'img/'.$file_name;
    }

    /**
     * Updates user information (name, last name, email, or avatar) based on the provided parameters.
     *
     * @param int    $user_id   The ID of the user to update.
     * @param string $name      The new name of the user.
     * @param string $last_name The new last name of the user.
     * @param string $email     The new email of the user.
     * @param array  $image     The new image (avatar) of the user.
     */
    public function editUserData($user_id, $name=null, $last_name=null, $email=null, $image=null) {
        if ($name != null) {
            $sql = "UPDATE user SET first_name = :name WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':id', $user_id);

            $stmt->execute();
        }
        if ($last_name != null) {
            $sql = "UPDATE user SET last_name = :last_name WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':id', $user_id);

            $stmt->execute();
        }
        if ($email != null) {
            $user = $this->getUser($email);
            if ($user == null) {
              $sql = "UPDATE user SET email = :email WHERE id = :id";
              $stmt = $this->pdo->prepare($sql);
  
              $stmt->bindParam(':email', $email);
              $stmt->bindParam(':id', $user_id);
  
              $stmt->execute();
            }
        }
        if ($image != null) {

              $result = $this->changeImageSize($user_id, $image);
              if (!$result)
                return false;
              $sql = "UPDATE user SET avatar = :avatar WHERE id = :id";
              $stmt = $this->pdo->prepare($sql);

              $stmt->bindParam(':avatar', $result);
              $stmt->bindParam(':id', $user_id);

              $stmt->execute();


          }
      }
}