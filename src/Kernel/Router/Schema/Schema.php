<?php
namespace App\Kernel\Router\Schema;

use App\Kernel\Router\Enum\Method;
use App\Kernel\Router\Enum\RouteObject;
use App\Kernel\Router\Enum\Controllers;
use App\Kernel\Exceptions\MissingParameter;

abstract class Schema
{
  public $method;
  public $url;
  public $controller;
  public $template;
  public $auth;
  public $meta;
  public $data;

  /**
   * Initialize
   *
   * @param array $route
   */
  public function __construct(array $route = [])
  {
    $this->setMethod($route);
    $this->setURL($route);
    $this->setController($route);
    $this->setTemplate($route);
    $this->setAuth($route);
    $this->setMeta($route);
    $this->setData($route);
  }
  
  /**
   * Http Request Method
   *
   * @param array $route
   * @return void
   */
  public function setMethod(array $route = []): void
  {
    if (isset($route[RouteObject::method]) &&
      in_array($route[RouteObject::method],[Method::GET,Method::POST])
    ) {
      $this->method = $route[RouteObject::method];
    } else  {
      $this->method = Method::GET;
    }
  }

  /**
   * URL
   *
   * @param array $route
   * @return void
   */
  public function setURL(array $route = []): void
  {
    if (!isset($route[RouteObject::url]))
      throw new MissingParameter('Missing parameter url in routes configuration file');

    $this->url = $route[RouteObject::url];
  }

  /**
   * Controller
   *
   * @param array $route
   * @return void
   */
  public function setController(array $route = []): void
  {
    if (isset($route[RouteObject::controller]) &&
      0 < strlen($route[RouteObject::controller])
    ) {
      $this->controller = $route[RouteObject::controller];
    } else {
      $this->controller = Controllers::base;
    }
  }

  /**
   * Template
   *
   * @param array $route
   * @return void
   */
  abstract public function setTemplate(array $route = []): void;

  /**
   * Auth
   *
   * @param array $route
   * @return void
   */
  public function setAuth(array $route = []): void
  {
    $this->auth = isset($route[RouteObject::auth]) 
      ? (bool) $route[RouteObject::auth]
      : false;
  }

  /**
   * Meta
   *
   * @param array $route
   * @return void
   */
  public function setMeta(array $route = []): void
  {
    $this->meta = $route[RouteObject::meta];
  }

  /**
   * Data
   *
   * @param array $route
   * @return void
   */
  public function setData(array $route = []): void
  {
    $this->data = $route[RouteObject::data];
  }
}