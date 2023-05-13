<?php

namespace App;

use App\Models\User;
use Exception;
use Firebase\JWT\JWT;

class JwtAuth
{
  public static $secretKey;



  public static function setSecretKey($secretKey)
  {
    static::$secretKey = $secretKey;
  }

  /**
   * @dev returns the JWT token from header, if present. otherwise returns null
   */
  public static function getTokenFromHeaders()
  {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
      $authHeader = $headers['Authorization'];
      $token = substr($authHeader, 7); // Remove the "Bearer " prefix
      return $token;
    }
    return null;
  }

  public static function issueToken(User $user)
  {
    $issuedAt = time(); // Current timestamp
    $expirationTime = $issuedAt + (24 * 60 * 60); // 24 hours in seconds

    $payload = array(
      "sub" => $user->getUsername(), // Subject
      "iat" => $issuedAt, // Issued At (current time)
      'exp' => $expirationTime
    );

    return 'Bearer ' . JWT::encode($payload, static::$secretKey, 'HS256');
  }

  public static function validateToken($token)
  {
    try {
      $decoded = JWT::decode($token, static::$secretKey, array('HS256'));
      return  $decoded;
    } catch (Exception $e) {
      return false;
    }
  }
}
