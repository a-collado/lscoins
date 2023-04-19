<?php

declare(strict_types=1);

namespace Salle\LSCoins\Controller;

use Exception;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Flash\Messages;
use Salle\LSCoins\Model\User;
use Salle\LSCoins\Model\UserRepository;

final class UserController
{
    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository,
        private Messages $flash
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

    public function showLoginFormAction(Request $request, Response $response): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $messages = $this->flash->getMessages();

        $notifications = $messages['notifications'] ?? [];

        return $this->twig->render(
            $response, 
            'sign-in.twig', 
            [
                'formAction' => $routeParser->urlFor("sign-in"),
                'formMethod' => "POST",
                'notifs' => $notifications
            ]
        );
    }

    public function registerAction(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $errors = $this->validate_common($data);
        $errors_register = $this->validate_sign_up($data, $errors);

        if (count($errors) > 0 || count($errors_register) > 0) {
            
            return $this->twig->render(
                $response->withStatus(412),
                'sign-up.twig',
                [
                    'formErrors' => array_merge($errors, $errors_register),
                    'formData' => $data,
                    'formAction' => $routeParser->urlFor("sign-up")
                ]
            );
        }

        try {
            $data = $request->getParsedBody();

            $user = new User(
                $data['email'] ?? '',
                $data['password'] ?? '',
                intval($data['coins']) ?? ''
            );

            $this->userRepository->save($user);
            return $response->withHeader('Location', $routeParser->urlFor("sign-in"))->withStatus(302);
        } catch (Exception $exception) {
            
            return $this->twig->render( $response->withStatus(500), 'sign-up.twig', [] );
            
        }

        
    }

    public function loginAction(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $errors = $this->validate_common($data);
        $errors_login = $this->validate_sign_in($data, $errors);

        if (count($errors) > 0 || count($errors_login) > 0) {

            return $this->twig->render(
                $response->withStatus(412),
                'sign-in.twig',
                [
                    'formErrors' => array_merge($errors, $errors_login),
                    'formData' => $data,
                    'formAction' => $routeParser->urlFor("sign-in")
                ]
            );
        }

        $_SESSION["user"]=$data['email'];
        return $response->withHeader('Location', $routeParser->urlFor("home"));

    }

    private function validate_common(array $data): array
    {
        $errors = [];
        $uppercase = preg_match('@[A-Z]@', $data['password']);
        $lowercase = preg_match('@[a-z]@', $data['password']);
        $number    = preg_match('@[0-9]@', $data['password']);

        $allowed    = 'salle.url.edu'; 
        $parts      = explode('@', $data['email']);
        $domain     = array_pop($parts);

        if (empty($data['email'])) {
            $errors['email'] = 'The email cannot be empty.';
        }

        if ( $domain !== $allowed ) {
            $errors['email'] = 'Only emails from the domain @salle.url.edu are accepted.';
        }

        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'The email address is not valid.';
        }

        if (!$uppercase || !$number || !$lowercase) {
            $errors['password'] = 'The password must contain both upper and lower case letters and numbers.';
        }
        
        if (empty($data['password']) || strlen($data['password']) < 7) {
            $errors['password'] = 'The password must contain at least 7 characters.';
        }

        return $errors;
    }

    private function validate_sign_in(array $data, array $errors_common): array
    {
        $errors = [];

        if (!isset($errors_common['email']) && !$this->userRepository->checkEmail($data['email'])){
            $errors['email'] = 'User with this email address does not exist.';
        }

        if (!isset($errors_common['password']) && !$this->userRepository->login($data['email'],  $data['password'])){
            $errors['password'] = 'Your email and/or password are incorrect.';
        }

        return $errors;
    }

    private function validate_sign_up(array $data, array $errors_common): array
    {
        $errors = [];

        $number_coins = preg_match('@[0-9]@', $data['coins']);

        if (!isset($errors_common['email'])) {

            if ($this->userRepository->checkEmail($data['email'])){
                $errors['email'] = 'This email is already taken.';
            }

        }

        if (!empty($data['coins']) && ( intval($data['coins']) < 50 || intval($data['coins']) > 30000 ) ) {
            $errors['coins'] = 'Sorry, the number of LSCoins is either below or above the limits.';
        }

        if (!empty($data['coins']) && (!$number_coins || !ctype_digit($data['coins'])) ) {
            $errors['coins'] = 'The number of LSCoins is not a valid number.';
        }

        if ($data['password'] !== $data['repeatPassword']){
            $errors['password'] = 'Passwords do not match.';
        }

        return $errors;
    }


}