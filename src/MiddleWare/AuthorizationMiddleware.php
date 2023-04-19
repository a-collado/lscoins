<?php
declare(strict_types=1);

namespace Salle\LSCoins\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Flash\Messages;
use Slim\Routing\RouteContext;

final class AuthorizationMiddleware
{
    public function __construct(
        private Messages $flash)
    {}
    public function __invoke(Request $request, RequestHandler $next): Response
    {
        if (isset($_SESSION['user']) && $_SESSION['user'] != null){

            return $next->handle($request);
        } else {

            $path   = $request->getUri()->getPath();
            $parts  = explode('/', $path);
            $name   = array_pop($parts);

            $this->flash->addMessage(
                'notifications',
                'You must be logged in to access the ' . $name . ' page.'
            );
            $response = $next->handle($request);
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();

            return $response
                ->withHeader('Location',  $routeParser->urlFor("sign-in"));
        }
    }
}