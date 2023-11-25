<?php
namespace App\Kernel\Router;

final class IPAddress 
{
  /** @var string **/
  protected $ipAddress;
  
  /**
   * Constructor
   */
  public function __construct() 
  {
    if(
      isset($_SERVER['HTTP_X_FORWARDED_FOR']) && 
      $_SERVER['HTTP_X_FORWARDED_FOR'] && 
      (
        !isset($_SERVER['REMOTE_ADDR']) || 
        preg_match('/^127\..*/i', trim($_SERVER['REMOTE_ADDR'])) || 
        preg_match('/^172\.16.*/i', trim($_SERVER['REMOTE_ADDR'])) || 
        preg_match('/^192\.168\.*/i', trim($_SERVER['REMOTE_ADDR'])) || 
        preg_match('/^10\..*/i', trim($_SERVER['REMOTE_ADDR']))
      )
    ) {
      if(strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        
        $this->ipAddress = $ips[0];
      } else {
        $this->ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
      }
    }
    $this->ipAddress = $_SERVER['REMOTE_ADDR'];
  }
  
  /**
   * @return string
   */
  public function get(): string
  {
    return $this->ipAddress;
  }

  /**
   * is LOCAHOST
   *
   * @return boolean
   */
  public function isLocalost(): bool
  {
    if (preg_match('/^((127\.)|(192\.168\.)|(10\.)|(172\.1[6-9]\.)|(172\.2[0-9]\.)|(172\.3[0-1]\.)|(::1)|(fe80::))/', $this->ipAddress)) {
      return true;
    } else {
      return false;
    }
  }
}