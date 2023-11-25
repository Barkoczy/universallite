<?php
namespace App\Kernel\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Kernel\Controllers\Controller;
use App\Kernel\Language\Enum\SchemaParams;

class LocalizationController extends Controller
{
  public function __invoke(Request $request, Response $response, array $args): Response
  {
    $this->lang->rewriteLocale(
      $args[SchemaParams::iso], $args[SchemaParams::locality]
    );
    return $response->withHeader('Location', '/')->withStatus(302);
  }
}