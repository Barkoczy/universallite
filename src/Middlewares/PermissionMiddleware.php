<?php
namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class PermissionMiddleware
{
  public static function handle(Request $request, RequestHandler $handler, array $route = []): Response
  {
    return $handler->handle($request);
  }
}