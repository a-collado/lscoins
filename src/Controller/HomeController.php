<?php
declare(strict_types=1);

namespace Salle\LSCoins\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

final class HomeController
{
    private Twig $twig;


    // You can also use https://stitcher.io/blog/constructor-promotion-in-php-8
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function showHome(Request $request, Response $response)
    {

        if (isset($_SESSION['user']) && $_SESSION['user'] != null){
            $parts = explode('@', $_SESSION['user']);
            $parts_inv = array_reverse($parts);
            $name = array_pop($parts_inv);
        } else {
            $name = 'stranger';
        }

        return $this->twig->render(
            $response,
            'home.twig',
            [
                'username' => $name
            ]
        );
    }
}