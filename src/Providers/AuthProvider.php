<?php
namespace App\Providers;

class AuthProvider 
{
  const KEY = 'user';

  /**
   * @return boolean
   */
  public static function logged(): bool
  {
    return isset($_SESSION[self::KEY]);
  }

  /**
   * @return boolean
   */
  public static function expired(): bool
  {
    return strtotime("now") > $_SESSION[self::KEY]['exp'];
  }

  /**
   * @return array
   */
  public function user(): array
  {
    return static::logged() ? $_SESSION[self::KEY] : [];
  }

  /**
   * Login
   *
   * @param string $jwt
   * @return array
   */
  public function login(string $jwt = ''): array
  {
    $data = [
      'exp' => strtotime("now + 60 minutes")
    ];

    $_SESSION[self::KEY] = $data;

    return $data;
  }

  /**
   * Logout
   *
   * @return array
   */
  public function logout(): array
  {
    unset($_SESSION[self::KEY]);

    return [
      'state' => 'success'
    ];
  }
}