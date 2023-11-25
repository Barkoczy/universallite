<?php
namespace App\Kernel\Router;

use App\Kernel\Router\Entity\Welcome;
use App\Kernel\Router\Enum\RouterSchema;
use App\Kernel\Router\PageRouteList;
use App\Kernel\Router\ApiRouteList;
use App\Kernel\Router\DashboardRouteList;
use App\Kernel\Router\Schema\Api as ApiSchema;
use App\Kernel\Router\Schema\Dashboard as DashboardSchema;
use App\Kernel\Router\Schema\Page as PageSchema;

final class Router
{
  /**
   * Find route config by URL
   *
   * @return array
   */
  public function findByURL(): array
  {
    // @apiConf
    $apiConf = (new ApiRouteList())->findByURL();

    // @validate
    if ([] !== $apiConf)
      return $this->route(RouterSchema::API, $apiConf);

    // @dashboardConf
    $dashboardConf = (new DashboardRouteList())->findByURL();

    // @validate
    if ([] !== $dashboardConf)
      return $this->route(RouterSchema::DASHBOARD, $dashboardConf);

    // @pageConf
    $pageConf = (new PageRouteList())->findByURL();

    // @validate
    if ([] !== $pageConf)
      return $this->route(RouterSchema::PAGE, $pageConf);

    // @default
    return $this->defaultRoute();
  }

  /**
   * Route
   *
   * @param string $schema
   * @param array $conf
   * @return array
   */
  private function route(string $schema = '', array $conf = []): array
  {
    // @api
    if(RouterSchema::API === $schema)
      return (array)(new ApiSchema($conf));

    // @dashboard
    if(RouterSchema::DASHBOARD === $schema)
      return (array)(new DashboardSchema($conf));

    // @page
    if(RouterSchema::PAGE === $schema)
      return (array)(new PageSchema($conf));
  }

  /**
   * Default route
   *
   * @return array
   */
  private function defaultRoute(): array
  {
    return (array)(new Welcome());
  }
}