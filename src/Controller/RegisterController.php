<?php

declare(strict_types=1);

namespace Salle\LSCoins\Controller;

use DateTime;
use Exception;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Salle\LSCoins\Model\MySQLUserRepository;
use Salle\LSCoins\Model\PDOSingleton;
use Salle\LSCoins\Model\User;
use Salle\LSCoins\Model\UserRepository;

final class RegisterController
{
    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository
    )
    {
    }

    public function showRegisterFormAction(Request $request, Response $response): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $this->twig->render(
            $response, 
            'sign-up.twig', 
            [
                'formAction' => $routeParser->urlFor("sign-up"),
                'formMethod' => "POST"
            ]
        );
    }

    public function registerAction(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $errors = $this->validate($data);

        if (count($errors) > 0) {
            //$response->getBody()->write(json_encode(['errors' => $errors]));
            //return $response->withHeader('Content-Type', 'application/json')->withStatus(412);
            return $this->twig->render(
                $response->withStatus(412),
                'sign-up.twig',
                [
                    'formErrors' => $errors,
                    'formData' => $data,
                    'formAction' => $routeParser->urlFor("sign-up")
                ]
            );
        }

        //$response->getBody()->write(json_encode(['responseData' => 'everything is fine']));
        //return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        try {
            $data = $request->getParsedBody();

            $user = new User(
                $data['email'] ?? '',
                $data['password'] ?? '',
                intval($data['coins']) ?? '',
                new DateTime(),
                new DateTime()
            );

            $this->userRepository->save($user);
            return $response->withHeader('Location', $routeParser->urlFor("show-sign-in"))->withStatus(302);
        } catch (Exception $exception) {
            
            return $this->twig->render( $response->withStatus(500), 'sign-up.twig', [] );
            
        }

        
    }

    private function validate(array $data): array
    {
        $errors = [];
        $uppercase = preg_match('@[A-Z]@', $data['password']);
        $lowercase = preg_match('@[a-z]@', $data['password']);
        $number    = preg_match('@[0-9]@', $data['password']);


        if (empty($data['email'])) {
            $errors['email'] = 'The email cannot be empty.';
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors['password'] = 'The password must contain at least 6 characters.';
        }

        if (!empty($data['coins']) && ( intval($data['coins']) < 50 || intval($data['coins']) > 30000 ) ) {
            $errors['coins'] = 'Sorry, the number of LSCoins is either below or above the limits.';
        }

        return $errors;
    }
}