<?php
declare(strict_types=1);

namespace Salle\LSCoins\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

final class VisitsController
{
    public function __construct(private Twig $twig)
    {
    }

    public function showVisits(Request $request, Response $response): Response
    {
        if (empty($_SESSION['counter'])) {
            $_SESSION['counter'] = 1;
        } else {
            $_SESSION['counter']++;
        }

        return $this->twig->render(
            $response,
            'visits.twig',
            [
                'visits' => $_SESSION['counter'],
            ]
        );
    }
}