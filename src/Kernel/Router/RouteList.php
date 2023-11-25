<?php
namespace App\Kernel\Router;

abstract class RouteList
{
  public $conf;

  /**
   * Constructor
   * 
   * @param array $conf
   */
  public function __construct(array $conf = []) {
    $this->conf = $conf;
  }

  abstract public function findByURL(): array;
  abstract public function findByController(string $controller = ''): array;

  /**
   * Find key in configuration file
   *
   * @param string $key
   * @param string $value
   * @return mixed
   */
  public function find(string $key = '', string $value = ''): mixed
  {
    // @not-exists
    if (!is_array($this->conf) || 0 === count($this->conf))
      return [];

    // @routes
    $routes = array_column(
      $this->conf, $key
    );

    // @index
    $index = array_search($value, $routes);

    // @found
    if ($index !== false)
      return $this->conf[$index];

    // @routes_regexps
    $routes_regexps = array_map(
      function($route){
        return 
          '#^' // RE delimiter and a string start
          . preg_replace("/\{(.*?)\}/", '(?<$1>[^/]+?)', $route) 
          . '$#' ; // String end and a RE delimiter
      }, 
      $routes
    );

    // @matches
    $matches = [];

    // @each
    foreach ($routes_regexps as $i => $re) {
      if (preg_match_all($re, $value, $matches)) {
        return $this->conf[$i];
      }
    }

    // @notFound
    return [];
  }
}