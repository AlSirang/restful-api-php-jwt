<?php

namespace App\Models;

use PDO;


class User
{
  /************ CONSTANTS *****************/
  private $table = 'users';
  /****************************************/

  private $id;
  private $username;
  private $passwordHash;
  private $salt;


  /************ GETTERS *****************/

  public function getId()
  {
    return $this->id;
  }

  public function getUsername()
  {
    return $this->username;
  }

  public function getPasswordHash()
  {
    return $this->passwordHash;
  }

  public function getSalt()
  {
    return $this->salt;
  }


  /************ QUERIES *****************/

  public function queryByUsername($username)
  {

    try {
      $usernameFiltered = filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      $sql = "SELECT * FROM `{$this->table}` WHERE username = :username";
      $stmt = connection()->prepare($sql);
      $stmt->bindParam(":username", $usernameFiltered, PDO::PARAM_STR);
      $stmt->execute();

      if ($stmt->rowCount() === 1) {
        $row = ($stmt->fetchAll(PDO::FETCH_ASSOC))[0];
        $this->id = $row['user_id'];
        $this->username = $row['username'];
        $this->passwordHash = $row['password_hash'];
        $this->salt = $row['salt'];
        return true;
      }
      return false;
    } catch (\Throwable $th) {
      //throw $th;
      echo $th->getMessage();
      return false;
    }
  }


  public function createUser($usernameInput, $passwordInput)
  {
    try {

      // Get the user's input from the form and sanitize it
      $username = filter_var($usernameInput, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $password = filter_var($passwordInput, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      // Generate a random salt
      $salt = password_hash(random_bytes(16), PASSWORD_DEFAULT);

      // Combine the password and salt
      $passwordWithSalt = $password . $salt;

      // Hash the password with the salt
      $hashedPassword = password_hash($passwordWithSalt, PASSWORD_DEFAULT);

      $sql = "INSERT INTO {$this->table} (username, password_hash, salt) VALUES (:username, :password_hash, :salt)";

      // Perform the database insert operation
      $stmt = connection()->prepare($sql);
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':password_hash', $hashedPassword);
      $stmt->bindParam(':salt', $salt);
      $stmt->execute();


      // error false but 201
      return true;
    } catch (\Throwable $th) {
      //throw $th;
      return false;
    }
  }
}
