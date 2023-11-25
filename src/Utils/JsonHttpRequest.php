<?php
namespace App\Utils;

use App\Kernel\Router\Enum\Method;

final class JsonHttpRequest
{
  /** @var array  */
  private $options = [];

  /**
   * Construct
   *
   * @param array $data
   * @param string $method
   */
  public function __construct(array $data = [], string $method = Method::POST)
  {
    $this->options = [
      'http' => [
        'method'  => $method,
        'content' => json_encode($data),
        'header'  =>  "Content-Type: application/json\r\n" .
                      "Accept: application/json\r\n"
      ]
    ];
  }

  /**
   * Fetch
   *
   * @param string $url
   * @return void
   */
  public function fetch(string $url = ''): array
  {
    $context = stream_context_create($this->options);
    $result  = file_get_contents($url, false, $context);
  
    return $result === false ? [] : json_decode($result, true);
  }
}