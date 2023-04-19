<?php
declare(strict_types=1);

namespace Salle\LSCoins\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
final class ProfileController
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function showProfile(Request $request, Response $response)
    {
        return $this->twig->render( $response, 'profile.twig');
    }

    public function showChangePassword(Request $request, Response $response)
    {
        return $this->twig->render( $response, 'changePassword.twig');
    }

    public function showMarket(Request $request, Response $response)
    {
        return $this->twig->render( $response, 'market.twig');
    }
}