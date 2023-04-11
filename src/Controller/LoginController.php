<?php

declare(strict_types=1);

namespace Salle\LSCoins\Controller;

use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class LoginController
{
    public function __construct(
        private Twig $twig
    )
    {
    }

    public function showLoginFormAction(Request $request, Response $response): Response
    {

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $this->twig->render(
            $response, 
            'sign-in.twig', 
            [
                'formAction' => $routeParser->urlFor("sign-in"),
                'formMethod' => "POST"
            ]
        );
    }

    public function loginAction(Request $request, Response $response): Response
    {
        // This method decodes the received json
        $data = $request->getParsedBody();

        $errors = $this->validate($data);

        if (count($errors) > 0) {
            //$response->getBody()->write(json_encode(['errors' => $errors]));
            //return $response->withHeader('Content-Type', 'application/json')->withStatus(412);
            return $this->twig->render(
                $response,
                'sign-up.twig',
                [
                    'formErrors' => $errors,
                    'formData' => $data,
                    'formAction' => $routeParser->urlFor("sign-in"),
                    'formMethod' => "POST"
                ]
            );
        }

        $response->getBody()->write(json_encode(['responseData' => 'everything is fine']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    private function validate(array $data): array
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username'] = 'The username cannot be empty.';
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors['password'] = 'The password must contain at least 6 characters.';
        }

        return $errors;
    }
}