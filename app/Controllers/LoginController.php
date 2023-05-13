<?php

namespace App\Controllers;

use App\{JwtAuth, Request};
use App\Models\User;
use Exception;

class LoginController
{
  // private functions 
  private function verifyPassword(
    User $user,
    $passwordInput
  ) {

    try {
      $enteredPassword = filter_var($passwordInput, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $passwordWithRetrievedSalt = $enteredPassword . $user->getSalt();

      return password_verify(
        $passwordWithRetrievedSalt,
        $user->getPasswordHash(),
      );
    } catch (\Throwable $th) {
      echo $th->getMessage();
    }
    return false;
  }


  public function isUsernameExist($username)
  {
    return (new User())->queryByUsername($username);
  }


  public function login()
  {
    try {


      if (!isset($_POST['username']) || !isset($_POST['password'])) {
        http_response_code(400);
        echo "invalid username or password";
        return;
      }
      $username = Request::values()['username'];
      $password = Request::values()['password'];
      $user = new User();

      $response = $user->queryByUsername($username);

      if (
        $response
        &&
        $this->verifyPassword($user, $password)
      ) {


        $token = JwtAuth::issueToken($user);

        echo json_encode(
          [
            'username' => $user->getUsername(),
            'userId' => $user->getId(),
            "token" => $token
          ]
        );
        return;
      }
    } catch (Exception $e) {
      http_response_code(500);
      return;
    }

    // Invalid username or password
    http_response_code(401);
    echo "Unauthorized: Invalid username or password";
  }


  public function signup()
  {

    try {
      if (!isset($_POST['username']) || !isset($_POST['password'])) {
        http_response_code(400);
        echo "invalid username or password";
        return;
      }

      $username = Request::values()['username'];
      $password = Request::values()['password'];


      if ($this->isUsernameExist($username)) {
        http_response_code(409);
        echo "Conflict: Username is already taken";
        return;
      }

      $user = (new User())->createUser(
        $username,
        $password
      );

      if ($user) {
        http_response_code(201);
        return;
      }
    } catch (\Throwable $th) {
      //throw $th;
    }

    http_response_code(400);
  }
}
