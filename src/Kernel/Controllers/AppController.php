<?php
namespace App\Kernel\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use App\Kernel\Router\Enum\RouteObject;

final class AppController
{
  private $request;
  private $args = [];
  private $conf = [];

  /**
   * Component conf
   *
   * @param array $conf
   */
  public function __construct(array $conf = [])
  {
    $this->conf = $conf;
  }

  /**
   * Request
   *
   * @param Request $request
   * @return void
   */
  public function request(Request $request): void
  {
    $this->request = $request;
  }

  /**
   * Request arguments
   *
   * @param array $args
   * @return void
   */
  public function args(array $args): void
  {
    $this->args = $args;
  }

  /**
   * Path to twig file
   *
   * @return string
   */
  public function template(): string
  {
    return $this->conf[RouteObject::template];
  }

  /**
   * Template variables
   *
   * @param array $data
   * @return array
   */
  public function data(array $data = []): array
  {
    return array_merge(
      $data, 
      (new DataComponent(
        $this->conf[RouteObject::data], 
        $this->conf[RouteObject::meta],
        $this->conf[RouteObject::url]
      ))->build()
    );
  }
}
